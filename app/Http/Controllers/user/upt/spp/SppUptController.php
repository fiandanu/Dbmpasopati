<?php

namespace App\Http\Controllers\user\upt\spp;

use App\Http\Controllers\Controller;
use App\Models\db\upt\UploadFolderUptSpp;
use App\Models\user\upt\Upt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SppUptController extends Controller
{
    private function calculatePdfStatus($uploadFolder)
    {
        if (! $uploadFolder) {
            return 'Belum Upload';
        }

        $uploadedFolders = 0;
        $totalFolders = 10;

        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_'.$i;
            if (! empty($uploadFolder->$column)) {
                $uploadedFolders++;
            }
        }

        if ($uploadedFolders == 0) {
            return 'Belum Upload';
        } elseif ($uploadedFolders == $totalFolders) {
            return 'Sudah Upload Lengkap';
        } else {
            return "Sebagian ({$uploadedFolders}/{$totalFolders})";
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific searches
        if ($request->has('search_namaupt') && ! empty($request->search_namaupt)) {
            $query->where('namaupt', 'LIKE', '%'.$request->search_namaupt.'%');
        }
        if ($request->has('search_kanwil') && ! empty($request->search_kanwil)) {
            $query->whereHas('kanwil', function ($q) use ($request) {
                $q->where('kanwil', 'LIKE', '%'.$request->search_kanwil.'%');
            });
        }
        if ($request->has('search_tipe') && ! empty($request->search_tipe)) {
            $query->where('tipe', 'LIKE', '%'.$request->search_tipe.'%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_dari') && ! empty($request->search_tanggal_dari)) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->has('search_tanggal_sampai') && ! empty($request->search_tanggal_sampai)) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        return $query;
    }

    private function applyPdfStatusFilter($data, Request $request)
    {
        if ($request->has('search_status') && ! empty($request->search_status)) {
            $statusSearch = strtolower($request->search_status);

            return $data->filter(function ($d) use ($statusSearch) {
                // PERBAIKAN: Gunakan uploadFolderSpp
                $status = strtolower($this->calculatePdfStatus($d->uploadFolderSpp));

                return strpos($status, $statusSearch) !== false;
            });
        }

        return $data;
    }

    public function ListDataSpp(Request $request)
    {
        // PERBAIKAN: Gunakan uploadFolderSpp
        $query = Upt::with('uploadFolderSpp', 'kanwil')->whereHas('uploadFolderSpp');

        // Apply database filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Handle pagination
        if ($perPage == 'all') {
            $data = $query->orderBy('tanggal', 'desc')->get();

            // Apply status filter to collection
            $data = $this->applyPdfStatusFilter(collect($data), $request);

            // Create a mock paginator for "all" option
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $data->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // For paginated results, we need to get all data first, apply status filter, then paginate
            $allData = $query->orderBy('tanggal', 'desc')->get();
            $filteredData = $this->applyPdfStatusFilter($allData, $request);

            // Manual pagination of filtered data
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
            $offset = ($currentPage - 1) * $perPage;
            $itemsForCurrentPage = $filteredData->slice($offset, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsForCurrentPage,
                $filteredData->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'page',
                ]
            );
        }

        $uptList = Upt::whereDoesntHave('uploadFolderSpp')->get();

        return view('db.upt.spp.indexSpp', compact('data', 'uptList'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'namaupt' => 'required',
                'kanwil' => 'required',
            ], [
                'namaupt.required' => 'Nama upt harus diisi.',
                'kanwil.required' => 'Nama kanwil harus diisi.',
            ]);

            // Cari Ponpes berdasarkan namaupt dan relasi kanwil
            $ponpes = Upt::where('namaupt', $request->namaupt)
                ->whereHas('kanwil', function ($query) use ($request) {
                    $query->where('kanwil', $request->kanwil);
                })
                ->first();

            if (! $ponpes) {
                return redirect()->back()
                    ->with('error', 'Data Ponpes tidak ditemukan')
                    ->withInput();
            }

            // Create UploadFolder untuk menandai data sudah ditambahkan ke SPP
            UploadFolderUptSpp::firstOrCreate(
                ['data_upt_id' => $ponpes->id],
                ['data_upt_id' => $ponpes->id]
            );

            return redirect()->back()
                ->with('success', 'Data SPP berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating SPP: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menambahkan data: '.$e->getMessage())
                ->withInput();
        }
    }

    public function DataBasePageDestroy($id)
    {
        try {
            $uploadFolder = UploadFolderUptSpp::where('data_upt_id', $id)->first();

            if ($uploadFolder) {
                // Hapus semua file PDF yang terkait
                for ($i = 1; $i <= 10; $i++) {
                    $column = 'pdf_folder_'.$i;
                    if (! empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                        Storage::disk('public')->delete($uploadFolder->$column);
                    }
                }

                // Hapus record upload folder
                $uploadFolder->delete();
            }

            // $dataupt->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting UPT: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $upt = Upt::findOrFail($id);
            // PERBAIKAN: Gunakan UploadFolderUptSpp
            $uploadFolder = UploadFolderUptSpp::where('data_upt_id', $id)->first();

            if (! $uploadFolder) {
                return abort(404, 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_'.$folder;

            // Cek apakah file ada dan path tidak kosong
            if (empty($uploadFolder->$column)) {
                return abort(404, 'File PDF belum diupload untuk folder '.$folder.'.');
            }

            $filePath = storage_path('app/public/'.$uploadFolder->$column);

            if (! file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
            Log::error('Error viewing PDF: '.$e->getMessage());

            return abort(500, 'Error loading PDF: '.$e->getMessage());
        }
    }

    public function uploadFilePDF(Request $request, $id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            // Validasi file PDF
            $request->validate([
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.',
            ]);

            if (! $request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $upt = Upt::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            // Debug: Cek apakah file valid
            if (! $file || ! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // Ambil nama asli file
            $originalFileName = $file->getClientOriginalName();

            // PERBAIKAN: Gunakan UploadFolderUptSpp
            $uploadFolder = UploadFolderUptSpp::firstOrCreate(
                ['data_upt_id' => $id],
                ['data_upt_id' => $id]
            );

            // Hapus file lama jika ada
            $column = 'pdf_folder_'.$folder;
            if (! empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            // Buat nama file unik dengan sanitasi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            // $filename = time() . '_' . $sanitizedName . '.pdf';
            $filename = time().'_'.Str::random(8).'_'.$sanitizedName.'.pdf';

            // Pastikan direktori ada - gunakan tipe UPT untuk folder
            $directory = 'upt/'.$upt->tipe.'/folder_'.$folder;
            if (! Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Simpan file
            $path = $file->storeAs($directory, $filename, 'public');

            if (! $path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            // Simpan path ke database
            $uploadFolder->$column = $path;
            $uploadFolder->save();

            Log::info("PDF uploaded successfully for UPT ID: {$id}, Folder: {$folder}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF '.$originalFileName.' berhasil di-upload ke folder '.$folder.'!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal upload file: '.$e->getMessage());
        }
    }

    // Delete File PDF
    public function deleteFilePDF($id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $upt = Upt::findOrFail($id);
            // PERBAIKAN: Gunakan UploadFolderUptSpp
            $uploadFolder = UploadFolderUptSpp::where('data_upt_id', $id)->first();

            if (! $uploadFolder) {
                return redirect()->back()->with('error', 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_'.$folder;

            if (empty($uploadFolder->$column)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload di folder '.$folder.'.');
            }

            if (Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            $uploadFolder->$column = null;
            $uploadFolder->save();

            return redirect()->back()->with('success', 'File PDF di folder '.$folder.' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus file: '.$e->getMessage());
        }
    }

    // Export data CSV GLOBAL
    public function exportListCsv(Request $request): StreamedResponse
    {
        // PERBAIKAN: Gunakan uploadFolderSpp
        $query = Upt::with('uploadFolderSpp', 'kanwil')->whereIn('tipe', ['vpas', 'reguler'])->whereHas('uploadFolderSpp');
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyPdfStatusFilter($data, $request);

        // Additional sorting if date filter is applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_upt_vpas_reguler_'.Carbon::now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Nama UPT', 'Kanwil', 'Tipe', 'Tanggal Dibuat', 'Status Upload PDF']];
        $no = 1;
        foreach ($data as $d) {
            // PERBAIKAN: Gunakan uploadFolderSpp
            $status = $this->calculatePdfStatus($d->uploadFolderSpp);
            $rows[] = [
                $no++,
                $d->namaupt,
                $d->kanwil->kanwil,
                ucfirst($d->tipe),
                \Carbon\Carbon::parse($d->tanggal)->format('d M Y'),
                $status,
            ];
        }

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export data PDF GLOBAL
    public function exportListPdf(Request $request)
    {
        // PERBAIKAN: Gunakan uploadFolderSpp
        $query = Upt::with('uploadFolderSpp')->whereIn('tipe', ['vpas', 'reguler'])->whereHas('uploadFolderSpp');
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Calculate status for each item
        $data = $data->map(function ($item) {
            $totalFiles = $item->uploadFolderSpp?->uploaded_folders_count ?? 0;

            if ($totalFiles === 0) {
                $item->calculated_status = 'Belum Upload';
            } elseif ($totalFiles < 10) {
                $item->calculated_status = 'Upload Sebagian ('.$totalFiles.'/10)';
            } else {
                $item->calculated_status = 'Upload Lengkap';
            }

            return $item;
        });

        // Apply status filter
        $data = $this->applyPdfStatusFilter($data, $request);

        $pdfData = [
            'title' => 'List Data UPT SPP',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.upt.indexSpp', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_upt_vpas_reguler_'.Carbon::now()->translatedFormat('d_M_Y').'.pdf';

        return $pdf->download($filename);
    }
}

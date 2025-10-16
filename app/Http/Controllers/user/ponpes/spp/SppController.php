<?php

namespace App\Http\Controllers\user\ponpes\spp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Ponpes;
use App\Models\user\Provider;
use App\Models\db\ponpes\UploadFolderPonpesSpp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class SppController extends Controller
{
    private function calculateStatus($uploadFolder)
    {
        if (!$uploadFolder) {
            return 'Belum Upload';
        }

        $uploadedFolders = 0;
        $totalFolders = 10;

        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($uploadFolder->$column)) {
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
        if ($request->has('search_namaponpes') && !empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }

        if ($request->has('search_wilayah') && !empty($request->search_wilayah)) {
            $query->whereHas('namaWilayah', function ($q) use ($request) {
                $q->where('nama_wilayah', 'LIKE', '%' . $request->search_wilayah . '%');
            });
        }

        if ($request->has('search_tipe') && !empty($request->search_tipe)) {
            $query->where('tipe', 'LIKE', '%' . $request->search_tipe . '%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_dari') && !empty($request->search_tanggal_dari)) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->has('search_tanggal_sampai') && !empty($request->search_tanggal_sampai)) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        return $query;
    }

    private function applyPdfStatusFilter($data, Request $request)
    {
        if ($request->has('search_status') && !empty($request->search_status)) {
            $statusSearch = strtolower($request->search_status);
            return $data->filter(function ($d) use ($statusSearch) {
                // PERBAIKAN: Gunakan uploadFolderSpp
                $status = strtolower($this->calculateStatus($d->uploadFolderSpp));
                return strpos($status, $statusSearch) !== false;
            });
        }
        return $data;
    }

    public function ListDataSpp(Request $request)
    {
        // PERBAIKAN: Gunakan uploadFolderSpp
        $query = Ponpes::with(['uploadFolderSpp', 'namaWilayah'])
            ->whereHas('uploadFolderSpp');

        // Apply database filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
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
            // For paginated results
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
                    'pageName' => 'page'
                ]
            );
        }

        $ponpesList = Ponpes::with('namaWilayah')
            ->whereDoesntHave('uploadFolderSpp')
            ->orderBy('nama_ponpes')
            ->get();

        $providers = Provider::all();
        return view('db.ponpes.spp.indexSpp', compact('data', 'providers', 'ponpesList'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with(['uploadFolderSpp', 'namaWilayah'])->whereIn('tipe', ['vtren', 'reguler'])->whereHas('uploadFolderSpp');
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_ponpes_spp_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Tipe', 'Tanggal Dibuat', 'Status Upload PDF']];
        $no = 1;
        foreach ($data as $d) {
            // PERBAIKAN: Gunakan uploadFolderSpp
            $status = $this->calculateStatus($d->uploadFolderSpp);
            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->namaWilayah->nama_wilayah ?? '-',
                ucfirst($d->tipe ?? '-'),
                $d->tanggal ? Carbon::parse($d->tanggal)->format('d M Y') : '-',
                $status
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

    public function exportListPdf(Request $request)
    {
        $query = Ponpes::with('uploadFolderSpp', 'namaWilayah')->whereIn('tipe', ['vtren', 'reguler' ])->whereHas('uploadFolderSpp');
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        $data->transform(function ($ponpes) {
            $ponpes->calculated_status = $this->calculateStatus($ponpes->uploadFolderSpp);
            return $ponpes;
        });

        $pdfData = [
            'title' => 'List Data Ponpes SPP',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.db.ponpes.indexSpp', $pdfData);
        $filename = 'list_ponpes_spp_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function DatabasePageDestroy($id)
    {
        try {
            // PERBAIKAN: Gunakan UploadFolderPonpesSpp
            $uploadFolder = UploadFolderPonpesSpp::where('data_ponpes_id', $id)->first();

            if ($uploadFolder) {
                // Hapus semua file PDF yang terkait
                for ($i = 1; $i <= 10; $i++) {
                    $column = 'pdf_folder_' . $i;
                    if (!empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                        Storage::disk('public')->delete($uploadFolder->$column);
                    }
                }

                // Hapus record upload folder
                $uploadFolder->delete();
            }

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting Ponpes SPP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpesSpp::where('data_ponpes_id', $id)->first();

            if (!$uploadFolder) {
                return abort(404, 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_' . $folder;

            if (empty($uploadFolder->$column)) {
                return abort(404, 'File PDF belum diupload untuk folder ' . $folder . '.');
            }

            $filePath = storage_path('app/public/' . $uploadFolder->$column);

            if (!file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
            Log::error('Error viewing PDF: ' . $e->getMessage());
            return abort(500, 'Error loading PDF: ' . $e->getMessage());
        }
    }

    public function uploadFilePDF(Request $request, $id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            // Validasi file PDF
            $request->validate([
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240',
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.'
            ]);

            if (!$request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $ponpes = Ponpes::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // PERBAIKAN: Gunakan UploadFolderPonpesSpp
            $uploadFolder = UploadFolderPonpesSpp::firstOrCreate(
                ['data_ponpes_id' => $id],
                ['data_ponpes_id' => $id]
            );

            // Hapus file lama jika ada
            $column = 'pdf_folder_' . $folder;
            if (!empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            // Buat nama file unik dengan sanitasi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName . '.pdf';

            // Pastikan direktori ada
            $directory = 'ponpes/spp/folder_' . $folder;
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Simpan file
            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            // Simpan path ke database
            $uploadFolder->$column = $path;
            $uploadFolder->save();

            Log::info("PDF uploaded successfully for Ponpes ID: {$id}, Folder: {$folder}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF berhasil di-upload ke folder ' . $folder . '!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_ponpes' => 'required',
            ], [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
            ]);

            // Cari Ponpes berdasarkan nama_ponpes saja (nama sudah unik)
            $ponpes = Ponpes::where('nama_ponpes', $request->nama_ponpes)->first();

            if (!$ponpes) {
                return redirect()->back()
                    ->with('error', 'Data Ponpes tidak ditemukan')
                    ->withInput();
            }

            // Create UploadFolder untuk menandai data sudah ditambahkan ke SPP
            UploadFolderPonpesSpp::firstOrCreate(
                ['data_ponpes_id' => $ponpes->id],
                ['data_ponpes_id' => $ponpes->id]
            );

            return redirect()->back()->with('success', 'Data SPP berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating SPP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteFilePDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpesSpp::where('data_ponpes_id', $id)->first();

            if (!$uploadFolder) {
                return redirect()->back()->with('error', 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_' . $folder;

            if (empty($uploadFolder->$column)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload di folder ' . $folder . '.');
            }

            if (Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            $uploadFolder->$column = null;
            $uploadFolder->save();

            return redirect()->back()->with('success', 'File PDF di folder ' . $folder . ' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

}

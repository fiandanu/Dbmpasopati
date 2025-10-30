<?php

namespace App\Http\Controllers\user\upt\pks;

use App\Http\Controllers\Controller;
use App\Models\db\upt\UploadFolderUptPks;
use App\Models\user\upt\Upt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PksController extends Controller
{
    private function calculatePdfStatus($uploadFolder)
    {
        if (! $uploadFolder) {
            return 'Belum Upload';
        }

        $hasPdf1 = ! empty($uploadFolder->uploaded_pdf_1);
        $hasPdf2 = ! empty($uploadFolder->uploaded_pdf_2);

        if ($hasPdf1 && $hasPdf2) {
            return 'Sudah Upload (2/2)';
        } elseif ($hasPdf1 || $hasPdf2) {
            return 'Sebagian (1/2)';
        } else {
            return 'Belum Upload';
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
                // PERBAIKAN: Gunakan uploadFolderPks
                $status = strtolower($this->calculatePdfStatus($d->uploadFolderPks));

                return strpos($status, $statusSearch) !== false;
            });
        }

        return $data;
    }

    public function ListDataPks(Request $request)
    {
        // PERBAIKAN: Gunakan uploadFolderPks
        $query = Upt::with('uploadFolderPks')
            ->whereHas('uploadFolderPks');

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
                    'pageName' => 'page',
                ]
            );
        }

        $uptList = Upt::whereDoesntHave('uploadFolderPks')
            ->orWhereHas('uploadFolderPks', function ($q) {
                $q->whereNull('tanggal_kontrak')
                    ->whereNull('tanggal_jatuh_tempo');
            })
            ->orderBy('namaupt')
            ->get();

        return view('db.upt.pks.indexPks', compact('data', 'uptList'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Upt::with('uploadFolderPks')->whereIn('tipe', ['vpas', 'reguler'])->whereHas('uploadFolderPks');
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_upt_pks_'.Carbon::now()->format('Y-m-d_H-i-s').'.csv';

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
            // PERBAIKAN: Gunakan uploadFolderPks
            $status = $this->calculatePdfStatus($d->uploadFolderPks);
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

    public function exportListPdf(Request $request)
    {
        // PERBAIKAN: Gunakan uploadFolderPks
        $query = Upt::with('uploadFolderPks')->whereIn('tipe', ['vpas', 'reguler'])->whereHas('uploadFolderPks');
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        $data = $data->map(function ($item) {
            $item->calculated_status = $this->calculatePdfStatus($item->uploadFolderPks);

            return $item;
        });

        $pdfData = [
            'title' => 'List Data UPT PKS',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.upt.indexPks', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_upt_pks_'.Carbon::now()->translatedFormat('d_M_Y').'.pdf';

        return $pdf->download($filename);
    }

    public function viewUploadedPDF($id, $folderNumber)
    {
        try {
            // Validasi folder number
            if (! in_array($folderNumber, [1, 2])) {
                return abort(404, 'Nomor folder tidak valid.');
            }

            $upt = Upt::findOrFail($id);
            $uploadFolder = UploadFolderUptPks::where('data_upt_id', $id)->first();

            $columnName = 'uploaded_pdf_'.$folderNumber;

            if (! $uploadFolder || empty($uploadFolder->$columnName)) {
                return abort(404, 'File PDF belum diupload untuk folder ini.');
            }

            $filePath = storage_path('app/public/'.$uploadFolder->$columnName);

            if (! file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
            Log::error('Error viewing PDF: '.$e->getMessage());

            return abort(500, 'Error loading PDF: '.$e->getMessage());
        }
    }

    public function uploadFilePDFPks(Request $request, $id, $folderNumber)
    {
        try {
            // Validasi file PDF
            $request->validate([
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240',
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.',
            ]);

            if (! $request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            // Validasi folder number (1 atau 2)
            if (! in_array($folderNumber, [1, 2])) {
                return redirect()->back()->with('error', 'Nomor folder tidak valid!');
            }

            $upt = Upt::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (! $file || ! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            $originalFileName = $file->getClientOriginalName();

            // Gunakan namespace lengkap untuk model
            $uploadFolder = UploadFolderUptPks::firstOrCreate(
                ['data_upt_id' => $id],
                ['data_upt_id' => $id]
            );

            // Tentukan kolom berdasarkan folder number
            $columnName = 'uploaded_pdf_'.$folderNumber;

            // Hapus file lama jika ada
            if (! empty($uploadFolder->$columnName) && Storage::disk('public')->exists($uploadFolder->$columnName)) {
                Storage::disk('public')->delete($uploadFolder->$columnName);
            }

            // Buat nama file unik
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time().'_folder'.$folderNumber.'_'.$sanitizedName.'.pdf';

            // Simpan file
            $directory = 'upt/pks/folder_'.$folderNumber;
            if (! Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $path = $file->storeAs($directory, $filename, 'public');

            if (! $path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            // Simpan path ke database
            $uploadFolder->$columnName = $path;
            $uploadFolder->save();

            Log::info("PDF uploaded successfully for UPT PKS ID: {$id}, Folder: {$folderNumber}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF Folder '.$originalFileName.' berhasil di-upload!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF PKS: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal upload file: '.$e->getMessage());
        }
    }

    public function deleteFilePDF($id, $folderNumber)
    {
        try {
            // Validasi folder number
            if (! in_array($folderNumber, [1, 2])) {
                return redirect()->back()->with('error', 'Nomor folder tidak valid.');
            }

            $upt = Upt::findOrFail($id);
            $uploadFolder = UploadFolderUptPks::where('data_upt_id', $id)->first();

            $columnName = 'uploaded_pdf_'.$folderNumber;

            if (! $uploadFolder || empty($uploadFolder->$columnName)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload untuk folder ini.');
            }

            if (Storage::disk('public')->exists($uploadFolder->$columnName)) {
                Storage::disk('public')->delete($uploadFolder->$columnName);
            }

            $uploadFolder->$columnName = null;
            $uploadFolder->save();

            return redirect()->back()->with('success', 'File PDF Folder '.$folderNumber.' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF PKS: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus file: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tanggal_kontrak' => 'nullable|date',
                'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_kontrak',
            ], [
                'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal kontrak.',
            ]);

            $upt = Upt::findOrFail($id);

            // Update or create upload folder - PERBAIKAN: gunakan namespace lengkap
            $uploadFolder = UploadFolderUptPks::firstOrCreate(
                ['data_upt_id' => $id],
                ['data_upt_id' => $id]
            );

            $uploadFolder->update([
                'tanggal_kontrak' => $request->tanggal_kontrak,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            ]);

            return redirect()->back()->with('success', 'Data PKS berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating PKS: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengupdate data: '.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'namaupt' => 'required',
                'kanwil' => 'required',
                'tanggal_kontrak' => 'nullable|date',
                'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_kontrak',
            ], [
                'namaupt.required' => 'Nama Ponpes harus diisi.',
                'kanwil.required' => 'Nama Wilayah harus diisi.',
                'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal kontrak.',
            ]);

            // Cari Ponpes yang dipilih untuk ambil tipe-nya
            $upt = Upt::where('namaupt', $request->namaupt)
                ->whereHas('kanwil', function ($query) use ($request) {
                    $query->where('kanwil', $request->kanwil);
                })
                ->first();

            if (! $upt) {
                return redirect()->back()->with('error', 'Data Ponpes tidak ditemukan')->withInput();
            }

            // Create atau Update UploadFolderUptPks dengan contract dates - PERBAIKAN: gunakan namespace lengkap
            UploadFolderUptPks::updateOrCreate(
                ['data_upt_id' => $upt->id],
                [
                    'tanggal_kontrak' => $request->tanggal_kontrak,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                ]
            );

            return redirect()->back()->with('success', 'Data PKS berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating PKS: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menambahkan data: '.$e->getMessage())->withInput();
        }
    }

    public function DatabasePageDestroy($id)
    {
        try {

            // PERBAIKAN: Gunakan UploadFolderUptPks
            $uploadFolder = UploadFolderUptPks::where('data_upt_id', $id)->first();

            if ($uploadFolder) {
                // Hapus file PDF yang terkait
                if (! empty($uploadFolder->uploaded_pdf) && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
                }

                // Hapus record upload folder
                $uploadFolder->delete();
            }

            $uploadFolder->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting UPT PKS: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}

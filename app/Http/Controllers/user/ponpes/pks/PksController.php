<?php

namespace App\Http\Controllers\user\ponpes\pks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Ponpes;
use App\Models\user\Provider;
use App\Models\db\UploadFolderPonpes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class PksController extends Controller
{
    private function calculatePdfStatus($uploadFolder)
    {
        if (!$uploadFolder) {
            return 'Belum Upload';
        }

        $hasUploadedPdf = !empty($uploadFolder->uploaded_pdf);

        if ($hasUploadedPdf) {
            return 'Sudah Upload';
        } else {
            return 'Belum Upload';
        }
    }



    private function applyFilters($query, Request $request)
    {
        // Global search
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Column-specific searches
        if ($request->has('search_namaponpes') && !empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }
        if ($request->has('search_wilayah') && !empty($request->search_wilayah)) {
            $query->where('nama_wilayah', 'LIKE', '%' . $request->search_wilayah . '%');
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
                $status = strtolower($this->calculatePdfStatus($d->uploadFolder));
                return strpos($status, $statusSearch) !== false;
            });
        }
        return $data;
    }

    public function ListDataPks(Request $request)
    {
        // Menampilkan data PKS Ponpes - hanya yang sudah dibuat manual
        $query = Ponpes::with('uploadFolder')
            ->whereHas('uploadFolder'); // Hanya tampilkan yang sudah punya upload folder (sudah dibuat di PKS)

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
                    'pageName' => 'page'
                ]
            );
        }

        // Get list ponpes untuk dropdown - ambil dari tabel Ponpes yang belum ada di PKS
        $ponpesList = Ponpes::whereDoesntHave('uploadFolder')
            ->orWhereHas('uploadFolder', function ($q) {
                $q->whereNull('tanggal_kontrak')
                    ->whereNull('tanggal_jatuh_tempo');
            })
            ->orderBy('nama_ponpes')
            ->get();

        return view('db.ponpes.pks.indexPks', compact('data', 'ponpesList'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with('uploadFolder');
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

        $filename = 'list_ponpes_pks_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Tipe', 'Tanggal Dibuat', 'Tanggal Kontrak', 'Tanggal Jatuh Tempo', 'Status Upload PDF']];
        $no = 1;
        foreach ($data as $d) {
            $status = $this->calculatePdfStatus($d->uploadFolder);
            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->nama_wilayah,
                ucfirst($d->tipe),
                \Carbon\Carbon::parse($d->tanggal)->format('d M Y'),
                $d->uploadFolder && $d->uploadFolder->tanggal_kontrak ? \Carbon\Carbon::parse($d->uploadFolder->tanggal_kontrak)->format('d M Y') : '-',
                $d->uploadFolder && $d->uploadFolder->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($d->uploadFolder->tanggal_jatuh_tempo)->format('d M Y') : '-',
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
        $query = Ponpes::with('uploadFolder');
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyPdfStatusFilter($data, $request);

        // Convert collection to array with calculated status
        $dataArray = [];
        foreach ($data as $d) {
            $dataItem = $d->toArray();
            $dataItem['calculated_status'] = $this->calculatePdfStatus($d->uploadFolder);
            $dataArray[] = $dataItem;
        }

        // Additional sorting using correct field name
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            usort($dataArray, function ($a, $b) {
                $dateA = strtotime($a['tanggal']);
                $dateB = strtotime($b['tanggal']);
                return $dateA - $dateB;
            });
        }

        $pdfData = [
            'title' => 'List Data Ponpes PKS',
            'data' => $dataArray,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.db.ponpes.indexPks', $pdfData);
        $filename = 'list_ponpes_pks_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function DataBasePageDestroy($id)
    {
        try {
            $dataponpes = Ponpes::findOrFail($id);

            // Cari data upload folder yang terkait
            $uploadFolder = UploadFolderPonpes::where('ponpes_id', $id)->first();

            if ($uploadFolder) {
                // Hapus file PDF yang terkait
                if (!empty($uploadFolder->uploaded_pdf) && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
                }

                // Hapus file PDF folder lainnya (1-10) jika ada
                for ($i = 1; $i <= 10; $i++) {
                    $pdfField = "pdf_folder_$i";
                    if (!empty($uploadFolder->$pdfField) && Storage::disk('public')->exists($uploadFolder->$pdfField)) {
                        Storage::disk('public')->delete($uploadFolder->$pdfField);
                    }
                }

                // Hapus record upload folder
                $uploadFolder->delete();
            }

            $dataponpes->delete();
            return redirect()->route('ponpes.pks.ListDataPks')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting Ponpes PKS: ' . $e->getMessage());
            return redirect()->route('ponpes.pks.ListDataPks')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function viewUploadedPDF($id)
    {
        try {
            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpes::where('ponpes_id', $id)->first();

            if (!$uploadFolder || empty($uploadFolder->uploaded_pdf)) {
                return abort(404, 'File PDF belum diupload.');
            }

            $filePath = storage_path('app/public/' . $uploadFolder->uploaded_pdf);

            if (!file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
            Log::error('Error viewing PDF: ' . $e->getMessage());
            return abort(500, 'Error loading PDF: ' . $e->getMessage());
        }
    }

    public function uploadFilePDFPonpesPks(Request $request, $id)
    {
        try {
            // Validasi file PDF
            $request->validate([
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
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

            // Debug: Cek apakah file valid
            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // Cari atau buat record upload folder
            $uploadFolder = UploadFolderPonpes::firstOrCreate(
                ['ponpes_id' => $id],
                ['ponpes_id' => $id]
            );

            // Hapus file lama jika ada
            if (!empty($uploadFolder->uploaded_pdf) && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
            }

            // Buat nama file unik dengan sanitasi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName . '.pdf';

            // Pastikan direktori ada
            $directory = 'ponpes/pks';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Simpan file
            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            // Simpan path ke database
            $uploadFolder->uploaded_pdf = $path;
            $uploadFolder->save();

            Log::info("PDF uploaded successfully for Ponpes PKS ID: {$id}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF berhasil di-upload!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF Ponpes PKS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function deleteFilePDF($id)
    {
        try {
            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpes::where('ponpes_id', $id)->first();

            if (!$uploadFolder || empty($uploadFolder->uploaded_pdf)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload.');
            }

            if (Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
            }

            $uploadFolder->uploaded_pdf = null;
            $uploadFolder->save();

            return redirect()->back()->with('success', 'File PDF berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF Ponpes PKS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_ponpes' => 'required',
                'nama_wilayah' => 'required',
                'tanggal' => 'required|date',
                'tanggal_kontrak' => 'nullable|date',
                'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_kontrak',
            ], [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
                'tanggal.required' => 'Tanggal dibuat harus diisi.',
                'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal kontrak.',
            ]);

            // Cari Ponpes yang dipilih untuk ambil tipe-nya
            $ponpes = Ponpes::where('nama_ponpes', $request->nama_ponpes)
                ->where('nama_wilayah', $request->nama_wilayah)
                ->first();

            if (!$ponpes) {
                return redirect()->back()->with('error', 'Data Ponpes tidak ditemukan')->withInput();
            }

            // Create atau Update UploadFolder dengan contract dates
            UploadFolderPonpes::updateOrCreate(
                ['ponpes_id' => $ponpes->id],
                [
                    'tanggal_kontrak' => $request->tanggal_kontrak,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                ]
            );

            return redirect()->route('DbPonpes.pks.ListDataPks')->with('success', 'Data PKS berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating PKS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
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

            $ponpes = Ponpes::findOrFail($id);

            // Update or create upload folder
            $uploadFolder = UploadFolderPonpes::firstOrCreate(
                ['ponpes_id' => $id],
                ['ponpes_id' => $id]
            );

            $uploadFolder->update([
                'tanggal_kontrak' => $request->tanggal_kontrak,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            ]);

            return redirect()->back()->with('success', 'Data PKS berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating PKS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }
}

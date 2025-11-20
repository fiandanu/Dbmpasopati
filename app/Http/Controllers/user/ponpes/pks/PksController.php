<?php

namespace App\Http\Controllers\user\ponpes\pks;

use App\Http\Controllers\Controller;
use App\Models\db\ponpes\UploadFolderPonpesPks;
use App\Models\user\ponpes\Ponpes;
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
            return 'Sudah Upload (1/2)';
        } else {
            return 'Belum Upload';
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific searches
        if ($request->has('search_namaponpes') && ! empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }

        if ($request->has('search_wilayah') && ! empty($request->search_wilayah)) {
            $query->whereHas('namaWilayah', function ($q) use ($request) {
                $q->where('nama_wilayah', 'LIKE', '%' . $request->search_wilayah . '%');
            });
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
        $query = Ponpes::with(['uploadFolderPks', 'namaWilayah'])
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

        $ponpesList = Ponpes::with('namaWilayah')
            ->whereDoesntHave('uploadFolderPks')
            ->orWhereHas('uploadFolderPks', function ($q) {
                $q->whereNull('tanggal_kontrak')
                    ->whereNull('tanggal_jatuh_tempo');
            })
            ->orderBy('nama_ponpes')
            ->get()
            ->unique(function ($ponpes) {
                return preg_replace('/\s*\(VtrenReg\)$/', '', $ponpes->nama_ponpes);
            });

        return view('db.ponpes.pks.indexPks', compact('data', 'ponpesList'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with(['uploadFolderPks', 'namaWilayah'])
            ->whereHas('uploadFolderPks');  // TAMBAHKAN BARIS INI

        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_ponpes_pks_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Tipe', 'Tanggal Dibuat', 'Tanggal Kontrak', 'Tanggal Jatuh Tempo', 'Status Upload PDF']];
        $no = 1;
        foreach ($data as $d) {
            // PERBAIKAN: Gunakan uploadFolderPks
            $status = $this->calculatePdfStatus($d->uploadFolderPks);
            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->namaWilayah->nama_wilayah ?? '-',
                ucfirst($d->tipe ?? '-'),
                $d->tanggal ? Carbon::parse($d->tanggal)->format('d M Y') : '-',
                $d->uploadFolderPks && $d->uploadFolderPks->tanggal_kontrak ? Carbon::parse($d->uploadFolderPks->tanggal_kontrak)->format('d M Y') : '-',
                $d->uploadFolderPks && $d->uploadFolderPks->tanggal_jatuh_tempo ? Carbon::parse($d->uploadFolderPks->tanggal_jatuh_tempo)->format('d M Y') : '-',
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
        // TAMBAHKAN whereHas untuk filter hanya data yang punya uploadFolderPks
        $query = Ponpes::with(['uploadFolderPks', 'namaWilayah'])
            ->whereHas('uploadFolderPks');  // TAMBAHKAN BARIS INI

        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        $pdfData = [
            'title' => 'List Data Ponpes PKS',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.ponpes.indexPks', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_ponpes_pks_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function viewUploadedPDF($id, $folderNumber)
    {
        try {
            // Validasi folder number
            if (! in_array($folderNumber, [1, 2])) {
                return abort(404, 'Nomor folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpesPks::where('data_ponpes_id', $id)->first();

            $columnName = 'uploaded_pdf_' . $folderNumber;

            if (! $uploadFolder || empty($uploadFolder->$columnName)) {
                return abort(404, 'File PDF belum diupload untuk folder ini.');
            }

            $filePath = storage_path('app/public/' . $uploadFolder->$columnName);

            if (! file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
            Log::error('Error viewing PDF: ' . $e->getMessage());

            return abort(500, 'Error loading PDF: ' . $e->getMessage());
        }
    }

    public function uploadFilePDFPonpesPks(Request $request, $id, $folderNumber)
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

            $ponpes = Ponpes::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (! $file || ! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // PERBAIKAN: Gunakan UploadFolderPonpesPks dengan namespace lengkap
            $uploadFolder = UploadFolderPonpesPks::firstOrCreate(
                ['data_ponpes_id' => $id],
                ['data_ponpes_id' => $id]
            );

            // Tentukan kolom berdasarkan folder number
            $columnName = 'uploaded_pdf_' . $folderNumber;

            // Hapus file lama jika ada
            if (! empty($uploadFolder->$columnName) && Storage::disk('public')->exists($uploadFolder->$columnName)) {
                Storage::disk('public')->delete($uploadFolder->$columnName);
            }

            // Buat nama file unik
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_folder' . $folderNumber . '_' . $sanitizedName . '.pdf';

            // Simpan file
            $directory = 'ponpes/pks/folder_' . $folderNumber;
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

            Log::info("PDF uploaded successfully for Ponpes PKS ID: {$id}, Folder: {$folderNumber}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF Folder ' . $folderNumber . ' berhasil di-upload!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF PKS: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function deleteFilePDF($id, $folderNumber)
    {
        try {
            // Validasi folder number
            if (! in_array($folderNumber, [1, 2])) {
                return redirect()->back()->with('error', 'Nomor folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpesPks::where('data_ponpes_id', $id)->first();

            $columnName = 'uploaded_pdf_' . $folderNumber;

            if (! $uploadFolder || empty($uploadFolder->$columnName)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload untuk folder ini.');
            }

            // Simpan nama file untuk pesan sukses
            $fileName = basename($uploadFolder->$columnName);

            if (Storage::disk('public')->exists($uploadFolder->$columnName)) {
                Storage::disk('public')->delete($uploadFolder->$columnName);
            }

            $uploadFolder->$columnName = null;
            $uploadFolder->save();

            Log::info("PDF deleted successfully for Ponpes PKS ID: {$id}, Folder: {$folderNumber}");

            return redirect()->back()->with('success', 'File PDF Folder ' . $folderNumber . ' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF PKS: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
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

            // PERBAIKAN: Gunakan UploadFolderPonpesPks dengan namespace lengkap
            $uploadFolder = UploadFolderPonpesPks::firstOrCreate(
                ['data_ponpes_id' => $id],
                ['data_ponpes_id' => $id]
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_ponpes' => 'required',
                'tanggal_kontrak' => 'nullable|date',
                'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_kontrak',
            ], [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal kontrak.',
            ]);

            // Cari Ponpes berdasarkan nama_ponpes saja (nama sudah unik)
            $ponpes = Ponpes::where('nama_ponpes', $request->nama_ponpes)->first();

            if (! $ponpes) {
                return redirect()->back()->with('error', 'Data Ponpes tidak ditemukan')->withInput();
            }

            // Create atau Update UploadFolderPonpesPks
            UploadFolderPonpesPks::updateOrCreate(
                ['data_ponpes_id' => $ponpes->id],
                [
                    'tanggal_kontrak' => $request->tanggal_kontrak,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                ]
            );

            return redirect()->back()->with('success', 'Data PKS berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating PKS: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function DatabasePageDestroy($id)
    {
        try {
            // PERBAIKAN: Gunakan UploadFolderPonpesPks
            $uploadFolder = UploadFolderPonpesPks::where('data_ponpes_id', $id)->first();

            if ($uploadFolder) {
                // Hapus file PDF yang terkait (kedua folder)
                if (! empty($uploadFolder->uploaded_pdf_1) && Storage::disk('public')->exists($uploadFolder->uploaded_pdf_1)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf_1);
                }

                if (! empty($uploadFolder->uploaded_pdf_2) && Storage::disk('public')->exists($uploadFolder->uploaded_pdf_2)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf_2);
                }

                // Hapus record upload folder
                $uploadFolder->delete();

                Log::info("Upload folder and PDFs deleted successfully for Ponpes PKS ID: {$id}");
            }

            return redirect()->back()->with('success', 'Data PKS berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting Ponpes PKS: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

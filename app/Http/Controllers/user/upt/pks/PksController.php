<?php

namespace App\Http\Controllers\user\upt\pks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Provider;
use App\Models\user\Upt;
use App\Models\db\UploadFolderUptPks;
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
                $q->where('namaupt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tipe', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Column-specific searches
        if ($request->has('search_namaupt') && !empty($request->search_namaupt)) {
            $query->where('namaupt', 'LIKE', '%' . $request->search_namaupt . '%');
        }
        if ($request->has('search_kanwil') && !empty($request->search_kanwil)) {
            $query->where('kanwil', 'LIKE', '%' . $request->search_kanwil . '%');
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
        $query = Upt::with('uploadFolderPks')->whereIn('tipe', ['vpas', 'reguler']);

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

        $providers = Provider::all();
        return view('db.upt.pks.indexPks', compact('data', 'providers'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        // PERBAIKAN: Gunakan uploadFolderPks
        $query = Upt::with('uploadFolderPks')->whereIn('tipe', ['vpas', 'reguler']);
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_upt_pks_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama UPT', 'Kanwil', 'Tipe', 'Tanggal Dibuat', 'Status Upload PDF']];
        $no = 1;
        foreach ($data as $d) {
            // PERBAIKAN: Gunakan uploadFolderPks
            $status = $this->calculatePdfStatus($d->uploadFolderPks);
            $rows[] = [
                $no++,
                $d->namaupt,
                $d->kanwil,
                ucfirst($d->tipe),
                \Carbon\Carbon::parse($d->tanggal)->format('d M Y'),
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
        // PERBAIKAN: Gunakan uploadFolderPks
        $query = Upt::with('uploadFolderPks')->whereIn('tipe', ['vpas', 'reguler']);
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();
        $data = $this->applyPdfStatusFilter($data, $request);

        $dataArray = [];
        foreach ($data as $d) {
            $dataItem = $d->toArray();
            // PERBAIKAN: Gunakan uploadFolderPks
            $dataItem['calculated_status'] = $this->calculatePdfStatus($d->uploadFolderPks);
            $dataArray[] = $dataItem;
        }

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            usort($dataArray, function ($a, $b) {
                $dateA = strtotime($a['tanggal']);
                $dateB = strtotime($b['tanggal']);
                return $dateA - $dateB;
            });
        }

        $pdfData = [
            'title' => 'List Data UPT PKS',
            'data' => $dataArray,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.db.upt.indexPks', $pdfData);
        $filename = 'list_upt_pks_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function DatabasePageDestroy($id)
    {
        try {
            $dataupt = Upt::findOrFail($id);

            // PERBAIKAN: Gunakan UploadFolderUptPks
            $uploadFolder = UploadFolderUptPks::where('upt_id', $id)->first();

            if ($uploadFolder) {
                // Hapus file PDF yang terkait
                if (!empty($uploadFolder->uploaded_pdf) && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
                }

                // Hapus record upload folder
                $uploadFolder->delete();
            }

            $dataupt->delete();
            return redirect()->route('dbpks.ListDataPks')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting UPT PKS: ' . $e->getMessage());
            return redirect()->route('dbpks.ListDataPks')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function viewUploadedPDF($id)
    {
        try {
            $upt = Upt::findOrFail($id);
            // PERBAIKAN: Gunakan UploadFolderUptPks
            $uploadFolder = UploadFolderUptPks::where('upt_id', $id)->first();

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

    public function uploadFilePDFPks(Request $request, $id)
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

            $upt = Upt::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // PERBAIKAN: Gunakan UploadFolderUptPks
            $uploadFolder = UploadFolderUptPks::firstOrCreate(
                ['upt_id' => $id],
                ['upt_id' => $id]
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
            $directory = 'upt/pks';
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

            Log::info("PDF uploaded successfully for UPT PKS ID: {$id}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF berhasil di-upload!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF PKS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function deleteFilePDF($id)
    {
        try {
            $upt = Upt::findOrFail($id);
            // PERBAIKAN: Gunakan UploadFolderUptPks
            $uploadFolder = UploadFolderUptPks::where('upt_id', $id)->first();

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
            Log::error('Error deleting PDF PKS: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }
}

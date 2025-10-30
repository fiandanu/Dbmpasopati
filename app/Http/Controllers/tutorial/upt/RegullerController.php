<?php

namespace App\Http\Controllers\tutorial\upt;

use App\Http\Controllers\Controller;
use App\Models\tutorial\upt\Reguller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegullerController extends Controller
{
    private function calculatePdfStatus($reguller)
    {
        $uploadedFolders = 0;
        $totalFolders = 10;

        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_'.$i;
            if (! empty($reguller->$column)) {
                $uploadedFolders++;
            }
        }

        if ($uploadedFolders == 0) {
            return 'Belum Upload';
        } elseif ($uploadedFolders == $totalFolders) {
            return '10/10 Folder';
        } else {
            return $uploadedFolders.'/'.$totalFolders.' Terupload';
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Global search
        if ($request->has('table_search') && ! empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tutor_reguller', 'LIKE', '%'.$searchTerm.'%')
                    ->orWhere('tanggal', 'LIKE', '%'.$searchTerm.'%');
            });
        }

        // Column-specific searches
        if ($request->has('search_judul_tutorial') && ! empty($request->search_judul_tutorial)) {
            $query->where('tutor_reguller', 'LIKE', '%'.$request->search_judul_tutorial.'%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_dibuat_dari') && ! empty($request->search_tanggal_dibuat_dari)) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dibuat_dari);
        }

        if ($request->has('search_tanggal_dibuat_sampai') && ! empty($request->search_tanggal_dibuat_sampai)) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_dibuat_sampai);
        }

        return $query;
    }

    private function applyPdfStatusFilter($data, Request $request)
    {
        if ($request->has('search_status') && ! empty($request->search_status)) {
            $statusSearch = strtolower($request->search_status);

            return $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculatePdfStatus($d));

                return strpos($status, $statusSearch) !== false;
            });
        }

        return $data;
    }

    public function TutorialUpt()
    {
        return view('tutorial.kategoriUpt');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tutor_reguller' => 'required|string|max:255',
        ], [
            'tutor_reguller.required' => 'Judul tutorial harus diisi.',
            'tutor_reguller.string' => 'Judul tutorial harus berupa teks.',
            'tutor_reguller.max' => 'Judul tutorial tidak boleh lebih dari 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['tanggal'] = date('Y-m-d');
        Reguller::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function ListDataSpp(Request $request)
    {
        $query = Reguller::query();

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
            // For paginated results, get all data first, apply status filter, then paginate
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

        return view('tutorial.upt.reguller', compact('data'));
    }

    public function DatabasePageDestroy($id)
    {
        try {
            $dataupt = Reguller::findOrFail($id);

            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_'.$i;
                if (! empty($dataupt->$column) && Storage::disk('public')->exists($dataupt->$column)) {
                    Storage::disk('public')->delete($dataupt->$column);
                }
            }

            $dataupt->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting Reguller: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $tutorial = Reguller::findOrFail($id);
            $column = 'pdf_folder_'.$folder;

            if (empty($tutorial->$column)) {
                return abort(404, 'File PDF belum diupload untuk folder '.$folder.'.');
            }

            $filePath = storage_path('app/public/'.$tutorial->$column);

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

            $tutorial = Reguller::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (! $file || ! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            $column = 'pdf_folder_'.$folder;
            if (! empty($tutorial->$column) && Storage::disk('public')->exists($tutorial->$column)) {
                Storage::disk('public')->delete($tutorial->$column);
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time().'_'.$sanitizedName.'.pdf';

            $directory = 'tutorial/upt/reguller/folder_'.$folder;
            if (! Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $path = $file->storeAs($directory, $filename, 'public');

            if (! $path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            $tutorial->$column = $path;
            $tutorial->save();

            Log::info("PDF uploaded successfully for Reguller ID: {$id}, Folder: {$folder}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF berhasil di-upload ke folder '.$folder.'!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF Reguller: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal upload file: '.$e->getMessage());
        }
    }

    public function deleteFilePDF($id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $tutorial = Reguller::findOrFail($id);
            $column = 'pdf_folder_'.$folder;

            if (empty($tutorial->$column)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload di folder '.$folder.'.');
            }

            if (Storage::disk('public')->exists($tutorial->$column)) {
                Storage::disk('public')->delete($tutorial->$column);
            }

            $tutorial->$column = null;
            $tutorial->save();

            return redirect()->back()->with('success', 'File PDF di folder '.$folder.' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF Reguller: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus file: '.$e->getMessage());
        }
    }

    public function exportListPdf(Request $request)
    {
        $query = Reguller::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dibuat_dari') || $request->filled('search_tanggal_dibuat_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyPdfStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dibuat_dari') || $request->filled('search_tanggal_dibuat_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $pdfData = [
            'title' => 'List Data Tutorial Reguler',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.tutorial.upt.indexReguler', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_tutorial_reguler_'.Carbon::now()->translatedFormat('d_M_Y').'.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Reguller::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dibuat_dari') || $request->filled('search_tanggal_dibuat_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyPdfStatusFilter($data, $request);

        // Additional sorting if date filter is applied
        if ($request->filled('search_tanggal_dibuat_dari') || $request->filled('search_tanggal_dibuat_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_tutorial_reguler_'.Carbon::now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Judul Tutorial', 'Tanggal Dibuat', 'Status Upload PDF']];
        $no = 1;
        foreach ($data as $d) {
            $status = $this->calculatePdfStatus($d);
            $rows[] = [
                $no++,
                $d->tutor_reguller,
                Carbon::parse($d->tanggal)->format('d M Y'),
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
}

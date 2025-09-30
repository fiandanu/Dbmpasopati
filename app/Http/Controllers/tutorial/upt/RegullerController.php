<?php

namespace App\Http\Controllers\tutorial\upt;

use App\Http\Controllers\Controller;
use App\Models\tutorial\upt\Reguller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class RegullerController extends Controller
{
    public function ListDataSpp(Request $request)
    {
        $query = Reguller::query();

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Handle pagination
        if ($perPage == 'all') {
            $data = $query->orderBy('created_at', 'desc')->get();

            // Create a mock paginator for "all" option
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $data->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
        }

        return view('tutorial.upt.reguller', compact('data'));
    }

    private function applyFilters($query, Request $request)
    {
        // Global search
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tutor_reguller', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Column-specific searches
        if ($request->has('search_judul_tutorial') && !empty($request->search_judul_tutorial)) {
            $query->where('tutor_reguller', 'LIKE', '%' . $request->search_judul_tutorial . '%');
        }

        if ($request->has('search_status') && !empty($request->search_status)) {
            $searchStatus = strtolower(trim($request->search_status));

            $query->where(function ($q) use ($searchStatus) {
                // Search by upload status keywords
                if (strpos($searchStatus, 'belum') !== false) {
                    // User searching for "belum upload" - find records with 0 uploads
                    $q->whereRaw('(
                        (pdf_folder_1 IS NULL OR pdf_folder_1 = "") AND
                        (pdf_folder_2 IS NULL OR pdf_folder_2 = "") AND
                        (pdf_folder_3 IS NULL OR pdf_folder_3 = "") AND
                        (pdf_folder_4 IS NULL OR pdf_folder_4 = "") AND
                        (pdf_folder_5 IS NULL OR pdf_folder_5 = "") AND
                        (pdf_folder_6 IS NULL OR pdf_folder_6 = "") AND
                        (pdf_folder_7 IS NULL OR pdf_folder_7 = "") AND
                        (pdf_folder_8 IS NULL OR pdf_folder_8 = "") AND
                        (pdf_folder_9 IS NULL OR pdf_folder_9 = "") AND
                        (pdf_folder_10 IS NULL OR pdf_folder_10 = "")
                    )');
                } elseif (strpos($searchStatus, 'selesai') !== false || strpos($searchStatus, '10/10') !== false || strpos($searchStatus, '10') !== false) {
                    // User searching for complete uploads - all 10 folders filled
                    $q->whereRaw('(
                        (pdf_folder_1 IS NOT NULL AND pdf_folder_1 != "") AND
                        (pdf_folder_2 IS NOT NULL AND pdf_folder_2 != "") AND
                        (pdf_folder_3 IS NOT NULL AND pdf_folder_3 != "") AND
                        (pdf_folder_4 IS NOT NULL AND pdf_folder_4 != "") AND
                        (pdf_folder_5 IS NOT NULL AND pdf_folder_5 != "") AND
                        (pdf_folder_6 IS NOT NULL AND pdf_folder_6 != "") AND
                        (pdf_folder_7 IS NOT NULL AND pdf_folder_7 != "") AND
                        (pdf_folder_8 IS NOT NULL AND pdf_folder_8 != "") AND
                        (pdf_folder_9 IS NOT NULL AND pdf_folder_9 != "") AND
                        (pdf_folder_10 IS NOT NULL AND pdf_folder_10 != "")
                    )');
                } elseif (strpos($searchStatus, 'proses') !== false || strpos($searchStatus, 'terupload') !== false) {
                    // User searching for in-progress uploads - some but not all folders filled
                    $q->whereRaw('NOT (
                        (pdf_folder_1 IS NULL OR pdf_folder_1 = "") AND
                        (pdf_folder_2 IS NULL OR pdf_folder_2 = "") AND
                        (pdf_folder_3 IS NULL OR pdf_folder_3 = "") AND
                        (pdf_folder_4 IS NULL OR pdf_folder_4 = "") AND
                        (pdf_folder_5 IS NULL OR pdf_folder_5 = "") AND
                        (pdf_folder_6 IS NULL OR pdf_folder_6 = "") AND
                        (pdf_folder_7 IS NULL OR pdf_folder_7 = "") AND
                        (pdf_folder_8 IS NULL OR pdf_folder_8 = "") AND
                        (pdf_folder_9 IS NULL OR pdf_folder_9 = "") AND
                        (pdf_folder_10 IS NULL OR pdf_folder_10 = "")
                    )')
                        ->whereRaw('NOT (
                        (pdf_folder_1 IS NOT NULL AND pdf_folder_1 != "") AND
                        (pdf_folder_2 IS NOT NULL AND pdf_folder_2 != "") AND
                        (pdf_folder_3 IS NOT NULL AND pdf_folder_3 != "") AND
                        (pdf_folder_4 IS NOT NULL AND pdf_folder_4 != "") AND
                        (pdf_folder_5 IS NOT NULL AND pdf_folder_5 != "") AND
                        (pdf_folder_6 IS NOT NULL AND pdf_folder_6 != "") AND
                        (pdf_folder_7 IS NOT NULL AND pdf_folder_7 != "") AND
                        (pdf_folder_8 IS NOT NULL AND pdf_folder_8 != "") AND
                        (pdf_folder_9 IS NOT NULL AND pdf_folder_9 != "") AND
                        (pdf_folder_10 IS NOT NULL AND pdf_folder_10 != "")
                    )');
                }
            });
        }

        // Date range filtering
        if ($request->has('search_tanggal_dibuat_dari') && !empty($request->search_tanggal_dibuat_dari)) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dibuat_dari);
        }

        if ($request->has('search_tanggal_dibuat_sampai') && !empty($request->search_tanggal_dibuat_sampai)) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_dibuat_sampai);
        }

        return $query;
    }

    public function TutorialUpt()
    {
        return view('tutorial.kategoriUpt');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tutor_reguller' => 'required|string|max:255',
            ],
            [
                'tutor_reguller.required' => 'Judul tutorial harus diisi.',
                'tutor_reguller.string' => 'Judul tutorial harus berupa teks.',
                'tutor_reguller.max' => 'Judul tutorial tidak boleh lebih dari 255 karakter.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            $data = $request->all();
            $data['tanggal'] = Carbon::now()->format('Y-m-d');

            Reguller::create($data);

            return redirect()->back()->with('success', 'Data tutorial berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function DatabasePageDestroy($id)
    {
        try {
            $dataupt = Reguller::findOrFail($id);
            $tutorName = $dataupt->tutor_reguller;

            // Delete all PDF files in 10 folders
            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_' . $i;
                if (!empty($dataupt->$column) && Storage::disk('public')->exists($dataupt->$column)) {
                    Storage::disk('public')->delete($dataupt->$column);
                }
            }

            $dataupt->delete();

            return redirect()->back()->with('success', "Tutorial '{$tutorName}' beserta semua PDF berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            // Validate folder number
            if (!in_array($folder, range(1, 10))) {
                abort(400, 'Folder tidak valid. Pilih folder antara 1-10.');
            }

            $tutorial = Reguller::findOrFail($id);
            $column = 'pdf_folder_' . $folder;

            // Check if PDF exists
            if (empty($tutorial->$column)) {
                abort(404, 'File PDF belum diupload untuk folder ' . $folder . '.');
            }

            $filePath = storage_path('app/public/' . $tutorial->$column);

            // Check if file exists in storage
            if (!file_exists($filePath)) {
                abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Tutorial tidak ditemukan.');
        } catch (\Exception $e) {
            abort(500, 'Error loading PDF: ' . $e->getMessage());
        }
    }

    public function uploadFilePDF(Request $request, $id, $folder)
    {
        try {
            // Validate folder number
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid. Pilih folder antara 1-10.');
            }

            // Validate file upload
            $validator = Validator::make(
                $request->all(),
                [
                    'uploaded_pdf' => 'required|file|mimes:pdf|max:10240',
                ],
                [
                    'uploaded_pdf.required' => 'File PDF harus dipilih.',
                    'uploaded_pdf.file' => 'Upload harus berupa file.',
                    'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                    'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.'
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', 'File tidak valid atau melebihi ukuran maksimal.');
            }

            // Check if file exists in request
            if (!$request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $tutorial = Reguller::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            // Validate file is valid
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid atau corrupt!');
            }

            $column = 'pdf_folder_' . $folder;

            // Delete old PDF if exists
            if (!empty($tutorial->$column) && Storage::disk('public')->exists($tutorial->$column)) {
                Storage::disk('public')->delete($tutorial->$column);
            }

            // Generate safe filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName . '.pdf';

            // Create directory if not exists
            $directory = 'tutorial/upt/reguller/folder_' . $folder;
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Store file
            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file ke storage!');
            }

            // Update database
            $tutorial->$column = $path;
            $tutorial->save();

            return redirect()->back()->with('success', "PDF berhasil di-upload ke folder {$folder}!");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Tutorial tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali file yang diupload.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function deleteFilePDF($id, $folder)
    {
        try {
            // Validate folder number
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid. Pilih folder antara 1-10.');
            }

            $tutorial = Reguller::findOrFail($id);
            $column = 'pdf_folder_' . $folder;

            // Check if PDF exists
            if (empty($tutorial->$column)) {
                return redirect()->back()->with('error', "File PDF belum di-upload di folder {$folder}.");
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($tutorial->$column)) {
                Storage::disk('public')->delete($tutorial->$column);
            }

            // Update database
            $tutorial->$column = null;
            $tutorial->save();

            return redirect()->back()->with('success', "File PDF di folder {$folder} berhasil dihapus!");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Tutorial tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
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

        $data = $query->orderBy('created_at', 'desc')->get();

        // Calculate upload status for each record
        $data->map(function ($item) {
            $uploadedFolders = 0;
            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_' . $i;
                if (!empty($item->$column)) {
                    $uploadedFolders++;
                }
            }
            $item->uploaded_folders = $uploadedFolders;
            return $item;
        });

        $pdfData = [
            'title' => 'List Data Tutorial Reguler',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.tutorial.upt.reguller', $pdfData);
        $filename = 'list_tutorial_reguler_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

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

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'list_tutorial_reguler_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Judul Tutorial', 'Tanggal Dibuat', 'Status Upload', 'Folder Terupload', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $uploadedFolders = 0;
            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_' . $i;
                if (!empty($row->$column)) {
                    $uploadedFolders++;
                }
            }

            $statusUpload = '';
            if ($uploadedFolders == 0) {
                $statusUpload = 'Belum Upload';
            } elseif ($uploadedFolders == 10) {
                $statusUpload = '10/10 Folder';
            } else {
                $statusUpload = $uploadedFolders . '/10 Terupload';
            }

            $rows[] = [
                $no++,
                $row->tutor_reguller,
                $row->tanggal ? Carbon::parse($row->tanggal)->format('d M Y') : '',
                $statusUpload,
                $uploadedFolders,
                $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : ''
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

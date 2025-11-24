<?php

namespace App\Http\Controllers\user\ponpes\spp;

use App\Http\Controllers\Controller;
use App\Models\db\ponpes\UploadFolderPonpesSpp;
use App\Models\user\ponpes\Ponpes;
use App\Models\user\provider\Provider;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class SppController extends Controller
{
    private function getJenisLayanan()
    {
        return [
            'vtren' => 'VTREN',
            'reguler' => 'Reguler',
            'vtrenreg' => 'VTREN + Reguler',
        ];
    }

    private function removeVpasRegSuffix($namaPonpes)
    {
        return preg_replace('/\s*\(VtrenReg\)$/', '', $namaPonpes);
    }

    private function groupVpasRegData($allData)
    {
        $grouped = collect();
        $processed = [];

        foreach ($allData as $item) {
            $baseNama = $this->removeVpasRegSuffix($item->nama_ponpes);

            if (in_array($baseNama, $processed)) {
                continue;
            }

            // Cari semua data dengan nama base yang sama
            $relatedItems = $allData->filter(function ($d) use ($baseNama) {
                return $this->removeVpasRegSuffix($d->nama_ponpes) === $baseNama;
            });

            if ($relatedItems->count() === 2) {
                // Ada 2 tipe (reguler + vtren), gabungkan menjadi satu baris
                $mergedItem = $relatedItems->first();

                // Set jenis_layanan sebagai vtrenreg
                $mergedItem->jenis_layanan = 'vtrenreg';
                $mergedItem->combined_ids = $relatedItems->pluck('id')->toArray();
                $mergedItem->is_combined = true;

                $grouped->push($mergedItem);
            } else {
                // Hanya 1 tipe
                $item->jenis_layanan = $item->tipe;
                $item->combined_ids = [$item->id];
                $item->is_combined = false;
                $grouped->push($item);
            }

            $processed[] = $baseNama;
        }

        return $grouped;
    }
    private function calculateStatus($uploadFolder)
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
        if ($request->has('search_namaponpes') && ! empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%'.$request->search_namaponpes.'%');
        }

        if ($request->has('search_wilayah') && ! empty($request->search_wilayah)) {
            $query->whereHas('namaWilayah', function ($q) use ($request) {
                $q->where('nama_wilayah', 'LIKE', '%'.$request->search_wilayah.'%');
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
                $status = strtolower($this->calculateStatus($d->uploadFolderSpp));

                return strpos($status, $statusSearch) !== false;
            });
        }

        return $data;
    }

public function ListDataSpp(Request $request)
{
    $query = Ponpes::with(['uploadFolderSpp', 'namaWilayah'])
        ->whereHas('uploadFolderSpp');

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
        $data = $this->groupVpasRegData($data);
        $data = $this->applyPdfStatusFilter(collect($data), $request);

        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $data,
            $data->count(),
            99999,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    } else {
        $allData = $query->orderBy('tanggal', 'desc')->get();
        $allData = $this->groupVpasRegData($allData);
        $filteredData = $this->applyPdfStatusFilter($allData, $request);

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
        ->whereDoesntHave('uploadFolderSpp')
        ->orderBy('nama_ponpes')
        ->get()
        ->unique(function ($item) {
            return preg_replace('/\s*\(VtrenReg\)$/', '', $item->nama_ponpes);
        });

    $providers = Provider::all();
    $jenisLayananOptions = $this->getJenisLayanan();

    return view('db.ponpes.spp.indexSpp', compact('data', 'providers', 'ponpesList', 'jenisLayananOptions'));
}

public function exportListCsv(Request $request): StreamedResponse
{
    $query = Ponpes::with(['uploadFolderSpp', 'namaWilayah'])->whereIn('tipe', ['vtren', 'reguler'])->whereHas('uploadFolderSpp');
    $query = $this->applyFilters($query, $request);

    if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
        $query = $query->orderBy('tanggal', 'asc');
    }

    $allData = $query->get();
    $data = $this->groupVpasRegData($allData);
    $data = $this->applyPdfStatusFilter($data, $request);

    if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
        $data = $data->sortBy('tanggal')->values();
    }

    $filename = 'list_ponpes_spp_'.Carbon::now()->format('Y-m-d_H-i-s').'.csv';

    $headers = [
        'Content-type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0',
    ];

    $jenisLayanan = $this->getJenisLayanan();
    $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Jenis Layanan', 'Tanggal Dibuat', 'Status Upload PDF']];
    $no = 1;

    foreach ($data as $d) {
        $status = $this->calculateStatus($d->uploadFolderSpp);
        $layanan = $jenisLayanan[$d->jenis_layanan] ?? ucfirst($d->jenis_layanan ?? '-');

        $rows[] = [
            $no++,
            $d->nama_ponpes,
            $d->namaWilayah->nama_wilayah ?? '-',
            $layanan,
            $d->tanggal ? Carbon::parse($d->tanggal)->format('d M Y') : '-',
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
    $query = Ponpes::with('uploadFolderSpp', 'namaWilayah')->whereIn('tipe', ['vtren', 'reguler'])->whereHas('uploadFolderSpp');
    $query = $this->applyFilters($query, $request);

    if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
        $query = $query->orderBy('tanggal', 'asc');
    }

    $allData = $query->get();
    $data = $this->groupVpasRegData($allData);
    $data = $this->applyPdfStatusFilter($data, $request);

    $jenisLayanan = $this->getJenisLayanan();

    $pdfData = [
        'title' => 'List Data Ponpes SPP',
        'data' => $data,
        'jenisLayanan' => $jenisLayanan,
        'generated_at' => Carbon::now()->format('d M Y H:i:s'),
    ];

    $pdf = Pdf::loadView('export.public.db.ponpes.indexSpp', $pdfData)
        ->setPaper('a4', 'landscape');
    $filename = 'list_ponpes_spp_'.Carbon::now()->translatedFormat('d_M_Y').'.pdf';

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
                    $column = 'pdf_folder_'.$i;
                    if (! empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                        Storage::disk('public')->delete($uploadFolder->$column);
                    }
                }

                // Hapus record upload folder
                $uploadFolder->delete();
            }

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting Ponpes SPP: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpesSpp::where('data_ponpes_id', $id)->first();

            if (! $uploadFolder) {
                return abort(404, 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_'.$folder;

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
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240',
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.',
            ]);

            if (! $request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $ponpes = Ponpes::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (! $file || ! $file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // PERBAIKAN: Gunakan UploadFolderPonpesSpp
            $uploadFolder = UploadFolderPonpesSpp::firstOrCreate(
                ['data_ponpes_id' => $id],
                ['data_ponpes_id' => $id]
            );

            // Hapus file lama jika ada
            $column = 'pdf_folder_'.$folder;
            if (! empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            // Buat nama file unik dengan sanitasi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time().'_'.$sanitizedName.'.pdf';

            // Pastikan direktori ada
            $directory = 'ponpes/spp/folder_'.$folder;
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

            Log::info("PDF uploaded successfully for Ponpes ID: {$id}, Folder: {$folder}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF berhasil di-upload ke folder '.$folder.'!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal upload file: '.$e->getMessage());
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

        // Cari SEMUA data Ponpes dengan nama yang sama (bisa reguler, vtren, atau keduanya)
        $ponpesData = Ponpes::where('nama_ponpes', $request->nama_ponpes)->get();

        if ($ponpesData->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Data Ponpes tidak ditemukan')
                ->withInput();
        }

        $createdCount = 0;
        $alreadyExistsCount = 0;

        // Loop untuk setiap data Ponpes yang ditemukan (bisa 1 atau 2)
        foreach ($ponpesData as $ponpes) {
            // Cek apakah sudah ada di SPP
            $exists = UploadFolderPonpesSpp::where('data_ponpes_id', $ponpes->id)->exists();

            if (!$exists) {
                // Create UploadFolder untuk menandai data sudah ditambahkan ke SPP
                UploadFolderPonpesSpp::create([
                    'data_ponpes_id' => $ponpes->id
                ]);
                $createdCount++;
            } else {
                $alreadyExistsCount++;
            }
        }

        if ($createdCount > 0 && $alreadyExistsCount == 0) {
            return redirect()->back()
                ->with('success', 'Data SPP berhasil ditambahkan untuk ' . $createdCount . ' tipe layanan');
        } elseif ($createdCount > 0 && $alreadyExistsCount > 0) {
            return redirect()->back()
                ->with('success', 'Data SPP berhasil ditambahkan untuk ' . $createdCount . ' tipe layanan. ' . $alreadyExistsCount . ' tipe sudah ada sebelumnya.');
        } else {
            return redirect()->back()
                ->with('error', 'Data SPP sudah ada untuk semua tipe layanan Ponpes ini');
        }

    } catch (\Exception $e) {
        Log::error('Error creating SPP: '.$e->getMessage());

        return redirect()->back()->with('error', 'Gagal menambahkan data: '.$e->getMessage())->withInput();
    }
}

    public function deleteFilePDF($id, $folder)
    {
        try {
            if (! in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpesSpp::where('data_ponpes_id', $id)->first();

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
}

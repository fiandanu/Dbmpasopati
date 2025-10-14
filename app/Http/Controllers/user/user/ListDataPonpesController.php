<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use App\Models\user\Provider;
use Illuminate\Http\Request;
use App\Models\user\Ponpes;
use App\Models\user\Vpn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\user\NamaWilayah;

class ListDataPonpesController extends Controller
{
    private $optionalFields = [
        'pic_upt',
        'no_telpon',
        'alamat',
        'jumlah_wbp',
        'jumlah_line',
        'provider_internet',
        'kecepatan_internet',
        'tarif_wartel',
        'status_wartel',
        'akses_topup_pulsa',
        'password_topup',
        'akses_download_rekaman',
        'password_download',
        'internet_protocol',
        'vpn_user',
        'vpn_password',
        'jenis_vpn',
        'jumlah_extension',
        'no_extension',
        'extension_password',
        'pin_tes',
        'no_pemanggil',
        'email_airdroid',
        'password'
    ];

    private function calculateStatus($dataOpsional)
    {
        if (!$dataOpsional) {
            return 'Belum di Update';
        }
        $filledFields = 0;
        foreach ($this->optionalFields as $field) {
            if (!empty($dataOpsional->$field)) {
                $filledFields++;
            }
        }
        $totalFields = count($this->optionalFields);
        $percentage = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;

        if ($filledFields == 0) {
            return 'Belum di Update';
        } elseif ($filledFields == $totalFields) {
            return 'Sudah Update';
        } else {
            return "Sebagian ({$percentage}%)";
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Global search
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('namaWilayah', function ($q) use ($searchTerm) { // PERBAIKAN
                        $q->where('nama_wilayah', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('dataOpsional', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('pic_upt', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        // Column-specific searches
        if ($request->has('search_namaponpes') && !empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }
        if ($request->has('search_wilayah') && !empty($request->search_wilayah)) {
            $query->whereHas('namaWilayah', function ($q) use ($request) { // PERBAIKAN
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


    private function applyStatusFilter($data, Request $request)
    {
        if ($request->has('search_status') && !empty($request->search_status)) {
            $statusSearch = strtolower($request->search_status);
            return $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculateStatus($d->dataOpsional));
                return strpos($status, $statusSearch) !== false;
            });
        }
        return $data;
    }

    // Create mock paginator for "all" option
    private function createMockPaginator($data, Request $request)
    {
        return new LengthAwarePaginator(
            $data,
            $data->count(),
            $data->count(),
            1,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page'
            ]
        );
    }

    // Remove VtrenReg suffix from Ponpes name
    private function removeVtrenRegSuffix($namaPonpes)
    {
        return preg_replace('/\s*\(VtrenReg\)$/', '', $namaPonpes);
    }

    // Update Ponpes names based on remaining data after deletion
    private function updatePonpesNamesBySuffix($namaPonpesBase)
    {
        // Cari semua data dengan nama Ponpes base yang sama (dengan atau tanpa suffix)
        $relatedData = Ponpes::where(function ($query) use ($namaPonpesBase) {
            $query->where('nama_ponpes', $namaPonpesBase)
                ->orWhere('nama_ponpes', $namaPonpesBase . ' (VtrenReg)');
        })->get();

        // Jika hanya tersisa 1 data, hapus suffix (VtrenReg)
        if ($relatedData->count() == 1) {
            $remainingData = $relatedData->first();
            if (str_contains($remainingData->nama_ponpes, '(VtrenReg)')) {
                $remainingData->update(['nama_ponpes' => $namaPonpesBase]);
            }
        }
        // Jika masih ada 2 data (reguler dan vtren), pastikan keduanya menggunakan suffix
        elseif ($relatedData->count() == 2) {
            foreach ($relatedData as $data) {
                if (!str_contains($data->nama_ponpes, '(VtrenReg)')) {
                    $data->update(['nama_ponpes' => $namaPonpesBase . ' (VtrenReg)']);
                }
            }
        }
    }

    public function UserPage(Request $request)
    {
        // Get Ponpes data with reguler and vtren types, include relationships
        $query = Ponpes::with(['dataOpsional', 'uploadFolderSpp', 'uploadFolderPks', 'namaWilayah']) // PERBAIKAN: Ganti uploadFolderSpp/Pks dengan dbPonpesSpp/Pks, tambah namaWilayah
            ->whereIn('tipe', ['reguler', 'vtren']);

        // Apply database filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Apply ordering
        $query->orderBy('tanggal', 'desc');

        // Handle pagination
        if ($perPage === 'all') {
            $data = $query->get();
            $data = $this->applyStatusFilter(collect($data), $request);

            // Create a mock paginator for "all" option
            $data = $this->createMockPaginator($data, $request);
        } else {
            // For paginated results, we need to get all data first, apply status filter, then paginate
            $allData = $query->get();
            $filteredData = $this->applyStatusFilter($allData, $request);

            // Manual pagination of filtered data
            $currentPage = Paginator::resolveCurrentPage('page');
            $offset = ($currentPage - 1) * $perPage;
            $itemsForCurrentPage = $filteredData->slice($offset, $perPage)->values();

            $data = new LengthAwarePaginator(
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

        // Get providers and VPN data for the modals
        $providers = Provider::all();
        $vpns = Vpn::all();

        $datanamawilayah = NamaWilayah::all();

        return view('user.indexPonpes', compact('data', 'providers', 'vpns', 'datanamawilayah'));
    }

    public function UserPageStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string',
                'nama_wilayah_id' => 'required|exists:nama_wilayah,id',
                'tipe' => 'required|array|min:1',
                'tipe.*' => 'in:reguler,vtren',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi',
                'nama_wilayah_id.required' => 'Nama Wilayah harus diisi',
                'nama_wilayah_id.exists' => 'Nama Wilayah tidak valid',
                'tipe.required' => 'Tipe harus dipilih minimal satu',
                'tipe.array' => 'Tipe harus berupa array',
                'tipe.min' => 'Pilih minimal satu tipe',
                'tipe.*.in' => 'Tipe hanya boleh reguler atau vtren',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data tipe yang dipilih
        $selectedTypes = $request->tipe;
        $createdRecords = [];

        // Bersihkan nama Ponpes dari suffix ganda yang mungkin ada
        $cleanNamaPonpes = $this->removeVtrenRegSuffix($request->nama_ponpes);

        // Tentukan nama Ponpes berdasarkan jumlah tipe yang dipilih
        $namaPonpes = $cleanNamaPonpes;
        if (count($selectedTypes) == 2 && in_array('reguler', $selectedTypes) && in_array('vtren', $selectedTypes)) {
            $namaPonpes = $cleanNamaPonpes . ' (VtrenReg)';
        }

        // Validasi manual untuk kombinasi nama Ponpes + tipe
        foreach ($selectedTypes as $tipeValue) {
            $existingRecord = Ponpes::where('nama_ponpes', $namaPonpes)
                ->where('tipe', $tipeValue)
                ->first();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Data Ponpes '{$namaPonpes}' dengan tipe '{$tipeValue}' sudah ada!");
            }
        }

        // Loop untuk setiap tipe yang dipilih
        foreach ($selectedTypes as $tipeValue) {
            // Buat record baru untuk setiap tipe
            $dataponpes = [
                'nama_ponpes' => $namaPonpes,
                'nama_wilayah_id' => $request->nama_wilayah_id,
                'tipe' => $tipeValue,
                'tanggal' => Carbon::now()->format('Y-m-d'),
            ];

            $newRecord = Ponpes::create($dataponpes);
            $createdRecords[] = $tipeValue;
        }

        // Berikan pesan berdasarkan hasil
        if (count($createdRecords) > 0) {
            $message = 'Data Ponpes berhasil ditambahkan untuk tipe: ' . implode(', ', $createdRecords);
            return redirect()->route('UserPonpes.UserPage')->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data Ponpes');
        }
    }

    public function UserPageDestroy($id)
    {
        $dataponpes = Ponpes::find($id);

        if (!$dataponpes) {
            return redirect()->route('UserPonpes.UserPage')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama Ponpes tanpa suffix (VtrenReg) untuk pengecekan
        $namaPonpesBase = $this->removeVtrenRegSuffix($dataponpes->nama_ponpes);

        // Hapus data yang dipilih
        $dataponpes->delete();

        // Update nama Ponpes yang tersisa berdasarkan jumlah data
        $this->updatePonpesNamesBySuffix($namaPonpesBase);

        return redirect()->route('UserPonpes.UserPage')->with('success', 'Data berhasil dihapus!');
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($id, $request) {
                        // Cek apakah ada record lain dengan nama yang sama dan tipe yang sama
                        $existingRecord = Ponpes::where('nama_ponpes', $value)
                            ->where('id', '!=', $id)
                            ->where('tipe', $request->tipe)
                            ->first();

                        if ($existingRecord) {
                            $fail("Nama Ponpes '{$value}' dengan tipe '{$request->tipe}' sudah ada.");
                        }
                    }
                ],
                'nama_wilayah_id' => 'required|exists:nama_wilayah,id|string',
                'tipe' => 'required|string|in:reguler,vtren',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi',
                'nama_wilayah_id.required' => 'Nama Wilayah harus diisi',
                'nama_wilayah_id.exists' => 'Nama Wilayah tidak valid',
                'tipe.required' => 'Tipe harus diisi',
                'tipe.in' => 'Tipe hanya boleh reguler atau vtren',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataponpes = Ponpes::findOrFail($id);
        $dataponpes->nama_ponpes = $request->nama_ponpes;
        $dataponpes->nama_wilayah_id  = $request->nama_wilayah_id;
        $dataponpes->tipe = $request->tipe;
        $dataponpes->save();

        return redirect()->route('UserPonpes.UserPage')->with('success', 'Data Ponpes berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with(['dataOpsional', 'namaWilayah'])->whereIn('tipe', ['reguler', 'vtren']); // PERBAIKAN: Tambah namaWilayah
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyStatusFilter($data, $request);

        // Additional sorting if date filter is applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_ponpes_reguler_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Tipe', 'Tanggal Dibuat', 'Status Update']];
        $no = 1;
        foreach ($data as $d) {
            $status = $this->calculateStatus($d->dataOpsional);
            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->namaWilayah->nama_wilayah ?? '-', // PERBAIKAN: Gunakan relasi
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
        $query = Ponpes::with(['dataOpsional', 'namaWilayah'])->whereIn('tipe', ['reguler', 'vtren']); // PERBAIKAN: Tambah namaWilayah
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyStatusFilter($data, $request);

        // Convert collection to array with calculated status
        $dataArray = [];
        foreach ($data as $d) {
            $dataItem = $d->toArray();
            $dataItem['calculated_status'] = $this->calculateStatus($d->dataOpsional);
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
            'title' => 'List Data Ponpes Reguler',
            'data' => $dataArray,
            'optionalFields' => $this->optionalFields,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.indexPonpes', $pdfData);
        $filename = 'list_ponpes_reguler_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

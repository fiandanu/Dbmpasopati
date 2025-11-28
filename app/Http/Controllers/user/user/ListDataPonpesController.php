<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use App\Models\user\NamaWilayah\NamaWilayah;
use App\Models\user\ponpes\Ponpes;
use App\Models\user\provider\Provider;
use App\Models\user\vpn\Vpn;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        'password',
    ];

    private function calculateStatus($dataOpsional)
    {
        if (! $dataOpsional) {
            return 'Belum di Update';
        }
        $filledFields = 0;
        foreach ($this->optionalFields as $field) {
            if (! empty($dataOpsional->$field)) {
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
        if ($request->has('table_search') && ! empty($request->table_search)) {
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
        if ($request->has('search_namaponpes') && ! empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }
        if ($request->has('search_wilayah') && ! empty($request->search_wilayah)) {
            $query->whereHas('namaWilayah', function ($q) use ($request) { // PERBAIKAN
                $q->where('nama_wilayah', 'LIKE', '%' . $request->search_wilayah . '%');
            });
        }
        if ($request->has('search_tipe') && ! empty($request->search_tipe)) {
            $query->where('tipe', 'LIKE', '%' . $request->search_tipe . '%');
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

    private function applyStatusFilter($data, Request $request)
    {
        if ($request->has('search_status') && ! empty($request->search_status)) {
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
                'pageName' => 'page',
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
                if (! str_contains($data->nama_ponpes, '(VtrenReg)')) {
                    $data->update(['nama_ponpes' => $namaPonpesBase . ' (VtrenReg)']);
                }
            }
        }
    }

    private function groupVtrenRegData($allData)
    {
        $grouped = collect();
        $processed = [];

        foreach ($allData as $item) {
            $baseNama = $this->removeVtrenRegSuffix($item->nama_ponpes);

            if (in_array($baseNama, $processed)) {
                continue;
            }

            // Cari semua data dengan nama base yang sama
            $relatedItems = $allData->filter(function ($d) use ($baseNama) {
                return $this->removeVtrenRegSuffix($d->nama_ponpes) === $baseNama;
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

    private function getJenisLayanan()
    {
        return [
            'vtren' => 'Vtren',
            'reguler' => 'Reguler',
            'vtrenreg' => 'Vtren + Reguler',
        ];
    }


    public function index(Request $request)
    {
        // Get Ponpes data with reguler and vtren types, include relationships
        $query = Ponpes::with(['dataOpsional', 'uploadFolderSpp', 'uploadFolderPks', 'namaWilayah']) // PERBAIKAN: Ganti uploadFolderSpp/Pks dengan dbPonpesSpp/Pks, tambah namaWilayah
            ->whereIn('tipe', ['reguler', 'vtren']);

        // Apply database filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Apply ordering
        $query->orderBy('tanggal', 'desc');

        $allData = $query->get();
        $groupedData  = $this->groupVtrenRegData($allData);

        $groupedData = $this->applyStatusFilter($groupedData, $request);

        $totalDataPonpes = $groupedData->count();

        // Handle pagination
        if ($perPage === 'all') {
            // Create a mock paginator for "all" option
            $data = $this->createMockPaginator($groupedData, $request);
        } else {
            $totalItems = $groupedData->count();
            $lastPage = (int) ceil($totalItems / $perPage);
            $currentPage = Paginator::resolveCurrentPage('page');

            // Manual pagination of filtered data
            $offset = ($currentPage - 1) * $perPage;
            $itemsForCurrentPage = $groupedData->slice($offset, $perPage)->values();

            $data = new LengthAwarePaginator(
                $itemsForCurrentPage,
                $totalItems,
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'page',
                ]
            );
        }

        // Get providers and VPN data for the modals
        $providers = Provider::all();
        $vpns = Vpn::all();

        $datanamawilayah = NamaWilayah::all();
        $jenisLayananOptions = $this->getJenisLayanan();

        return view(
            'user.indexPonpes',
            compact(
                'data',
                'providers',
                'vpns',
                'datanamawilayah',
                'jenisLayananOptions',
                'totalDataPonpes'
            )
        );
    }

    public function store(Request $request)
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
                    ->with('warning', "Data Ponpes '{$namaPonpes}' dengan tipe '{$tipeValue}' sudah ada!");
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

            return redirect()->route('UserPonpes.ponpes.index')->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data Ponpes');
        }
    }

    public function destroy($id)
    {
        $dataponpes = Ponpes::find($id);

        if (! $dataponpes) {
            return redirect()->route('UserPonpes.ponpes.index')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama Ponpes tanpa suffix (VtrenReg) untuk pengecekan
        $namaPonpesBase = $this->removeVtrenRegSuffix($dataponpes->nama_ponpes);

        // Hapus data yang dipilih
        $dataponpes->delete();

        // Update nama Ponpes yang tersisa berdasarkan jumlah data
        $this->updatePonpesNamesBySuffix($namaPonpesBase);

        return redirect()->route('UserPonpes.ponpes.index')->with('success', 'Data berhasil dihapus!');
    }

    public function update(Request $request, $id)
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
                    },
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
        $dataponpes->nama_wilayah_id = $request->nama_wilayah_id;
        $dataponpes->tipe = $request->tipe;
        $dataponpes->save();

        return redirect()->route('UserPonpes.ponpes.index')->with('success', 'Data Ponpes berhasil diupdate!');
    }


    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with(['dataOpsional', 'namaWilayah'])->whereIn('tipe', ['reguler', 'vtren']);
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $allData = $query->get();
        $data = $this->groupVtrenRegData($allData);
        $data = $this->applyStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_ponpes_reguler_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $jenisLayanan = $this->getJenisLayanan();
        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Jenis Layanan', 'Tanggal Dibuat']]; // ← 'Tipe' jadi 'Jenis Layanan'
        $no = 1;

        foreach ($data as $d) {
            $layanan = $jenisLayanan[$d->jenis_layanan] ?? $d->jenis_layanan;

            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->namaWilayah->nama_wilayah ?? '-',
                $layanan, // ← pakai $layanan, bukan ucfirst($d->tipe)
                Carbon::parse($d->tanggal)->format('d M Y'),
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
        $query = Ponpes::with(['dataOpsional', 'namaWilayah'])->whereIn('tipe', ['reguler', 'vtren']);
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        // PERBAIKAN: Tambahkan limit 1000 untuk menghindari memory issues
        $allData = $query->limit(1000)->get();
        $data = $this->groupVtrenRegData($allData);
        $data = $this->applyStatusFilter($data, $request);

        $jenisLayanan = $this->getJenisLayanan();

        $dataArray = [];
        foreach ($data as $d) {
            $layanan = $jenisLayanan[$d->jenis_layanan] ?? $d->jenis_layanan;

            $dataArray[] = [
                'nama_ponpes' => $d->nama_ponpes,
                'nama_wilayah' => $d->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => $layanan,
                'tanggal' => $d->tanggal,
            ];
        }

        // Additional sorting menggunakan field tanggal yang benar
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            usort($dataArray, function ($a, $b) {
                $dateA = strtotime($a['tanggal']);
                $dateB = strtotime($b['tanggal']);

                return $dateA - $dateB;
            });
        }

        $pdfData = [
            'title' => 'List Data Ponpes',
            'data' => $data,
            'dataArray' => $dataArray,
            'jenisLayanan' => $jenisLayanan,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
            'total_records' => count($dataArray),
        ];

        $pdf = Pdf::loadView('export.public.user.indexPonpes', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_ponpes_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

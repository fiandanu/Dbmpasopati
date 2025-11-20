<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use App\Models\user\kanwil\Kanwil;
use App\Models\user\provider\Provider;
use App\Models\user\upt\Upt;
use App\Models\user\vpn\Vpn;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListDataUptController extends Controller
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
        'no_pemanggil',
        'email_airdroid',
        'password',
        'pin_tes',
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

        // Column-specific searches
        if ($request->has('search_namaupt') && ! empty($request->search_namaupt)) {
            $query->where('namaupt', 'LIKE', '%' . $request->search_namaupt . '%');
        }
        if ($request->has('search_kanwil') && ! empty($request->search_kanwil)) {
            $query->whereHas('kanwil', function ($q) use ($request) {
                $q->where('kanwil', 'LIKE', '%' . $request->search_kanwil . '%');
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

    // MISSING METHOD: Create mock paginator for "all" option
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

    // MISSING METHOD: Remove VpasReg suffix from UPT name

    private function removeVpasRegSuffix($namaUpt)
    {
        return preg_replace('/\s*\(VpasReg\)$/', '', $namaUpt);
    }

    // MISSING METHOD: Update UPT names based on remaining data after deletion
    private function updateUptNamesBySuffix($namaUptBase)
    {
        // Cari semua data dengan nama UPT base yang sama (dengan atau tanpa suffix)
        $relatedData = Upt::where(function ($query) use ($namaUptBase) {
            $query->where('namaupt', $namaUptBase)
                ->orWhere('namaupt', $namaUptBase . ' (VpasReg)');
        })->get();

        // Jika hanya tersisa 1 data, hapus suffix (VpasReg)
        if ($relatedData->count() == 1) {
            $remainingData = $relatedData->first();
            if (str_contains($remainingData->namaupt, '(VpasReg)')) {
                $remainingData->update(['namaupt' => $namaUptBase]);
            }
        }
        // Jika masih ada 2 data (reguler dan vpas), pastikan keduanya menggunakan suffix
        elseif ($relatedData->count() == 2) {
            foreach ($relatedData as $data) {
                if (! str_contains($data->namaupt, '(VpasReg)')) {
                    $data->update(['namaupt' => $namaUptBase . ' (VpasReg)']);
                }
            }
        }
    }

    private function getJenisLayanan()
    {
        return [
            'vpas' => 'VPAS',
            'reguler' => 'Reguler',
            'vpasreg' => 'VPAS + Reguler',
        ];
    }

    public function UserPage(Request $request)
    {
        // Get UPT data with reguler and vpas types, include relationships
        $query = Upt::with(['dataOpsional', 'uploadFolder'])
            ->whereIn('tipe', ['reguler', 'vpas']);

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

        // MODIFIKASI: Group data by namaUpt base untuk menggabungkan VpasReg
        $allData = $query->get();
        $groupedData = $this->groupVpasRegData($allData);

        // Apply status filter
        $groupedData = $this->applyStatusFilter(collect($groupedData), $request);

        // Handle pagination
        if ($perPage === 'all') {
            $data = $this->createMockPaginator($groupedData, $request);
        } else {
            $totalItems = $groupedData->count();
            $lastPage = (int) ceil($totalItems / $perPage);
            $currentPage = Paginator::resolveCurrentPage('page');

            if ($currentPage > $lastPage && $lastPage > 0) {
                $currentPage = $lastPage;
            }

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

        $providers = Provider::all();
        $vpns = Vpn::all();
        $datakanwil = Kanwil::orderBy('kanwil')->limit(100)->get();
        $jenisLayananOptions = $this->getJenisLayanan();

        return view('user.indexUser', compact('data', 'providers', 'vpns', 'datakanwil', 'jenisLayananOptions'));
    }

    private function groupVpasRegData($allData)
    {
        $grouped = collect();
        $processed = [];

        foreach ($allData as $item) {
            $baseNama = $this->removeVpasRegSuffix($item->namaupt);

            if (in_array($baseNama, $processed)) {
                continue;
            }

            // Cari semua data dengan nama base yang sama
            $relatedItems = $allData->filter(function ($d) use ($baseNama) {
                return $this->removeVpasRegSuffix($d->namaupt) === $baseNama;
            });

            if ($relatedItems->count() === 2) {
                // Ada 2 tipe (reguler + vpas), gabungkan menjadi satu baris
                $mergedItem = $relatedItems->first();

                // Set jenis_layanan sebagai vpasreg
                $mergedItem->jenis_layanan = 'vpasreg';
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


    public function UserPageStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string',
                'kanwil_id' => 'required|exists:kanwil,id',
                'tipe' => 'required|array|min:1',
                'tipe.*' => 'in:reguler,vpas',
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil_id.required' => 'Kanwil harus diisi',
                'kanwil_id.exists' => 'Kanwil tidak valid',
                'tipe.required' => 'Tipe harus dipilih minimal satu',
                'tipe.array' => 'Tipe harus berupa array',
                'tipe.min' => 'Pilih minimal satu tipe',
                'tipe.*.in' => 'Tipe hanya boleh reguler atau vpas',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data tipe yang dipilih
        $selectedTypes = $request->tipe;
        $createdRecords = [];

        // Bersihkan nama UPT dari suffix ganda yang mungkin ada
        $cleanNamaUpt = $this->removeVpasRegSuffix($request->namaupt);

        // Tentukan nama UPT berdasarkan jumlah tipe yang dipilih
        $namaUpt = $cleanNamaUpt;
        if (count($selectedTypes) == 2 && in_array('reguler', $selectedTypes) && in_array('vpas', $selectedTypes)) {
            $namaUpt = $cleanNamaUpt . ' (VpasReg)';
        }

        // Validasi manual untuk kombinasi nama UPT + tipe
        foreach ($selectedTypes as $tipeValue) {
            $existingRecord = Upt::where('namaupt', $namaUpt)
                ->where('tipe', $tipeValue)
                ->first();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Data UPT '{$namaUpt}' dengan tipe '{$tipeValue}' sudah ada!");
            }
        }

        // Loop untuk setiap tipe yang dipilih
        foreach ($selectedTypes as $tipeValue) {
            // Buat record baru untuk setiap tipe
            $dataupt = [
                'namaupt' => $namaUpt,
                'kanwil_id' => $request->kanwil_id,
                'tipe' => $tipeValue,
                'tanggal' => Carbon::now()->format('Y-m-d'),
            ];

            $newRecord = Upt::create($dataupt);
            $createdRecords[] = $tipeValue;
        }

        // Berikan pesan berdasarkan hasil
        if (count($createdRecords) > 0) {
            $message = 'Data UPT berhasil ditambahkan untuk tipe: ' . implode(', ', $createdRecords);

            return redirect()->route('User.UserPage')->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data UPT');
        }
    }

    public function UserPageDestroy($id)
    {
        $dataupt = Upt::find($id);

        if (! $dataupt) {
            return redirect()->route('User.UserPage')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama UPT tanpa suffix (VpasReg) untuk pengecekan
        $namaUptBase = $this->removeVpasRegSuffix($dataupt->namaupt);

        // Hapus data yang dipilih
        $dataupt->delete();

        // Update nama UPT yang tersisa berdasarkan jumlah data
        $this->updateUptNamesBySuffix($namaUptBase);

        return redirect()->route('User.UserPage')->with('success', 'Data berhasil dihapus!');
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($id, $request) {
                        // Cek apakah ada record lain dengan nama yang sama dan tipe yang sama
                        $existingRecord = Upt::where('namaupt', $value)
                            ->where('id', '!=', $id)
                            ->where('tipe', $request->tipe)
                            ->first();

                        if ($existingRecord) {
                            $fail("Nama UPT '{$value}' dengan tipe '{$request->tipe}' sudah ada.");
                        }
                    },
                ],
                'kanwil_id' => 'required|exists:kanwil,id',
                'tipe' => 'required|string|in:reguler,vpas',
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil_id.required' => 'Kanwil harus diisi',
                'kanwil_id.exists' => 'Kanwil tidak valid',
                'tipe.required' => 'Tipe harus diisi',
                'tipe.in' => 'Tipe hanya boleh reguler atau vpas',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataupt = Upt::findOrFail($id);
        $dataupt->namaupt = $request->namaupt;
        $dataupt->kanwil_id = $request->kanwil_id;
        $dataupt->tipe = $request->tipe;
        $dataupt->save();

        // FIXED: Corrected route name
        return redirect()->route('User.UserPage')->with('success', 'Data UPT berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Upt::with('dataOpsional')->whereIn('tipe', ['reguler', 'vpas']);
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $allData = $query->get();
        $data = $this->groupVpasRegData($allData);
        $data = $this->applyStatusFilter($data, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_upt_reguler_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $jenisLayanan = $this->getJenisLayanan();
        $rows = [['No', 'Nama UPT', 'Kanwil', 'Jenis Layanan', 'Tanggal Dibuat', 'Status Update']];
        $no = 1;

        foreach ($data as $d) {
            $status = $this->calculateStatus($d->dataOpsional);
            $layanan = $jenisLayanan[$d->jenis_layanan] ?? $d->jenis_layanan;

            $rows[] = [
                $no++,
                $d->namaupt,
                $d->kanwil->kanwil ?? '-',
                $layanan,
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

    public function exportListPdf(Request $request)
    {
        $query = Upt::with('dataOpsional')->whereIn('tipe', ['reguler', 'vpas']);
        $query = $this->applyFilters($query, $request);

        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $allData = $query->get();
        $data = $this->groupVpasRegData($allData);
        $data = $this->applyStatusFilter($data, $request);

        $jenisLayanan = $this->getJenisLayanan();

        $pdfData = [
            'title' => 'List Data UPT',
            'data' => $data,
            'jenisLayanan' => $jenisLayanan,
            'optionalFields' => $this->optionalFields,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.user.indexUpt', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_upt_reguler_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
    
}

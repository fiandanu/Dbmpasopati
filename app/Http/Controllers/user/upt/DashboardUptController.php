<?php

namespace App\Http\Controllers\user\upt;

use App\Http\Controllers\Controller;
use App\Models\user\upt\Upt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardUptController extends Controller
{

    private function getTotalVpasData()
    {
        return Upt::where('tipe', 'vpas')->count();
    }

    private function getTotalRegulerData()
    {
        return Upt::where('tipe', 'reguler')->count();
    }


    public function index(Request $request)
    {
        $baseQuery = Upt::query();

        if ($request->filled('search_tanggal_dari')) {
            $baseQuery->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $baseQuery->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $pksData = $this->getPksStatistics();
        $sppData = $this->getSppStatistics();
        $vpasData = $this->getVpasStatistics();
        $regulerData = $this->getRegulerStatistics();

        $query = Upt::with(['kanwil', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        if ($request->filled('search_tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $query = $this->applyFilters($query, $request);

        $perPage = $request->get('per_page', 10);

        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        $allData = $query->orderBy('namaupt', 'asc')->get();

        // PERUBAHAN: Filter VpasReg - prioritas Reguler
        $allData = $this->filterUniqueVpasRegPrioritasReguler($allData);

        $filteredData = $this->applyStatusFilter($allData, $request);

        if ($perPage == 'all') {
            $data = new \Illuminate\Pagination\LengthAwarePaginator($filteredData, $filteredData->count(), 99999, 1, ['path' => $request->url(), 'query' => $request->query()]);
        } else {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
            $offset = ($currentPage - 1) * $perPage;
            $itemsForCurrentPage = $filteredData->slice($offset, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, $filteredData->count(), $perPage, $currentPage, [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page',
            ]);
        }

        $totalVpasCount = Upt::where('tipe', 'vpas')->count();
        $totalRegulerCount = Upt::where('tipe', 'reguler')->count();
        $totalUpt = $totalVpasCount + $totalRegulerCount;

        $totalExtensionVpas = $filteredData->where('tipe', 'vpas')->sum(function ($upt) {
            return $upt->dataOpsional->jumlah_extension ?? 0;
        });

        $totalExtensionReguler = $filteredData->where('tipe', 'reguler')->sum(function ($upt) {
            return $upt->dataOpsional->jumlah_extension ?? 0;
        });

        $pksStats = $this->getPksStatistics();
        $sppStats = $this->getSppStatistics();
        $VpasWartelStats = $this->getVpasWartelStatistics();
        $RegulerWartelStats = $this->getRegulerWartelStatistics();
        $totalVpasData = $this->getTotalVpasData();
        $totalRegulerData = $this->getTotalRegulerData();

        return view('db.pageKategoriUpt', compact(
            'pksData',
            'sppData',
            'vpasData',
            'regulerData',
            'data',
            'totalUpt',
            'totalExtensionVpas',
            'totalExtensionReguler',
            'pksStats',
            'sppStats',
            'VpasWartelStats',
            'RegulerWartelStats',
            'totalVpasData',
            'totalRegulerData',
        ));
    }

    // EXPORT DATA CARD TOP
    public function exportCardsPdf(Request $request)
    {
        // Base query dengan filter tanggal
        $baseQuery = Upt::query();

        if ($request->filled('search_tanggal_dari')) {
            $baseQuery->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $baseQuery->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        // Clone query untuk menghindari konflik
        $allData = (clone $baseQuery)->get();

        // Filter VpasReg - prioritas Reguler (sama seperti method index)
        $filteredData = $this->filterUniqueVpasRegPrioritasReguler($allData);

        // Hitung statistik dari data yang sudah difilter
        $pksStats = $this->getPksStatistics();
        $sppStats = $this->getSppStatistics();
        $VpasWartelStats = $this->getVpasWartelStatistics();
        $RegulerWartelStats = $this->getRegulerWartelStatistics();

        // Total UPT dari filtered data
        $totalUpt = $filteredData->count();

        // Total Vpas dan Reguler dari filtered data
        $totalVpasCount = $filteredData->where('tipe', 'vpas')->count();
        $totalRegulerCount = $filteredData->where('tipe', 'reguler')->count();

        // Extension dari filtered data
        $totalExtensionVpas = $filteredData->where('tipe', 'vpas')->sum(function ($upt) {
            return $upt->dataOpsional->jumlah_extension ?? 0;
        });

        $totalExtensionReguler = $filteredData->where('tipe', 'reguler')->sum(function ($upt) {
            return $upt->dataOpsional->jumlah_extension ?? 0;
        });

        $pdfData = [
            'title' => 'Statistik Database UPT',
            'pksStats' => $pksStats,
            'sppStats' => $sppStats,
            'totalVpasCount' => $totalVpasCount,        // TAMBAHAN
            'totalRegulerCount' => $totalRegulerCount,  // TAMBAHAN
            'VpasWartelStats' => $VpasWartelStats,
            'RegulerWartelStats' => $RegulerWartelStats,
            'totalUpt' => $totalUpt,
            'totalExtensionVpas' => $totalExtensionVpas,
            'totalExtensionReguler' => $totalExtensionReguler,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.DatabaseUptCards', $pdfData)
            ->setPaper('a4', 'portrait');

        $filename = 'statistik_upt_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }


    // FILTER BY KOLOM
    private function applyFilters($query, Request $request)
    {
        if ($request->has('search_namaupt') && ! empty($request->search_namaupt)) {
            $query->where('namaupt', 'LIKE', '%' . $request->search_namaupt . '%');
        }

        if ($request->has('search_kanwil') && ! empty($request->search_kanwil)) {
            $query->whereHas('kanwil', function ($q) use ($request) {
                $q->where('kanwil', 'LIKE', '%' . $request->search_kanwil . '%');
            });
        }

        if ($request->has('search_extension') && ! empty($request->search_extension)) {
            $query->whereHas('dataOpsional', function ($q) use ($request) {
                $q->where('jumlah_extension', 'LIKE', '%' . $request->search_extension . '%');
            });
        }

        if ($request->has('search_extension') && ! empty($request->search_extension)) {
            $query->whereHas('dataOpsional', function ($q) use ($request) {
                $q->where('jumlah_extension', 'LIKE', '%' . $request->search_extension . '%');
            });
        }

        if ($request->filled('search_tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }

        if ($request->filled('search_tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        return $query;
    }

    private function filterUniqueVpasRegPrioritasReguler($data)
    {
        $grouped = [];

        foreach ($data as $item) {
            // Hilangkan suffix (VpasReg) untuk pengecekan duplikat
            $baseNama = preg_replace('/\s*\(VpasReg\)$/', '', $item->namaupt);

            if (!isset($grouped[$baseNama])) {
                $grouped[$baseNama] = $item;
            } else {
                // Jika sudah ada, prioritas ke yang tipenya 'reguler'
                if ($item->tipe === 'reguler') {
                    $grouped[$baseNama] = $item;
                }
            }
        }

        return collect(array_values($grouped));
    }

    private function getVpasWartelStatistics()
    {
        $total = Upt::where('tipe', 'vpas')->count();

        $aktif = 0;
        $tidakAktif = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('dataOpsional')->where('tipe', 'vpas')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $tidakAktif++;
                continue;
            }

            if ($dataOpsional->status_wartel == 1) {
                $aktif++;
            } else {
                $tidakAktif++;
            }
        }

        return [
            'total' => $total,
            'aktif' => $aktif,
            'tidak_aktif' => $tidakAktif,
        ];
    }

    private function getRegulerWartelStatistics()
    {
        $total = Upt::where('tipe', 'reguler')->count();

        $aktif = 0;
        $tidakAktif = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('dataOpsional')->where('tipe', 'reguler')->get();


        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $tidakAktif++;
                continue;
            }

            if ($dataOpsional->status_wartel == 1) {
                $aktif++;
            } else {
                $tidakAktif++;
            }
        }

        return [
            'total' => $total,
            'aktif' => $aktif,
            'tidak_aktif' => $tidakAktif,
        ];
    }

    private function applyStatusFilter($data, Request $request)
    {
        if ($request->has('search_status_pks') && ! empty($request->search_status_pks)) {
            $statusSearch = strtolower($request->search_status_pks);
            $data = $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculatePksStatus($d->uploadFolderPks));

                return strpos($status, $statusSearch) !== false;
            });
        }

        if ($request->has('search_status_spp') && ! empty($request->search_status_spp)) {
            $statusSearch = strtolower($request->search_status_spp);
            $data = $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculateSppStatus($d->uploadFolderSpp));

                return strpos($status, $statusSearch) !== false;
            });
        }

        // ===== UBAH INI: Filter Status Wartel Universal =====
        if ($request->has('search_status_wartel') && ! empty($request->search_status_wartel)) {
            $statusSearch = strtolower(trim($request->search_status_wartel));
            $data = $data->filter(function ($d) use ($statusSearch) {
                if (! $d->dataOpsional || ! isset($d->dataOpsional->status_wartel)) {
                    return $statusSearch === '-';
                }

                $status = $d->dataOpsional->status_wartel == 1 ? 'aktif' : 'tidak aktif';

                if ($statusSearch === 'aktif') {
                    return $d->dataOpsional->status_wartel == 1;
                } elseif ($statusSearch === 'tidak aktif' || $statusSearch === 'tidak') {
                    return $d->dataOpsional->status_wartel == 0;
                }

                return strpos($status, $statusSearch) !== false;
            });
        }

        return $data;
    }

    private function calculatePksStatus($uploadFolder)
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

    private function calculateSppStatus($uploadFolder)
    {
        if (! $uploadFolder) {
            return 'Belum Upload';
        }

        $uploadedFolders = 0;
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (! empty($uploadFolder->$column)) {
                $uploadedFolders++;
            }
        }

        if ($uploadedFolders == 0) {
            return 'Belum Upload';
        } elseif ($uploadedFolders == 10) {
            return '10/10 Folder';
        } else {
            return $uploadedFolders . '/10 Terupload';
        }
    }


    // METHOD MENGHITUNG COUNT STATUS UPLOAD FOLDER PKS DAN SPP
    private function getPksStatistics()
    {
        $total = Upt::whereHas('uploadFolderPks')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        $data = Upt::with('uploadFolderPks')->whereHas('uploadFolderPks')->get();

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        foreach ($data as $item) {
            $uploadFolder = $item->uploadFolderPks;

            if (!$uploadFolder) {
                $belumUpload++;
                continue;
            }

            $hasPdf1 = !empty($uploadFolder->uploaded_pdf_1);
            $hasPdf2 = !empty($uploadFolder->uploaded_pdf_2);

            if ($hasPdf1 && $hasPdf2) {
                $sudahUpload++;
            } elseif ($hasPdf1 || $hasPdf2) {
                $sebagian++;
            } else {
                $belumUpload++;
            }
        }

        return [
            'total' => $total,
            'belum_upload' => $belumUpload,
            'sebagian' => $sebagian,
            'sudah_upload' => $sudahUpload,
        ];
    }

    private function getSppStatistics()
    {
        $total = Upt::whereHas('uploadFolderSpp')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        $data = Upt::with('uploadFolderSpp')->whereHas('uploadFolderSpp')->get();

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        foreach ($data as $item) {
            $uploadFolder = $item->uploadFolderSpp;

            if (!$uploadFolder) {
                $belumUpload++;
                continue;
            }

            $uploadedFolders = 0;
            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_' . $i;
                if (!empty($uploadFolder->$column)) {
                    $uploadedFolders++;
                }
            }

            if ($uploadedFolders == 0) {
                $belumUpload++;
            } elseif ($uploadedFolders == 10) {
                $sudahUpload++;
            } else {
                $sebagian++;
            }
        }

        return [
            'total' => $total,
            'belum_upload' => $belumUpload,
            'sebagian' => $sebagian,
            'sudah_upload' => $sudahUpload,
        ];
    }


    // METHOD MENGHITUNG COUNT STATUS WARTEL
    private function getVpasStatistics()
    {
        $total = Upt::where('tipe', 'vpas')->count();

        $belumUpdate = 0;
        $sudahUpdate = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('dataOpsional')->where('tipe', 'vpas')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $belumUpdate++;
                continue;
            }

            // Jika status_wartel sudah diisi (1 atau 0), dianggap sudah update
            $sudahUpdate++;
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sudah_update' => $sudahUpdate,
        ];
    }

    private function getRegulerStatistics()
    {
        $total = Upt::where('tipe', 'reguler')->count();

        $belumUpdate = 0;
        $sudahUpdate = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('dataOpsional')->where('tipe', 'reguler')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $belumUpdate++;
                continue;
            }

            // Jika status_wartel sudah diisi (1 atau 0), dianggap sudah update
            $sudahUpdate++;
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sudah_update' => $sudahUpdate,
        ];
    }

    private function removeVpasRegSuffix($namaUpt)
    {
        return preg_replace('/\s*\(VpasReg\)$/', '', $namaUpt);
    }

    private function getJenisLayanan()
    {
        return [
            'vpas' => 'Vpas',
            'reguler' => 'Reguler',
            'vpasreg' => 'Vpas + Reguler',
        ];
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


    // EXPORT CSV DAN PDF DATA UPT BOTTOM
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Upt::with(['kanwil', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        $query = $this->applyFilters($query, $request);

        // UBAH INI: Get all data dulu
        $allData = $query->orderBy('namaupt', 'asc')->get();

        // TAMBAHKAN INI: Group VpasReg data
        $groupedData = $this->groupVpasRegData($allData);

        // UBAH INI: Apply status filter ke grouped data
        $data = $this->applyStatusFilter($groupedData, $request);

        $filename = 'dashboard_upt_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // UBAH INI: Header dengan Jenis Layanan
        $rows = [['No', 'Nama UPT', 'Kanwil', 'Jenis Layanan', 'Status PKS', 'Status SPP', 'Extension', 'Status Wartel']];

        // TAMBAHKAN INI
        $jenisLayanan = $this->getJenisLayanan();

        $no = 1;
        foreach ($data as $d) {
            $extension = $d->dataOpsional ? $d->dataOpsional->jumlah_extension ?? '-' : '-';
            $statusWartel = $d->dataOpsional && isset($d->dataOpsional->status_wartel)
                ? ($d->dataOpsional->status_wartel == 1 ? 'Aktif' : 'Tidak Aktif')
                : '-';

            // UBAH INI: Ganti kolom Tipe jadi Jenis Layanan
            $layanan = $jenisLayanan[$d->jenis_layanan] ?? ucfirst($d->jenis_layanan);

            $rows[] = [
                $no++,
                $d->namaupt,
                $d->kanwil->kanwil,
                $layanan,  // UBAH INI
                $this->calculatePksStatus($d->uploadFolderPks),
                $this->calculateSppStatus($d->uploadFolderSpp),
                $extension,
                $statusWartel
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

    public function exportPdf(Request $request)
    {
        $query = Upt::with(['kanwil', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        $query = $this->applyFilters($query, $request);

        $allData = $query->orderBy('namaupt', 'asc')->get();

        $groupedData = $this->groupVpasRegData($allData);

        $data = $this->applyStatusFilter($groupedData, $request);


        $data = $data->map(function ($item) {
            $item->pks_status = $this->calculatePksStatus($item->uploadFolderPks);
            $item->spp_status = $this->calculateSppStatus($item->uploadFolderSpp);

            return $item;
        });

        $jenisLayanan = $this->getJenisLayanan();

        $pdfData = [
            'title' => 'Dashboard Database UPT',
            'data' => $data,
            'jenisLayanan' => $jenisLayanan,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.DatabaseUPT', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'dashboard_upt_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

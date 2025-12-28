<?php

namespace App\Http\Controllers\user\ponpes;

use App\Http\Controllers\Controller;
use App\Models\user\ponpes\Ponpes;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardPonpesController extends Controller
{

    private function filterUniqueVtrenRegPrioritasReguler($data)
    {
        $grouped = [];

        foreach ($data as $item) {
            // Hilangkan suffix (VpasReg) untuk pengecekan duplikat
            $baseNama = preg_replace('/\s*\(VtrenReg\)$/', '', $item->nama_ponpes);
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

    private function getTotalVtren()
    {
        return Ponpes::where('tipe', 'vtren')->count();
    }

    private function getTotalRegulerData()
    {
        return Ponpes::where('tipe', 'reguler')->count();
    }

    public function index(Request $request)
    {
        $baseQuery = Ponpes::query();

        if ($request->filled('search_tanggal_dari')) {
            $baseQuery->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $baseQuery->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $pksData = $this->getPksStatistics();
        $sppData = $this->getSppStatistics();
        $vtrenData = $this->getVtrenStatistics();
        $regulerData = $this->getRegulerStatistics();

        $query = Ponpes::with(['namaWilayah', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        if ($request->filled('search_tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $query = $this->applyFilters($query, $request);

        $perPage = $request->get('per_page', 10);

        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        $allData = $query->orderBy('nama_ponpes', 'asc')->get();

        // PERUBAHAN: Filter VtrenReg - prioritas Reguler
        $allData = $this->filterUniqueVtrenRegPrioritasReguler($allData);

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

        $totalVtrenCount = Ponpes::where('tipe', 'vtren')->count();
        $totalRegulerCount = Ponpes::where('tipe', 'reguler')->count();
        $totalPonpes = $totalVtrenCount + $totalRegulerCount;

        $totalExtensionVtren = $filteredData->where('tipe', 'vtren')->sum(function ($ponpes) {
            return $ponpes->dataOpsional->jumlah_extension ?? 0;
        });

        $totalExtensionReguler = $filteredData->where('tipe', 'reguler')->sum(function ($ponpes) {
            return $ponpes->dataOpsional->jumlah_extension ?? 0;
        });

        $pksStats = $this->getPksStatistics();
        $sppStats = $this->getSppStatistics();
        $VtrenWartelStats = $this->getVtrenWartelStatistics();
        $RegulerWartelStats = $this->getRegulerWartelStatistics();
        $getTotalVtren = $this->getTotalVtren();
        $getTotalRegulerData = $this->getTotalRegulerData();

        return view('db.pageKategoriPonpes', compact(
            'pksData',
            'sppData',
            'vtrenData',
            'regulerData',
            'data',
            'totalPonpes',
            'totalExtensionVtren',
            'totalExtensionReguler',
            'pksStats',
            'sppStats',
            'VtrenWartelStats',
            'RegulerWartelStats',
            'getTotalVtren',
            'getTotalRegulerData',
        ));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('search_namaponpes') && ! empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }

        if ($request->has('search_wilayah') && ! empty($request->search_wilayah)) {
            $query->whereHas('namaWilayah', function ($q) use ($request) {
                $q->where('nama_wilayah', 'LIKE', '%' . $request->search_wilayah . '%');
            });
        }

        if ($request->has('search_extension') && ! empty($request->search_extension)) {
            $query->whereHas('dataOpsional', function ($q) use ($request) {
                $q->where('jumlah_extension', 'LIKE', '%' . $request->search_extension . '%');
            });
        }

        return $query;
    }

    private function applyStatusFilter($data, Request $request)
    {
        if ($request->has('search_status_pks') && !empty($request->search_status_pks)) {
            $statusSearch = strtolower($request->search_status_pks);
            $data = $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculatePksStatus($d->uploadFolderPks));
                return strpos($status, $statusSearch) !== false;
            });
        }

        if ($request->has('search_status_spp') && !empty($request->search_status_spp)) {
            $statusSearch = strtolower($request->search_status_spp);
            $data = $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculateSppStatus($d->uploadFolderSpp));
                return strpos($status, $statusSearch) !== false;
            });
        }

        // UBAH INI: Sesuaikan dengan format UPT (Aktif/Tidak Aktif bukan 'Aktif'/'Tidak Aktif')
        if ($request->has('search_status_wartel') && !empty($request->search_status_wartel)) {
            $statusSearch = strtolower(trim($request->search_status_wartel));
            $data = $data->filter(function ($d) use ($statusSearch) {
                if (!$d->dataOpsional || !isset($d->dataOpsional->status_wartel)) {
                    return $statusSearch === '-';
                }

                // UBAH: Cek value string 'Aktif' atau 'Tidak Aktif'
                $status = strtolower($d->dataOpsional->status_wartel);

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

    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with(['namaWilayah', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        if ($request->filled('search_tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $query = $this->applyFilters($query, $request);

        // TAMBAH: Get all data dan filter VtrenReg
        $allData = $query->orderBy('nama_ponpes', 'asc')->get();
        $allData = $this->filterUniqueVtrenRegPrioritasReguler($allData);
        $data = $this->applyStatusFilter($allData, $request);

        $filename = 'dashboard_ponpes_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // UBAH: Header CSV tanpa kolom Tipe
        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Status PKS', 'Status SPP', 'Extension', 'Status Wartel']];

        $no = 1;
        foreach ($data as $d) {
            $extension = ($d->dataOpsional) ? ($d->dataOpsional->jumlah_extension ?? '-') : '-';

            // UBAH: Format status wartel seperti di UPT (Aktif/Tidak Aktif)
            $statusWartel = ($d->dataOpsional && isset($d->dataOpsional->status_wartel)) ?
                ($d->dataOpsional->status_wartel == 1 ? 'Aktif' : 'Tidak Aktif') : '-';

            // UBAH: Hapus kolom Tipe
            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->namaWilayah->nama_wilayah ?? '-',
                $this->calculatePksStatus($d->uploadFolderPks),
                $this->calculateSppStatus($d->uploadFolderSpp),
                $extension,
                $statusWartel,
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
        $query = Ponpes::with(['namaWilayah', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        if ($request->filled('search_tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $query = $this->applyFilters($query, $request);

        // TAMBAH: Get all data dan filter VtrenReg
        $allData = $query->orderBy('nama_ponpes', 'asc')->get();
        $allData = $this->filterUniqueVtrenRegPrioritasReguler($allData);
        $data = $this->applyStatusFilter($allData, $request);

        $data = $data->map(function ($item) {
            $item->pks_status = $this->calculatePksStatus($item->uploadFolderPks);
            $item->spp_status = $this->calculateSppStatus($item->uploadFolderSpp);

            return $item;
        });

        $pdfData = [
            'title' => 'Dashboard Database PONPES',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.DatabasePonpes', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'dashboard_ponpes_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    private function getPksStatistics()
    {
        $total = Ponpes::whereHas('uploadFolderPks')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        $data = Ponpes::with('uploadFolderPks')->whereHas('uploadFolderPks')->get();

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\ponpes\Ponpes> $data */
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
        $total = Ponpes::whereHas('uploadFolderSpp')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        $data = Ponpes::with('uploadFolderSpp')->whereHas('uploadFolderSpp')->get();

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\ponpes\Ponpes> $data */
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

    private function getVtrenStatistics()
    {
        $total = Ponpes::where('tipe', 'vtren')->count();

        $belumUpdate = 0;
        $sudahUpdate = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\ponpes\Ponpes> $data */
        $data = Ponpes::with('dataOpsional')->where('tipe', 'vtren')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $belumUpdate++;
                continue;
            }

            // Jika status_wartel sudah diisi ('Aktif' atau 'Tidak Aktif'), dianggap sudah update
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
        $total = Ponpes::where('tipe', 'reguler')->count();

        $belumUpdate = 0;
        $sudahUpdate = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\ponpes\Ponpes> $data */
        $data = Ponpes::with('dataOpsional')->where('tipe', 'reguler')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $belumUpdate++;
                continue;
            }

            // Jika status_wartel sudah diisi ('Aktif' atau 'Tidak Aktif'), dianggap sudah update
            $sudahUpdate++;
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sudah_update' => $sudahUpdate,
        ];
    }

    private function getVtrenWartelStatistics()
    {
        $total = Ponpes::where('tipe', 'vtren')->count();

        $aktif = 0;
        $tidakAktif = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\ponpes\Ponpes> $data */
        $data = Ponpes::with('dataOpsional')->where('tipe', 'vtren')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $tidakAktif++;
                continue;
            }

            // Cek apakah status_wartel bernilai 'Aktif' (string)
            if ($dataOpsional->status_wartel === 1) {
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
        $total = Ponpes::where('tipe', 'reguler')->count();

        $aktif = 0;
        $tidakAktif = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\ponpes\Ponpes> $data */
        $data = Ponpes::with('dataOpsional')->where('tipe', 'reguler')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional || !isset($dataOpsional->status_wartel)) {
                $tidakAktif++;
                continue;
            }

            // Cek apakah status_wartel bernilai 'Aktif' (string)
            if ($dataOpsional->status_wartel === 'Aktif') {
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

    public function exportCardsPdf(Request $request)
    {
        $baseQuery = Ponpes::query();

        if ($request->filled('search_tanggal_dari')) {
            $baseQuery->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->filled('search_tanggal_sampai')) {
            $baseQuery->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        $pksStats = $this->getPksStatistics();
        $sppStats = $this->getSppStatistics();
        $VtrenWartelStats = $this->getVtrenWartelStatistics();
        $RegulerWartelStats = $this->getRegulerWartelStatistics();

        $totalPonpes = $baseQuery->count();

        $totalExtensionVtren = Ponpes::where('tipe', 'vtren')
            ->with('dataOpsional')
            ->get()
            ->sum(function ($ponpes) {
                return $ponpes->dataOpsional->jumlah_extension ?? 0;
            });

        $totalExtensionReguler = Ponpes::where('tipe', 'reguler')
            ->with('dataOpsional')
            ->get()
            ->sum(function ($ponpes) {
                return $ponpes->dataOpsional->jumlah_extension ?? 0;
            });

        $pdfData = [
            'title' => 'Statistik Database PONPES',
            'pksStats' => $pksStats,
            'sppStats' => $sppStats,
            'VtrenWartelStats' => $VtrenWartelStats,
            'RegulerWartelStats' => $RegulerWartelStats,
            'totalPonpes' => $totalPonpes,
            'totalExtensionVtren' => $totalExtensionVtren,
            'totalExtensionReguler' => $totalExtensionReguler,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.DatabasePonpesCards', $pdfData)
            ->setPaper('a4', 'portrait');

        $filename = 'statistik_ponpes_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

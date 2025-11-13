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

        // ===== UBAH BAGIAN INI: Hitung statistik dari data yang sudah difilter =====
        $totalUpt = $filteredData->count();

        $totalExtensionVpas = $filteredData->where('tipe', 'vpas')->sum(function ($upt) {
            return $upt->dataOpsional->jumlah_extension ?? 0;
        });

        $totalExtensionReguler = $filteredData->where('tipe', 'reguler')->sum(function ($upt) {
            return $upt->dataOpsional->jumlah_extension ?? 0;
        });
        // ===== AKHIR PERUBAHAN =====

        return view('db.pageKategoriUpt', compact('pksData', 'sppData', 'vpasData', 'regulerData', 'data', 'totalUpt', 'totalExtensionVpas', 'totalExtensionReguler'));
    }

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

        // ===== UBAH INI: Filter tipe =====
        if ($request->has('search_tipe') && ! empty($request->search_tipe)) {
            $query->where('tipe', 'LIKE', '%' . $request->search_tipe . '%');
        }
        // ===== AKHIR PERUBAHAN =====

        // ===== UBAH INI: Filter Extension Universal =====
        if ($request->has('search_extension') && ! empty($request->search_extension)) {
            $query->whereHas('dataOpsional', function ($q) use ($request) {
                $q->where('jumlah_extension', 'LIKE', '%' . $request->search_extension . '%');
            });
        }
        // ===== AKHIR PERUBAHAN =====

        return $query;
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
        // ===== AKHIR PERUBAHAN =====

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
        $query = Upt::with(['kanwil', 'uploadFolderPks', 'uploadFolderSpp', 'dataOpsional']);

        $query = $this->applyFilters($query, $request);
        $data = $query->orderBy('namaupt', 'asc')->get();
        $data = $this->applyStatusFilter($data, $request);

        $filename = 'dashboard_upt_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Header CSV dengan kolom Tipe
        $rows = [['No', 'Nama UPT', 'Kanwil', 'Tipe', 'Status PKS', 'Status SPP', 'Extension', 'Status Wartel']];

        $no = 1;
        foreach ($data as $d) {
            $extension = $d->dataOpsional ? $d->dataOpsional->jumlah_extension ?? '-' : '-';
            $statusWartel = $d->dataOpsional && isset($d->dataOpsional->status_wartel) ? ($d->dataOpsional->status_wartel == 1 ? 'Aktif' : 'Tidak Aktif') : '-';

            $rows[] = [$no++, $d->namaupt, $d->kanwil->kanwil, ucfirst($d->tipe), $this->calculatePksStatus($d->uploadFolderPks), $this->calculateSppStatus($d->uploadFolderSpp), $extension, $statusWartel];
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
        $data = $query->orderBy('namaupt', 'asc')->get();
        $data = $this->applyStatusFilter($data, $request);

        $data = $data->map(function ($item) {
            $item->pks_status = $this->calculatePksStatus($item->uploadFolderPks);
            $item->spp_status = $this->calculateSppStatus($item->uploadFolderSpp);

            return $item;
        });

        $pdfData = [
            'title' => 'Dashboard Database UPT',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.db.DatabaseUPT', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'dashboard_upt_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    private function getPksStatistics()
    {
        $total = Upt::whereHas('uploadFolderPks')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('uploadFolderPks')->whereHas('uploadFolderPks')->get();

        foreach ($data as $item) {
            // /** @var \App\Models\user\upt\UploadFolderPks|null $uploadFolder */
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

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('uploadFolderSpp')->whereHas('uploadFolderSpp')->get();

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

    private function getVpasStatistics()
    {
        $optionalFields = ['pic_upt', 'no_telpon', 'alamat', 'jumlah_wbp', 'jumlah_line', 'provider_internet', 'kecepatan_internet', 'tarif_wartel', 'status_wartel', 'akses_topup_pulsa', 'password_topup', 'akses_download_rekaman', 'password_download', 'internet_protocol', 'vpn_user', 'vpn_password', 'jumlah_extension', 'no_pemanggil', 'email_airdroid', 'password', 'pin_tes'];

        $total = Upt::where('tipe', 'vpas')->count();

        $belumUpdate = 0;
        $sebagian = 0;
        $sudahUpdate = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('dataOpsional')->where('tipe', 'vpas')->get();

        foreach ($data as $item) {
            // /** @var \App\Models\user\upt\DataOpsional|null $dataOpsional */
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional) {
                $belumUpdate++;
                continue;
            }

            $filledFields = 0;
            foreach ($optionalFields as $field) {
                if (!empty($dataOpsional->$field)) {
                    $filledFields++;
                }
            }

            $totalFields = count($optionalFields);

            if ($filledFields == 0) {
                $belumUpdate++;
            } elseif ($filledFields == $totalFields) {
                $sudahUpdate++;
            } else {
                $sebagian++;
            }
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sebagian' => $sebagian,
            'sudah_update' => $sudahUpdate,
        ];
    }

    private function getRegulerStatistics()
    {
        $optionalFields = ['pic_upt', 'no_telpon', 'alamat', 'jumlah_wbp', 'jumlah_line', 'provider_internet', 'kecepatan_internet', 'tarif_wartel', 'status_wartel', 'akses_topup_pulsa', 'password_topup', 'akses_download_rekaman', 'password_download', 'internet_protocol', 'vpn_user', 'vpn_password', 'jumlah_extension', 'no_extension', 'extension_password', 'pin_tes'];

        $total = Upt::where('tipe', 'reguler')->count();

        $belumUpdate = 0;
        $sebagian = 0;
        $sudahUpdate = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\user\upt\Upt> $data */
        $data = Upt::with('dataOpsional')->where('tipe', 'reguler')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (! $dataOpsional) {
                $belumUpdate++;

                continue;
            }

            $filledFields = 0;
            foreach ($optionalFields as $field) {
                if (! empty($dataOpsional->$field)) {
                    $filledFields++;
                }
            }

            $totalFields = count($optionalFields);

            if ($filledFields == 0) {
                $belumUpdate++;
            } elseif ($filledFields == $totalFields) {
                $sudahUpdate++;
            } else {
                $sebagian++;
            }
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sebagian' => $sebagian,
            'sudah_update' => $sudahUpdate,
        ];
    }
}

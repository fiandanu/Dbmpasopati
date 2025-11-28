<?php

namespace App\Http\Controllers\mclient;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\Kunjungan;
use App\Models\mclient\ponpes\Pengiriman;
use App\Models\mclient\ponpes\Vtren;
use App\Models\mclient\ponpes\Reguller;
use App\Models\mclient\ponpes\SettingPonpes;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardPonpesController extends Controller
{
    private function applyMonitoringFilters(Collection $collection, Request $request)
    {
        // Filter by nama_ponpes
        if ($request->has('search_nama_ponpes') && !empty($request->search_nama_ponpes)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['nama_ponpes'], $request->search_nama_ponpes) !== false;
            });
        }

        // Filter by nama_wilayah
        if ($request->has('search_nama_wilayah') && !empty($request->search_nama_wilayah)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['nama_wilayah'], $request->search_nama_wilayah) !== false;
            });
        }

        // Filter by jenis_layanan
        if ($request->has('search_jenis_layanan') && !empty($request->search_jenis_layanan)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['jenis_layanan'], $request->search_jenis_layanan) !== false;
            });
        }

        // Filter by status
        if ($request->has('search_status') && !empty($request->search_status)) {
            $searchStatus = strtolower($request->search_status);
            $collection = $collection->filter(function ($item) use ($searchStatus) {
                $itemStatus = strtolower($item['status']);

                if (stripos($itemStatus, $searchStatus) !== false) {
                    return true;
                }

                if ((str_contains($searchStatus, 'belum') || str_contains($searchStatus, 'ditentukan'))
                    && ($itemStatus == '' || $itemStatus == 'belum ditentukan')
                ) {
                    return true;
                }

                return false;
            });
        }

        // Date range filters
        if ($request->has('search_tanggal_dari') && !empty($request->search_tanggal_dari)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return $item['tanggal_terlapor'] &&
                    $item['tanggal_terlapor']->format('Y-m-d') >= $request->search_tanggal_dari;
            });
        }

        if ($request->has('search_tanggal_sampai') && !empty($request->search_tanggal_sampai)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return $item['tanggal_terlapor'] &&
                    $item['tanggal_terlapor']->format('Y-m-d') <= $request->search_tanggal_sampai;
            });
        }

        return $collection;
    }

    public function monitoringClientPonpesOverview(Request $request)
    {
        // Collect data from all monitoring client tables
        $regulerData = Reguller::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Komplain Reguler',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_terlapor,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        $vtrenData = Vtren::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Komplain Vtren',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_terlapor,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        $kunjunganData = Kunjungan::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Kunjungan Ponpes',
                'jenis_kendala' => $item->keterangan ?? 'Monitoring rutin',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->jadwal,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        $pengirimanData = Pengiriman::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Pengiriman Alat',
                'jenis_kendala' => $item->keterangan ?? 'Pengiriman alat',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_pengiriman,
                'tanggal_selesai' => $item->tanggal_sampai,
                'created_at' => $item->created_at,
            ];
        });

        $settingAlatData = SettingPonpes::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Setting Alat',
                'jenis_kendala' => $item->keterangan ?? 'Setting alat',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_terlapor,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        // Merge all data
        $allData = collect()
            ->merge($regulerData)
            ->merge($vtrenData)
            ->merge($kunjunganData)
            ->merge($pengirimanData)
            ->merge($settingAlatData);

        // Apply filters
        $allData = $this->applyMonitoringFilters($allData, $request);

        // Sort by created_at desc
        $allData = $allData->sortByDesc('created_at')->values();

        // Calculate statistics
        $totalKomplain = $allData->count();
        $totalVtren = $vtrenData->count();
        $totalReguler = $regulerData->count();
        $totalKunjungan = $kunjunganData->count();
        $totalPengiriman = $pengirimanData->count();
        $totalSettingAlat = $settingAlatData->count();

        // Calculate statistics by status for each category
        $statusStats = [
            'vtren' => [
                'pending' => $vtrenData->where('status', 'pending')->count(),
                'proses' => $vtrenData->where('status', 'proses')->count(),
                'terjadwal' => $vtrenData->where('status', 'terjadwal')->count(),
                'selesai' => $vtrenData->where('status', 'selesai')->count(),
                'belum_ditentukan' => $vtrenData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
            ],
            'reguler' => [
                'pending' => $regulerData->where('status', 'pending')->count(),
                'proses' => $regulerData->where('status', 'proses')->count(),
                'terjadwal' => $regulerData->where('status', 'terjadwal')->count(),
                'selesai' => $regulerData->where('status', 'selesai')->count(),
                'belum_ditentukan' => $regulerData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
            ],
            'kunjungan' => [
                'pending' => $kunjunganData->where('status', 'pending')->count(),
                'proses' => $kunjunganData->where('status', 'proses')->count(),
                'terjadwal' => $kunjunganData->where('status', 'terjadwal')->count(),
                'selesai' => $kunjunganData->where('status', 'selesai')->count(),
                'belum_ditentukan' => $kunjunganData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
            ],
            'pengiriman' => [
                'pending' => $pengirimanData->where('status', 'pending')->count(),
                'proses' => $pengirimanData->where('status', 'proses')->count(),
                'terjadwal' => $pengirimanData->where('status', 'terjadwal')->count(),
                'selesai' => $pengirimanData->where('status', 'selesai')->count(),
                'belum_ditentukan' => $pengirimanData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
            ],
            'setting_alat' => [
                'pending' => $settingAlatData->where('status', 'pending')->count(),
                'proses' => $settingAlatData->where('status', 'proses')->count(),
                'terjadwal' => $settingAlatData->where('status', 'terjadwal')->count(),
                'selesai' => $settingAlatData->where('status', 'selesai')->count(),
                'belum_ditentukan' => $settingAlatData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
            ],
        ];

        // Pagination
        $perPage = $request->get('per_page', 10);

        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        if ($perPage == 'all') {
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $allData,
                $allData->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $currentItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $allData->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('mclient.indexPonpes', compact(
            'data',
            'totalKomplain',
            'totalVtren',
            'totalReguler',
            'totalKunjungan',
            'totalPengiriman',
            'totalSettingAlat',
            'statusStats'
        ));
    }

    // EXPORT GLOBAL DATA DASHBOARD ATAS
    public function exportMonitoringClientSummaryPdf(Request $request)
    {
        // Get raw data untuk statistik
        $vtrenData = Vtren::with(['ponpes.namaWilayah'])->get();
        $regulerData = Reguller::with(['ponpes.namaWilayah'])->get();
        $kunjunganData = Kunjungan::with(['ponpes.namaWilayah'])->get();
        $pengirimanData = Pengiriman::with(['ponpes.namaWilayah'])->get();
        $settingAlatData = SettingPonpes::with(['ponpes.namaWilayah'])->get();

        // Calculate statistics by status for each category
        $summaryData = [
            [
                'kategori' => 'Komplain VTREN',
                'layanan' => 'Layanan VTREN',
                'total' => $vtrenData->count(),
                'belum_ditentukan' => $vtrenData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                'pending' => $vtrenData->where('status', 'pending')->count(),
                'proses' => $vtrenData->where('status', 'proses')->count(),
                'terjadwal' => $vtrenData->where('status', 'terjadwal')->count(),
                'selesai' => $vtrenData->where('status', 'selesai')->count(),
            ],
            [
                'kategori' => 'Komplain Reguler',
                'layanan' => 'Layanan Reguler',
                'total' => $regulerData->count(),
                'belum_ditentukan' => $regulerData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                'pending' => $regulerData->where('status', 'pending')->count(),
                'proses' => $regulerData->where('status', 'proses')->count(),
                'terjadwal' => $regulerData->where('status', 'terjadwal')->count(),
                'selesai' => $regulerData->where('status', 'selesai')->count(),
            ],
            [
                'kategori' => 'Kunjungan Ponpes',
                'layanan' => 'Kunjungan Monitoring Client',
                'total' => $kunjunganData->count(),
                'belum_ditentukan' => $kunjunganData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                'pending' => $kunjunganData->where('status', 'pending')->count(),
                'proses' => $kunjunganData->where('status', 'proses')->count(),
                'terjadwal' => $kunjunganData->where('status', 'terjadwal')->count(),
                'selesai' => $kunjunganData->where('status', 'selesai')->count(),
            ],
            [
                'kategori' => 'Pengiriman Alat Ponpes',
                'layanan' => 'Layanan Pengiriman Alat',
                'total' => $pengirimanData->count(),
                'belum_ditentukan' => $pengirimanData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                'pending' => $pengirimanData->where('status', 'pending')->count(),
                'proses' => $pengirimanData->where('status', 'proses')->count(),
                'terjadwal' => $pengirimanData->where('status', 'terjadwal')->count(),
                'selesai' => $pengirimanData->where('status', 'selesai')->count(),
            ],
            [
                'kategori' => 'Setting Alat Ponpes',
                'layanan' => 'Layanan Setting Alat Ponpes',
                'total' => $settingAlatData->count(),
                'belum_ditentukan' => $settingAlatData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                'pending' => $settingAlatData->where('status', 'pending')->count(),
                'proses' => $settingAlatData->where('status', 'proses')->count(),
                'terjadwal' => $settingAlatData->where('status', 'terjadwal')->count(),
                'selesai' => $settingAlatData->where('status', 'selesai')->count(),
            ],
        ];

        // Calculate grand total
        $grandTotal = $vtrenData->count() + $regulerData->count() + $kunjunganData->count()
            + $pengirimanData->count() + $settingAlatData->count();

        $pdfData = [
            'title' => 'Ringkasan Monitoring Client Ponpes',
            'data' => $summaryData,
            'grandTotal' => $grandTotal,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.mclient.DashboardSummaryPonpes', $pdfData)
            ->setPaper('a4', 'portrait');

        $filename = 'ringkasan_monitoring_client_ponpes_' . Carbon::now()->format('d_M_Y_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportMonitoringClientSummaryCsv(Request $request): StreamedResponse
    {
        // Get raw data untuk statistik
        $vtrenData = Vtren::with(['ponpes.namaWilayah'])->get();
        $regulerData = Reguller::with(['ponpes.namaWilayah'])->get();
        $kunjunganData = Kunjungan::with(['ponpes.namaWilayah'])->get();
        $pengirimanData = Pengiriman::with(['ponpes.namaWilayah'])->get();
        $settingAlatData = SettingPonpes::with(['ponpes.namaWilayah'])->get();

        $filename = 'ringkasan_monitoring_client_ponpes_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($vtrenData, $regulerData, $kunjunganData, $pengirimanData, $settingAlatData) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, ['RINGKASAN MONITORING CLIENT PONPES']);
            fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d M Y H:i:s')]);
            fputcsv($file, []); // Empty row

            // Column headers
            fputcsv($file, [
                'No',
                'Kategori',
                'Jenis Layanan',
                'Total Data',
                'Belum Ditentukan',
                'Pending',
                'Proses',
                'Terjadwal',
                'Selesai'
            ]);

            // Data rows
            $no = 1;
            $summaryData = [
                [
                    'kategori' => 'Komplain VTREN',
                    'layanan' => 'Layanan VTREN',
                    'total' => $vtrenData->count(),
                    'belum_ditentukan' => $vtrenData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                    'pending' => $vtrenData->where('status', 'pending')->count(),
                    'proses' => $vtrenData->where('status', 'proses')->count(),
                    'terjadwal' => $vtrenData->where('status', 'terjadwal')->count(),
                    'selesai' => $vtrenData->where('status', 'selesai')->count(),
                ],
                [
                    'kategori' => 'Komplain Reguler',
                    'layanan' => 'Layanan Reguler',
                    'total' => $regulerData->count(),
                    'belum_ditentukan' => $regulerData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                    'pending' => $regulerData->where('status', 'pending')->count(),
                    'proses' => $regulerData->where('status', 'proses')->count(),
                    'terjadwal' => $regulerData->where('status', 'terjadwal')->count(),
                    'selesai' => $regulerData->where('status', 'selesai')->count(),
                ],
                [
                    'kategori' => 'Kunjungan Ponpes',
                    'layanan' => 'Kunjungan Monitoring Client',
                    'total' => $kunjunganData->count(),
                    'belum_ditentukan' => $kunjunganData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                    'pending' => $kunjunganData->where('status', 'pending')->count(),
                    'proses' => $kunjunganData->where('status', 'proses')->count(),
                    'terjadwal' => $kunjunganData->where('status', 'terjadwal')->count(),
                    'selesai' => $kunjunganData->where('status', 'selesai')->count(),
                ],
                [
                    'kategori' => 'Pengiriman Alat Ponpes',
                    'layanan' => 'Layanan Pengiriman Alat',
                    'total' => $pengirimanData->count(),
                    'belum_ditentukan' => $pengirimanData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                    'pending' => $pengirimanData->where('status', 'pending')->count(),
                    'proses' => $pengirimanData->where('status', 'proses')->count(),
                    'terjadwal' => $pengirimanData->where('status', 'terjadwal')->count(),
                    'selesai' => $pengirimanData->where('status', 'selesai')->count(),
                ],
                [
                    'kategori' => 'Setting Alat Ponpes',
                    'layanan' => 'Layanan Setting Alat Ponpes',
                    'total' => $settingAlatData->count(),
                    'belum_ditentukan' => $settingAlatData->whereIn('status', ['', 'Belum ditentukan', null])->count(),
                    'pending' => $settingAlatData->where('status', 'pending')->count(),
                    'proses' => $settingAlatData->where('status', 'proses')->count(),
                    'terjadwal' => $settingAlatData->where('status', 'terjadwal')->count(),
                    'selesai' => $settingAlatData->where('status', 'selesai')->count(),
                ],
            ];

            foreach ($summaryData as $row) {
                fputcsv($file, [
                    $no++,
                    $row['kategori'],
                    $row['layanan'],
                    $row['total'],
                    $row['belum_ditentukan'],
                    $row['pending'],
                    $row['proses'],
                    $row['terjadwal'],
                    $row['selesai'],
                ]);
            }

            // Total row
            fputcsv($file, []); // Empty row
            fputcsv($file, [
                '',
                'TOTAL KESELURUHAN',
                '',
                array_sum(array_column($summaryData, 'total')),
                array_sum(array_column($summaryData, 'belum_ditentukan')),
                array_sum(array_column($summaryData, 'pending')),
                array_sum(array_column($summaryData, 'proses')),
                array_sum(array_column($summaryData, 'terjadwal')),
                array_sum(array_column($summaryData, 'selesai')),
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // EXPORT GLOBAL DATA DASHBOARD BAWAH
    public function exportMonitoringClientCsv(Request $request): StreamedResponse
    {
        // Get all data
        $regulerData = Reguller::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Komplain Reguler',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $vtrenData = Vtren::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Komplain VTREN',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $kunjunganData = Kunjungan::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Kunjungan',
                'jenis_kendala' => $item->keterangan ?? 'Monitoring rutin',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $pengirimanData = Pengiriman::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Pengiriman Alat',
                'jenis_kendala' => $item->keterangan ?? 'Pengiriman alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $settingAlatData = SettingPonpes::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Setting Alat',
                'jenis_kendala' => $item->keterangan ?? 'Setting alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $allData = collect()
            ->merge($regulerData)
            ->merge($vtrenData)
            ->merge($kunjunganData)
            ->merge($pengirimanData)
            ->merge($settingAlatData);

        // Apply filters
        $allData = $this->applyMonitoringFilters($allData, $request);

        $filename = 'monitoring_client_ponpes_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Nama Ponpes', 'Wilayah', 'Menu', 'Jenis Kendala', 'Status']];
        $no = 1;

        foreach ($allData as $row) {
            $rows[] = [
                $no++,
                $row['nama_ponpes'],
                $row['nama_wilayah'],
                $row['jenis_layanan'],
                $row['jenis_kendala'],
                ucfirst($row['status']),
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

    public function exportMonitoringClientPdf(Request $request)
    {
        // Similar to CSV but return PDF
        $regulerData = Reguller::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Komplain Reguler',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $vtrenData = Vtren::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Komplain VTREN',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $kunjunganData = Kunjungan::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Kunjungan',
                'jenis_kendala' => $item->keterangan ?? 'Monitoring rutin',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $pengirimanData = Pengiriman::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Pengiriman Alat',
                'jenis_kendala' => $item->keterangan ?? 'Pengiriman alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $settingAlatData = SettingPonpes::with(['ponpes.namaWilayah'])->get()->map(function ($item) {
            return [
                'nama_ponpes' => $item->ponpes->nama_ponpes ?? '-',
                'nama_wilayah' => $item->ponpes->namaWilayah->nama_wilayah ?? '-',
                'jenis_layanan' => 'Setting Alat',
                'jenis_kendala' => $item->keterangan ?? 'Setting alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $allData = collect()
            ->merge($regulerData)
            ->merge($vtrenData)
            ->merge($kunjunganData)
            ->merge($pengirimanData)
            ->merge($settingAlatData);

        $allData = $this->applyMonitoringFilters($allData, $request);

        $pdfData = [
            'title' => 'Monitoring Client Ponpes',
            'data' => $allData,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.mclient.DashboardPonpes', $pdfData);
        $filename = 'monitoring_client_ponpes_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

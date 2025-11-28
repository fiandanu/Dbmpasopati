<?php

namespace App\Http\Controllers\mclient\grafik;

use App\Http\Controllers\Controller;
use App\Models\mclient\catatankartu\Vtren as CatatanVtren;
use App\Models\mclient\ponpes\Reguller;
use App\Models\mclient\ponpes\Vtren;
use App\Models\mclient\ponpes\Kunjungan;
use App\Models\mclient\ponpes\Pengiriman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GrafikPonpesController extends Controller
{
    public function index()
    {
        return view('mclient.indexGrafikPonpes');
    }

    public function getData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            if ($type === 'daily') {
                return $this->getDailyData($startDate, $endDate);
            } else {
                return $this->getMonthlyData($startDate, $endDate);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getVtrenData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            if ($type === 'daily') {
                return $this->getVtrenDailyData($startDate, $endDate);
            } else {
                return $this->getVtrenMonthlyData($startDate, $endDate);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRegullerData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            if ($type === 'daily') {
                return $this->getRegullerDailyData($startDate, $endDate);
            } else {
                return $this->getRegullerMonthlyData($startDate, $endDate);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getKunjunganData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            if ($type === 'daily') {
                return $this->getKunjunganDailyData($startDate, $endDate);
            } else {
                return $this->getKunjunganMonthlyData($startDate, $endDate);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPengirimanData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            if ($type === 'daily') {
                return $this->getPengirimanDailyData($startDate, $endDate);
            } else {
                return $this->getPengirimanMonthlyData($startDate, $endDate);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'daily');
        $chartType = $request->get('chart_type', 'all-cards');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $chartImage = $request->get('chart_image');

        try {
            // Determine which data to fetch based on chart type
            if ($chartType === 'vtren-kendala') {
                $data = $type === 'daily'
                    ? $this->getVtrenDailyData($startDate, $endDate)
                    : $this->getVtrenMonthlyData($startDate, $endDate);
                $viewPath = 'export.public.mclient.grarfikponpes.indexKendala';
                $kendalaType = 'Vtren';
            } elseif ($chartType === 'reguler-kendala') {
                $data = $type === 'daily'
                    ? $this->getRegullerDailyData($startDate, $endDate)
                    : $this->getRegullerMonthlyData($startDate, $endDate);
                $viewPath = 'export.public.mclient.grarfikponpes.indexKendala';
                $kendalaType = 'Reguler';
            } elseif ($chartType === 'kunjungan-ponpes') {
                $data = $type === 'daily'
                    ? $this->getKunjunganDailyData($startDate, $endDate)
                    : $this->getKunjunganMonthlyData($startDate, $endDate);
                $viewPath = 'export.public.mclient.grarfikponpes.indexKunjungan';
            } elseif ($chartType === 'pengiriman-ponpes') {
                $data = $type === 'daily'
                    ? $this->getPengirimanDailyData($startDate, $endDate)
                    : $this->getPengirimanMonthlyData($startDate, $endDate);
                $viewPath = 'export.public.mclient.grarfikponpes.indexPengiriman';
            } elseif ($chartType === 'total-monthly') {
                $data = $type === 'daily'
                    ? $this->getDailyData($startDate, $endDate)
                    : $this->getMonthlyData($startDate, $endDate);
                $viewPath = 'export.public.mclient.grarfikponpes.indexTotal';
            } else {
                $data = $type === 'daily'
                    ? $this->getDailyData($startDate, $endDate)
                    : $this->getMonthlyData($startDate, $endDate);
                $viewPath = 'export.public.mclient.ponpes.indexGrafikPonpes';
            }

            $responseData = json_decode($data->getContent(), true);

            $pdf = Pdf::loadView($viewPath, [
                'data' => $responseData,
                'chartType' => $chartType,
                'type' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'chartImage' => $chartImage,
                'kendalaType' => $kendalaType ?? null,
            ]);

            $pdf->setPaper('A4', 'landscape');
            $filename = 'grafik_ponpes_'.$chartType.'_'.date('Y-m-d_His').'.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    private function getDailyData($startDate = null, $endDate = null)
    {
        if (! $startDate) {
            $startDate = Carbon::now()->subDays(29)->format('Y-m-d');
        }
        if (! $endDate) {
            $endDate = Carbon::now()->format('Y-m-d');
        }

        $data = CatatanVtren::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->format('Y-m-d');
            });

        $dates = [];
        $kartuBaru = [];
        $kartuBekas = [];
        $kartuGoip = [];
        $kartuBelumRegister = [];
        $whatsappTerpakai = [];
        $totalPerHari = [];

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $dateKey = $start->format('Y-m-d');
            $dates[] = $start->format('d M');

            if (isset($data[$dateKey])) {
                $dayData = $data[$dateKey];
                $kartuBaru[] = $dayData->sum('spam_vtren_kartu_baru');
                $kartuBekas[] = $dayData->sum('spam_vtren_kartu_bekas');
                $kartuGoip[] = $dayData->sum('spam_vtren_kartu_goip');
                $kartuBelumRegister[] = $dayData->sum('kartu_belum_teregister');
                $whatsappTerpakai[] = $dayData->sum('whatsapp_telah_terpakai');
                $totalPerHari[] = $dayData->sum('jumlah_kartu_terpakai_perhari');
            } else {
                $kartuBaru[] = 0;
                $kartuBekas[] = 0;
                $kartuGoip[] = 0;
                $kartuBelumRegister[] = 0;
                $whatsappTerpakai[] = 0;
                $totalPerHari[] = 0;
            }

            $start->addDay();
        }

        // Hitung summary data
        $totalKartuBaru = array_sum($kartuBaru);
        $totalKartuBekas = array_sum($kartuBekas);
        $totalKartuGoip = array_sum($kartuGoip);
        $totalKartuBelumRegister = array_sum($kartuBelumRegister);
        $totalWhatsappTerpakai = array_sum($whatsappTerpakai);

        return response()->json([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Kartu Baru',
                    'data' => $kartuBaru,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Kartu Bekas',
                    'data' => $kartuBekas,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
                [
                    'label' => 'Kartu GOIP',
                    'data' => $kartuGoip,
                    'borderColor' => 'rgb(168, 85, 247)',
                    'backgroundColor' => 'rgba(168, 85, 247, 0.1)',
                ],
                [
                    'label' => 'Kartu Belum Register',
                    'data' => $kartuBelumRegister,
                    'borderColor' => 'rgb(234, 179, 8)',
                    'backgroundColor' => 'rgba(234, 179, 8, 0.1)',
                ],
                [
                    'label' => 'WhatsApp Terpakai',
                    'data' => $whatsappTerpakai,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
            'totalPerHari' => $totalPerHari,
            'totalDataset' => [
                [
                    'label' => 'Total Kartu Terpakai',
                    'data' => $totalPerHari,
                    'borderColor' => 'rgb(147, 51, 234)',
                    'backgroundColor' => 'rgba(147, 51, 234, 0.1)',
                ],
            ],
            'summaryData' => [
                'kartuBaru' => $totalKartuBaru,
                'kartuBekas' => $totalKartuBekas,
                'kartuGoip' => $totalKartuGoip,
                'kartuBelumRegister' => $totalKartuBelumRegister,
                'whatsappTerpakai' => $totalWhatsappTerpakai,
            ],
        ]);
    }

    private function getMonthlyData($startDate = null, $endDate = null)
    {
        if (! $startDate) {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
        }

        if (! $endDate) {
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $endDate = Carbon::parse($endDate)->endOfMonth()->format('Y-m-d');
        }

        $data = CatatanVtren::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->format('Y-m');
            });

        $months = [];
        $totalKartu = [];
        $totalKartuBaru = 0;
        $totalKartuBekas = 0;
        $totalKartuGoip = 0;
        $totalKartuBelumRegister = 0;
        $totalWhatsappTerpakai = 0;

        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();

        while ($start->lte($end)) {
            $monthKey = $start->format('Y-m');
            $months[] = $start->format('M Y');

            if (isset($data[$monthKey])) {
                $monthData = $data[$monthKey];
                $totalKartu[] = $monthData->sum('jumlah_kartu_terpakai_perhari');
                $totalKartuBaru += $monthData->sum('spam_vtren_kartu_baru');
                $totalKartuBekas += $monthData->sum('spam_vtren_kartu_bekas');
                $totalKartuGoip += $monthData->sum('spam_vtren_kartu_goip');
                $totalKartuBelumRegister += $monthData->sum('kartu_belum_teregister');
                $totalWhatsappTerpakai += $monthData->sum('whatsapp_telah_terpakai');
            } else {
                $totalKartu[] = 0;
            }

            $start->addMonth();
        }

        return response()->json([
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Total Kartu Terpakai',
                    'data' => $totalKartu,
                    'borderColor' => 'rgb(147, 51, 234)',
                    'backgroundColor' => 'rgba(147, 51, 234, 0.1)',
                ],
            ],
            'summaryData' => [
                'kartuBaru' => $totalKartuBaru,
                'kartuBekas' => $totalKartuBekas,
                'kartuGoip' => $totalKartuGoip,
                'kartuBelumRegister' => $totalKartuBelumRegister,
                'whatsappTerpakai' => $totalWhatsappTerpakai,
            ],
        ]);
    }

    private function getVtrenDailyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            // 7 hari terakhir = hari ini + 6 hari sebelumnya
            $startDate = Carbon::now()->subDays(6)->startOfDay()->format('Y-m-d');
        }
        if (!$endDate) {
            // Pastikan endDate adalah akhir hari ini
            $endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }

        // Ambil semua data Vtren dalam rentang tanggal dengan eager loading
        $allVtrenData = Vtren::with('kendala')
            ->whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->whereNotNull('kendala_id')
            ->orderBy('tanggal_terlapor', 'asc')
            ->get();

        // Ambil SEMUA jenis kendala yang ADA di database (bukan hanya yang ada di range tanggal)
        $allKendalaTypes = \App\Models\user\kendala\Kendala::orderBy('jenis_kendala')->get();

        // Buat mapping kendala_id => jenis_kendala
        $kendalaMapping = [];
        foreach ($allKendalaTypes as $kendala) {
            $kendalaMapping[$kendala->id] = $kendala->jenis_kendala;
        }

        // Generate semua tanggal dalam rentang
        $dates = [];
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        $allDates = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $dateKey = $current->format('Y-m-d');
            $allDates[] = $dateKey;
            $dates[] = $current->format('d M');
            $current->addDay();
        }

        // Group data by date
        $dataByDate = $allVtrenData->groupBy(function ($item) {
            return $item->tanggal_terlapor->format('Y-m-d');
        });

        // Prepare datasets for each kendala type
        $datasets = [];
        $colors = [
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
            'rgb(234, 179, 8)',
            'rgb(239, 68, 68)',
            'rgb(147, 51, 234)',
            'rgb(14, 165, 233)',
            'rgb(236, 72, 153)',
            'rgb(249, 115, 22)',
            'rgb(20, 184, 166)',
            'rgb(168, 85, 247)',
        ];

        $colorIndex = 0;
        foreach ($kendalaMapping as $kendalaId => $jenisKendala) {
            $dataPoints = [];

            // Untuk setiap tanggal, hitung jumlah kendala jenis ini
            foreach ($allDates as $dateKey) {
                if (isset($dataByDate[$dateKey])) {
                    $count = $dataByDate[$dateKey]->where('kendala_id', $kendalaId)->count();
                    $dataPoints[] = $count;
                } else {
                    $dataPoints[] = 0;
                }
            }

            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenisKendala,
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        // Calculate status summary
        $statusCounts = Vtren::whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return response()->json([
            'labels' => $dates,
            'datasets' => $datasets,
            'summaryData' => [
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
                'total' => array_sum($statusCounts),
            ],
        ]);
    }

    private function getKunjunganDailyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subDays(6)->startOfDay()->format('Y-m-d');
        }
        if (!$endDate) {
            $endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }

        // Ambil data kunjungan berdasarkan created_at
        $kunjunganData = Kunjungan::with('ponpes')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        // Group by nama Ponpes dan hitung jumlah kunjungan
        $ponpesCounts = $kunjunganData->groupBy(function($item) {
                return $item->ponpes->nama_ponpes ?? 'Unknown';
            })
            ->map(function ($group) {
                return [
                    'nama_ponpes' => $group->first()->ponpes->nama_ponpes ?? 'Unknown',
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $labels = $ponpesCounts->pluck('nama_ponpes')->toArray();
        $data = $ponpesCounts->pluck('count')->toArray();

        // Calculate summary statistics
        $totalKunjungan = $kunjunganData->count();
        $topPonpes = $ponpesCounts->first()['nama_ponpes'] ?? '-';

        // Hitung status
        $statusCounts = $kunjunganData->groupBy('status')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'summaryData' => [
                'total' => $totalKunjungan,
                'topPonpes' => $topPonpes,
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
            ],
        ]);
    }

    private function getPengirimanDailyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subDays(6)->startOfDay()->format('Y-m-d');
        }
        if (!$endDate) {
            $endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }

        // Ambil data pengiriman berdasarkan created_at
        $pengirimanData = Pengiriman::with('ponpes')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        // Group by nama Ponpes dan hitung jumlah pengiriman
        $ponpesCounts = $pengirimanData->groupBy(function($item) {
                return $item->ponpes->nama_ponpes ?? 'Unknown';
            })
            ->map(function ($group) {
                return [
                    'nama_ponpes' => $group->first()->ponpes->nama_ponpes ?? 'Unknown',
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $labels = $ponpesCounts->pluck('nama_ponpes')->toArray();
        $data = $ponpesCounts->pluck('count')->toArray();

        // Calculate summary statistics
        $totalPengiriman = $pengirimanData->count();
        $topPonpes = $ponpesCounts->first()['nama_ponpes'] ?? '-';

        // Hitung status
        $statusCounts = $pengirimanData->groupBy('status')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'summaryData' => [
                'total' => $totalPengiriman,
                'topPonpes' => $topPonpes,
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
            ],
        ]);
    }

    private function getVtrenMonthlyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
        }

        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $endDate = Carbon::parse($endDate)->endOfMonth()->format('Y-m-d');
        }

        $allVtrenData = Vtren::with('kendala')
            ->whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->whereNotNull('kendala_id')
            ->orderBy('tanggal_terlapor', 'asc')
            ->get();

        // Ambil SEMUA jenis kendala dari database
        $allKendalaTypes = \App\Models\user\kendala\Kendala::orderBy('jenis_kendala')->get();

        $kendalaMapping = [];
        foreach ($allKendalaTypes as $kendala) {
            $kendalaMapping[$kendala->id] = $kendala->jenis_kendala;
        }

        // Generate all months in range
        $months = [];
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();
        $allMonths = [];

        while ($start->lte($end)) {
            $monthKey = $start->format('Y-m');
            $allMonths[] = $monthKey;
            $months[] = $start->format('M Y');
            $start->addMonth();
        }

        $dataByMonth = $allVtrenData->groupBy(function ($item) {
            return $item->tanggal_terlapor->format('Y-m');
        });

        $datasets = [];
        $colors = [
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
            'rgb(234, 179, 8)',
            'rgb(239, 68, 68)',
            'rgb(147, 51, 234)',
            'rgb(14, 165, 233)',
            'rgb(236, 72, 153)',
            'rgb(249, 115, 22)',
            'rgb(20, 184, 166)',
            'rgb(168, 85, 247)',
        ];

        $colorIndex = 0;
        foreach ($kendalaMapping as $kendalaId => $jenisKendala) {
            $dataPoints = [];

            foreach ($allMonths as $monthKey) {
                if (isset($dataByMonth[$monthKey])) {
                    $count = $dataByMonth[$monthKey]->where('kendala_id', $kendalaId)->count();
                    $dataPoints[] = $count;
                } else {
                    $dataPoints[] = 0;
                }
            }

            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenisKendala,
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Vtren::whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return response()->json([
            'labels' => $months,
            'datasets' => $datasets,
            'summaryData' => [
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
                'total' => array_sum($statusCounts),
            ],
        ]);
    }

    private function getRegullerDailyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            // 7 hari terakhir = hari ini + 6 hari sebelumnya
            $startDate = Carbon::now()->subDays(6)->startOfDay()->format('Y-m-d');
        }
        if (!$endDate) {
            // Pastikan endDate adalah akhir hari ini
            $endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }

        $allRegullerData = Reguller::with('kendala')
            ->whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->whereNotNull('kendala_id')
            ->orderBy('tanggal_terlapor', 'asc')
            ->get();

        // Ambil SEMUA jenis kendala dari database
        $allKendalaTypes = \App\Models\user\kendala\Kendala::orderBy('jenis_kendala')->get();

        $kendalaMapping = [];
        foreach ($allKendalaTypes as $kendala) {
            $kendalaMapping[$kendala->id] = $kendala->jenis_kendala;
        }

        $dates = [];
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        $allDates = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $dateKey = $current->format('Y-m-d');
            $allDates[] = $dateKey;
            $dates[] = $current->format('d M');
            $current->addDay();
        }

        $dataByDate = $allRegullerData->groupBy(function ($item) {
            return $item->tanggal_terlapor->format('Y-m-d');
        });

        $datasets = [];
        $colors = [
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
            'rgb(234, 179, 8)',
            'rgb(239, 68, 68)',
            'rgb(147, 51, 234)',
            'rgb(14, 165, 233)',
            'rgb(236, 72, 153)',
            'rgb(249, 115, 22)',
            'rgb(20, 184, 166)',
            'rgb(168, 85, 247)',
        ];

        $colorIndex = 0;
        foreach ($kendalaMapping as $kendalaId => $jenisKendala) {
            $dataPoints = [];

            foreach ($allDates as $dateKey) {
                if (isset($dataByDate[$dateKey])) {
                    $count = $dataByDate[$dateKey]->where('kendala_id', $kendalaId)->count();
                    $dataPoints[] = $count;
                } else {
                    $dataPoints[] = 0;
                }
            }

            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenisKendala,
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Reguller::whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return response()->json([
            'labels' => $dates,
            'datasets' => $datasets,
            'summaryData' => [
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
                'total' => array_sum($statusCounts),
            ],
        ]);
    }

    private function getRegullerMonthlyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
        }

        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $endDate = Carbon::parse($endDate)->endOfMonth()->format('Y-m-d');
        }

        $allRegullerData = Reguller::with('kendala')
            ->whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->whereNotNull('kendala_id')
            ->orderBy('tanggal_terlapor', 'asc')
            ->get();

        // Ambil SEMUA jenis kendala dari database
        $allKendalaTypes = \App\Models\user\kendala\Kendala::orderBy('jenis_kendala')->get();

        $kendalaMapping = [];
        foreach ($allKendalaTypes as $kendala) {
            $kendalaMapping[$kendala->id] = $kendala->jenis_kendala;
        }

        $months = [];
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();
        $allMonths = [];

        while ($start->lte($end)) {
            $monthKey = $start->format('Y-m');
            $allMonths[] = $monthKey;
            $months[] = $start->format('M Y');
            $start->addMonth();
        }

        $dataByMonth = $allRegullerData->groupBy(function ($item) {
            return $item->tanggal_terlapor->format('Y-m');
        });

        $datasets = [];
        $colors = [
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
            'rgb(234, 179, 8)',
            'rgb(239, 68, 68)',
            'rgb(147, 51, 234)',
            'rgb(14, 165, 233)',
            'rgb(236, 72, 153)',
            'rgb(249, 115, 22)',
            'rgb(20, 184, 166)',
            'rgb(168, 85, 247)',
        ];

        $colorIndex = 0;
        foreach ($kendalaMapping as $kendalaId => $jenisKendala) {
            $dataPoints = [];

            foreach ($allMonths as $monthKey) {
                if (isset($dataByMonth[$monthKey])) {
                    $count = $dataByMonth[$monthKey]->where('kendala_id', $kendalaId)->count();
                    $dataPoints[] = $count;
                } else {
                    $dataPoints[] = 0;
                }
            }

            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenisKendala,
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Reguller::whereDate('tanggal_terlapor', '>=', $startDate)
            ->whereDate('tanggal_terlapor', '<=', $endDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return response()->json([
            'labels' => $months,
            'datasets' => $datasets,
            'summaryData' => [
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
                'total' => array_sum($statusCounts),
            ],
        ]);
    }

    private function getKunjunganMonthlyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
        }

        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $endDate = Carbon::parse($endDate)->endOfMonth()->format('Y-m-d');
        }

        $kunjunganData = Kunjungan::with('ponpes')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        // Group by nama Ponpes
        $ponpesCounts = $kunjunganData->groupBy(function($item) {
                return $item->ponpes->nama_ponpes ?? 'Unknown';
            })
            ->map(function ($group) {
                return [
                    'nama_ponpes' => $group->first()->ponpes->nama_ponpes ?? 'Unknown',
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $labels = $ponpesCounts->pluck('nama_ponpes')->toArray();
        $data = $ponpesCounts->pluck('count')->toArray();

        $totalKunjungan = $kunjunganData->count();
        $topPonpes = $ponpesCounts->first()['nama_ponpes'] ?? '-';

        $statusCounts = $kunjunganData->groupBy('status')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'summaryData' => [
                'total' => $totalKunjungan,
                'topPonpes' => $topPonpes,
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
            ],
        ]);
    }

    private function getPengirimanMonthlyData($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
        }

        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $endDate = Carbon::parse($endDate)->endOfMonth()->format('Y-m-d');
        }

        $pengirimanData = Pengiriman::with('ponpes')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        // Group by nama Ponpes
        $ponpesCounts = $pengirimanData->groupBy(function($item) {
                return $item->ponpes->nama_ponpes ?? 'Unknown';
            })
            ->map(function ($group) {
                return [
                    'nama_ponpes' => $group->first()->ponpes->nama_ponpes ?? 'Unknown',
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $labels = $ponpesCounts->pluck('nama_ponpes')->toArray();
        $data = $ponpesCounts->pluck('count')->toArray();

        $totalPengiriman = $pengirimanData->count();
        $topPonpes = $ponpesCounts->first()['nama_ponpes'] ?? '-';

        $statusCounts = $pengirimanData->groupBy('status')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'summaryData' => [
                'total' => $totalPengiriman,
                'topPonpes' => $topPonpes,
                'selesai' => $statusCounts['selesai'] ?? 0,
                'proses' => $statusCounts['proses'] ?? 0,
                'pending' => $statusCounts['pending'] ?? 0,
                'terjadwal' => $statusCounts['terjadwal'] ?? 0,
            ],
        ]);
    }
}

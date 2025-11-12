<?php

namespace App\Http\Controllers\mclient\grafik;

use App\Http\Controllers\Controller;
use App\Models\mclient\catatankartu\Catatan;
use App\Models\mclient\Reguller;
use App\Models\mclient\Vpas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GrafikUptController extends Controller
{
    public function index()
    {
        return view('mclient.indexGrafikUpt');
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

    // TAMBAHAN: Method untuk data VPAS
    public function getVpasData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            if ($type === 'daily') {
                return $this->getVpasDailyData($startDate, $endDate);
            } else {
                return $this->getVpasMonthlyData($startDate, $endDate);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // TAMBAHAN: Method untuk data Reguler
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

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'daily');
        $chartType = $request->get('chart_type', 'all-cards');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $chartImage = $request->get('chart_image'); // Base64 image dari chart

        try {
            // Get data based on chart type
            if ($chartType === 'vpas-kendala') {
                $data = $type === 'daily'
                    ? $this->getVpasDailyData($startDate, $endDate)
                    : $this->getVpasMonthlyData($startDate, $endDate);
            } elseif ($chartType === 'reguler-kendala') {
                $data = $type === 'daily'
                    ? $this->getRegullerDailyData($startDate, $endDate)
                    : $this->getRegullerMonthlyData($startDate, $endDate);
            } else {
                $data = $type === 'daily'
                    ? $this->getDailyData($startDate, $endDate)
                    : $this->getMonthlyData($startDate, $endDate);
            }

            $responseData = json_decode($data->getContent(), true);

            $pdf = Pdf::loadView('export.public.mclient.upt.indexGrafikUpt', [
                'data' => $responseData,
                'chartType' => $chartType,
                'type' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'chartImage' => $chartImage, // Kirim image ke view
            ]);

            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');

            $filename = 'grafik_'.$chartType.'_'.date('Y-m-d_His').'.pdf';

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

        $data = Catatan::whereBetween('tanggal', [$startDate, $endDate])
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
                $kartuBaru[] = $dayData->sum('spam_vpas_kartu_baru');
                $kartuBekas[] = $dayData->sum('spam_vpas_kartu_bekas');
                $kartuGoip[] = $dayData->sum('spam_vpas_kartu_goip');
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

        return response()->json([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Kartu Baru',
                    'data' => $kartuBaru,
                    'borderColor' => 'rgb(59, 130, 246)', // Biru
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Kartu Bekas',
                    'data' => $kartuBekas,
                    'borderColor' => 'rgb(34, 197, 94)', // Hijau
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
                [
                    'label' => 'Kartu GOIP',
                    'data' => $kartuGoip,
                    'borderColor' => 'rgb(168, 85, 247)', // Ungu (CHANGED)
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

        $data = Catatan::whereBetween('tanggal', [$startDate, $endDate])
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
                $totalKartuBaru += $monthData->sum('spam_vpas_kartu_baru');
                $totalKartuBekas += $monthData->sum('spam_vpas_kartu_bekas');
                $totalKartuGoip += $monthData->sum('spam_vpas_kartu_goip');
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

    // TAMBAHAN: VPAS Daily Data
    private function getVpasDailyData($startDate = null, $endDate = null)
    {
        if (! $startDate) {
            $startDate = Carbon::now()->subDays(6)->format('Y-m-d');
        }
        if (! $endDate) {
            $endDate = Carbon::now()->format('Y-m-d');
        }

        $data = Vpas::whereBetween('tanggal_terlapor', [$startDate, $endDate])
            ->orderBy('tanggal_terlapor', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_terlapor->format('Y-m-d');
            });

        // PERBAIKAN: Gunakan relasi kendala
        $jenisKendalaList = Vpas::with('kendala')
            ->whereNotNull('kendala_id')
            ->whereBetween('tanggal_terlapor', [$startDate, $endDate])
            ->get()
            ->pluck('kendala.jenis_kendala', 'kendala_id')
            ->unique()
            ->filter()
            ->toArray();

        $dates = [];
        $kendalaData = [];

        foreach ($jenisKendalaList as $jenis) {
            $kendalaData[$jenis] = [];
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $dateKey = $start->format('Y-m-d');
            $dates[] = $start->format('d M');

            foreach ($jenisKendalaList as $kendalaId => $jenis) {
                if (isset($data[$dateKey])) {
                    $count = $data[$dateKey]->where('kendala_id', $kendalaId)->count();
                    $kendalaData[$jenis][] = $count;
                } else {
                    $kendalaData[$jenis][] = 0;
                }
            }

            $start->addDay();
        }

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
        foreach ($kendalaData as $jenis => $dataPoints) {
            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenis ?: 'Tidak ada jenis',
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Vpas::whereBetween('tanggal_terlapor', [$startDate, $endDate])
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

    // TAMBAHAN: VPAS Monthly Data
    private function getVpasMonthlyData($startDate = null, $endDate = null)
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

        $data = Vpas::whereBetween('tanggal_terlapor', [$startDate, $endDate])
            ->orderBy('tanggal_terlapor', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_terlapor->format('Y-m');
            });

    $jenisKendalaList = Vpas::with('kendala')
        ->whereNotNull('kendala_id')
        ->whereBetween('tanggal_terlapor', [$startDate, $endDate])
        ->get()
        ->pluck('kendala.jenis_kendala', 'kendala_id')
        ->unique()
        ->filter()
        ->toArray();

        $months = [];
        $kendalaData = [];

        foreach ($jenisKendalaList as $jenis) {
            $kendalaData[$jenis] = [];
        }

        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();

        while ($start->lte($end)) {
            $monthKey = $start->format('Y-m');
            $months[] = $start->format('M Y');

            foreach ($jenisKendalaList as $jenis) {
                if (isset($data[$monthKey])) {
                    $count = $data[$monthKey]->where('jenis_kendala', $jenis)->count();
                    $kendalaData[$jenis][] = $count;
                } else {
                    $kendalaData[$jenis][] = 0;
                }
            }

            $start->addMonth();
        }

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
        foreach ($kendalaData as $jenis => $dataPoints) {
            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenis ?: 'Tidak ada jenis',
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Vpas::whereBetween('tanggal_terlapor', [$startDate, $endDate])
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

    // TAMBAHAN: Reguler Daily Data
    private function getRegullerDailyData($startDate = null, $endDate = null)
    {
        if (! $startDate) {
            $startDate = Carbon::now()->subDays(6)->format('Y-m-d');
        }
        if (! $endDate) {
            $endDate = Carbon::now()->format('Y-m-d');
        }

        $data = Reguller::whereBetween('tanggal_terlapor', [$startDate, $endDate])
            ->orderBy('tanggal_terlapor', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_terlapor->format('Y-m-d');
            });

    $jenisKendalaList = Reguller::with('kendala')
        ->whereNotNull('kendala_id')
        ->whereBetween('tanggal_terlapor', [$startDate, $endDate])
        ->get()
        ->pluck('kendala.jenis_kendala', 'kendala_id')
        ->unique()
        ->filter()
        ->toArray();

        $dates = [];
        $kendalaData = [];

        foreach ($jenisKendalaList as $jenis) {
            $kendalaData[$jenis] = [];
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $dateKey = $start->format('Y-m-d');
            $dates[] = $start->format('d M');

            foreach ($jenisKendalaList as $jenis) {
                if (isset($data[$dateKey])) {
                    $count = $data[$dateKey]->where('jenis_kendala', $jenis)->count();
                    $kendalaData[$jenis][] = $count;
                } else {
                    $kendalaData[$jenis][] = 0;
                }
            }

            $start->addDay();
        }

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
        foreach ($kendalaData as $jenis => $dataPoints) {
            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenis ?: 'Tidak ada jenis',
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Reguller::whereBetween('tanggal_terlapor', [$startDate, $endDate])
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

    // TAMBAHAN: Reguler Monthly Data
    private function getRegullerMonthlyData($startDate = null, $endDate = null)
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

        $data = Reguller::whereBetween('tanggal_terlapor', [$startDate, $endDate])
            ->orderBy('tanggal_terlapor', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_terlapor->format('Y-m');
            });
            
    $jenisKendalaList = Reguller::with('kendala')
        ->whereNotNull('kendala_id')
        ->whereBetween('tanggal_terlapor', [$startDate, $endDate])
        ->get()
        ->pluck('kendala.jenis_kendala', 'kendala_id')
        ->unique()
        ->filter()
        ->toArray();
        $months = [];
        $kendalaData = [];

        foreach ($jenisKendalaList as $jenis) {
            $kendalaData[$jenis] = [];
        }

        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();

        while ($start->lte($end)) {
            $monthKey = $start->format('Y-m');
            $months[] = $start->format('M Y');

            foreach ($jenisKendalaList as $jenis) {
                if (isset($data[$monthKey])) {
                    $count = $data[$monthKey]->where('jenis_kendala', $jenis)->count();
                    $kendalaData[$jenis][] = $count;
                } else {
                    $kendalaData[$jenis][] = 0;
                }
            }

            $start->addMonth();
        }

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
        foreach ($kendalaData as $jenis => $dataPoints) {
            $color = $colors[$colorIndex % count($colors)];
            $datasets[] = [
                'label' => $jenis ?: 'Tidak ada jenis',
                'data' => $dataPoints,
                'borderColor' => $color,
                'backgroundColor' => str_replace('rgb', 'rgba', str_replace(')', ', 0.1)', $color)),
            ];
            $colorIndex++;
        }

        $statusCounts = Reguller::whereBetween('tanggal_terlapor', [$startDate, $endDate])
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
}

<?php

namespace App\Http\Controllers\mclient\reguler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\mclient\Pengiriman;
use App\Models\user\upt\Upt;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\pic\Pic;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanController extends Controller
{
    private function applyFilters($query, Request $request)
    {
        // column-specific search
        if ($request->has('search_nama_upt') && !empty($request->search_nama_upt)) {
            $query->where('nama_upt', 'LIKE', '%' . $request->search_nama_upt . '%');
        }
        if ($request->has('search_kanwil') && !empty($request->search_kanwil)) {
            $query->where('kanwil', 'LIKE', '%' . $request->search_kanwil . '%');
        }
        if ($request->has('search_jenis_layanan') && !empty($request->search_jenis_layanan)) {
            $query->where('jenis_layanan', 'LIKE', '%' . $request->search_jenis_layanan . '%');
        }
        if ($request->has('search_keterangan') && !empty($request->search_keterangan)) {
            $query->where('keterangan', 'LIKE', '%' . $request->search_keterangan . '%');
        }
        if ($request->has('search_status') && !empty($request->search_status)) {
            $query->where('status', 'LIKE', '%' . $request->search_status . '%');
        }
        if ($request->has('search_pic_1') && !empty($request->search_pic_1)) {
            $query->where('pic_1', 'LIKE', '%' . $request->search_pic_1 . '%');
        }
        if ($request->has('search_pic_2') && !empty($request->search_pic_2)) {
            $query->where('pic_2', 'LIKE', '%' . $request->search_pic_2 . '%');
        }

        // Date range filtering - PERBAIKAN DI SINI
        if ($request->has('search_tanggal_pengiriman_dari') && !empty($request->search_tanggal_pengiriman_dari)) {
            $query->whereDate('tanggal_pengiriman', '>=', $request->search_tanggal_pengiriman_dari);
        }
        if ($request->has('search_tanggal_pengiriman_sampai') && !empty($request->search_tanggal_pengiriman_sampai)) {
            $query->whereDate('tanggal_pengiriman', '<=', $request->search_tanggal_pengiriman_sampai);
        }
        if ($request->has('search_tanggal_selesai_dari') && !empty($request->search_tanggal_selesai_dari)) {
            $query->whereDate('tanggal_sampai', '>=', $request->search_tanggal_selesai_dari);
        }
        if ($request->has('search_tanggal_selesai_sampai') && !empty($request->search_tanggal_selesai_sampai)) {
            $query->whereDate('tanggal_sampai', '<=', $request->search_tanggal_selesai_sampai);
        }

        return $query;
    }

    private function getJenisLayanan()
    {
        return [
            'vpas' => 'VPAS',
            'reguler' => 'Reguler',
            'vpasreg' => 'VPAS + Reguler'
        ];
    }

    public function exportListPdf(Request $request)
    {
        $query = Pengiriman::query();
        $query = $this->applyFilters($query, $request);

        if (
            $request->filled('search_tanggal_pengiriman_dari') || $request->filled('search_tanggal_pengiriman_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_pengiriman', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $pdfData = [
            'title' => 'List Data Pengiriman Alat UPT',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.mclient.upt.indexPengirimanAlat', $pdfData);
        $filename = 'list_pengiriman_alat_upt_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request)
    {
        $query = Pengiriman::query();
        $query = $this->applyFilters($query, $request);

        if (
            $request->filled('search_tanggal_pengiriman_dari') || $request->filled('search_tanggal_pengiriman_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_pengiriman', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'List_Pengiriman_Alat_Upt_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama UPT', 'Jenis Layanan', 'Keterangan', 'Tanggal Pengiriman', 'Tanggal Sampai', 'Durasi (Hari)', 'Status', 'PIC 1', 'PIC 2', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->nama_upt,
                $row->jenis_layanan,
                $row->keterangan,
                $row->tanggal_pengiriman ? $row->tanggal_pengiriman->format('Y-m-d') : '',
                $row->tanggal_sampai ? $row->tanggal_sampai->format('Y-m-d') : '',
                $row->durasi_hari,
                $row->status,
                $row->pic_1,
                $row->pic_2,
                $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : ''
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

    public function ListDataMclientPengirimanUpt(Request $request)
    {
        $query = Pengiriman::query();

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Handle pagination
        if ($perPage == 'all') {
            $data = $query->orderBy('created_at', 'desc')->get();
            // Create a mock paginator for "all" option
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $data->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
        }

        $picList = Pic::orderBy('nama_pic')->get();

        // Get UPT list based on jenis layanan
        $uptListVpas = Upt::with('kanwil:id,kanwil')
            ->where('tipe', 'vpas')
            ->orderBy('namaupt')
            ->get()
            ->map(function ($upt) {
                return [
                    'namaupt' => $upt->namaupt,
                    'kanwil' => $upt->kanwil->kanwil ?? '-'
                ];
            });

        $uptListReguler = Upt::with('kanwil:id,kanwil')
            ->where('tipe', 'reguler')
            ->orderBy('namaupt')
            ->get()
            ->map(function ($upt) {
                return [
                    'namaupt' => $upt->namaupt,
                    'kanwil' => $upt->kanwil->kanwil ?? '-'
                ];
            });

        // Combine both lists for vpasreg
        $uptListAll = $uptListVpas->merge($uptListReguler)->unique('namaupt')->sortBy('namaupt');

        $jenisLayananOptions = $this->getJenisLayanan();

        return view('mclient.upt.indexPengiriman', compact(
            'data',
            'picList',
            'uptListVpas',
            'uptListReguler',
            'uptListAll',
            'jenisLayananOptions'
        ));
    }

    public function MclientPengirimanStoreUpt(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'jenis_layanan' => 'required|string|in:vpas,reguler,vpasreg',
                'keterangan' => 'nullable|string',
                'tanggal_pengiriman' => 'nullable|date',
                'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_pengiriman',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'jenis_layanan.required' => 'Jenis layanan harus dipilih.',
                'jenis_layanan.in' => 'Jenis layanan harus salah satu dari: VPAS, Reguler, atau VPAS + Reguler.',
                'keterangan.string' => 'Keterangan harus berupa teks.',
                'tanggal_pengiriman.date' => 'Format tanggal pengiriman harus valid.',
                'tanggal_sampai.date' => 'Format tanggal sampai harus valid.',
                'tanggal_sampai.after_or_equal' => 'Tanggal sampai tidak boleh lebih awal dari tanggal pengiriman.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            $data = $request->all();

            if ($request->tanggal_pengiriman) {
                if ($request->tanggal_sampai) {
                    $tanggalSampai = Carbon::parse($request->tanggal_sampai);
                } else {
                    $tanggalSampai = Carbon::now();
                    $data['tanggal_sampai'] = $tanggalSampai;
                }

                $tanggalSampai = Carbon::parse($request->tanggal_sampai);
                $data['durasi_hari'] = $tanggalSampai->diffInDays(Carbon::parse($request->tanggal_pengiriman));
            } else {
                $data['durasi_hari'] = null;
            }

            Pengiriman::create($data);

            return redirect()->back()->with('success', 'Data pengiriman alat monitoring client berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientPengirimanUpdateUpt(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'jenis_layanan' => 'required|string|in:vpas,reguler,vpasreg',
                'keterangan' => 'nullable|string',
                'tanggal_pengiriman' => 'nullable|date',
                'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_pengiriman',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'jenis_layanan.required' => 'Jenis layanan harus dipilih.',
                'jenis_layanan.in' => 'Jenis layanan harus salah satu dari: VPAS, Reguler, atau VPAS + Reguler.',
                'keterangan.string' => 'Keterangan harus berupa teks.',
                'tanggal_pengiriman.date' => 'Format tanggal pengiriman harus valid.',
                'tanggal_sampai.date' => 'Format tanggal sampai harus valid.',
                'tanggal_sampai.after_or_equal' => 'Tanggal sampai tidak boleh lebih awal dari tanggal pengiriman.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            $data = Pengiriman::findOrFail($id);
            $updateData = $request->all();

            // Kalkulasi durasi_hari jika tanggal_sampai diisi
            if ($request->tanggal_sampai) {
                // Gunakan tanggal_pengiriman dari request atau fallback ke data existing
                if ($request->tanggal_pengiriman) {
                    $tanggalPengiriman = Carbon::parse($request->tanggal_pengiriman);
                } else {
                    $tanggalPengiriman = Carbon::parse($data->tanggal_pengiriman ?? $data->created_at);
                }

                $tanggalSampai = Carbon::parse($request->tanggal_sampai);
                $updateData['durasi_hari'] = $tanggalPengiriman->diffInDays($tanggalSampai);
            } elseif ($request->has('tanggal_sampai') && empty($request->tanggal_sampai)) {
                // Reset durasi_hari jika tanggal_sampai dikosongkan
                $updateData['durasi_hari'] = null;
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data pengiriman alat monitoring client berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientKunjunganDestroyUpt($id)
    {
        try {
            $data = Pengiriman::findOrFail($id);
            $namaUpt = $data->nama_upt;
            $jenisLayanan = $data->formatted_jenis_layanan;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data pengiriman alat monitoring client '{$jenisLayanan}' di UPT '{$namaUpt}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function getDashboardStats()
    {
        $totalData = Pengiriman::count();
        $statusPending = Pengiriman::where('status', 'pending')->count();
        $statusProses = Pengiriman::where('status', 'proses')->count();
        $statusSelesai = Pengiriman::where('status', 'selesai')->count();
        $statusTerjadwal = Pengiriman::where('status', 'terjadwal')->count();

        $jenisVpas = Pengiriman::where('jenis_layanan', 'vpas')->count();
        $jenisReguler = Pengiriman::where('jenis_layanan', 'reguler')->count();
        $jenisVpasReg = Pengiriman::where('jenis_layanan', 'vpasreg')->count();

        $bulanIni = Pengiriman::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $avgDurasi = Pengiriman::where('status', 'selesai')
            ->whereNotNull('durasi_hari')
            ->avg('durasi_hari');

        return [
            'total' => $totalData,
            'pending' => $statusPending,
            'proses' => $statusProses,
            'selesai' => $statusSelesai,
            'terjadwal' => $statusTerjadwal,
            'vpas' => $jenisVpas,
            'reguler' => $jenisReguler,
            'vpasreg' => $jenisVpasReg,
            'bulan_ini' => $bulanIni,
            'avg_durasi' => round($avgDurasi, 1)
        ];
    }

    public function getUptData(Request $request)
    {
        $namaUpt = $request->input('nama_upt');
        $jenisLayanan = $request->input('jenis_layanan');

        // Determine which UPT table to query based on jenis layanan
        $tipeUpt = '';
        switch ($jenisLayanan) {
            case 'vpas':
                $tipeUpt = 'vpas';
                break;
            case 'reguler':
                $tipeUpt = 'reguler';
                break;
            case 'vpasreg':
                // For vpasreg, check both vpas and reguler
                $upt = Upt::where('namaupt', $namaUpt)
                    ->whereIn('tipe', ['vpas', 'reguler'])
                    ->first();
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jenis layanan tidak valid'
                ]);
        }

        if ($jenisLayanan !== 'vpasreg') {
            $upt = Upt::where('namaupt', $namaUpt)
                ->where('tipe', $tipeUpt)
                ->first();
        }

        if (isset($upt) && $upt) {
            return response()->json([
                'status' => 'success',
                'kanwil' => $upt->kanwil
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'UPT not found untuk jenis layanan tersebut'
        ]);
    }
}

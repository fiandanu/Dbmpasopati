<?php

namespace App\Http\Controllers\mclient\ponpes;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\SettingPonpes;
use App\Models\user\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\Pic;
use Barryvdh\DomPDF\Facade\Pdf;

class SettingPonpesController extends Controller
{
    public function exportListPdf(Request $request)
    {
        $query = SettingPonpes::query();
        $query = $this->applyFilters($query, $request);

        if (
            $request->filled('search_tanggal_terlapor_dari') || $request->filled('search_tanggal_terlapor_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_terlapor', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $pdfData = [
            'title' => 'List Data Setting Ponpes',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.mclient.ponpes.indexSetting', $pdfData);
        $filename = 'list_setting_ponpes_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request)
    {
        $query = SettingPonpes::query();
        $query = $this->applyFilters($query, $request);

        if (
            $request->filled('search_tanggal_terlapor_dari') || $request->filled('search_tanggal_terlapor_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_terlapor', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'List_Setting_Ponpes_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Jenis Layanan', 'Keterangan', 'Tanggal Terlapor', 'Tanggal Selesai', 'Durasi (Hari)', 'Status', 'Pic 1', 'Pic 2', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->nama_ponpes,
                $row->formatted_jenis_layanan,
                $row->keterangan,
                $row->tanggal_terlapor ? $row->tanggal_terlapor->format('Y-m-d') : '',
                $row->tanggal_selesai ? $row->tanggal_selesai->format('Y-m-d') : '',
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

    private function getJenisLayanan()
    {
        return [
            'vtren' => 'VTREN',
            'reguler' => 'Reguler',
            'vtrenreg' => 'VTREN + Reguler'
        ];
    }

    public function ListDataMclientPonpesSetting(Request $request)
    {
        $query = SettingPonpes::query();

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

        // Get Ponpes list based on jenis layanan
        $ponpesListVtren = Ponpes::select('nama_ponpes', 'nama_wilayah', 'tipe')
            ->where('tipe', 'vtren')
            ->orderBy('nama_ponpes')
            ->get();

        $ponpesListReguler = Ponpes::select('nama_ponpes', 'nama_wilayah', 'tipe')
            ->where('tipe', 'reguler')
            ->orderBy('nama_ponpes')
            ->get();

        // Combine both lists for vtrenreg
        $ponpesListAll = $ponpesListVtren->merge($ponpesListReguler)->unique('nama_ponpes')->sortBy('nama_ponpes');

        $jenisLayananOptions = $this->getJenisLayanan();

        return view('mclient.ponpes.indexSettingPonpes', compact(
            'data',
            'picList',
            'ponpesListVtren',
            'ponpesListReguler',
            'ponpesListAll',
            'jenisLayananOptions'
        ));
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific search
        if ($request->has('search_nama_ponpes') && !empty($request->search_nama_ponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_nama_ponpes . '%');
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

        // Date range filtering for tanggal_terlapor
        if ($request->has('search_tanggal_terlapor_dari') && !empty($request->search_tanggal_terlapor_dari)) {
            $query->whereDate('tanggal_terlapor', '>=', $request->search_tanggal_terlapor_dari);
        }
        if ($request->has('search_tanggal_terlapor_sampai') && !empty($request->search_tanggal_terlapor_sampai)) {
            $query->whereDate('tanggal_terlapor', '<=', $request->search_tanggal_terlapor_sampai);
        }

        // Date range filtering for tanggal_selesai
        if ($request->has('search_tanggal_selesai_dari') && !empty($request->search_tanggal_selesai_dari)) {
            $query->whereDate('tanggal_selesai', '>=', $request->search_tanggal_selesai_dari);
        }
        if ($request->has('search_tanggal_selesai_sampai') && !empty($request->search_tanggal_selesai_sampai)) {
            $query->whereDate('tanggal_selesai', '<=', $request->search_tanggal_selesai_sampai);
        }

        return $query;
    }

    public function MclientPonpesSettingStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|max:255',
                'jenis_layanan' => 'required|string|in:vtren,reguler,vtrenreg',
                'keterangan' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_ponpes.string' => 'Nama Ponpes harus berupa teks.',
                'nama_ponpes.max' => 'Nama Ponpes tidak boleh lebih dari 255 karakter.',
                'jenis_layanan.required' => 'Jenis layanan harus dipilih.',
                'jenis_layanan.in' => 'Jenis layanan harus salah satu dari: VTREN, Reguler, atau VTREN + Reguler.',
                'keterangan.string' => 'Keterangan harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
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

            // Hitung durasi HANYA jika tanggal_selesai ada
            if ($request->tanggal_selesai && $request->tanggal_terlapor) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalTerlapor->diffInDays($tanggalSelesai);
            } else {
                // Jika belum ada tanggal_selesai, set null (akan dihitung dinamis)
                $data['durasi_hari'] = null;
            }

            SettingPonpes::create($data);

            return redirect()->back()->with('success', 'Data setting monitoring client Ponpes berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

public function MclientPonpesSettingUpdate(Request $request, $id)
{
    $validator = Validator::make(
        $request->all(),
        [
            'nama_ponpes' => 'required|string|max:255',
            'jenis_layanan' => 'required|string|in:vtren,reguler,vtrenreg',
            'keterangan' => 'nullable|string',
            'tanggal_terlapor' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
            'durasi_hari' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
            'pic_1' => 'nullable|string|max:255',
            'pic_2' => 'nullable|string|max:255',
        ],
        [
            'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
            'nama_ponpes.string' => 'Nama Ponpes harus berupa teks.',
            'nama_ponpes.max' => 'Nama Ponpes tidak boleh lebih dari 255 karakter.',
            'jenis_layanan.required' => 'Jenis layanan harus dipilih.',
            'jenis_layanan.in' => 'Jenis layanan harus salah satu dari: VTREN, Reguler, atau VTREN + Reguler.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
            'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
            'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
            'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
            'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
            'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
            'pic_1.string' => 'PIC 1 harus berupa teks.',
            'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
            'pic_2.string' => 'PIC 2 harus berupa teks.',
            'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
        ]
    );

    try {
        $data = SettingPonpes::findOrFail($id);
        $updateData = $request->all();

        // Hitung dan simpan durasi HANYA jika tanggal_selesai baru ditentukan
        if ($request->tanggal_selesai && $request->tanggal_terlapor) {
            $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
            $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
            $updateData['durasi_hari'] = $tanggalTerlapor->diffInDays($tanggalSelesai);
        } elseif ($request->has('tanggal_selesai') && empty($request->tanggal_selesai)) {
            // Jika tanggal_selesai dihapus, set durasi ke null
            $updateData['durasi_hari'] = null;
        }

        $data->update($updateData);

        return redirect()->back()->with('success', 'Data setting monitoring client Ponpes berhasil diupdate!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal update data: ' . $e->getMessage());
    }
}

    public function MclientPonpesSettingDestroy($id)
    {
        try {
            $data = SettingPonpes::findOrFail($id);
            $namaPonpes = $data->nama_ponpes;
            $jenisLayanan = $data->formatted_jenis_layanan;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data setting monitoring client '{$jenisLayanan}' di Ponpes '{$namaPonpes}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function getDashboardStats()
    {
        $totalData = SettingPonpes::count();
        $statusPending = SettingPonpes::where('status', 'pending')->count();
        $statusProses = SettingPonpes::where('status', 'proses')->count();
        $statusSelesai = SettingPonpes::where('status', 'selesai')->count();
        $statusTerjadwal = SettingPonpes::where('status', 'terjadwal')->count();

        $jenisVtren = SettingPonpes::where('jenis_layanan', 'vtren')->count();
        $jenisReguler = SettingPonpes::where('jenis_layanan', 'reguler')->count();
        $jenisVtrenReg = SettingPonpes::where('jenis_layanan', 'vtrenreg')->count();

        $bulanIni = SettingPonpes::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $avgDurasi = SettingPonpes::where('status', 'selesai')
            ->whereNotNull('durasi_hari')
            ->avg('durasi_hari');

        return [
            'total' => $totalData,
            'pending' => $statusPending,
            'proses' => $statusProses,
            'selesai' => $statusSelesai,
            'terjadwal' => $statusTerjadwal,
            'vtren' => $jenisVtren,
            'reguler' => $jenisReguler,
            'vtrenreg' => $jenisVtrenReg,
            'bulan_ini' => $bulanIni,
            'avg_durasi' => round($avgDurasi, 1)
        ];
    }

    public function getPonpesData(Request $request)
    {
        $namaPonpes = $request->input('nama_ponpes');
        $jenisLayanan = $request->input('jenis_layanan');

        // Determine which Ponpes table to query based on jenis layanan
        $tipePonpes = '';
        switch ($jenisLayanan) {
            case 'vtren':
                $tipePonpes = 'vtren';
                break;
            case 'reguler':
                $tipePonpes = 'reguler';
                break;
            case 'vtrenreg':
                // For vtrenreg, check both vtren and reguler
                $ponpes = Ponpes::where('nama_ponpes', $namaPonpes)
                    ->whereIn('tipe', ['vtren', 'reguler'])
                    ->first();
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jenis layanan tidak valid'
                ]);
        }

        if ($jenisLayanan !== 'vtrenreg') {
            $ponpes = Ponpes::where('nama_ponpes', $namaPonpes)
                ->where('tipe', $tipePonpes)
                ->first();
        }

        if (isset($ponpes) && $ponpes) {
            return response()->json([
                'status' => 'success',
                'nama_wilayah' => $ponpes->nama_wilayah
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ponpes not found untuk jenis layanan tersebut'
        ]);
    }
}

<?php

namespace App\Http\Controllers\mclient\reguler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\mclient\SettingAlat;
use App\Models\user\Upt;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\Pic;

class SettingAlatController extends Controller
{
    private function getJenisLayanan()
    {
        return [
            'vpas' => 'VPAS',
            'reguler' => 'Reguler',
            'vpasreg' => 'VPAS + Reguler'
        ];
    }

    public function ListDataMclientSettingAlat(Request $request)
    {
        $query = SettingAlat::query();

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('jenis_layanan', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('keterangan', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_1', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_2', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_terlapor', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_selesai', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        $picList = Pic::orderBy('nama_pic')->get();

        // Get UPT list based on jenis layanan
        $uptListVpas = Upt::select('namaupt', 'kanwil')
            ->where('tipe', 'vpas')
            ->orderBy('namaupt')
            ->get();

        $uptListReguler = Upt::select('namaupt', 'kanwil')
            ->where('tipe', 'reguler')
            ->orderBy('namaupt')
            ->get();

        // Combine both lists for vpasreg
        $uptListAll = $uptListVpas->merge($uptListReguler)->unique('namaupt')->sortBy('namaupt');

        $jenisLayananOptions = $this->getJenisLayanan();

        return view('mclient.upt.indexSettingAlat', compact(
            'data',
            'picList',
            'uptListVpas',
            'uptListReguler',
            'uptListAll',
            'jenisLayananOptions'
        ));
    }

    public function MclientSettingAlatStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'jenis_layanan' => 'required|string|in:vpas,reguler,vpasreg',
                'keterangan' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:jadwal',
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
                'jadwal.date' => 'Format jadwal harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari jadwal.',
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

            if ($request->jadwal && $request->tanggal_selesai) {
                $jadwal = Carbon::parse($request->jadwal);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalSelesai->diffInDays($jadwal);
            }

            SettingAlat::create($data);

            return redirect()->back()->with('success', 'Data kunjungan monitoring client berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientSettingAlatUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'jenis_layanan' => 'required|string|in:vpas,reguler,vpasreg',
                'keterangan' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:jadwal',
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
                'jadwal.date' => 'Format jadwal harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari jadwal.',
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
            $validatedData = [];
            $invalidFields = array_keys($validator->errors()->messages());

            foreach ($request->all() as $key => $value) {
                if (!in_array($key, $invalidFields)) {
                    $validatedData[$key] = $value;
                }
            }

            try {
                if (!empty($validatedData)) {
                    $data = SettingAlat::findOrFail($id);

                    if (isset($validatedData['tanggal_terlapor']) && isset($validatedData['tanggal_selesai'])) {
                        $jadwal = Carbon::parse($validatedData['tanggal_terlapor']);
                        $tanggalSelesai = Carbon::parse($validatedData['tanggal_selesai']);
                        $validatedData['durasi_hari'] = $tanggalSelesai->diffInDays($jadwal);
                    }

                    $data->update($validatedData);
                }
            } catch (\Exception $e) {
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('partial_success', 'Data valid telah disimpan. Silakan perbaiki field yang bermasalah.');
        }

        try {
            $data = SettingAlat::findOrFail($id);
            $updateData = $request->all();

            if ($request->jadwal && $request->tanggal_selesai) {
                $jadwal = Carbon::parse($request->jadwal);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalSelesai->diffInDays($jadwal);
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data kunjungan monitoring client berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientSettingAlatDestroy($id)
    {
        try {
            $data = SettingAlat::findOrFail($id);
            $namaUpt = $data->nama_upt;
            $jenisLayanan = $data->formatted_jenis_layanan;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data kunjungan monitoring client '{$jenisLayanan}' di UPT '{$namaUpt}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportCsv()
    {
        $data = SettingAlat::orderBy('created_at', 'desc')->get();

        $filename = 'monitoring_client_kunjungan_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Nama UPT',
                'Jenis Layanan',
                'Keterangan',
                'tanggal_terlapor',
                'Tanggal Selesai',
                'Durasi (Hari)',
                'Status',
                'PIC 1',
                'PIC 2',
                'Dibuat Pada',
                'Diupdate Pada'
            ]);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama_upt,
                    $row->formatted_jenis_layanan,
                    $row->keterangan,
                    $row->jadwal ? $row->jadwal->format('Y-m-d') : '',
                    $row->tanggal_selesai ? $row->tanggal_selesai->format('Y-m-d') : '',
                    $row->durasi_hari,
                    $row->status,
                    $row->pic_1,
                    $row->pic_2,
                    $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
                    $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getDashboardStats()
    {
        $totalData = SettingAlat::count();
        $statusPending = SettingAlat::where('status', 'pending')->count();
        $statusProses = SettingAlat::where('status', 'proses')->count();
        $statusSelesai = SettingAlat::where('status', 'selesai')->count();
        $statusTerjadwal = SettingAlat::where('status', 'terjadwal')->count();

        $jenisVpas = SettingAlat::where('jenis_layanan', 'vpas')->count();
        $jenisReguler = SettingAlat::where('jenis_layanan', 'reguler')->count();
        $jenisVpasReg = SettingAlat::where('jenis_layanan', 'vpasreg')->count();

        $bulanIni = SettingAlat::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $avgDurasi = SettingAlat::where('status', 'selesai')
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

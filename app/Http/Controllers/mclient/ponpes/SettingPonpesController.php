<?php

namespace App\Http\Controllers\mclient\ponpes;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\SettingPonpes;
use App\Models\user\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\Pic;

class SettingPonpesController extends Controller
{
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

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
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

            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
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
                    $data = SettingPonpes::findOrFail($id);

                    if (isset($validatedData['tanggal_terlapor']) && isset($validatedData['tanggal_selesai'])) {
                        $tanggalTerlapor = Carbon::parse($validatedData['tanggal_terlapor']);
                        $tanggalSelesai = Carbon::parse($validatedData['tanggal_selesai']);
                        $validatedData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
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
            $data = SettingPonpes::findOrFail($id);
            $updateData = $request->all();

            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
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

    public function exportCsv()
    {
        $data = SettingPonpes::orderBy('created_at', 'desc')->get();

        $filename = 'monitoring_client_ponpes_setting_' . date('Y-m-d_H-i-s') . '.csv';

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
                'Nama Ponpes',
                'Jenis Layanan',
                'Keterangan',
                'Tanggal Terlapor',
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
                    $row->nama_ponpes,
                    $row->formatted_jenis_layanan,
                    $row->keterangan,
                    $row->tanggal_terlapor ? $row->tanggal_terlapor->format('Y-m-d') : '',
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
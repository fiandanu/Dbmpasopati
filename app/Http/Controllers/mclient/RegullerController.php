<?php

namespace App\Http\Controllers\mclient;

use App\Http\Controllers\Controller;
use App\Models\mclient\Reguller;
use App\Models\Upt; // Import model Upt yang sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegullerController extends Controller
{
    private function getJenisKendala()
    {
        return [
            'Tidak ada sinyal',
            'Suara tidak jelas',
            'Aplikasi error',
            'Layar rusak',
            'Internet lambat',
            'Tidak bisa login',
            'Kamera bermasalah',
            'Data tidak sinkron',
            'Server down',
            'Update gagal',
            'Mikrofon rusak',
            'VPN terputus',
            'Memory penuh',
            'Android tidak support',
            'Jaringan bermasalah',
            'Aplikasi hang',
            'Video tidak jalan',
            'Koneksi timeout',
            'Database error',
            'Firewall block',
            'Maintenance rutin',
            'Aplikasi lambat',
            'SSL expired',
            'Recording error',
            'Notifikasi tidak masuk'
        ];
    }

    // Update method ListDataMclientReguller untuk mengirim data jenis kendala dan UPT
    public function ListDataMclientReguller(Request $request)
    {
        $query = Reguller::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('jenis_kendala', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('detail_kendala', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_1', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_2', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_terlapor', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_selesai', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Urutkan berdasarkan data terbaru
        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim juga data jenis kendala dan UPT ke view
        $jenisKendala = $this->getJenisKendala();
        // Filter UPT hanya yang memiliki tipe 'reguler'
        $uptList = Upt::select('namaupt', 'kanwil')
                    ->where('tipe', 'reguler')
                    ->orderBy('namaupt')
                    ->get();

        return view('mclient.reguller.indexReguller', compact('data', 'jenisKendala', 'uptList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function MclientRegullerStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'kanwil' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'kanwil.string' => 'Kanwil harus berupa teks.',
                'kanwil.max' => 'Kanwil tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala Reguller harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala Reguller harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, atau selesai.',
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

            // Hitung durasi otomatis jika tanggal terlapor dan selesai diisi
            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
            }

            Reguller::create($data);

            return redirect()->route('ListDataMclientReguller')->with('success', 'Data monitoring client Reguller berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function MclientRegullerUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'kanwil' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'kanwil.string' => 'Kanwil harus berupa teks.',
                'kanwil.max' => 'Kanwil tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala Reguller harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala Reguller harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, atau selesai.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            // Pisahkan data valid dan invalid
            $validatedData = [];
            $invalidFields = array_keys($validator->errors()->messages());

            // Ambil hanya field yang valid
            foreach ($request->all() as $key => $value) {
                if (!in_array($key, $invalidFields)) {
                    $validatedData[$key] = $value;
                }
            }

            // Update field yang valid ke database
            try {
                if (!empty($validatedData)) {
                    $data = Reguller::findOrFail($id);

                    // Hitung durasi otomatis jika tanggal terlapor dan selesai diisi
                    if (isset($validatedData['tanggal_terlapor']) && isset($validatedData['tanggal_selesai'])) {
                        $tanggalTerlapor = Carbon::parse($validatedData['tanggal_terlapor']);
                        $tanggalSelesai = Carbon::parse($validatedData['tanggal_selesai']);
                        $validatedData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
                    }

                    $data->update($validatedData);
                }
            } catch (\Exception $e) {
                // Jika ada error saat update, tetap tampilkan error validasi
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('partial_success', 'Data valid telah disimpan. Silakan perbaiki field yang bermasalah.');
        }

        // Jika semua validasi berhasil
        try {
            $data = Reguller::findOrFail($id);
            $updateData = $request->all();

            // Hitung durasi otomatis jika tanggal terlapor dan selesai diisi
            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
            }

            $data->update($updateData);

            return redirect()->route('ListDataMclientReguller')->with('success', 'Data monitoring client Reguller berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function MclientRegullerDestroy($id)
    {
        try {
            $data = Reguller::findOrFail($id);
            $namaUpt = $data->nama_upt;
            $data->delete();

            return redirect()->route('ListDataMclientReguller')
                ->with('success', "Data monitoring client Reguller di UPT '{$namaUpt}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->route('ListDataMclientReguller')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export data to CSV
     */
    public function exportCsv()
    {
        $data = Reguller::orderBy('created_at', 'desc')->get();

        $filename = 'monitoring_client_reguller_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'No',
                'Nama UPT',
                'Kanwil',
                'Kendala Reguller',
                'Detail Kendala',
                'Tanggal Terlapor',
                'Tanggal Selesai',
                'Durasi (Hari)',
                'Status',
                'PIC 1',
                'PIC 2',
                'Dibuat Pada',
                'Diupdate Pada'
            ]);

            // Data rows
            $no = 1;
            foreach ($data as $row) {
                fputcsv($file, [
                    $no++,
                    $row->nama_upt,
                    $row->kanwil,
                    $row->jenis_kendala,
                    $row->detail_kendala,
                    $row->tanggal_terlapor,
                    $row->tanggal_selesai,
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

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $totalData = Reguller::count();
        $statusPending = Reguller::where('status', 'pending')->count();
        $statusProses = Reguller::where('status', 'proses')->count();
        $statusSelesai = Reguller::where('status', 'selesai')->count();

        // Data bulan ini
        $bulanIni = Reguller::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Rata-rata durasi penyelesaian
        $avgDurasi = Reguller::where('status', 'selesai')
            ->whereNotNull('durasi_hari')
            ->avg('durasi_hari');

        return [
            'total' => $totalData,
            'pending' => $statusPending,
            'proses' => $statusProses,
            'selesai' => $statusSelesai,
            'bulan_ini' => $bulanIni,
            'avg_durasi' => round($avgDurasi, 1)
        ];
    }

    /**
     * Get UPT data by name for AJAX requests
     */
    public function getUptData(Request $request)
    {
        $namaUpt = $request->input('nama_upt');
        $upt = Upt::where('namaupt', $namaUpt)->first();
        
        if ($upt) {
            return response()->json([
                'status' => 'success',
                'kanwil' => $upt->kanwil
            ]);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'UPT not found'
        ]);
    }
}
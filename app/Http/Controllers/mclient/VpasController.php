<?php

namespace App\Http\Controllers\mclient;

use App\Http\Controllers\Controller;
use App\Models\mclient\Vpas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VpasController extends Controller
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


    // Update method ListDataMclientVpas untuk mengirim data jenis kendala
    public function ListDataMclientVpas(Request $request)
    {
        $query = Vpas::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('lokasi', 'LIKE', '%' . $searchTerm . '%')
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

        // Kirim juga data jenis kendala ke view
        $jenisKendala = $this->getJenisKendala();

        return view('mclient.vpas.indexVpas', compact('data', 'jenisKendala'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function MclientVpasStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'lokasi' => 'required|string|max:255',
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
                'lokasi.required' => 'Lokasi harus diisi.',
                'lokasi.string' => 'Lokasi harus berupa teks.',
                'lokasi.max' => 'Lokasi tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala VPAS harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala VPAS harus berupa teks.',
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

            Vpas::create($data);

            return redirect()->route('ListDataMclientVpas')->with('success', 'Data monitoring client VPAS berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function MclientVpasUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'lokasi' => 'required|string|max:255',
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
                'lokasi.required' => 'Lokasi harus diisi.',
                'lokasi.string' => 'Lokasi harus berupa teks.',
                'lokasi.max' => 'Lokasi tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala VPAS harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala VPAS harus berupa teks.',
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
                    $data = Vpas::findOrFail($id);

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
            $data = Vpas::findOrFail($id);
            $updateData = $request->all();

            // Hitung durasi otomatis jika tanggal terlapor dan selesai diisi
            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
            }

            $data->update($updateData);

            return redirect()->route('ListDataMclientVpas')->with('success', 'Data monitoring client VPAS berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function MclientVpasDestroy($id)
    {
        try {
            $data = Vpas::findOrFail($id);
            $lokasi = $data->lokasi; // Simpan nama lokasi untuk pesan
            $data->delete();

            return redirect()->route('ListDataMclientVpas')
                ->with('success', "Data monitoring client VPAS di lokasi '{$lokasi}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->route('ListDataMclientVpas')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export data to CSV
     */
    public function exportCsv()
    {
        $data = Vpas::orderBy('created_at', 'desc')->get();

        $filename = 'monitoring_client_vpas_' . date('Y-m-d_H-i-s') . '.csv';

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
                'Lokasi',
                'Kendala VPAS',
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
                    $row->lokasi,
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
        $totalData = Vpas::count();
        $statusPending = Vpas::where('status', 'pending')->count();
        $statusProses = Vpas::where('status', 'proses')->count();
        $statusSelesai = Vpas::where('status', 'selesai')->count();

        // Data bulan ini
        $bulanIni = Vpas::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Rata-rata durasi penyelesaian
        $avgDurasi = Vpas::where('status', 'selesai')
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
}

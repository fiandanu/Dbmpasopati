<?php

namespace App\Http\Controllers\mclient\ponpes;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\SettingPonpes;
use App\Models\user\pic\Pic;
use App\Models\user\ponpes\Ponpes;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingPonpesController extends Controller
{
    private function applyFilters($query, Request $request)
    {
        $filters = [
            // === Pencarian teks biasa ===
            'search_keterangan' => fn($q, $v) => $q->where('keterangan', 'LIKE', "%{$v}%"),
            'search_pic_1' => fn($q, $v) => $q->where('pic_1', 'LIKE', "%{$v}%"),
            'search_pic_2' => fn($q, $v) => $q->where('pic_2', 'LIKE', "%{$v}%"),

            // === Relasi ponpes.namaWilayah ===
            'search_nama_ponpes' => fn($q, $v) => $q->whereHas('ponpes.namaWilayah', fn($qq) => $qq->where('nama_ponpes', 'LIKE', "%{$v}%")),
            'search_nama_wilayah' => fn($q, $v) => $q->whereHas('ponpes.namaWilayah', fn($qq) => $qq->where('nama_wilayah', 'LIKE', "%{$v}%")),

            // === Status (khusus: include NULL / empty) ===
            'search_status' => function ($q, $v) {
                $v = strtolower($v);
                $q->where(function ($qq) use ($v) {
                    $qq->where('status', 'LIKE', "%{$v}%");

                    if (str_contains($v, 'belum') || str_contains($v, 'ditentukan')) {
                        $qq->orWhereNull('status')
                            ->orWhere('status', '');
                    }
                });
            },

            // === Rentang tanggal terlapor ===
            'search_tanggal_terlapor_dari' => fn($q, $v) => $q->whereDate('tanggal_terlapor', '>=', $v),
            'search_tanggal_terlapor_sampai' => fn($q, $v) => $q->whereDate('tanggal_terlapor', '<=', $v),

            // === Rentang tanggal selesai ===
            'search_tanggal_selesai_dari' => fn($q, $v) => $q->whereDate('tanggal_selesai', '>=', $v),
            'search_tanggal_selesai_sampai' => fn($q, $v) => $q->whereDate('tanggal_selesai', '<=', $v),
        ];

        foreach ($filters as $key => $callback) {
            // gunakan `filled()` agar nilai kosong string tidak dianggap valid
            if ($request->filled($key)) {
                $callback($query, $request->input($key));
            }
        }

        return $query;
    }

    private function getJenisLayanan()
    {
        return [
            'vtren' => 'VTREN',
            'reguler' => 'Reguler',
            'vtrenreg' => 'VTREN + Reguler',
        ];
    }

    public function ListDataMclientPonpesSetting(Request $request)
    {
        // $query = SettingPonpes::with('ponpes.namaWilayah');
        $query = SettingPonpes::with([
            'ponpes.namaWilayah' => function ($query) {
                $query->select('id', 'nama_wilayah'); // Hanya ambil field needed
            },
            'ponpes' => function ($query) {
                $query->select('id', 'nama_ponpes', 'nama_wilayah_id');
            },
        ]);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (! in_array($perPage, [10, 15, 20, 'all'])) {
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
        $ponpesListVtren = Ponpes::with('namaWilayah')
            ->where('tipe', 'vtren')
            ->orderBy('nama_ponpes')
            ->get();

        $ponpesListReguler = Ponpes::with('namaWilayah')
            ->where('tipe', 'reguler')
            ->orderBy('nama_ponpes')
            ->get();

        // Combine both lists for vtrenreg
        $ponpesListAll = $ponpesListVtren->merge($ponpesListReguler)->unique('id')->sortBy('nama_ponpes');

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
                'nama_ponpes' => 'required|string',
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

            $data['data_ponpes_id'] = $request->nama_ponpes;
            unset($data['nama_ponpes']);

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
                'nama_ponpes' => 'required|string|exists:data_ponpes,id',
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
                'nama_ponpes.exists' => 'Nama Ponpes tidak ditemukan.',
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
            $data = SettingPonpes::findOrFail($id);
            $updateData = $request->all();

            // Update ID ponpes
            $updateData['data_ponpes_id'] = $request->nama_ponpes;
            unset($updateData['nama_ponpes']);

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
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.mclient.ponpes.indexSetting', $pdfData)
            ->setPaper('a4', 'landscape');
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
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Jenis Layanan', 'Keterangan', 'Tanggal Terlapor', 'Tanggal Selesai', 'Durasi (Hari)', 'Status', 'Pic 1', 'Pic 2', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->ponpes->nama_ponpes,
                $row->ponpes->namaWilayah->nama_wilayah,
                $row->formatted_jenis_layanan,
                $row->keterangan,
                $row->tanggal_terlapor ? $row->tanggal_terlapor->format('Y-m-d') : '',
                $row->tanggal_selesai ? $row->tanggal_selesai->format('Y-m-d') : '',
                $row->durasi_hari,
                $row->status,
                $row->pic_1,
                $row->pic_2,
                $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
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
}

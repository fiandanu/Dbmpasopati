<?php

namespace App\Http\Controllers\mclient\ponpes;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\Pengiriman;
use App\Models\user\ponpes\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\pic\Pic;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanController extends Controller
{

    private function getJenisLayanan()
    {
        return [
            'vtren' => 'VTREN',
            'reguler' => 'Reguler',
            'vtrenreg' => 'VTREN + Reguler'
        ];
    }

    public function ListDataMclientPonpesPengiriman(Request $request)
    {
        $query = Pengiriman::with('ponpes.namaWilayah');

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

        return view('mclient.ponpes.indexPengiriman', compact(
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
            $query->whereHas('ponpes.namaWilayah', function ($q) use ($request) {
                $q->where('nama_ponpes', 'LIKE', '%' . $request->search_nama_ponpes . '%');
            });
        }
        if ($request->has('search_nama_wilayah') && !empty($request->search_nama_wilayah)) {
            $query->whereHas('ponpes.namaWilayah', function ($q) use ($request) {
                $q->where('nama_wilayah', 'LIKE', '%' . $request->search_nama_wilayah . '%');
            });
        }
        if ($request->has('search_jenis_layanan') && !empty($request->search_jenis_layanan)) {
            $query->where('jenis_layanan', 'LIKE', '%' . $request->search_jenis_layanan . '%');
        }
        if ($request->has('search_keterangan') && !empty($request->search_keterangan)) {
            $query->where('keterangan', 'LIKE', '%' . $request->search_keterangan . '%');
        }
        if ($request->has('search_status') && !empty($request->search_status)) {
            $searchStatus = strtolower($request->search_status);

            $query->where(function ($q) use ($searchStatus) {
                $q->where('status', 'LIKE', '%' . $searchStatus . '%');

                // Jika mencari "belum" atau "ditentukan", include yang NULL/empty
                if (str_contains($searchStatus, 'belum') || str_contains($searchStatus, 'ditentukan')) {
                    $q->orWhereNull('status')
                        ->orWhere('status', '');
                }
            });
        }
        if ($request->has('search_pic_1') && !empty($request->search_pic_1)) {
            $query->where('pic_1', 'LIKE', '%' . $request->search_pic_1 . '%');
        }
        if ($request->has('search_pic_2') && !empty($request->search_pic_2)) {
            $query->where('pic_2', 'LIKE', '%' . $request->search_pic_2 . '%');
        }

        // Date range filtering for tanggal_pengiriman
        if ($request->has('search_tanggal_pengiriman_dari') && !empty($request->search_tanggal_pengiriman_dari)) {
            $query->whereDate('tanggal_pengiriman', '>=', $request->search_tanggal_pengiriman_dari);
        }
        if ($request->has('search_tanggal_pengiriman_sampai') && !empty($request->search_tanggal_pengiriman_sampai)) {
            $query->whereDate('tanggal_pengiriman', '<=', $request->search_tanggal_pengiriman_sampai);
        }

        // Date range filtering for tanggal_sampai
        if ($request->has('search_tanggal_sampai_dari') && !empty($request->search_tanggal_sampai_dari)) {
            $query->whereDate('tanggal_sampai', '>=', $request->search_tanggal_sampai_dari);
        }
        if ($request->has('search_tanggal_sampai_sampai') && !empty($request->search_tanggal_sampai_sampai)) {
            $query->whereDate('tanggal_sampai', '<=', $request->search_tanggal_sampai_sampai);
        }

        return $query;
    }

    public function MclientPonpesPengirimanStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string',
                'jenis_layanan' => 'required|string|in:vtren,reguler,vtrenreg',
                'keterangan' => 'nullable|string',
                'tanggal_pengiriman' => 'nullable|date',
                'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_pengiriman',
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

            $data['data_ponpes_id'] = $request->nama_ponpes;
            unset($data['nama_ponpes']);

            // Hitung durasi HANYA jika tanggal_sampai ada
            if ($request->tanggal_sampai && $request->tanggal_pengiriman) {
                $tanggalPengiriman = Carbon::parse($request->tanggal_pengiriman);
                $tanggalSampai = Carbon::parse($request->tanggal_sampai);
                $data['durasi_hari'] = $tanggalPengiriman->diffInDays($tanggalSampai);
            } else {
                // Jika belum ada tanggal_sampai, set null (akan dihitung dinamis)
                $data['durasi_hari'] = null;
            }

            Pengiriman::create($data);

            return redirect()->back()->with('success', 'Data pengiriman monitoring client Ponpes berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientPonpesPengirimanUpdatePonpes(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|exists:data_ponpes,id',
                'jenis_layanan' => 'required|string|in:vtren,reguler,vtrenreg',
                'keterangan' => 'nullable|string',
                'tanggal_pengiriman' => 'nullable|date',
                'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_pengiriman',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_ponpes.exists' => 'Nama Ponpes harus berupa teks.',
                'jenis_layanan.required' => 'Jenis layanan harus dipilih.',
                'jenis_layanan.in' => 'Jenis layanan harus salah satu dari: VTREN, Reguler, atau VTREN + Reguler.',
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

            $updateData['data_ponpes_id'] = $request->nama_ponpes;
            unset($updateData['nama_ponpes']);

            // Hitung dan simpan durasi HANYA jika tanggal_sampai baru ditentukan
            if ($request->tanggal_sampai && $request->tanggal_pengiriman) {
                $tanggalPengiriman = Carbon::parse($request->tanggal_pengiriman);
                $tanggalSampai = Carbon::parse($request->tanggal_sampai);
                $updateData['durasi_hari'] = $tanggalPengiriman->diffInDays($tanggalSampai);
            } elseif ($request->has('tanggal_sampai') && empty($request->tanggal_sampai)) {
                // Jika tanggal_sampai dihapus, set durasi ke null
                $updateData['durasi_hari'] = null;
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data pengiriman monitoring client Ponpes berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientPonpesPengirimanDestroyPonpes($id)
    {
        try {
            $data = Pengiriman::findOrFail($id);
            $namaPonpes = $data->nama_ponpes;
            $jenisLayanan = $data->formatted_jenis_layanan;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data pengiriman monitoring client '{$jenisLayanan}' di Ponpes '{$namaPonpes}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }


    // EXPORT DATA PDF & CSV GLOBAL
    public function exportListPdf(Request $request)
    {
        $query = Pengiriman::query();
        $query = $this->applyFilters($query, $request);

        if (
            $request->filled('search_tanggal_pengiriman_dari') || $request->filled('search_tanggal_pengiriman_sampai') ||
            $request->filled('search_tanggal_sampai_dari') || $request->filled('search_tanggal_sampai_sampai')
        ) {
            $query = $query->orderBy('tanggal_pengiriman', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $pdfData = [
            'title' => 'List Data Pengiriman Ponpes',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.mclient.ponpes.indexPengiriman', $pdfData);
        $filename = 'list_pengiriman_ponpes_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request)
    {
        $query = Pengiriman::query();
        $query = $this->applyFilters($query, $request);

        if (
            $request->filled('search_tanggal_pengiriman_dari') || $request->filled('search_tanggal_pengiriman_sampai') ||
            $request->filled('search_tanggal_sampai_dari') || $request->filled('search_tanggal_sampai_sampai')
        ) {
            $query = $query->orderBy('tanggal_pengiriman', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'List_Pengiriman_Ponpes_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Jenis Layanan', 'Keterangan', 'Tanggal Pengiriman', 'Tanggal Sampai', 'Durasi (Hari)', 'Status', 'Pic 1', 'Pic 2', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->nama_ponpes,
                $row->formatted_jenis_layanan,
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
}

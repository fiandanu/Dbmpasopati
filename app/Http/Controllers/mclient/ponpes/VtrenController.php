<?php

namespace App\Http\Controllers\mclient\ponpes;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\Vtren;
use App\Models\user\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\Kendala;
use App\Models\user\Pic;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;



class VtrenController extends Controller
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

    public function ListDataMclientVtren(Request $request)
    {
        $query = Vtren::query();

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 20;
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

        $jenisKendala = Kendala::orderBy('jenis_kendala')->get();
        $picList = Pic::orderBy('nama_pic')->get();

        $ponpesList = Ponpes::select('nama_ponpes', 'nama_wilayah')
            ->where('tipe', 'vtren')
            ->orderBy('nama_ponpes')
            ->get();

        return view('mclient.ponpes.indexVtren', compact('data', 'jenisKendala', 'picList', 'ponpesList'));
    }

    private function applyFilters($query, Request $request)
    {
        // Global search
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('jenis_kendala', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('detail_kendala', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_1', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_2', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_terlapor', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_selesai', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Column-specific searches
        if ($request->has('search_nama_ponpes') && !empty($request->search_nama_ponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_nama_ponpes . '%');
        }
        if ($request->has('search_nama_wilayah') && !empty($request->search_nama_wilayah)) {
            $query->where('nama_wilayah', 'LIKE', '%' . $request->search_nama_wilayah . '%');
        }
        if ($request->has('search_jenis_kendala') && !empty($request->search_jenis_kendala)) {
            $query->where('jenis_kendala', 'LIKE', '%' . $request->search_jenis_kendala . '%');
        }
        if ($request->has('search_detail_kendala') && !empty($request->search_detail_kendala)) {
            $query->where('detail_kendala', 'LIKE', '%' . $request->search_detail_kendala . '%');
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

        // Date range filtering
        if ($request->has('search_tanggal_terlapor_dari') && !empty($request->search_tanggal_terlapor_dari)) {
            $query->whereDate('tanggal_terlapor', '>=', $request->search_tanggal_terlapor_dari);
        }
        if ($request->has('search_tanggal_terlapor_sampai') && !empty($request->search_tanggal_terlapor_sampai)) {
            $query->whereDate('tanggal_terlapor', '<=', $request->search_tanggal_terlapor_sampai);
        }
        if ($request->has('search_tanggal_selesai_dari') && !empty($request->search_tanggal_selesai_dari)) {
            $query->whereDate('tanggal_selesai', '>=', $request->search_tanggal_selesai_dari);
        }
        if ($request->has('search_tanggal_selesai_sampai') && !empty($request->search_tanggal_selesai_sampai)) {
            $query->whereDate('tanggal_selesai', '<=', $request->search_tanggal_selesai_sampai);
        }

        return $query;
    }

    public function MclientVtrenStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|max:255',
                'nama_wilayah' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string|max:100',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                // pesan validasi tetap sama
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
            if ($request->tanggal_selesai) {
                $createdAt = Carbon::now();
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $createdAt->diffInDays($tanggalSelesai);
            } else {
                // Jika belum ada tanggal_selesai, set null (akan dihitung dinamis oleh accessor)
                $data['durasi_hari'] = null;
            }

            Vtren::create($data);

            return redirect()->back()->with('success', 'Data monitoring client Vtren berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientVtrenUpdate(Request $request, $id)
    {
        // ... validasi tetap sama ...

        try {
            $data = Vtren::findOrFail($id);
            $updateData = $request->all();

            // Hitung dan simpan durasi HANYA jika tanggal_selesai baru ditentukan
            if ($request->tanggal_selesai) {
                $createdAt = Carbon::parse($data->created_at);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $createdAt->diffInDays($tanggalSelesai);
            } elseif ($request->has('tanggal_selesai') && empty($request->tanggal_selesai)) {
                // Jika tanggal_selesai dihapus, set durasi ke null
                $updateData['durasi_hari'] = null;
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data monitoring client Vtren berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientVtrenDestroy($id)
    {
        try {
            $data = Vtren::findOrFail($id);
            $nama_ponpes = $data->nama_ponpes;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data monitoring client Vtren di Ponpes '{$nama_ponpes}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportListPdf(Request $request)
    {
        $query = Vtren::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_terlapor_dari') || $request->filled('search_tanggal_terlapor_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_terlapor', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $pdfData = [
            'title' => 'List Data Monitoring Client Vtren',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.mclient.ponpes.indexVtren', $pdfData);
        $filename = 'list_monitoring_client_vtren_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Vtren::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_terlapor_dari') || $request->filled('search_tanggal_terlapor_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_terlapor', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'list_monitoring_client_vtren_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Jenis Kendala', 'Detail Kendala', 'Tanggal Terlapor', 'Tanggal Selesai', 'Durasi (Hari)', 'Status', 'PIC 1', 'PIC 2', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->nama_ponpes,
                $row->nama_wilayah,
                $row->jenis_kendala,
                $row->detail_kendala,
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

    public function exportCsv()
    {
        $data = Vtren::orderBy('created_at', 'desc')->get();

        $filename = 'monitoring_client_vtren_' . date('Y-m-d_H-i-s') . '.csv';

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
                'Nama Wilayah',
                'Kendala Vtren',
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

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama_ponpes,
                    $row->nama_wilayah,
                    $row->jenis_kendala,
                    $row->detail_kendala,
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
        $totalData = Vtren::count();
        $statusPending = Vtren::where('status', 'pending')->count();
        $statusProses = Vtren::where('status', 'proses')->count();
        $statusSelesai = Vtren::where('status', 'selesai')->count();
        $statusTerjadwal = Vtren::where('status', 'terjadwal')->count();

        $bulanIni = Vtren::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $avgDurasi = Vtren::where('status', 'selesai')
            ->whereNotNull('durasi_hari')
            ->avg('durasi_hari');

        return [
            'total' => $totalData,
            'pending' => $statusPending,
            'proses' => $statusProses,
            'selesai' => $statusSelesai,
            'terjadwal' => $statusTerjadwal,
            'bulan_ini' => $bulanIni,
            'avg_durasi' => round($avgDurasi, 1)
        ];
    }

    public function getPonpesData(Request $request)
    {
        $nama_ponpes = $request->input('nama_ponpes');
        $ponpes = Ponpes::where('nama_ponpes', $nama_ponpes)->first();

        if ($ponpes) {
            return response()->json([
                'status' => 'success',
                'nama_wilayah' => $ponpes->nama_wilayah
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ponpes not found'
        ]);
    }
}

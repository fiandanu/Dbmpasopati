<?php

namespace App\Http\Controllers\mclient\ponpes;

use App\Http\Controllers\Controller;
use App\Models\mclient\ponpes\Vtren;
use App\Models\user\kendala\Kendala;
use App\Models\user\pic\Pic;
use App\Models\user\ponpes\Ponpes;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VtrenController extends Controller
{
    public function ListDataMclientVtren(Request $request)
    {
        $query = Vtren::with(['ponpes.namaWilayah']);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (! in_array($perPage, [10, 15, 20, 'all'])) {
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

        $ponpesList = Ponpes::with('namaWilayah')
            ->where('tipe', 'vtren')
            ->orderBy('nama_ponpes')
            ->get();

        return view('mclient.ponpes.indexVtren', compact('data', 'jenisKendala', 'picList', 'ponpesList'));
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific searches
        if ($request->has('search_nama_ponpes') && ! empty($request->search_nama_ponpes)) {
            $query->whereHas('ponpes.namaWilayah', function ($q) use ($request) {
                $q->where('nama_ponpes', 'LIKE', '%'.$request->search_nama_ponpes.'%');
            });
        }

        if ($request->has('search_nama_wilayah') && ! empty($request->search_nama_wilayah)) {
            $query->whereHas('ponpes.namaWilayah', function ($q) use ($request) {
                $q->where('nama_wilayah', 'LIKE', '%'.$request->search_nama_wilayah.'%');
            });
        }

        if ($request->has('search_jenis_kendala') && ! empty($request->search_jenis_kendala)) {
            $searchJenisKendala = strtolower($request->search_jenis_kendala);
            $query->where(function ($q) use ($searchJenisKendala) {
                $q->where('jenis_kendala', 'LIKE', '%'.$searchJenisKendala.'%');
                if (str_contains($searchJenisKendala, 'belum') || str_contains($searchJenisKendala, 'ditentukan')) {
                    $q->orWhereNull('jenis_kendala')
                        ->orWhere('jenis_kendala', '');
                }
            });
        }

        if ($request->has('search_detail_kendala') && ! empty($request->search_detail_kendala)) {
            $query->where('detail_kendala', 'LIKE', '%'.$request->search_detail_kendala.'%');
        }

        if ($request->has('search_status') && ! empty($request->search_status)) {
            $searchStatus = strtolower($request->search_status);

            $query->where(function ($q) use ($searchStatus) {
                $q->where('status', 'LIKE', '%'.$searchStatus.'%');

                // Jika mencari "belum" atau "ditentukan", include yang NULL/empty
                if (str_contains($searchStatus, 'belum') || str_contains($searchStatus, 'ditentukan')) {
                    $q->orWhereNull('status')
                        ->orWhere('status', '');
                }
            });
        }

        if ($request->has('search_pic_1') && ! empty($request->search_pic_1)) {
            $query->where('pic_1', 'LIKE', '%'.$request->search_pic_1.'%');
        }
        if ($request->has('search_pic_2') && ! empty($request->search_pic_2)) {
            $query->where('pic_2', 'LIKE', '%'.$request->search_pic_2.'%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_terlapor_dari') && ! empty($request->search_tanggal_terlapor_dari)) {
            $query->whereDate('tanggal_terlapor', '>=', $request->search_tanggal_terlapor_dari);
        }
        if ($request->has('search_tanggal_terlapor_sampai') && ! empty($request->search_tanggal_terlapor_sampai)) {
            $query->whereDate('tanggal_terlapor', '<=', $request->search_tanggal_terlapor_sampai);
        }
        if ($request->has('search_tanggal_selesai_dari') && ! empty($request->search_tanggal_selesai_dari)) {
            $query->whereDate('tanggal_selesai', '>=', $request->search_tanggal_selesai_dari);
        }
        if ($request->has('search_tanggal_selesai_sampai') && ! empty($request->search_tanggal_selesai_sampai)) {
            $query->whereDate('tanggal_selesai', '<=', $request->search_tanggal_selesai_sampai);
        }

        return $query;
    }

    public function MclientVtrenStore(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make(
            $request->all(),
            [
                'data_ponpes_id' => 'required|exists:data_ponpes,id',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string|max:100',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'data_ponpes_id.required' => 'Nama PONPES wajib dipilih',
                'data_ponpes_id.exists' => 'PONPES yang dipilih tidak valid',
                // pesan validasi lainnya...
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
                // Gunakan tanggal_terlapor sebagai acuan
                if ($request->tanggal_terlapor) {
                    $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                } else {
                    // Jika tanggal_terlapor kosong, gunakan waktu sekarang
                    $tanggalTerlapor = Carbon::now();
                    $data['tanggal_terlapor'] = $tanggalTerlapor;
                }

                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalTerlapor->diffInDays($tanggalSelesai);
            } else {
                $data['durasi_hari'] = null;
            }

            Vtren::create($data);

            return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: '.$e->getMessage());
        }
    }

    public function MclientVtrenUpdate(Request $request, $id)
    {
        try {
            $data = Vtren::findOrFail($id);
            $updateData = $request->all();

            // Update data_ponpes_id jika nama_ponpes berubah
            if ($request->nama_ponpes) {
                $ponpes = Ponpes::where('nama_ponpes', $request->nama_ponpes)->first();
                if ($ponpes) {
                    $updateData['data_ponpes_id'] = $ponpes->id;
                }
            }

            // Hitung durasi jika tanggal_selesai ada
            if ($request->tanggal_selesai) {
                // Gunakan tanggal_terlapor dari data yang sudah ada atau yang baru
                if ($request->tanggal_terlapor) {
                    $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                } else {
                    // Jika tidak ada input tanggal_terlapor baru, pakai yang lama
                    $tanggalTerlapor = Carbon::parse($data->tanggal_terlapor ?? $data->created_at);
                }

                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalTerlapor->diffInDays($tanggalSelesai);
            } elseif ($request->has('tanggal_selesai') && empty($request->tanggal_selesai)) {
                // Jika tanggal_selesai dihapus, reset durasi
                $updateData['durasi_hari'] = null;
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: '.$e->getMessage());
        }
    }

    public function MclientVtrenDestroy($id)
    {
        try {
            $data = Vtren::findOrFail($id);
            $nama_ponpes = $data->ponpes->nama_ponpes ?? 'Unknown';
            $data->delete();

            return redirect()->back()
                ->with('success', "Data monitoring client Vtren di Ponpes '{$nama_ponpes}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
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
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.mclient.ponpes.indexVtren', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_monitoring_client_vtren_'.Carbon::now()->translatedFormat('d_M_Y').'.pdf';

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

        $filename = 'list_monitoring_client_vtren_'.Carbon::now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
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

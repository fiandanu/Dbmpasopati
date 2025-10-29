<?php

namespace App\Http\Controllers\mclient\catatankartu;

use App\Http\Controllers\Controller;
use App\Models\mclient\catatankartu\Vtren;
use App\Models\user\pic\Pic;
use App\Models\user\ponpes\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class VtrenController extends Controller
{
    public function ListDataMclientCatatanVtren(Request $request)
    {
        $query = Vtren::with(['ponpes.namaWilayah']);

        // Apply filter
        $query = $this->applyFilters($query, $request);

        // Calculate totals from ALL filtered data (before pagination)
        $allFilteredData = $query->get();
        $totals = [
            'kartu_baru' => $allFilteredData->sum(function ($item) {
                return intval($item->spam_vtren_kartu_baru ?? 0);
            }),
            'kartu_bekas' => $allFilteredData->sum(function ($item) {
                return intval($item->spam_vtren_kartu_bekas ?? 0);
            }),
            'kartu_goip' => $allFilteredData->sum(function ($item) {
                return intval($item->spam_vtren_kartu_goip ?? 0);
            }),
            'kartu_belum_register' => $allFilteredData->sum(function ($item) {
                return intval($item->kartu_belum_teregister ?? 0);
            }),
            'whatsapp_terpakai' => $allFilteredData->sum(function ($item) {
                return intval($item->whatsapp_telah_terpakai ?? 0);
            }),
            'kartu_terpakai_perhari' => $allFilteredData->sum(function ($item) {
                return intval($item->jumlah_kartu_terpakai_perhari ?? 0);
            })
        ];

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Rebuild query for pagination (karena sudah di-get() sebelumnya)
        $query = Vtren::query();
        $query = $this->applyFilters($query, $request);

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
        $cardSupportingList = Pic::orderBy('nama_pic')->pluck('nama_pic');
        $ponpesList = Ponpes::with('namaWilayah')
            ->where('tipe', 'vtren')
            ->orderBy('nama_ponpes')
            ->get();

        return view('mclient.catatankartu.indexVtren', compact('data', 'picList', 'cardSupportingList', 'ponpesList', 'totals'));
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific searches
        if ($request->has('search_nama_ponpes') && !empty($request->search_nama_ponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_nama_ponpes . '%');
        }
        if ($request->has('search_kartu_baru') && !empty($request->search_kartu_baru)) {
            $query->where('spam_vtren_kartu_baru', 'LIKE', '%' . $request->search_kartu_baru . '%');
        }
        if ($request->has('search_kartu_bekas') && !empty($request->search_kartu_bekas)) {
            $query->where('spam_vtren_kartu_bekas', 'LIKE', '%' . $request->search_kartu_bekas . '%');
        }
        if ($request->has('search_kartu_goip') && !empty($request->search_kartu_goip)) {
            $query->where('spam_vtren_kartu_goip', 'LIKE', '%' . $request->search_kartu_goip . '%');
        }
        if ($request->has('search_kartu_belum_register') && !empty($request->search_kartu_belum_register)) {
            $query->where('kartu_belum_teregister', 'LIKE', '%' . $request->search_kartu_belum_register . '%');
        }
        if ($request->has('search_whatsapp_terpakai') && !empty($request->search_whatsapp_terpakai)) {
            $query->where('whatsapp_telah_terpakai', 'LIKE', '%' . $request->search_whatsapp_terpakai . '%');
        }
        if ($request->has('search_card_supporting') && !empty($request->search_card_supporting)) {
            $query->where('card_supporting', 'LIKE', '%' . $request->search_card_supporting . '%');
        }
        if ($request->has('search_pic') && !empty($request->search_pic)) {
            $query->where('pic', 'LIKE', '%' . $request->search_pic . '%');
        }
        if ($request->has('search_kartu_terpakai') && !empty($request->search_kartu_terpakai)) {
            $query->where('jumlah_kartu_terpakai_perhari', 'LIKE', '%' . $request->search_kartu_terpakai . '%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_dari') && !empty($request->search_tanggal_dari)) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->has('search_tanggal_sampai') && !empty($request->search_tanggal_sampai)) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        return $query;
    }

    public function MclientCatatanStoreVtren(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data_ponpes_id' => 'required|exists:data_ponpes,id',
                'spam_vtren_kartu_baru' => 'nullable|string',
                'spam_vtren_kartu_bekas' => 'nullable|string',
                'spam_vtren_kartu_goip' => 'nullable|string',
                'kartu_belum_teregister' => 'nullable|string',
                'whatsapp_telah_terpakai' => 'nullable|string',
                'card_supporting' => 'nullable|string|max:255',
                'pic' => 'nullable|string|max:255',
                'jumlah_kartu_terpakai_perhari' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif,proses,pending',
            ],
            [
                'data_ponpes_id.required' => 'Nama Ponpes harus diisi.',
                'spam_vtren_kartu_baru.string' => 'Spam Vtren kartu baru harus berupa teks.',
                'spam_vtren_kartu_bekas.string' => 'Spam Vtren kartu bekas harus berupa teks.',
                'spam_vtren_kartu_goip.string' => 'Spam Vtren kartu GOIP harus berupa teks.',
                'kartu_belum_teregister.string' => 'Kartu belum teregister harus berupa teks.',
                'whatsapp_telah_terpakai.string' => 'WhatsApp telah terpakai harus berupa teks.',
                'card_supporting.string' => 'Card supporting harus berupa teks.',
                'card_supporting.max' => 'Card supporting tidak boleh lebih dari 255 karakter.',
                'pic.string' => 'PIC harus berupa teks.',
                'pic.max' => 'PIC tidak boleh lebih dari 255 karakter.',
                'jumlah_kartu_terpakai_perhari.string' => 'Jumlah kartu terpakai per hari harus berupa teks.',
                'tanggal.date' => 'Format tanggal harus valid.',
                'status.in' => 'Status harus salah satu dari: aktif, nonaktif, proses, atau pending.',
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

            // Remove empty string fields to allow null values
            $stringFields = [
                'spam_vtren_kartu_baru',
                'spam_vtren_kartu_bekas',
                'spam_vtren_kartu_goip',
                'kartu_belum_teregister',
                'whatsapp_telah_terpakai',
                'jumlah_kartu_terpakai_perhari'
            ];

            foreach ($stringFields as $field) {
                if (isset($data[$field]) && trim($data[$field]) === '') {
                    unset($data[$field]);
                }
            }

            Vtren::create($data);

            return redirect()->back()->with('success', 'Data catatan kartu berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientCatatanUpdateVtren(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'data_ponpes_id' => 'required|exists:data_ponpes,id',
                'spam_vtren_kartu_baru' => 'nullable|string',
                'spam_vtren_kartu_bekas' => 'nullable|string',
                'spam_vtren_kartu_goip' => 'nullable|string',
                'kartu_belum_teregister' => 'nullable|string',
                'whatsapp_telah_terpakai' => 'nullable|string',
                'card_supporting' => 'nullable|string|max:255',
                'pic' => 'nullable|string|max:255',
                'jumlah_kartu_terpakai_perhari' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif,proses,pending',
            ],
            [
                'data_ponpes_id.required' => 'Nama Ponpes harus diisi.',
                'spam_vtren_kartu_baru.string' => 'Spam Vtren kartu baru harus berupa teks.',
                'spam_vtren_kartu_bekas.string' => 'Spam Vtren kartu bekas harus berupa teks.',
                'spam_vtren_kartu_goip.string' => 'Spam Vtren kartu GOIP harus berupa teks.',
                'kartu_belum_teregister.string' => 'Kartu belum teregister harus berupa teks.',
                'whatsapp_telah_terpakai.string' => 'WhatsApp telah terpakai harus berupa teks.',
                'card_supporting.string' => 'Card supporting harus berupa teks.',
                'card_supporting.max' => 'Card supporting tidak boleh lebih dari 255 karakter.',
                'pic.string' => 'PIC harus berupa teks.',
                'pic.max' => 'PIC tidak boleh lebih dari 255 karakter.',
                'jumlah_kartu_terpakai_perhari.string' => 'Jumlah kartu terpakai per hari harus berupa teks.',
                'tanggal.date' => 'Format tanggal harus valid.',
                'status.in' => 'Status harus salah satu dari: aktif, nonaktif, proses, atau pending.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan perbaiki data yang bermasalah.');
        }

        try {
            $data = Vtren::findOrFail($id);

            $ponpes = Ponpes::findOrFail($request->data_ponpes_id);

            // Only take allowed fields (whitelist approach)
            $updateData = $request->only([
                'data_ponpes_id',
                'spam_vtren_kartu_baru',
                'spam_vtren_kartu_bekas',
                'spam_vtren_kartu_goip',
                'kartu_belum_teregister',
                'whatsapp_telah_terpakai',
                'card_supporting',
                'pic',
                'jumlah_kartu_terpakai_perhari',
                'tanggal',
                'status'
            ]);

            $updateData['nama_ponpes'] = $ponpes->nama_ponpes;

            // Remove empty string fields to allow null values
            $stringFields = [
                'spam_vtren_kartu_baru',
                'spam_vtren_kartu_bekas',
                'spam_vtren_kartu_goip',
                'kartu_belum_teregister',
                'whatsapp_telah_terpakai',
                'jumlah_kartu_terpakai_perhari'
            ];

            foreach ($stringFields as $field) {
                if (isset($updateData[$field]) && trim($updateData[$field]) === '') {
                    unset($updateData[$field]);
                }
            }

            // Single update
            $data->update($updateData);

            return redirect()->back()->with('success', 'Data catatan kartu berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientCatatanDestroyVtren($id)
    {
        try {
            $data = Vtren::findOrFail($id);
            $nama_ponpes = $data->nama_ponpes;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data catatan kartu di Ponpes '{$nama_ponpes}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Vtren::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')
        ) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'list_catatan_kartu_vtren_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [[
            'No',
            'Nama Ponpes',
            'Spam Vtren Kartu Baru',
            'Spam Vtren Kartu Bekas',
            'Spam Vtren Kartu GOIP',
            'Kartu Belum Teregister',
            'WhatsApp Telah Terpakai',
            'Card Supporting',
            'PIC',
            'Jumlah Kartu Terpakai Per Hari',
            'Tanggal',
            'Dibuat Pada'
        ]];

        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->nama_ponpes,
                $row->spam_vtren_kartu_baru ?? '',
                $row->spam_vtren_kartu_bekas ?? '',
                $row->spam_vtren_kartu_goip ?? '',
                $row->kartu_belum_teregister ?? '',
                $row->whatsapp_telah_terpakai ?? '',
                $row->card_supporting ?? '',
                $row->pic ?? '',
                $row->jumlah_kartu_terpakai_perhari ?? '',
                $row->tanggal ? Carbon::parse($row->tanggal)->format('Y-m-d') : '',
                $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d H:i:s') : ''
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

    public function exportListPdf(Request $request)
    {
        $query = Vtren::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals for summary cards
        $totals = [
            'kartu_baru' => $data->sum(function ($item) {
                return intval($item->spam_vtren_kartu_baru ?? 0);
            }),
            'kartu_bekas' => $data->sum(function ($item) {
                return intval($item->spam_vtren_kartu_bekas ?? 0);
            }),
            'kartu_goip' => $data->sum(function ($item) {
                return intval($item->spam_vtren_kartu_goip ?? 0);
            }),
            'kartu_belum_register' => $data->sum(function ($item) {
                return intval($item->kartu_belum_teregister ?? 0);
            }),
            'whatsapp_terpakai' => $data->sum(function ($item) {
                return intval($item->whatsapp_telah_terpakai ?? 0);
            }),
            'kartu_terpakai_perhari' => $data->sum(function ($item) {
                return intval($item->jumlah_kartu_terpakai_perhari ?? 0);
            })
        ];

        $pdfData = [
            'title' => 'List Data Catatan Kartu Vtren',
            'data' => $data,
            'totals' => $totals,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
            'total_records' => $data->count()
        ];

        $pdf = Pdf::loadView('export.public.catatanKartuVtren.indexVtren', $pdfData);

        // Optional: Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');

        $filename = 'list_catatan_kartu_vtren_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
    public function getDashboardStats()
    {
        $totalData = Vtren::count();
        $statusAktif = Vtren::where('status', 'aktif')->count();
        $statusNonaktif = Vtren::where('status', 'nonaktif')->count();
        $statusProses = Vtren::where('status', 'proses')->count();
        $statusPending = Vtren::where('status', 'pending')->count();

        $bulanIni = Vtren::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalSpamTertangani = Vtren::get()->sum(function ($item) {
            return intval($item->spam_vtren_kartu_baru ?? '0') +
                intval($item->spam_vtren_kartu_bekas ?? '0') +
                intval($item->spam_vtren_kartu_goip ?? '0');
        });

        $totalKartuTerpakai = Vtren::get()->sum(function ($item) {
            return intval($item->jumlah_kartu_terpakai_perhari ?? '0');
        });

        return [
            'total' => $totalData,
            'aktif' => $statusAktif,
            'nonaktif' => $statusNonaktif,
            'proses' => $statusProses,
            'pending' => $statusPending,
            'bulan_ini' => $bulanIni,
            'total_spam_tertangani' => $totalSpamTertangani,
            'total_kartu_terpakai' => $totalKartuTerpakai
        ];
    }

    public function getUptData(Request $request)
    {
        $nama_ponpes = $request->input('nama_ponpes');
        $upt = Ponpes::where('nama_ponpes', $nama_ponpes)->first();

        if ($upt) {
            return response()->json([
                'status' => 'success',
                'nama_wilayah' => $upt->nama_wilayah
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ponpes not found'
        ]);
    }
}

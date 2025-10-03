<?php

namespace App\Http\Controllers\mclient\catatankartu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\mclient\catatankartu\Catatan;
use App\Models\user\Upt;
use App\Models\user\Pic;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class CatatanController extends Controller
{
    public function ListDataMclientCatatanVpas(Request $request)
    {
        $query = Catatan::query();

        // Apply filter
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

        $picList = Pic::orderBy('nama_pic')->get();
        $cardSupportingList = Pic::orderBy('nama_pic')->pluck('nama_pic');
        $uptList = Upt::select('namaupt', 'kanwil')
            ->where('tipe', 'vpas')
            ->orderBy('namaupt')
            ->get();

        return view('mclient.catatankartu.catatan', compact('data', 'picList', 'cardSupportingList', 'uptList'));
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific searches
        if ($request->has('search_nama_upt') && !empty($request->search_nama_upt)) {
            $query->where('nama_upt', 'LIKE', '%' . $request->search_nama_upt . '%');
        }
        if ($request->has('search_kartu_baru') && !empty($request->search_kartu_baru)) {
            $query->where('spam_vpas_kartu_baru', 'LIKE', '%' . $request->search_kartu_baru . '%');
        }
        if ($request->has('search_kartu_bekas') && !empty($request->search_kartu_bekas)) {
            $query->where('spam_vpas_kartu_bekas', 'LIKE', '%' . $request->search_kartu_bekas . '%');
        }
        if ($request->has('search_kartu_goip') && !empty($request->search_kartu_goip)) {
            $query->where('spam_vpas_kartu_goip', 'LIKE', '%' . $request->search_kartu_goip . '%');
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

    public function MclientCatatanStoreVpas(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'spam_vpas_kartu_baru' => 'nullable|string',
                'spam_vpas_kartu_bekas' => 'nullable|string',
                'spam_vpas_kartu_goip' => 'nullable|string',
                'kartu_belum_teregister' => 'nullable|string',
                'whatsapp_telah_terpakai' => 'nullable|string',
                'card_supporting' => 'nullable|string|max:255',
                'pic' => 'nullable|string|max:255',
                'jumlah_kartu_terpakai_perhari' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif,proses,pending',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'spam_vpas_kartu_baru.string' => 'Spam VPAS kartu baru harus berupa teks.',
                'spam_vpas_kartu_bekas.string' => 'Spam VPAS kartu bekas harus berupa teks.',
                'spam_vpas_kartu_goip.string' => 'Spam VPAS kartu GOIP harus berupa teks.',
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
                'spam_vpas_kartu_baru',
                'spam_vpas_kartu_bekas',
                'spam_vpas_kartu_goip',
                'kartu_belum_teregister',
                'whatsapp_telah_terpakai',
                'jumlah_kartu_terpakai_perhari'
            ];

            foreach ($stringFields as $field) {
                if (isset($data[$field]) && trim($data[$field]) === '') {
                    unset($data[$field]);
                }
            }

            Catatan::create($data);

            return redirect()->back()->with('success', 'Data catatan kartu berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientCatatanUpdateVpas(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'spam_vpas_kartu_baru' => 'nullable|string',
                'spam_vpas_kartu_bekas' => 'nullable|string',
                'spam_vpas_kartu_goip' => 'nullable|string',
                'kartu_belum_teregister' => 'nullable|string',
                'whatsapp_telah_terpakai' => 'nullable|string',
                'card_supporting' => 'nullable|string|max:255',
                'pic' => 'nullable|string|max:255',
                'jumlah_kartu_terpakai_perhari' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif,proses,pending',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'spam_vpas_kartu_baru.string' => 'Spam VPAS kartu baru harus berupa teks.',
                'spam_vpas_kartu_bekas.string' => 'Spam VPAS kartu bekas harus berupa teks.',
                'spam_vpas_kartu_goip.string' => 'Spam VPAS kartu GOIP harus berupa teks.',
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
            $data = Catatan::findOrFail($id);

            // Only take allowed fields (whitelist approach)
            $updateData = $request->only([
                'nama_upt',
                'spam_vpas_kartu_baru',
                'spam_vpas_kartu_bekas',
                'spam_vpas_kartu_goip',
                'kartu_belum_teregister',
                'whatsapp_telah_terpakai',
                'card_supporting',
                'pic',
                'jumlah_kartu_terpakai_perhari',
                'tanggal',
                'status'
            ]);

            // Remove empty string fields to allow null values
            $stringFields = [
                'spam_vpas_kartu_baru',
                'spam_vpas_kartu_bekas',
                'spam_vpas_kartu_goip',
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

    public function MclientCatatanDestroyVpas($id)
    {
        try {
            $data = Catatan::findOrFail($id);
            $namaUpt = $data->nama_upt;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data catatan kartu di UPT '{$namaUpt}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportListPdf(Request $request)
    {
        $query = Catatan::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')
        ) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $pdfData = [
            'title' => 'List Data Catatan Kartu Vpas',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.catatanKartuVpas.indexVpas', $pdfData);
        $filename = 'list_catatan_kartu_vpas_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Catatan::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')
        ) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'list_catatan_kartu_vpas_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [[
            'No',
            'Nama UPT',
            'Spam VPAS Kartu Baru',
            'Spam VPAS Kartu Bekas',
            'Spam VPAS Kartu GOIP',
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
                $row->nama_upt,
                $row->spam_vpas_kartu_baru ?? '',
                $row->spam_vpas_kartu_bekas ?? '',
                $row->spam_vpas_kartu_goip ?? '',
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

    public function exportCsv()
    {
        $data = Catatan::orderBy('created_at', 'desc')->get();

        $filename = 'catatan_kartu_vpas_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

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
                'Spam VPAS Kartu Baru',
                'Spam VPAS Kartu Bekas',
                'Spam VPAS Kartu GOIP',
                'Kartu Belum Teregister',
                'WhatsApp Telah Terpakai',
                'Card Supporting',
                'PIC',
                'Jumlah Kartu Terpakai Per Hari',
                'Tanggal',
                'Status',
                'Dibuat Pada',
                'Diupdate Pada'
            ]);

            $no = 1;
            foreach ($data as $row) {
                fputcsv($file, [
                    $no++,
                    $row->nama_upt,
                    $row->spam_vpas_kartu_baru ?? '',
                    $row->spam_vpas_kartu_bekas ?? '',
                    $row->spam_vpas_kartu_goip ?? '',
                    $row->kartu_belum_teregister ?? '',
                    $row->whatsapp_telah_terpakai ?? '',
                    $row->card_supporting ?? '',
                    $row->pic ?? '',
                    $row->jumlah_kartu_terpakai_perhari ?? '',
                    $row->tanggal ? Carbon::parse($row->tanggal)->format('Y-m-d') : '',
                    $row->status ?? '',
                    $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d H:i:s') : '',
                    $row->updated_at ? Carbon::parse($row->updated_at)->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getDashboardStats()
    {
        $totalData = Catatan::count();
        $statusAktif = Catatan::where('status', 'aktif')->count();
        $statusNonaktif = Catatan::where('status', 'nonaktif')->count();
        $statusProses = Catatan::where('status', 'proses')->count();
        $statusPending = Catatan::where('status', 'pending')->count();

        $bulanIni = Catatan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalSpamTertangani = Catatan::get()->sum(function ($item) {
            return intval($item->spam_vpas_kartu_baru ?? '0') +
                intval($item->spam_vpas_kartu_bekas ?? '0') +
                intval($item->spam_vpas_kartu_goip ?? '0');
        });

        $totalKartuTerpakai = Catatan::get()->sum(function ($item) {
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

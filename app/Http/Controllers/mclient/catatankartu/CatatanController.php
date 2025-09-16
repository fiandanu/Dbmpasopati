<?php

namespace App\Http\Controllers\mclient\catatankartu;

use App\Http\Controllers\Controller;
use App\Models\mclient\catatankartu\Catatan;
use App\Models\Upt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Pic;

class CatatanController extends Controller
{
    public function ListDataMclientCatatan(Request $request)
    {
        $query = Catatan::query();

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        $picList = Pic::orderBy('nama_pic')->get();

        // Get card supporting list from Pic model (same source as PIC)
        $cardSupportingList = Pic::orderBy('nama_pic')->pluck('nama_pic');

        $uptList = Upt::select('namaupt', 'kanwil')
            ->where('tipe', 'vpas')
            ->orderBy('namaupt')
            ->get();

        return view('mclient.catatankartu.catatan', compact('data', 'picList', 'cardSupportingList', 'uptList'));
    }

    public function MclientCatatanStore(Request $request)
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

            // Set default values for string fields
            $stringFields = [
                'spam_vpas_kartu_baru', 'spam_vpas_kartu_bekas', 'spam_vpas_kartu_goip',
                'kartu_belum_teregister', 'whatsapp_telah_terpakai',
                'jumlah_kartu_terpakai_perhari'
            ];

            foreach ($stringFields as $field) {
                if (empty($data[$field])) {
                    $data[$field] = '0';
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

    public function MclientCatatanUpdate(Request $request, $id)
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
            $validatedData = [];
            $invalidFields = array_keys($validator->errors()->messages());

            foreach ($request->all() as $key => $value) {
                if (!in_array($key, $invalidFields)) {
                    $validatedData[$key] = $value;
                }
            }

            try {
                if (!empty($validatedData)) {
                    $data = Catatan::findOrFail($id);

                    // Set default values for string fields
                    $stringFields = [
                        'spam_vpas_kartu_baru', 'spam_vpas_kartu_bekas', 'spam_vpas_kartu_goip',
                        'kartu_belum_teregister', 'whatsapp_telah_terpakai',
                        'jumlah_kartu_terpakai_perhari'
                    ];

                    foreach ($stringFields as $field) {
                        if (isset($validatedData[$field]) && empty($validatedData[$field])) {
                            $validatedData[$field] = '0';
                        }
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
            $data = Catatan::findOrFail($id);
            $updateData = $request->all();

            // Set default values for string fields
            $stringFields = [
                'spam_vpas_kartu_baru', 'spam_vpas_kartu_bekas', 'spam_vpas_kartu_goip',
                'kartu_belum_teregister', 'whatsapp_telah_terpakai',
                'jumlah_kartu_terpakai_perhari'
            ];

            foreach ($stringFields as $field) {
                if (empty($updateData[$field])) {
                    $updateData[$field] = '0';
                }
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data catatan kartu berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientCatatanDestroy($id)
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

    public function exportCsv()
    {
        $data = Catatan::orderBy('created_at', 'desc')->get();

        $filename = 'catatan_kartu_' . date('Y-m-d_H-i-s') . '.csv';

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

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama_upt,
                    $row->spam_vpas_kartu_baru,
                    $row->spam_vpas_kartu_bekas,
                    $row->spam_vpas_kartu_goip,
                    $row->kartu_belum_teregister,
                    $row->whatsapp_telah_terpakai,
                    $row->card_supporting,
                    $row->pic,
                    $row->jumlah_kartu_terpakai_perhari,
                    $row->tanggal ? $row->tanggal->format('Y-m-d') : '',
                    $row->status,
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
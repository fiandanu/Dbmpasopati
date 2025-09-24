<?php

namespace App\Http\Controllers\user\ponpes\vtren;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Provider;
use App\Models\user\Ponpes;
use App\Models\user\Vpn;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class VtrenController extends Controller
{
    
    public function ListDataVtrend(Request $request)
    {
        $query = Ponpes::with('dataOpsional');
        $query->where('tipe', 'vtren');

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('dataOpsional', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('pic_ponpes', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        // Dapatkan per_page dari request, default 10
        $perPage = $request->get('per_page', 10);

        // Validasi per_page agar tidak sembarangan
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 20;
        }

        // Jika pilih "semua", gunakan angka besar
        if ($perPage == 'all') {
            $data = $query->orderBy('tanggal', 'desc')->paginate(99999);
        } else {
            $data = $query->orderBy('tanggal', 'desc')->paginate($perPage);
        }

        $providers = Provider::all();
        $vpns = Vpn::all();

        return view('db.ponpes.vtren.indexVtren', compact('data', 'providers', 'vpns'));
    }

    public function ListDataPonpesUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|max:255',
                'nama_wilayah' => 'required|string|max:255',
                'tipe' => 'required|string|max:255',
                'tanggal' => 'nullable|date',

                'pic_ponpes' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',
                'jumlah_wbp' => 'nullable|integer|min:0',
                'jumlah_line' => 'nullable|integer|min:0',
                'provider_internet' => 'nullable|string|max:255',
                'kecepatan_internet' => 'nullable|string|max:255',
                'tarif_wartel' => 'nullable|string|max:255',
                'status_wartel' => 'nullable|string|in:Aktif,Tidak Aktif',

                'akses_topup_pulsa' => 'nullable|string',
                'password_topup' => 'nullable|string|max:255',
                'akses_download_rekaman' => 'nullable|string',
                'password_download' => 'nullable|string|max:255',

                'internet_protocol' => 'nullable|string|max:255',
                'vpn_user' => 'nullable|string|max:255',
                'vpn_password' => 'nullable|string|max:255',
                'jenis_vpn' => 'nullable|string|max:255',

                'jumlah_extension' => 'nullable|integer|min:0',
                'no_extension' => 'nullable|string',
                'extension_password' => 'nullable|string',
                'pin_tes' => 'nullable|string|max:255',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_wilayah.required' => 'Nama Daerah harus diisi.',
                'tanggal.date' => 'Format tanggal harus sesuai (YYYY-MM-DD).',

                'pic_ponpes.string' => 'PIC Ponpes harus berupa teks.',
                'no_telpon.regex' => 'Format nomor telepon tidak valid.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'jumlah_wbp.integer' => 'Jumlah WBP harus berupa angka.',
                'jumlah_wbp.min' => 'Jumlah WBP tidak boleh negatif.',
                'jumlah_line.integer' => 'Jumlah line reguler harus berupa angka.',
                'jumlah_line.min' => 'Jumlah line reguler tidak boleh negatif.',
                'provider_internet.string' => 'Provider internet harus berupa teks.',
                'kecepatan_internet.string' => 'Kecepatan internet harus berupa teks.',
                'tarif_wartel.string' => 'Tarif wartel harus berupa teks.',
                'status_wartel.in' => 'Status wartel harus Aktif atau Tidak Aktif.',

                'akses_topup_pulsa.string' => 'Akses top up pulsa harus berupa teks.',
                'password_topup.string' => 'Password top up harus berupa teks.',
                'akses_download_rekaman.string' => 'Akses download rekaman harus berupa teks.',
                'password_download.string' => 'Password download rekaman harus berupa teks.',

                'internet_protocol.string' => 'Internet Protocol harus berupa teks.',
                'vpn_user.string' => 'User VPN harus berupa teks.',
                'vpn_password.string' => 'Password VPN harus berupa teks.',
                'jenis_vpn.string' => 'Jenis VPN harus berupa teks.',

                'jumlah_extension.integer' => 'Jumlah extension harus berupa angka.',
                'jumlah_extension.min' => 'Jumlah extension tidak boleh negatif.',
                'no_extension.string' => 'Nomor extension harus berupa teks.',
                'extension_password.string' => 'Password extension harus berupa teks.',
                'pin_tes.string' => 'PIN Tes harus berupa teks.'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa input Anda.');
        }

        try {
            DB::beginTransaction();

            $ponpes = Ponpes::findOrFail($id);

            $ponpesData = [
                'nama_ponpes' => $request->nama_ponpes,
                'nama_wilayah' => $request->nama_wilayah,
                'tipe' => $request->tipe,
                'tanggal' => $request->tanggal ?? $ponpes->tanggal,
            ];

            $ponpes->update($ponpesData);

            $opsionalData = [
                'pic_ponpes' => $request->pic_ponpes,
                'no_telpon' => $request->no_telpon,
                'alamat' => $request->alamat,
                'jumlah_wbp' => $request->jumlah_wbp,
                'jumlah_line' => $request->jumlah_line,
                'provider_internet' => $request->provider_internet,
                'kecepatan_internet' => $request->kecepatan_internet,
                'tarif_wartel' => $request->tarif_wartel,
                'status_wartel' => $request->status_wartel == 'Aktif' ? 1 : 0,
                'akses_topup_pulsa' => $request->akses_topup_pulsa,
                'password_topup' => $request->password_topup,
                'akses_download_rekaman' => $request->akses_download_rekaman,
                'password_download' => $request->password_download,
                'internet_protocol' => $request->internet_protocol,
                'vpn_user' => $request->vpn_user,
                'vpn_password' => $request->vpn_password,
                'jenis_vpn' => $request->jenis_vpn,
                'jumlah_extension' => $request->jumlah_extension,
                'pin_tes' => $request->pin_tes,
                'no_extension' => $request->no_extension,
                'extension_password' => $request->extension_password,
            ];

            $ponpes->dataOpsional()->updateOrCreate(
                ['ponpes_id' => $ponpes->id],
                $opsionalData
            );

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }
    public function PonpesPageDestroy($id)
    {
        try {
            DB::beginTransaction();

            $ponpes = Ponpes::findOrFail($id);

            if ($ponpes->dataOpsional) {
                $ponpes->dataOpsional->delete();
            }

            $ponpes->delete();

            DB::commit();

            return redirect()->route('UserPage')->with('success', 'Data Ponpes berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportPonpesCsv($id): StreamedResponse
    {
        $ponpes = Ponpes::with('dataOpsional')->findOrFail($id);
        $dataOpsional = $ponpes->dataOpsional;

        $filename = 'data_ponpes_' . str_replace(' ', '_', $ponpes->nama_ponpes) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [
            ['Field', 'Value'],
            ['Nama Ponpes', $ponpes->nama_ponpes],
            ['Nama Daerah', $ponpes->nama_wilayah],
            ['Tipe', $ponpes->tipe],
            ['Tanggal', $ponpes->tanggal],
            ['PIC Ponpes', $dataOpsional ? $dataOpsional->pic_ponpes : ''],
            ['No. Telpon', $dataOpsional ? $dataOpsional->no_telpon : ''],
            ['Alamat', $dataOpsional ? $dataOpsional->alamat : ''],
            ['Jumlah WBP', $dataOpsional ? $dataOpsional->jumlah_wbp : ''],
            ['Jumlah Line Reguler Terpasang', $dataOpsional ? $dataOpsional->jumlah_line : ''],
            ['Provider Internet', $dataOpsional ? $dataOpsional->provider_internet : ''],
            ['Kecepatan Internet (Mbps)', $dataOpsional ? $dataOpsional->kecepatan_internet : ''],
            ['Tarif Wartel Reguler', $dataOpsional ? $dataOpsional->tarif_wartel : ''],
            ['Status Wartel', $dataOpsional ? ($dataOpsional->status_wartel ? 'Aktif' : 'Tidak Aktif') : ''],
            ['Akses Topup Pulsa', $dataOpsional ? ($dataOpsional->akses_topup_pulsa) : ''],
            ['Password Topup', $dataOpsional ? $dataOpsional->password_topup : ''],
            ['Akses Download Rekaman', $dataOpsional ? ($dataOpsional->akses_download_rekaman) : ''],
            ['Password Download Rekaman', $dataOpsional ? $dataOpsional->password_download : ''],
            ['Internet Protocol', $dataOpsional ? $dataOpsional->internet_protocol : ''],
            ['VPN User', $dataOpsional ? $dataOpsional->vpn_user : ''],
            ['VPN Password', $dataOpsional ? $dataOpsional->vpn_password : ''],
            ['Jenis VPN', $dataOpsional ? $dataOpsional->jenis_vpn : ''],
            ['Jumlah Extension', $dataOpsional ? $dataOpsional->jumlah_extension : ''],
            ['No Extension', $dataOpsional ? $dataOpsional->no_extension : ''],
            ['Extension Password', $dataOpsional ? $dataOpsional->extension_password : ''],
            ['PIN Tes', $dataOpsional ? $dataOpsional->pin_tes : ''],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Data Ponpes Export - ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPonpesPdf($id)
    {
        $ponpes = Ponpes::with('dataOpsional')->findOrFail($id);

        $data = [
            'title' => 'Data PONPES VTREN ' . $ponpes->nama_ponpes,
            'ponpes' => $ponpes,
        ];

        $pdf = Pdf::loadView('export.ponpes_pdf', $data);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'data_ponpes_' . str_replace(' ', '_', $ponpes->nama_ponpes) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}

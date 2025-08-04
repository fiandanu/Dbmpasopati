<?php

namespace App\Http\Controllers\user\ponpes\reguller;

use App\Models\Provider;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Vpn;

class RegullerController extends Controller
{
    public function ListDataPonpes(Request $request)
    {
        $query = Ponpes::query();

        $query->where('tipe', 'reguler');

        // Search filter
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status_wartel', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $data = $query->get();
        $providers = Provider::all();
        $vpns = Vpn::all();
        return view('db.ponpes.reguller.ponpes', compact('data', 'providers', 'vpns'));
    }

    public function UserPage()
    {
        $dataponpes  = Ponpes::all();
        return view('user.indexPonpes', compact('dataponpes'));
    }

    public function UserPageStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|unique:ponpes,nama_ponpes',
                'nama_wilayah' => 'required|string',
                'tipe' => 'required|string',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_ponpes.unique' => 'Nama Ponpes sudah terdaftar.',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
                'tipe.required' => 'Tipe harus diisi.',
            ]
        );

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Data siap simpan
        $dataPonpes = [
            'nama_ponpes' => $request->nama_ponpes,
            'nama_wilayah' => $request->nama_wilayah,
            'tipe' => $request->tipe,
            'tanggal' => Carbon::today()->toDateString(),
        ];

        Ponpes::create($dataPonpes);
        // return redirect()->route('ponpes.UserPage')->with('success', 'Data Ponpes berhasil ditambahkan!');

        try {
            Ponpes::create($dataPonpes);

            return redirect()->route('ponpes.UserPage')->with('success', 'Data Ponpes berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function ListDataPonpesUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // Field Wajib
                'nama_ponpes' => 'required|unique:ponpes,nama_ponpes,' . $id,
                'nama_wilayah' => 'required|string|max:255',
                'tipe' => 'required|string|max:255',
                // 'tanggal' => 'nullable|date',

                // Data Opsional
                'pic_ponpes' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',

                'jumlah_line_reguler' => 'nullable|integer|min:0',
                'provider_internet' => 'nullable|string|max:255',
                'kecepatan_internet' => 'nullable|string|max:255',
                'tarif_wartel_reguler' => 'nullable|integer|min:0',
                'status_wartel' => 'nullable|string|in:Aktif,Tidak Aktif',

                // IMC PAS
                'akses_topup_pulsa' => 'nullable|string|max:255',
                'password_topup' => 'nullable|string|max:255',
                'akses_download_rekaman' => 'nullable|string',
                'password_download' => 'nullable|string|max:255',

                // Akses VPN
                'internet_protocol' => 'nullable|string|max:255',
                'vpn_user' => 'nullable|string|max:255',
                'vpn_password' => 'nullable|string|max:255',
                'jenis_vpn' => 'nullable|string|max:255',

                // Extension Reguler
                'jumlah_extension' => 'nullable|integer|min:0',
                'no_extension' => 'nullable|string',
                'extension_password' => 'nullable|string',
                'pin_tes' => 'nullable|integer|min:0',
            ],
            [
                // Field Wajib
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_ponpes.unique' => 'Nama Ponpes sudah terdaftar.',
                'nama_wilayah.required' => 'Nama Daerah harus diisi.',
                'tanggal.date' => 'Format tanggal harus sesuai (YYYY-MM-DD).',

                // Data Opsional
                'pic_ponpes.string' => 'PIC Ponpes harus berupa teks.',
                'no_telpon.string' => 'Nomor telepon harus berupa teks.',
                'alamat.string' => 'Alamat harus berupa teks.',

                'jumlah_line_reguler.integer' => 'Jumlah line reguler harus berupa angka.',
                'jumlah_line_reguler.min' => 'Jumlah line reguler tidak boleh negatif.',
                'provider_internet.string' => 'Provider internet harus berupa teks.',
                'kecepatan_internet.string' => 'Kecepatan internet harus berupa teks.',
                'tarif_wartel_reguler.integer' => 'Tarif wartel harus berupa angka.',
                'tarif_wartel_reguler.min' => 'Tarif wartel tidak boleh negatif.',
                'status_wartel.string' => 'Status wartel harus berupa teks.',

                // IMC PAS
                'akses_topup_pulsa.string' => 'Akses top up pulsa harus berupa teks.',
                'password_topup.string' => 'Password top up harus berupa teks.',
                'akses_download_rekaman.string' => 'Akses download rekaman harus berupa teks.',
                'password_download.string' => 'Password download rekaman harus berupa teks.',

                // Akses VPN
                'internet_protocol.string' => 'Internet Protocol harus berupa teks.',
                'vpn_user.string' => 'User VPN harus berupa teks.',
                'vpn_password.string' => 'Password VPN harus berupa teks.',
                'jenis_vpn.string' => 'Jenis VPN harus berupa teks.',

                // Extension Reguler
                'jumlah_extension.integer' => 'Jumlah extension harus berupa angka.',
                'jumlah_extension.min' => 'Jumlah extension tidak boleh negatif.',
                'no_extension.string' => 'Nomor extension harus berupa teks.',
                'extension_password.string' => 'Password extension harus berupa teks.',
                'pin_tes.integer' => 'PIN Tes harus berupa angka.',
                'pin_tes.min' => 'PIN Tes tidak boleh negatif.'
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            $validatedData = [];
            $invalidFields = array_keys($validator->errors()->messages());

            // Ambil field yang valid
            foreach ($request->all() as $key => $value) {
                if (!in_array($key, $invalidFields)) {
                    $validatedData[$key] = $value;
                }
            }

            // Update field valid ke database
            try {
                if (!empty($validatedData)) {
                    $data = Ponpes::findOrFail($id);
                    $data->update($validatedData);
                }
            } catch (\Exception $e) {
                // Tetap tampilkan error validasi
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('partial_success', 'Data valid telah disimpan. Silakan perbaiki field yang bermasalah.');
        }

        // Jika validasi berhasil
        try {
            $data = Ponpes::findOrFail($id);
            $data->update($request->all());

            return redirect()->back()->with('success', 'Semua data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_ponpes' => 'required|string|max:255',
            'nama_wilayah' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ponpes = Ponpes::findOrFail($id);

            $ponpes->update([
                'nama_ponpes' => $request->nama_ponpes,
                'nama_wilayah' => $request->nama_wilayah,
            ]);

            return redirect()->back()
                ->with('success', 'Data ponpes berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function PonpesPageDestroy($id)
    {
        try {
            $data = Ponpes::findOrFail($id);
            $data->delete();
            return redirect()->route('UserPage')->with('success', 'Data Ponpes berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export Ponpes data to CSV
     */
    public function exportPonpesCsv($id): StreamedResponse
    {
        $ponpes = Ponpes::findOrFail($id);

        $filename = 'data_ponpes_' . $ponpes->nama_ponpes . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [
            ['Nama Ponpes', $ponpes->nama_ponpes],
            ['Nama Daerah', $ponpes->nama_wilayah],
            ['PIC Ponpes', $ponpes->pic_ponpes],
            ['No. Telpon', $ponpes->no_telpon],
            ['Alamat', $ponpes->alamat],
            ['Jumlah Line Reguler Terpasang', $ponpes->jumlah_line_reguler],
            ['Provider Internet', $ponpes->provider_internet],
            ['Kecepatan Internet (Mbps)', $ponpes->kecepatan_internet],
            ['Tarif Wartel Reguler', $ponpes->tarif_wartel_reguler],
            ['Status Wartel', $ponpes->status_wartel],
            ['Akses Topup Pulsa', $ponpes->akses_topup_pulsa],
            ['Password Topup', $ponpes->password_topup],
            ['Akses Download Rekaman', $ponpes->akses_download_rekaman],
            ['Password Download Rekaman', $ponpes->password_download],
            ['Internet Protocol', $ponpes->internet_protocol],
            ['VPN User', $ponpes->vpn_user],
            ['VPN Password', $ponpes->vpn_password],
            ['Jenis VPN', $ponpes->jenis_vpn],
            ['Jumlah Extension', $ponpes->jumlah_extension],
            ['No Extension', $ponpes->no_extension],
            ['Extension Password', $ponpes->extension_password],
            ['PIN Tes', $ponpes->pin_tes],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Ponpes data to PDF
     */
    public function exportPonpesPdf($id)
    {
        $ponpes = Ponpes::findOrFail($id);

        $data = [
            'title' => 'Data Pondok Pesantren ' . $ponpes->nama_ponpes,
            'ponpes' => $ponpes,
        ];

        $pdf = Pdf::loadView('export.ponpes_pdf', $data);
        return $pdf->download('data_ponpes_' . $ponpes->nama_ponpes . '.pdf');
    }
}

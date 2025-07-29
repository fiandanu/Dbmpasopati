<?php

namespace App\Http\Controllers\user\upt\reguler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class RegullerController extends Controller
{
    public function ListDataUpt(Request $request)
    {
        $query = User::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('namaupt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status_wartel', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        $providers = Provider::all();
        return view('db.upt.reguler.indexUpt', compact('data', 'providers'));
    }

    public function ListDataUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // Field Wajib Form UPT
                'namaupt' => 'required|string|max:255',
                'kanwil' => 'required|string|max:255',
                'tanggal' => 'nullable|date',

                // Data Opsional (Form VPAS)
                'pic_upt' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',
                'jumlah_wbp' => 'nullable|integer|min:0',
                'jumlah_line_reguler' => 'nullable|integer|min:0',
                'provider_internet' => 'nullable|string|max:255',
                'kecepatan_internet' => 'nullable|string|max:255',
                'tarif_wartel_reguler' => 'nullable|integer|min:0',
                'status_wartel' => 'nullable|string|max:255',

                // IMC PAS
                'akses_topup_pulsa' => 'nullable|string|max:255',
                'password_topup' => 'nullable|string|max:255',
                'akses_download_rekaman' => 'nullable|string',
                'password_download' => 'nullable|string|max:255',

                // AKSES VPN
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
            // Pesan Validasi
            [
                // Field Wajib Form UPT
                'namaupt.required' => 'Nama UPT harus diisi.',
                'kanwil.required' => 'Kanwil harus diisi.',
                'tanggal.date' => 'Format tanggal harus sesuai (YYYY-MM-DD).',

                // Data Opsional (Form VPAS)
                'pic_upt.string' => 'PIC UPT harus berupa teks.',
                'no_telpon.regex' => 'Nomor telepon harus berupa angka.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'jumlah_wbp.integer' => 'Jumlah WBP harus berupa angka.',
                'jumlah_wbp.min' => 'Jumlah WBP tidak boleh negatif.',
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

                // AKSES VPN
                'internet_protocol.string' => 'Internet Protocol harus berupa teks.',
                'vpn_user.string' => 'User VPN harus berupa teks.',
                'vpn_password.string' => 'Password VPN harus berupa teks.',
                'jenis_vpn.string' => 'Jenis VPN harus berupa teks.',

                // Extension Reguler
                'jumlah_extension.integer' => 'Jumlah extension harus berupa angka.',
                'jumlah_extension.min' => 'Jumlah extension tidak boleh negatif.',
                'no_extension.string' => 'Nomor extension 1 harus berupa teks.',
                'extension_password.string' => 'Nomor extension 2 harus berupa teks.',
                'pin_tes.integer' => 'PIN Tes harus berupa angka.',
                'pin_tes.min' => 'PIN Tes tidak boleh negatif.'
            ]
        );
        $providers = Provider::all();

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
                    $data = User::findOrFail($id);
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
            $data = User::findOrFail($id);
            $data->update($request->all());

            return redirect()->back()->with('success', 'Semua data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function UserPageDestroy($id)
    {
        $dataupt = User::find($id);
        $dataupt->delete();
        return redirect()->route('upt.UserPage');
    }

    public function UserPageStore(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string|unique:users,namaupt',
                'kanwil' => 'required|string',
                'tipe' => 'required|string',
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
                'tipe.required' => 'Tipe harus diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Perbaikan: gunakan array untuk data yang akan disimpan
        $dataupt = [
            'namaupt' => $request->namaupt,
            'kanwil' => $request->kanwil,
            'tipe' => $request->tipe,
            'tanggal' => Carbon::now()->format('Y-m-d'), // Format tanggal yang konsisten
        ];

        User::create($dataupt);

        return redirect()->route('upt.UserPage')->with('success', 'Data UPT berhasil ditambahkan!');
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string|unique:users,namaupt,' . $id,
                'kanwil' => 'required|string',
                'tipe' => 'required|string',
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
                'tipe.required' => 'Tipe harus diisi',

            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataupt = User::findOrFail($id); // Gunakan findOrFail untuk error handling yang lebih baik
        $dataupt->namaupt = $request->namaupt;
        $dataupt->kanwil = $request->kanwil;
        $dataupt->tipe = $request->tipe;
        $dataupt->save();

        return redirect()->route('upt.UserPage')->with('success', 'Data UPT berhasil diupdate!');
    }

    public function exportVerticalCsv($id): StreamedResponse
    {
        $user = User::findOrFail($id);

        $filename = 'data_upt_' . $user->namaupt . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [
            ['PIC UPT', $user->pic_upt],
            ['No. Telpon', $user->no_telpon],
            ['Alamat', $user->alamat],
            ['Kanwil', $user->kanwil],
            ['Jumlah WBP', $user->jumlah_wbp],
            ['Jumlah Line Reguler Terpasang', $user->jumlah_line_reguler],
            ['Provider Internet', $user->provider_internet],
            ['Kecepatan Internet (mbps)', $user->kecepatan_internet],
            ['Tarif Wartel Reguler', $user->tarif_wartel_reguler],
            ['Status Wartel', $user->status_wartel],
            ['Akses Topup Pulsa', $user->akses_topup_pulsa],
            ['Password Topup', $user->password_topup],
            ['Akses Download Rekaman', $user->akses_download_rekaman],
            ['Password Download Rekaman', $user->password_download],
            ['Internet Protocol', $user->internet_protocol],
            ['VPN User', $user->vpn_user],
            ['VPN Password', $user->vpn_password],
            ['Jenis VPN', $user->jenis_vpn],
            ['Jumlah Extension', $user->jumlah_extension],
            ['No Extension', $user->no_extension],
            ['Extension Password', $user->extension_password],
            ['PIN Tes', $user->pin_tes],
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

    public function exportUptPdf($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'title' => 'LAPAS PEREMPUAN KELAS IIA JAKARTA',
            'user' => $user,
        ];

        $pdf = Pdf::loadView('export.upt_pdf', $data);
        return $pdf->download('data_upt_' . $user->namaupt . '.pdf');
    }

    public function DatabasePageDestroy($id)
    {
        $dataupt = User::find($id);
        $dataupt->delete();
        return redirect()->route('DbReguler');
    }

    public function DbReguler(Request $request)
    {
        $query = User::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('namaupt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status_wartel', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        $providers = Provider::all();
        return view('db.upt.reguler.indexUpt', compact('data', 'providers'));
    }
}

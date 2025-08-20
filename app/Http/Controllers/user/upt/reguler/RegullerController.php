<?php

namespace App\Http\Controllers\user\upt\reguler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Upt;
use App\Models\Vpn; // TAMBAHKAN INI - Import model Vpn
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class RegullerController extends Controller
{

    public function ListDataReguller(Request $request)
    {
        $query = Upt::query();

        $query->where('tipe', 'reguler');

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
        $vpns = Vpn::all(); // TAMBAHKAN INI - Query data VPN

        // PERBAIKI INI - Tambahkan 'vpns' ke compact
        return view('db.upt.reguler.indexUpt', compact('data', 'providers', 'vpns'));
    }

    // Method lainnya tetap sama...
    public function ListUpdateReguller(Request $request, $id)
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
                    $data = Upt::findOrFail($id);
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
            $data = Upt::findOrFail($id);
            $data->update($request->all());

            return redirect()->back()->with('success', 'Semua data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    // Method lainnya tidak berubah
    public function UserPageDestroy($id)
    {
        $dataupt = Upt::find($id);

        if (!$dataupt) {
            return redirect()->route('upt.UserPage')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama UPT tanpa suffix (VpasReg) untuk pengecekan
        $namaUptBase = $this->removeVpasRegSuffix($dataupt->namaupt);

        // Hapus data yang dipilih
        $dataupt->delete();

        // Update nama UPT yang tersisa berdasarkan jumlah data
        $this->updateUptNamesBySuffix($namaUptBase);

        return redirect()->route('upt.UserPage')->with('success', 'Data berhasil dihapus!');
    }

    public function UserPageStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string',
                'kanwil' => 'required|string',
                'tipe' => 'required|array|min:1', // Validasi array dengan minimal 1 pilihan
                'tipe.*' => 'in:reguler,vpas', // Validasi setiap element array harus reguler atau vpas
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
                'tipe.required' => 'Tipe harus dipilih minimal satu',
                'tipe.array' => 'Tipe harus berupa array',
                'tipe.min' => 'Pilih minimal satu tipe',
                'tipe.*.in' => 'Tipe hanya boleh reguler atau vpas',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data tipe yang dipilih
        $selectedTypes = $request->tipe;
        $createdRecords = [];

        // Bersihkan nama UPT dari suffix ganda yang mungkin ada
        $cleanNamaUpt = $this->removeVpasRegSuffix($request->namaupt);

        // Tentukan nama UPT berdasarkan jumlah tipe yang dipilih
        $namaUpt = $cleanNamaUpt;
        if (count($selectedTypes) == 2 && in_array('reguler', $selectedTypes) && in_array('vpas', $selectedTypes)) {
            $namaUpt = $cleanNamaUpt . ' (VpasReg)';
        }

        // Validasi manual untuk kombinasi nama UPT + tipe
        foreach ($selectedTypes as $tipeValue) {
            $existingRecord = Upt::where('namaupt', $namaUpt)
                ->where('tipe', $tipeValue)
                ->first();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Data UPT '{$namaUpt}' dengan tipe '{$tipeValue}' sudah ada!");
            }
        }

        // Loop untuk setiap tipe yang dipilih
        foreach ($selectedTypes as $tipeValue) {
            // Buat record baru untuk setiap tipe
            $dataupt = [
                'namaupt' => $namaUpt,
                'kanwil' => $request->kanwil,
                'tipe' => $tipeValue,
                'tanggal' => Carbon::now()->format('Y-m-d'),
            ];

            $newRecord = Upt::create($dataupt);
            $createdRecords[] = $tipeValue;
        }

        // Berikan pesan berdasarkan hasil
        if (count($createdRecords) > 0) {
            $message = 'Data UPT berhasil ditambahkan untuk tipe: ' . implode(', ', $createdRecords);
            return redirect()->route('upt.UserPage')->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data UPT');
        }
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($id, $request) {
                        // Cek apakah ada record lain dengan nama yang sama dan tipe yang sama
                        $existingRecord = Upt::where('namaupt', $value)
                            ->where('id', '!=', $id)
                            ->where('tipe', $request->tipe)
                            ->first();

                        if ($existingRecord) {
                            $fail("Nama UPT '{$value}' dengan tipe '{$request->tipe}' sudah ada.");
                        }
                    }
                ],
                'kanwil' => 'required|string',
                'tipe' => 'required|string|in:reguler,vpas', // Hanya terima reguler atau vpas
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
                'tipe.required' => 'Tipe harus diisi',
                'tipe.in' => 'Tipe hanya boleh reguler atau vpas',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataupt = Upt::findOrFail($id);
        $dataupt->namaupt = $request->namaupt;
        $dataupt->kanwil = $request->kanwil;
        $dataupt->tipe = $request->tipe;
        $dataupt->save();

        return redirect()->route('upt.UserPage')->with('success', 'Data UPT berhasil diupdate!');
    }

    /**
     * Helper method untuk menghilangkan suffix (VpasReg) dari nama UPT
     * Menghapus semua kemungkinan suffix ganda
     */
    private function removeVpasRegSuffix($namaUpt)
    {
        // Hapus semua kemungkinan suffix (VpasReg) yang mungkin ganda
        return preg_replace('/\s*\(VpasReg\)+/', '', $namaUpt);
    }

    /**
     * Helper method untuk mengecek apakah UPT memiliki kedua tipe (reguler dan vpas)
     */
    // private function hasMultipleTypes($namaUpt)
    // {
    //     $namaUptBase = $this->removeVpasRegSuffix($namaUpt);

    //     $regulerExists = Upt::where('namaupt', 'LIKE', $namaUptBase . '%')
    //         ->where('tipe', 'reguler')
    //         ->exists();

    //     $vpasExists = Upt::where('namaupt', 'LIKE', $namaUptBase . '%')
    //         ->where('tipe', 'vpas')
    //         ->exists();

    //     return $regulerExists && $vpasExists;
    // }

    /**
     * Helper method untuk update nama UPT berdasarkan jumlah tipe
     */
    
    private function updateUptNamesBySuffix($namaUptBase)
    {
        $relatedData = Upt::where('namaupt', 'LIKE', $namaUptBase . '%')->get();

        // Jika ada 2 atau lebih data dengan nama base yang sama, pastikan ada suffix
        if ($relatedData->count() >= 2) {
            foreach ($relatedData as $data) {
                if (!str_contains($data->namaupt, '(VpasReg)')) {
                    $data->update([
                        'namaupt' => $namaUptBase . ' (VpasReg)'
                    ]);
                }
            }
        }
        // Jika hanya ada 1 data tersisa, hapus suffix
        elseif ($relatedData->count() == 1) {
            $remainingData = $relatedData->first();
            if (str_contains($remainingData->namaupt, '(VpasReg)')) {
                $remainingData->update([
                    'namaupt' => $namaUptBase
                ]);
            }
        }
    }

    public function exportVerticalCsv($id): StreamedResponse
    {
        $user = Upt::findOrFail($id);

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
        $user = Upt::findOrFail($id);

        $data = [
            'title' => 'LAPAS PEREMPUAN KELAS IIA JAKARTA',
            'user' => $user,
        ];

        $pdf = Pdf::loadView('export.upt_pdf', $data);
        return $pdf->download('data_upt_' . $user->namaupt . '.pdf');
    }

    public function DatabasePageDestroy($id)
    {
        $dataupt = Upt::find($id);

        if (!$dataupt) {
            return redirect()->route('DbReguler')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama UPT tanpa suffix (VpasReg) untuk pengecekan
        $namaUptBase = $this->removeVpasRegSuffix($dataupt->namaupt);

        // Hapus data yang dipilih
        $dataupt->delete();

        // Update nama UPT yang tersisa berdasarkan jumlah data
        $this->updateUptNamesBySuffix($namaUptBase);

        return redirect()->route('DbReguler')->with('success', 'Data berhasil dihapus!');
    }
}

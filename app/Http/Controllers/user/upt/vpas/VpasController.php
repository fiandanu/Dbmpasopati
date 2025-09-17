<?php

namespace App\Http\Controllers\user\upt\vpas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Provider;
use App\Models\user\Upt;
use App\Models\db\DataOpsionalUpt;
use App\Models\user\Vpn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class VpasController extends Controller
{
    public function ListDataVpas(Request $request)
    {
        $query = Upt::with('dataOpsional');

        $query->where('tipe', 'vpas');

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('namaupt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('dataOpsional', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('pic_upt', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        $data = $query->get();
        $providers = Provider::all();
        $vpns = Vpn::all();

        return view('db.upt.vpas.indexVpas', compact('data', 'providers', 'vpns'));
    }

    public function ListUpdateVpas(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // Data Opsional (Form VPAS)
                'pic_upt' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',
                'jumlah_wbp' => 'nullable|integer|min:0',
                'jumlah_line' => 'nullable|integer|min:0',
                'provider_internet' => 'nullable|string|max:255',
                'kecepatan_internet' => 'nullable|string|max:255',
                'tarif_wartel' => 'nullable|numeric|min:0',
                'status_wartel' => 'nullable|string',

                // IMC PAS
                'akses_topup_pulsa' => 'nullable|string',
                'password_topup' => 'nullable|string|max:255',
                'akses_download_rekaman' => 'nullable|string',
                'password_download' => 'nullable|string|max:255',

                // AKSES VPN
                'internet_protocol' => 'nullable|string|max:255',
                'vpn_user' => 'nullable|string|max:255',
                'vpn_password' => 'nullable|string|max:255',
                'jenis_vpn' => 'nullable|string|max:255',

                // Extension VPAS
                'jumlah_extension' => 'nullable|integer|min:0',
                'no_extension' => 'nullable|string',
                'extension_password' => 'nullable|string',
                'pin_tes' => 'nullable|string|max:255',
            ],
            // Pesan Validasi
            [
                'pic_upt.string' => 'PIC UPT harus berupa teks.',
                'no_telpon.regex' => 'Nomor telepon harus berupa angka.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'jumlah_wbp.integer' => 'Jumlah WBP harus berupa angka.',
                'jumlah_wbp.min' => 'Jumlah WBP tidak boleh negatif.',
                'jumlah_line.integer' => 'Jumlah line VPAS harus berupa angka.',
                'jumlah_line.min' => 'Jumlah line VPAS tidak boleh negatif.',
                'provider_internet.string' => 'Provider internet harus berupa teks.',
                'kecepatan_internet.string' => 'Kecepatan internet harus berupa teks.',
                'tarif_wartel.numeric' => 'Tarif wartel harus berupa angka.',
                'tarif_wartel.min' => 'Tarif wartel tidak boleh negatif.',
                'status_wartel.boolean' => 'Status wartel harus berupa boolean.',

                // IMC PAS
                'akses_topup_pulsa.string' => 'Akses top up pulsa harus berupa String.',
                'password_topup.string' => 'Password top up harus berupa teks.',
                'akses_download_rekaman.string' => 'Akses download rekaman harus berupa string.',
                'password_download.string' => 'Password download rekaman harus berupa teks.',

                // AKSES VPN
                'internet_protocol.string' => 'Internet Protocol harus berupa teks.',
                'vpn_user.string' => 'User VPN harus berupa teks.',
                'vpn_password.string' => 'Password VPN harus berupa teks.',
                'jenis_vpn.string' => 'Jenis VPN harus berupa teks.',

                // Extension VPAS
                'jumlah_extension.integer' => 'Jumlah extension harus berupa angka.',
                'jumlah_extension.min' => 'Jumlah extension tidak boleh negatif.',
                'no_extension.string' => 'Nomor extension harus berupa teks.',
                'extension_password.string' => 'Password extension harus berupa teks.',
                'pin_tes.string' => 'PIN Tes harus berupa teks.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam validasi data. Silakan periksa kembali.');
        }

        // Mulai database transaction
        DB::beginTransaction();

        try {
            // Find UPT data
            $upt = Upt::findOrFail($id);

            // Prepare data for data_opsional_upt table
            $opsionalData = [
                'pic_upt' => $request->pic_upt,
                'no_telpon' => $request->no_telpon,
                'alamat' => $request->alamat,
                'jumlah_wbp' => $request->jumlah_wbp,
                'jumlah_line' => $request->jumlah_line,
                'provider_internet' => $request->provider_internet,
                'kecepatan_internet' => $request->kecepatan_internet,
                'tarif_wartel' => $request->tarif_wartel,
                'status_wartel' => $request->status_wartel ? 1 : 0,
                'akses_topup_pulsa' => $request->akses_topup_pulsa,
                'password_topup' => $request->password_topup,
                'akses_download_rekaman' => $request->akses_download_rekaman,
                'password_download' => $request->password_download,
                'internet_protocol' => $request->internet_protocol,
                'vpn_user' => $request->vpn_user,
                'vpn_password' => $request->vpn_password,
                'jenis_vpn' => $request->jenis_vpn,
                'jumlah_extension' => $request->jumlah_extension,
                'no_extension' => $request->no_extension,
                'extension_password' => $request->extension_password,
                'pin_tes' => $request->pin_tes,
            ];

            // Update or create data_opsional_upt record
            DataOpsionalUpt::updateOrCreate(
                ['upt_id' => $upt->id],
                $opsionalData
            );

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

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
                'tipe' => 'required|array|min:1',
                'tipe.*' => 'in:reguler,vpas',
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
                'tipe' => 'required|string|in:reguler,vpas',
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
        $user = Upt::with('dataOpsional')->findOrFail($id);
        $dataOpsional = $user->dataOpsional;

        $filename = 'data_upt_vpas_' . $user->namaupt . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [
            ['PIC UPT', $dataOpsional->pic_upt ?? ''],
            ['No. Telpon', $dataOpsional->no_telpon ?? ''],
            ['Alamat', $dataOpsional->alamat ?? ''],
            ['Kanwil', $user->kanwil],
            ['Jumlah WBP', $dataOpsional->jumlah_wbp ?? ''],
            ['Jumlah Line VPAS Terpasang', $dataOpsional->jumlah_line ?? ''],
            ['Provider Internet', $dataOpsional->provider_internet ?? ''],
            ['Kecepatan Internet (mbps)', $dataOpsional->kecepatan_internet ?? ''],
            ['Tarif Wartel VPAS', $dataOpsional->tarif_wartel ?? ''],
            ['Status Wartel', $dataOpsional->status_wartel ? 'Aktif' : 'Tidak Aktif'],
            ['Akses Topup Pulsa', $dataOpsional->akses_topup_pulsa ?? ''],
            ['Password Topup', $dataOpsional->password_topup ?? ''],
            ['Akses Download Rekaman', $dataOpsional->akses_download_rekaman ?? ''],
            ['Password Download Rekaman', $dataOpsional->password_download ?? ''],
            ['Internet Protocol', $dataOpsional->internet_protocol ?? ''],
            ['VPN User', $dataOpsional->vpn_user ?? ''],
            ['VPN Password', $dataOpsional->vpn_password ?? ''],
            ['Jenis VPN', $dataOpsional->jenis_vpn ?? ''],
            ['Jumlah Extension', $dataOpsional->jumlah_extension ?? ''],
            ['No Extension', $dataOpsional->no_extension ?? ''],
            ['Extension Password', $dataOpsional->extension_password ?? ''],
            ['PIN Tes', $dataOpsional->pin_tes ?? ''],
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
        $user = Upt::with('dataOpsional')->findOrFail($id);

        $data = [
            'title' => 'LAPAS PEREMPUAN KELAS IIA JAKARTA - VPAS',
            'user' => $user,
        ];

        // Ganti dari 'export.vpas_pdf' menjadi 'export.upt_pdf'
        $pdf = Pdf::loadView('export.upt_pdf', $data);
        return $pdf->download('data_upt_vpas_' . $user->namaupt . '.pdf');
    }

    public function DatabasePageDestroy($id)
    {
        $dataupt = Upt::find($id);

        if (!$dataupt) {
            return redirect()->route('DbVpas')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama UPT tanpa suffix (VpasReg) untuk pengecekan
        $namaUptBase = $this->removeVpasRegSuffix($dataupt->namaupt);

        // Hapus data yang dipilih
        $dataupt->delete();

        // Update nama UPT yang tersisa berdasarkan jumlah data
        $this->updateUptNamesBySuffix($namaUptBase);

        return redirect()->route('DbVpas')->with('success', 'Data berhasil dihapus!');
    }
}

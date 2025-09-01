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
        $dataponpes = Ponpes::all();
        return view('user.indexPonpes', compact('dataponpes'));
    }

    public function UserPageStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|max:255',
                'nama_wilayah' => 'required|string',
                'tipe' => 'required|array|min:1', // Validasi array dengan minimal 1 pilihan
                'tipe.*' => 'in:reguler,vtren', // Validasi setiap element array harus reguler atau vtren
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi',
                'tipe.required' => 'Tipe harus dipilih minimal satu',
                'tipe.array' => 'Tipe harus berupa array',
                'tipe.min' => 'Pilih minimal satu tipe',
                'tipe.*.in' => 'Tipe hanya boleh reguler atau vtren',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data tipe yang dipilih
        $selectedTypes = $request->tipe;
        $createdRecords = [];

        // Bersihkan nama Ponpes dari suffix ganda yang mungkin ada
        $cleanNamaPonpes = $this->removeVtrenRegSuffix($request->nama_ponpes);

        // Tentukan nama Ponpes berdasarkan jumlah tipe yang dipilih
        $namaPonpes = $cleanNamaPonpes;
        if (count($selectedTypes) == 2 && in_array('reguler', $selectedTypes) && in_array('vtren', $selectedTypes)) {
            $namaPonpes = $cleanNamaPonpes . ' (VtrenReg)';
        }

        // Validasi manual untuk kombinasi nama Ponpes + tipe
        foreach ($selectedTypes as $tipeValue) {
            $existingRecord = Ponpes::where('nama_ponpes', $namaPonpes)
                ->where('tipe', $tipeValue)
                ->first();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Data Ponpes '{$namaPonpes}' dengan tipe '{$tipeValue}' sudah ada!");
            }
        }

        // Loop untuk setiap tipe yang dipilih
        foreach ($selectedTypes as $tipeValue) {
            // Buat record baru untuk setiap tipe
            $dataPonpes = [
                'nama_ponpes' => $namaPonpes,
                'nama_wilayah' => $request->nama_wilayah,
                'tipe' => $tipeValue,
                'tanggal' => Carbon::now()->format('Y-m-d'),
            ];

            $newRecord = Ponpes::create($dataPonpes);
            $createdRecords[] = $tipeValue;
        }

        // Berikan pesan berdasarkan hasil
        if (count($createdRecords) > 0) {
            $message = 'Data Ponpes berhasil ditambahkan untuk tipe: ' . implode(', ', $createdRecords);
            return redirect()->route('ponpes.UserPage')->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data Ponpes');
        }
    }

    public function ListDataPonpesUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // Field Wajib
                'nama_ponpes' => 'required|string|max:255',
                'nama_wilayah' => 'required|string|max:255',
                'tipe' => 'required|string|max:255',

                // Data Opsional
                'pic_ponpes' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',
                'jumlah_wbp' => 'nullable|integer|min:0',
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
                'pin_tes' => 'nullable|string|max:255', // Changed from integer to string
            ],
            [
                // Validation messages (same as before)
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_wilayah.required' => 'Nama Daerah harus diisi.',
                'pic_ponpes.string' => 'PIC Ponpes harus berupa teks.',
                'no_telpon.string' => 'Nomor telepon harus berupa teks.',
                'no_telpon.regex' => 'Format nomor telepon tidak valid.',
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

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada data yang dimasukkan. Silakan periksa kembali.');
        }

        try {
            // Find the main Ponpes record
            $ponpes = Ponpes::findOrFail($id);

            // Update main Ponpes data (fields that exist in data_ponpes table)
            $mainData = [
                'nama_ponpes' => $request->nama_ponpes,
                'nama_wilayah' => $request->nama_wilayah,
                'tipe' => $request->tipe,
            ];

            $ponpes->update($mainData);

            // Prepare optional data for data_opsional_ponpes table
            $optionalData = [
                'pic_ponpes' => $request->pic_ponpes,
                'no_telpon' => $request->no_telpon,
                'alamat' => $request->alamat,
                'jumlah_wbp' => $request->jumlah_wbp,
                'jumlah_line_reguler' => $request->jumlah_line_reguler,
                'provider_internet' => $request->provider_internet,
                'kecepatan_internet' => $request->kecepatan_internet,
                'tarif_wartel_reguler' => $request->tarif_wartel_reguler,
                'status_wartel' => $request->status_wartel === 'Aktif' ? 1 : 0,
                'akses_topup_pulsa' => !empty($request->akses_topup_pulsa) ? 1 : 0,
                'password_topup' => $request->password_topup,
                'akses_download_rekaman' => !empty($request->akses_download_rekaman) ? 1 : 0,
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

            // Update or create the optional data
            if ($ponpes->dataOpsional) {
                // Update existing optional data
                $ponpes->dataOpsional->update($optionalData);
            } else {
                // Create new optional data record
                $ponpes->dataOpsional()->create($optionalData);
            }

            return redirect()->back()->with('success', 'Semua data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($id, $request) {
                        // Cek apakah ada record lain dengan nama yang sama dan tipe yang sama
                        $existingRecord = Ponpes::where('nama_ponpes', $value)
                            ->where('id', '!=', $id)
                            ->where('tipe', $request->tipe)
                            ->first();

                        if ($existingRecord) {
                            $fail("Nama Ponpes '{$value}' dengan tipe '{$request->tipe}' sudah ada.");
                        }
                    }
                ],
                'nama_wilayah' => 'required|string',
                'tipe' => 'required|string|in:reguler,vtren', // Hanya terima reguler atau vtren
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi',
                'tipe.required' => 'Tipe harus diisi',
                'tipe.in' => 'Tipe hanya boleh reguler atau vtren',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataPonpes = Ponpes::findOrFail($id);
        $dataPonpes->nama_ponpes = $request->nama_ponpes;
        $dataPonpes->nama_wilayah = $request->nama_wilayah;
        $dataPonpes->tipe = $request->tipe;
        $dataPonpes->save();

        return redirect()->route('ponpes.UserPage')->with('success', 'Data Ponpes berhasil diupdate!');
    }

    public function PonpesPageDestroy($id)
    {
        $dataPonpes = Ponpes::find($id);

        if (!$dataPonpes) {
            return redirect()->route('ponpes.UserPage')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama Ponpes tanpa suffix (VtrenReg) untuk pengecekan
        $namaPonpesBase = $this->removeVtrenRegSuffix($dataPonpes->nama_ponpes);

        // Hapus data yang dipilih
        $dataPonpes->delete();

        // Update nama Ponpes yang tersisa berdasarkan jumlah data
        $this->updatePonpesNamesBySuffix($namaPonpesBase);

        return redirect()->route('ponpes.UserPage')->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Helper method untuk menghilangkan suffix (VtrenReg) dari nama Ponpes
     * Menghapus semua kemungkinan suffix ganda
     */
    private function removeVtrenRegSuffix($namaPonpes)
    {
        // Hapus semua kemungkinan suffix (VtrenReg) yang mungkin ganda
        return preg_replace('/\s*\(VtrenReg\)+/', '', $namaPonpes);
    }

    /**
     * Helper method untuk mengecek apakah Ponpes memiliki kedua tipe (reguler dan vtren)
     */
    private function hasMultipleTypes($namaPonpes)
    {
        $namaPonpesBase = $this->removeVtrenRegSuffix($namaPonpes);

        $regulerExists = Ponpes::where('nama_ponpes', 'LIKE', $namaPonpesBase . '%')
            ->where('tipe', 'reguler')
            ->exists();

        $vtrenExists = Ponpes::where('nama_ponpes', 'LIKE', $namaPonpesBase . '%')
            ->where('tipe', 'vtren')
            ->exists();

        return $regulerExists && $vtrenExists;
    }

    /**
     * Helper method untuk update nama Ponpes berdasarkan jumlah tipe
     */
    private function updatePonpesNamesBySuffix($namaPonpesBase)
    {
        $relatedData = Ponpes::where('nama_ponpes', 'LIKE', $namaPonpesBase . '%')->get();

        // Jika ada 2 atau lebih data dengan nama base yang sama, pastikan ada suffix
        if ($relatedData->count() >= 2) {
            foreach ($relatedData as $data) {
                if (!str_contains($data->nama_ponpes, '(VtrenReg)')) {
                    $data->update([
                        'nama_ponpes' => $namaPonpesBase . ' (VtrenReg)'
                    ]);
                }
            }
        }
        // Jika hanya ada 1 data tersisa, hapus suffix
        elseif ($relatedData->count() == 1) {
            $remainingData = $relatedData->first();
            if (str_contains($remainingData->nama_ponpes, '(VtrenReg)')) {
                $remainingData->update([
                    'nama_ponpes' => $namaPonpesBase
                ]);
            }
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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataBaseController extends Controller
{
    public function DataBaseUpt()
    {
        return view('db.indexUpt');
    }

    public function DataBasePonpes()
    {
        return view('db.indexPonpes');
    }

    public function DbPks()
    {
        return view('db.pks.index');
    }

    public function DbCreatePks()
    {
        return view('db.pks.create');
    }

    public function PksStore(Request $request)
    {
        dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'namaUpt' => 'required',
                'kanwil' => 'required',
                'file' => 'required',
            ]
        );

        dd($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }



    // ====================================== VPAS

    public function DbVpas(Request $request)
    {
        $data = User::all();
        return view('db.vpas.indexVpas', compact('data'));
    }
    // Untuk Memunculkan List Data Vpas Dari User

    // public function ListDataUpdate(Request $request, $id)
    // {
    //     $data = User::findOrFail($id);
    //     $data->update($request->all());

    //     return redirect()->back();
    // }


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
                'no_telpon' => 'nullable|integer|max:20', // ubah ke string untuk nomor telpon
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
                'internet_protocol' => 'nullable|string|max:255', // IP Address
                'vpn_user' => 'nullable|string|max:255',
                'vpn_password' => 'nullable|string|max:255',
                'jenis_vpn' => 'nullable|string|max:255',

                // Extension Reguler
                'jumlah_extension' => 'nullable|integer|min:0',
                'no_extension_1' => 'nullable|string|max:255',
                'no_extension_2' => 'nullable|string|max:255',
                'pin_tes' => 'nullable|integer|min:0',
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
                'tanggal.date' => 'Format tanggal harus sesuai (YYYY-MM-DD)',
                'no_telpon.integer' => 'Nomor telepon harus berupa angka',
            ]

        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Jika validasi berhasil, lakukan update
        try {
            $data = User::findOrFail($id);
            $data->update($request->all());

            return redirect()->back()->with('success', 'Data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Ponpes;
use App\Models\Provider; // Tambahkan import model Provider

class UserController extends Controller
{

    // ============================================================= METHOD DATA UPT
    public function UserPageDestroy($id)
    {
        $dataupt = User::find($id);
        $dataupt->delete();
        return redirect()->route('UserPage');
    }

    public function UserPage()
    {
        $dataupt = User::all();
        return view('user.indexUser', compact('dataupt'));
    }

    public function UserCreate()
    {
        return view('user.createUser');
    }

    public function UserPageStore(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string|unique:users,namaupt',
                'kanwil' => 'required|string',
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Perbaikan: gunakan array untuk data yang akan disimpan
        $dataupt = [
            'namaupt' => $request->namaupt,
            'kanwil' => $request->kanwil,
            'tanggal' => Carbon::now()->format('Y-m-d'), // Format tanggal yang konsisten
        ];

        User::create($dataupt);

        return redirect()->route('UserPage')->with('success', 'Data UPT berhasil ditambahkan!');
    }

    public function UserPageEdit(Request $request, $id)
    {
        $dataupt = User::find($id);
        return view('user.editUser', compact('dataupt')); // Return view yang sesuai
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string|unique:users,namaupt,' . $id,
                'kanwil' => 'required|string', // Perbaikan: hapus . $id yang tidak perlu
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataupt = User::findOrFail($id); // Gunakan findOrFail untuk error handling yang lebih baik
        $dataupt->namaupt = $request->namaupt;
        $dataupt->kanwil = $request->kanwil;
        $dataupt->save();
        
        return redirect()->route('UserPage')->with('success', 'Data UPT berhasil diupdate!');
    }
    // ============================================================= METHOD DATA UPT

    // ============================================================= METHOD DATA PONPES
    public function DataPonpes()
    {
        $dataponpes = Ponpes::all();
        return view('user.indexPonpes', compact('dataponpes'));
    }

    public function PonpesPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|max:255',
                'nama_wilayah' => 'required|string|max:255',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat data array untuk disimpan
        $dataponpes = [
            'nama_ponpes' => $request->nama_ponpes,
            'nama_wilayah' => $request->nama_wilayah,
            'tanggal' => Carbon::now()->format('Y-m-d'),
        ];

        Ponpes::create($dataponpes);
        return redirect()->back()->with('success', 'Data ponpes berhasil ditambahkan!');
    }

    public function PonpesPageDestroy($id)
    {
        $dataponpes = Ponpes::findOrFail($id);
        $dataponpes->delete();
        return redirect()->route('DataPonpes')->with('success', 'Data ponpes berhasil dihapus!');
    }

    public function PonpesPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string|max:255',
                'nama_wilayah' => 'required|string|max:255',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi.',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataponpes = Ponpes::findOrFail($id);
        $dataponpes->update([
            'nama_ponpes' => $request->nama_ponpes,
            'nama_wilayah' => $request->nama_wilayah,
        ]);
        
        return redirect()->back()->with('success', 'Data ponpes berhasil diupdate!');
    }
    // ============================================================= METHOD DATA PONPES

    // ============================================================= METHOD DATA PROVIDER
    public function DataProvider()
    {
        $dataprovider = Provider::all();
        return view('user.indexProvider', compact('dataprovider'));
    }

    public function ProviderPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_provider' => 'required|string|max:255',
                'jenis_vpn' => 'required|string|max:255',
            ],
            [
                'nama_provider.required' => 'Nama Provider harus diisi.',
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat data array untuk disimpan
        $dataprovider = [
            'nama_provider' => $request->nama_provider,
            'jenis_vpn' => $request->jenis_vpn,
            'tanggal_update' => Carbon::now()->format('Y-m-d'),
        ];

        Provider::create($dataprovider);
        return redirect()->back()->with('success', 'Data provider berhasil ditambahkan!');
    }

    public function ProviderPageDestroy($id)
    {
        $dataprovider = Provider::findOrFail($id);
        $dataprovider->delete();
        return redirect()->route('DataProvider')->with('success', 'Data provider berhasil dihapus!');
    }

    public function ProviderPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_provider' => 'required|string|max:255',
                'jenis_vpn' => 'required|string|max:255',
            ],
            [
                'nama_provider.required' => 'Nama Provider harus diisi.',
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataprovider = Provider::findOrFail($id);
        $dataprovider->update([
            'nama_provider' => $request->nama_provider,
            'jenis_vpn' => $request->jenis_vpn,
            'tanggal_update' => Carbon::now()->format('Y-m-d'),
        ]);
        
        return redirect()->back()->with('success', 'Data provider berhasil diupdate!');
    }
    // ============================================================= METHOD DATA PROVIDER
}
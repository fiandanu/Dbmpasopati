<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
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
        return redirect()->route('provider.DataProvider')->with('success', 'Data provider berhasil dihapus!');
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
}

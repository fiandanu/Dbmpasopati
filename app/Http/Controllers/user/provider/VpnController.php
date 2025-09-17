<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Vpn;
use App\Models\user\Provider;
use Illuminate\Support\Facades\Validator;

class VpnController extends Controller
{
    public function index()
    {
        $dataprovider = Provider::all();
        $datavpn = Vpn::all();
        return view('user.indexProvider', compact('dataprovider', 'datavpn'));
    }
    public function VpnPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_vpn' => 'required|string|max:255',
            ],
            [
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datavpn = [
            'jenis_vpn' => $request->jenis_vpn,
        ];

        Vpn::create($datavpn);
        return redirect()->back()->with('success', 'Data VPN berhasil ditambahkan!');
    }


    public function VpnPageDestroy($id)
    {
        $datavpn = Vpn::findOrFail($id);
        $datavpn->delete();
        return redirect()->back()->with('success', 'Data VPN berhasil dihapus!');
    }

    public function VpnPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_vpn' => 'required|string|max:255',
            ],
            [
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datavpn = Vpn::findOrFail($id);
        $datavpn->update([
            'jenis_vpn' => $request->jenis_vpn,
        ]);

        return redirect()->back()->with('success', 'Data VPN berhasil diupdate!');
    }
}

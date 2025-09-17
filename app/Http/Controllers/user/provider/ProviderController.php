<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Provider;
use App\Models\user\Vpn;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function index()
    {
        $dataprovider = Provider::all();
        $datavpn = Vpn::all();
        return view('user.indexProvider', compact('dataprovider', 'datavpn'));
    }

    public function ProviderPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_provider' => 'required|string|max:255',
            ],
            [
                'nama_provider.required' => 'Nama Provider harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataprovider = [
            'nama_provider' => $request->nama_provider,
        ];

        Provider::create($dataprovider);
        return redirect()->back()->with('success', 'Data provider berhasil ditambahkan!');
    }

    public function ProviderPageDestroy($id)
    {
        $dataprovider = Provider::findOrFail($id);
        $dataprovider->delete();
        return redirect()->back()->with('success', 'Data provider berhasil dihapus!');
    }

    public function ProviderPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_provider' => 'required|string|max:255',
            ],
            [
                'nama_provider.required' => 'Nama Provider harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataprovider = Provider::findOrFail($id);
        $dataprovider->update([
            'nama_provider' => $request->nama_provider,
        ]);

        return redirect()->back()->with('success', 'Data provider berhasil diupdate!');
    }
}

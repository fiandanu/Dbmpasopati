<?php

namespace App\Http\Controllers\user\kendalapic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendala;
use App\Models\Pic;
use Illuminate\Support\Facades\Validator;

class KendalaController extends Controller
{
    public function index()
    {
        $datakendala = Kendala::all();
        $datapic = Pic::all();
        return view('user.indexKendalaPic', compact('datakendala', 'datapic'));
    }

    public function KendalaPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_kendala' => 'required|string|max:255',
            ],
            [
                'jenis_kendala.required' => 'Jenis Kendala harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakendala = [
            'jenis_kendala' => $request->jenis_kendala,
        ];

        Kendala::create($datakendala);
        return redirect()->back()->with('success', 'Data kendala berhasil ditambahkan!');
    }

    public function KendalaPageDestroy($id)
    {
        $datakendala = Kendala::findOrFail($id);
        $datakendala->delete();
        return redirect()->back()->with('success', 'Data kendala berhasil dihapus!');
    }

    public function KendalaPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_kendala' => 'required|string|max:255',
            ],
            [
                'jenis_kendala.required' => 'Jenis Kendala harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakendala = Kendala::findOrFail($id);
        $datakendala->update([
            'jenis_kendala' => $request->jenis_kendala,
        ]);

        return redirect()->back()->with('success', 'Data kendala berhasil diupdate!');
    }
}
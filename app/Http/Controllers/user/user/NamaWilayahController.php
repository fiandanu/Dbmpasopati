<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\NamaWilayah;
use App\Models\user\Kanwil;
use Illuminate\Support\Facades\Validator;

class NamaWilayahController extends Controller
{
    public function index()
    {
        $datakanwil = Kanwil::all();
        $datanamawilayah = NamaWilayah::all();
        return view('user.indexKanwilNamaWilayah', compact('datakanwil', 'datanamawilayah'));
    }

    public function NamaWilayahPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_wilayah' => 'required|string|max:255',
            ],
            [
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datanamawilayah = [
            'nama_wilayah' => $request->nama_wilayah,
        ];

        NamaWilayah::create($datanamawilayah);
        return redirect()->back()->with('success', 'Data Nama Wilayah berhasil ditambahkan!');
    }

    public function NamaWilayahPageDestroy($id)
    {
        $datanamawilayah = NamaWilayah::findOrFail($id);
        $datanamawilayah->delete();
        return redirect()->back()->with('success', 'Data Nama Wilayah berhasil dihapus!');
    }

    public function NamaWilayahPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_wilayah' => 'required|string|max:255',
            ],
            [
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datanamawilayah = NamaWilayah::findOrFail($id);
        $datanamawilayah->update([
            'nama_wilayah' => $request->nama_wilayah,
        ]);

        return redirect()->back()->with('success', 'Data Nama Wilayah berhasil diupdate!');
    }
}

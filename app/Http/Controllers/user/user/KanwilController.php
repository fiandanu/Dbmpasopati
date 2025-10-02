<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Kanwil;
use App\Models\user\NamaWilayah;
use Illuminate\Support\Facades\Validator;

class KanwilController extends Controller
{
    public function index()
    {
        $datakanwil = Kanwil::all();
        $datanamawilayah = NamaWilayah::all();
        return view('user.indexKanwilNamaWilayah', compact('datakanwil', 'datanamawilayah'));
    }

    public function KanwilPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kanwil' => 'required|string|max:255',
            ],
            [
                'kanwil.required' => 'Kanwil harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakanwil = [
            'kanwil' => $request->kanwil,
        ];

        Kanwil::create($datakanwil);
        return redirect()->back()->with('success', 'Data kanwil berhasil ditambahkan!');
    }

    public function KanwilPageDestroy($id)
    {
        $datakanwil = Kanwil::findOrFail($id);
        $datakanwil->delete();
        return redirect()->back()->with('success', 'Data kanwil berhasil dihapus!');
    }

    public function KanwilPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kanwil' => 'required|string|max:255',
            ],
            [
                'kanwil.required' => 'Kanwil harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakanwil = Kanwil::findOrFail($id);
        $datakanwil->update([
            'kanwil' => $request->kanwil,
        ]);

        return redirect()->back()->with('success', 'Data kanwil berhasil diupdate!');
    }
}

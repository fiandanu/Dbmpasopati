<?php

namespace App\Http\Controllers\user\kendalapic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Pic;
use App\Models\user\Kendala;
use Illuminate\Support\Facades\Validator;

class PicController extends Controller
{
    public function index()
    {
        $datakendala = Kendala::all();
        $datapic = Pic::all();
        return view('user.indexKendalaPic', compact('datakendala', 'datapic'));
    }
    
    public function PicPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_pic' => 'required|string|max:255',
            ],
            [
                'nama_pic.required' => 'Nama PIC harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datapic = [
            'nama_pic' => $request->nama_pic,
        ];

        Pic::create($datapic);
        return redirect()->back()->with('success', 'Data PIC berhasil ditambahkan!');
    }

    public function PicPageDestroy($id)
    {
        $datapic = Pic::findOrFail($id);
        $datapic->delete();
        return redirect()->back()->with('success', 'Data PIC berhasil dihapus!');
    }

    public function PicPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_pic' => 'required|string|max:255',
            ],
            [
                'nama_pic.required' => 'Nama PIC harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datapic = Pic::findOrFail($id);
        $datapic->update([
            'nama_pic' => $request->nama_pic,
        ]);

        return redirect()->back()->with('success', 'Data PIC berhasil diupdate!');
    }
}
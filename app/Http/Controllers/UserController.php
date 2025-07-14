<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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

        // $user = new user();
        $dataupt['namaupt'] = $request->namaupt;
        $dataupt['kanwil'] = $request->kanwil;
        $dataupt['tanggal'] = Carbon::now();

        User::create($dataupt);

        return redirect()->route('UserPage');
    }


    public function UserPageEdit(Request $request, $id)
    {
        $dataupt = User::find($id);

        dd($dataupt);

        // return view('user.indexUser', compact('dataupt'));
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string|unique:users,namaupt,' . $id,
                'kanwil' => 'required|string' . $id,
                // Dan data detail yang lain   
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataupt = User::find($id);
        $dataupt->namaupt = $request->namaupt;
        $dataupt->kanwil = $request->kanwil;
        $dataupt->save();
        return redirect()->route('UserPage');
    }
    // ============================================================= METHOD DATA UPT




    // ============================================================= METHOD DATA PONPES

    public function DataPonpes()
    {
        $data = User::all();
        return view('user.indexPonpes');
    }

}

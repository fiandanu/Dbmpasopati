<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    public function UserPageDestroy($id)
    {
        $data = User::find($id);
        $data->delete();
        return redirect()->route('UserPage');
    }

    public function UserPage()
    {
        $data = User::all();
        return view('user.indexUser', compact('data'));
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
                'kanwil' => 'required|string|unique:users,kanwil',
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
        $data['namaupt'] = $request->namaupt;
        $data['kanwil'] = $request->kanwil;
        $data['tanggal'] = Carbon::now();

        User::create($data);

        return redirect()->route('UserPage');
    }


    public function UserPageEdit(Request $request, $id)
    {
        $data = User::find($id);

        dd($data);

        // return view('user.indexUser', compact('data'));
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'namaupt' => 'required|string|unique:users,namaupt,' . $id,
                'kanwil' => 'required|string|unique:users,kanwil,' . $id,
            ],
            [
                'namaupt.required' => 'Nama UPT harus diisi',
                'kanwil.required' => 'Kanwil harus diisi',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = User::find($id);
        $data->namaupt = $request->namaupt;
        $data->kanwil = $request->kanwil;
        $data->save();
        return redirect()->route('UserPage');
    }
}

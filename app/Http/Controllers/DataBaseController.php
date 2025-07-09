<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataBaseController extends Controller
{
    public function DataBaseUpt()
    {
        return view('db.indexUpt');
    }

    public function DataBasePonpes()
    {
        return view('db.indexPonpes');
    }

    public function DbPks()
    {
        return view('db.pks.index');
    }

    public function DbCreatePks()
    {
        return view('db.pks.create');
    }

    public function PksStore(Request $request)
    {
        dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'namaUpt' => 'required',
                'kanwil' => 'required',
                'file' => 'required',
            ]
        );

        dd($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
}

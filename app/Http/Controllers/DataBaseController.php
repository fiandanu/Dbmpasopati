<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataBaseController extends Controller
{
    public function DbVpas()
    {
        // Untuk sementara return view kosong atau redirect
        return view('db.vpas.indexVpas');
        // atau
        // return redirect()->route('DataBaseUpt')->with('info', 'Fitur VPAS sedang dalam pengembangan');
    }
}

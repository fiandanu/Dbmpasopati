<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataBaseController extends Controller
{
    public function DbVpas()
    {
        return view('db.upt.reguler.indexUpt');
    }
}

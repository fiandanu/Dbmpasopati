<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataBaseController extends Controller
{
    public function DataBaseUpt(){
        return view('db.indexUpt');
    }

    public function DataBasePonpes(){
        return view('db.indexPonpes');
    }
}

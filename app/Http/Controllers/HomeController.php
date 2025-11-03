<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.dashboard');
    }

    public function GrafikClient()
    {
        return view('mclient.indexGrafik');
    }

    public function KomplainPonpes()
    {
        return view('mclient.indexPonpes');
    }

    // public function PencatatanKartu(){
    //     return view('mclient.indexCard');
    // }
}

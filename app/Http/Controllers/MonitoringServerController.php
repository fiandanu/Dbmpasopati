<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringServerController extends Controller
{
    public function GrafikServer(){
        return view('mserver.indexGrafik');
    }

    public function MonitoringUpt(){
        return view('mserver.indexUpt');
    }

    public function MonitoringPonpes(){
        return view('mserver.indexPonpes');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorialController extends Controller
{

    public function TutorialPonpes(){
        return view('tutorial.indexPonpes');
    }
    public function TutorialServer(){
        return view('tutorial.upt.server');
    }
    public function TutorialMicrotik(){
        return view('tutorial.mikrotik');
    }
}

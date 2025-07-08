<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function TutorialUpt(){
        return view('tutorial.indexUpt');
    }
    public function TutorialPonpes(){
        return view('tutorial.indexPonpes');
    }
    public function TutorialServer(){
        return view('tutorial.indexServer');
    }
    public function TutorialMicrotik(){
        return view('tutorial.indexMicrotik');
    }
}

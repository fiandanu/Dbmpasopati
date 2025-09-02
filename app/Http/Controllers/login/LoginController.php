<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class LoginController extends Controller
{
    public function login(): Factory|View
    {
        return view('auth.login');
    }
}

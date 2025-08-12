<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ponpes;
use App\Models\Provider;
use App\Models\Upt;
use Illuminate\Http\Request;

class PageUser extends Controller
{
    // 🔷 FUNGSI: TAMPILKAN DATA UPT
    public function UserPage()
    {
        $dataupt = Upt::all();
        return view('user.indexUser', compact('dataupt'));
    }

    // 🔷 FUNGSI: TAMPILKAN LIST KATEGOTI DATA UPT
    public function DbUpt()
    {
        return view('db.pageKategoriUpt');
    }

    // 🔷 FUNGSI: TAMPILKAN LIST KATEGOTI DATA PONPES
    public function DataBasePonpes()
    {
        return view('db.pageKategoriPonpes');
    }

}

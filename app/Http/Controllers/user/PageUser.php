<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Upt;
use App\Models\Ponpes;
use App\Models\DataOpsionalUpt;
use App\Models\DataOpsionalPonpes;
use App\Models\UploadFolder;
use App\Models\UploadFolderPonpes;

class PageUser extends Controller
{
    public function UserPage()
    {
        $dataupt = Upt::with(['dataOpsional', 'uploadFolder'])->get();
        return view('user.indexUser', compact('dataupt'));
    }

    public function DbUpt()
    {
        return view('db.pageKategoriUpt');
    }

    public function DataBasePonpes()
    {
        return view('db.pageKategoriPonpes');
    }

    public function UserPagePonpes()
    {
        $dataponpes = Ponpes::with(['dataOpsional', 'uploadFolder'])->get();
        return view('user.indexUserPonpes', compact('dataponpes'));
    }

    public function detailUpt($id)
    {
        $upt = Upt::with(['dataOpsional', 'uploadFolder'])->findOrFail($id);
        return view('user.detailUpt', compact('upt'));
    }

    public function detailPonpes($id)
    {
        $ponpes = Ponpes::with(['dataOpsional', 'uploadFolder'])->findOrFail($id);
        return view('user.detailPonpes', compact('ponpes'));
    }

    public function uptByKanwil($kanwil)
    {
        $dataupt = Upt::where('kanwil', $kanwil)
                      ->with(['dataOpsional', 'uploadFolder'])
                      ->get();
        return view('user.uptByKanwil', compact('dataupt', 'kanwil'));
    }

    public function ponpesByWilayah($wilayah)
    {
        $dataponpes = Ponpes::where('nama_wilayah', $wilayah)
                             ->with(['dataOpsional', 'uploadFolder'])
                             ->get();
        return view('user.ponpesByWilayah', compact('dataponpes', 'wilayah'));
    }

    public function searchUpt()
    {
        $search = request('search');
        $dataupt = Upt::where('namaupt', 'like', '%' . $search . '%')
                      ->orWhere('kanwil', 'like', '%' . $search . '%')
                      ->orWhere('tipe', 'like', '%' . $search . '%')
                      ->with(['dataOpsional', 'uploadFolder'])
                      ->get();
        
        return view('user.indexUser', compact('dataupt'));
    }

    public function searchPonpes()
    {
        $search = request('search');
        $dataponpes = Ponpes::where('nama_ponpes', 'like', '%' . $search . '%')
                            ->orWhere('nama_wilayah', 'like', '%' . $search . '%')
                            ->orWhere('tipe', 'like', '%' . $search . '%')
                            ->with(['dataOpsional', 'uploadFolder'])
                            ->get();
        
        return view('user.indexUserPonpes', compact('dataponpes'));
    }
}
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
    // 🔷 FUNGSI: TAMPILKAN DATA UPT
    public function UserPage()
    {
        // Ambil data UPT dengan relasi data opsional dan upload folder
        $dataupt = Upt::with(['dataOpsional', 'uploadFolder'])->get();
        return view('user.indexUser', compact('dataupt'));
    }

    // 🔷 FUNGSI: TAMPILKAN LIST KATEGORI DATA UPT
    public function DbUpt()
    {
        return view('db.pageKategoriUpt');
    }

    // 🔷 FUNGSI: TAMPILKAN LIST KATEGORI DATA PONPES
    public function DataBasePonpes()
    {
        return view('db.pageKategoriPonpes');
    }

    // 🔷 FUNGSI: TAMPILKAN DATA PONPES
    public function UserPagePonpes()
    {
        // Ambil data Ponpes dengan relasi data opsional dan upload folder
        $dataponpes = Ponpes::with(['dataOpsional', 'uploadFolder'])->get();
        return view('user.indexUserPonpes', compact('dataponpes'));
    }

    // 🔷 FUNGSI: TAMPILKAN DETAIL UPT
    public function detailUpt($id)
    {
        $upt = Upt::with(['dataOpsional', 'uploadFolder'])->findOrFail($id);
        return view('user.detailUpt', compact('upt'));
    }

    // 🔷 FUNGSI: TAMPILKAN DETAIL PONPES
    public function detailPonpes($id)
    {
        $ponpes = Ponpes::with(['dataOpsional', 'uploadFolder'])->findOrFail($id);
        return view('user.detailPonpes', compact('ponpes'));
    }

    // 🔷 FUNGSI: TAMPILKAN DATA UPT BERDASARKAN KANWIL
    public function uptByKanwil($kanwil)
    {
        $dataupt = Upt::where('kanwil', $kanwil)
                      ->with(['dataOpsional', 'uploadFolder'])
                      ->get();
        return view('user.uptByKanwil', compact('dataupt', 'kanwil'));
    }

    // 🔷 FUNGSI: TAMPILKAN DATA PONPES BERDASARKAN WILAYAH
    public function ponpesByWilayah($wilayah)
    {
        $dataponpes = Ponpes::where('nama_wilayah', $wilayah)
                             ->with(['dataOpsional', 'uploadFolder'])
                             ->get();
        return view('user.ponpesByWilayah', compact('dataponpes', 'wilayah'));
    }

    // 🔷 FUNGSI: SEARCH UPT
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

    // 🔷 FUNGSI: SEARCH PONPES
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
<?php

namespace App\Http\Controllers\user\ponpes\spp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ponpes;
use App\Models\Provider;
use Illuminate\Support\Facades\Storage;

class SppController extends Controller
{
    public function ListDataSpp(Request $request)
    {
        $query = Ponpes::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('namaupt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status_wartel', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        $providers = Provider::all();
        return view('db.ponpes.spp.indexSpp', compact('data', 'providers'));
    }

    public function DatabasePageDestroy($id)
    {
        $dataupt = Ponpes::findOrFail($id);

        // Hapus semua file PDF yang terkait dengan user ini
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($dataupt->$column) && Storage::disk('public')->exists($dataupt->$column)) {
                Storage::disk('public')->delete($dataupt->$column);
            }
        }

        $dataupt->delete();
        return redirect()->route('spp.ListDataSpp')->with('success', 'Data berhasil dihapus');
    }

    public function viewUploadedPDF($id, $folder)
    {
        if (!in_array($folder, range(1, 10))) {
            return abort(400, 'Folder tidak valid.');
        }

        $user = Ponpes::findOrFail($id);
        $column = 'pdf_folder_' . $folder;

        // Cek apakah file ada dan path tidak kosong
        if (empty($user->$column)) {
            return abort(404, 'File PDF belum diupload untuk folder ' . $folder . '.');
        }

        if (!Storage::disk('public')->exists($user->$column)) {
            return abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(storage_path('app/public/' . $user->$column));
    }

    public function uploadFilePDF(Request $request, $id, $folder)
    {

        // dd($request->all());
        if (!in_array($folder, range(1, 10))) {
            return redirect()->back()->with('error', 'Folder tidak valid.');
        }

        // Validasi file PDF
        $request->validate([
            'uploaded_pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        if (!$request->hasFile('uploaded_pdf')) {
            return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
        }

        $user = Ponpes::findOrFail($id);
        $file = $request->file('uploaded_pdf');

        // Debug: Cek apakah file valid
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid!');
        }

        // Buat nama file unik
        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan ke dalam folder storage/app/public/uploads/pdf/spp/folder_{folder}
        $path = $file->storeAs('ponpes/spp/folder_' . $folder, $filename, 'public');

        // Simpan path ke database
        $column = 'pdf_folder_' . $folder;
        $user->$column = $path;
        $user->save();

        return redirect()->back()->with('success', 'PDF berhasil di-upload ke folder ' . $folder . '!');
    }

    public function deleteFilePDF($id, $folder)
    {
        if (!in_array($folder, range(1, 10))) {
            return redirect()->back()->with('error', 'Folder tidak valid.');
        }

        $user = Ponpes::findOrFail($id);
        $column = 'pdf_folder_' . $folder;

        if (empty($user->$column)) {
            return redirect()->back()->with('error', 'File PDF belum di-upload di folder ' . $folder . '.');
        }

        if (Storage::disk('public')->exists($user->$column)) {
            Storage::disk('public')->delete($user->$column);
        }

        $user->$column = null;
        $user->save();

        return redirect()->back()->with('success', 'File PDF di folder ' . $folder . ' berhasil dihapus');
    }
}

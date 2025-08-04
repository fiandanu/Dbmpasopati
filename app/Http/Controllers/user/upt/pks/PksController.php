<?php

namespace App\Http\Controllers\user\upt\pks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PksController extends Controller
{
    public function ListDataPks(Request $request)
    {
        $query = User::query();

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
        return view('db.upt.pks.indexPks', compact('data', 'providers'));
    }

    public function DatabasePageDestroy($id)
    {
        $dataupt = User::find($id);
        $dataupt->delete();
        return redirect()->route('pks.ListDataPks');
    }

    public function viewUploadedPDF($id)
    {
        $user = User::findOrFail($id);

        // Cek apakah file ada dan path tidak kosong
        if (empty($user->uploaded_pdf)) {
            return abort(404, 'File PDF belum diupload');
        }

        if (!Storage::disk('public')->exists($user->uploaded_pdf)) {
            return abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(storage_path('app/public/' . $user->uploaded_pdf));
    }

    public function uploadFilePDFPks(Request $request, $id)
    {
        // dd($request);

        if (!$request->hasFile('uploaded_pdf')) {
            return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
        }

        // Validasi file PDF
        $request->validate([
            'uploaded_pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = User::findOrFail($id);

        // Buat nama file unik
        $file = $request->file('uploaded_pdf');

        // Debug: Cek apakah file valid
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid!');
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan ke dalam folder storage/app/public/uploads/pdf
        $path = $file->storeAs('uploads/pdf', $filename, 'public');

        // Simpan path ke database
        $user->uploaded_pdf = $path;
        $user->save();

        return redirect()->back()->with('success', 'PDF berhasil di-upload!');
    }

    public function deleteFilePDF($id){
        $user = User::findOrFail($id);

        if (empty($user->uploaded_pdf)) {
            return redirect()->back()->with('error', 'File PDF belum di upload');
        }

        if (Storage::disk('public')->exists($user->uploaded_pdf)) {
            Storage::disk('public')->delete($user->uploaded_pdf);
        }

        $user->uploaded_pdf = null;
        $user->save();

        return redirect()->back()->with('success', 'File PDF berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers\user\ponpes\pks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ponpes;
use App\Models\Provider;
use Illuminate\Support\Facades\Storage;

class PksController extends Controller
{
    public function ListDataPks(Request $request)
    {
        $query = Ponpes::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tipe', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        $providers = Provider::all();
        return view('db.ponpes.pks.indexPks', compact('data', 'providers'));
    }

    public function DataBasePageDestroy($id)
    {
        $dataponpes = Ponpes::find($id);

        // Hapus file PDF jika ada
        if (!empty($dataponpes->uploaded_pdf) && Storage::disk('public')->exists($dataponpes->uploaded_pdf)) {
            Storage::disk('public')->delete($dataponpes->uploaded_pdf);
        }

        $dataponpes->delete();
        return redirect()->route('ponpes.pks.ListDataPks')->with('success', 'Data ponpes berhasil dihapus');
    }

    public function viewUploadedPDF($id)
    {
        $ponpes = Ponpes::findOrFail($id);

        // Cek apakah file ada dan path tidak kosong
        if (empty($ponpes->uploaded_pdf)) {
            return abort(404, 'File PDF belum diupload');
        }

        if (!Storage::disk('public')->exists($ponpes->uploaded_pdf)) {
            return abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(storage_path('app/public/' . $ponpes->uploaded_pdf));
    }

    public function uploadFilePDFPonpesPks(Request $request, $id)
    {
        // dd($request);

        if (!$request->hasFile('uploaded_pdf')) {
            return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
        }

        // Validasi file PDF
        $request->validate([
            'uploaded_pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        $ponpes = Ponpes::findOrFail($id);

        // Buat nama file unik
        $file = $request->file('uploaded_pdf');

        // Debug: Cek apakah file valid
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid!');
        }

        // Hapus file lama jika ada
        if (!empty($ponpes->uploaded_pdf) && Storage::disk('public')->exists($ponpes->uploaded_pdf)) {
            Storage::disk('public')->delete($ponpes->uploaded_pdf);
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan ke dalam folder storage/app/public/uploads/pdf/ponpes
        $path = $file->storeAs('ponpes/pks', $filename, 'public');

        // Simpan path ke database
        $ponpes->uploaded_pdf = $path;
        $ponpes->save();

        return redirect()->back()->with('success', 'PDF berhasil di-upload!');
    }

    public function deleteFilePDF($id)
    {
        $ponpes = Ponpes::findOrFail($id);

        if (empty($ponpes->uploaded_pdf)) {
            return redirect()->back()->with('error', 'File PDF belum di upload');
        }

        if (Storage::disk('public')->exists($ponpes->uploaded_pdf)) {
            Storage::disk('public')->delete($ponpes->uploaded_pdf);
        }

        $ponpes->uploaded_pdf = null;
        $ponpes->save();

        return redirect()->back()->with('success', 'File PDF berhasil dihapus');
    }
}

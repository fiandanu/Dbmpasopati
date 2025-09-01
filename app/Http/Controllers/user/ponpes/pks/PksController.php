<?php

namespace App\Http\Controllers\user\ponpes\pks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ponpes;
use App\Models\Provider;
use App\Models\UploadFolderPonpes;
use Illuminate\Support\Facades\Storage;

class PksController extends Controller
{
    public function ListDataPks(Request $request)
    {
        $query = Ponpes::with('uploadFolder'); // Load relasi upload folder

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
        $dataponpes = Ponpes::with('uploadFolder')->find($id);

        if ($dataponpes) {
            // Hapus juga data upload folder terkait
            if ($dataponpes->uploadFolder) {
                $uploadFolder = $dataponpes->uploadFolder;
                
                // Hapus uploaded_pdf jika ada
                if ($uploadFolder->uploaded_pdf && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
                }
                
                // Hapus pdf folder files jika ada (1-10)
                for ($i = 1; $i <= 10; $i++) {
                    $pdfField = "pdf_folder_$i";
                    if ($uploadFolder->$pdfField && Storage::disk('public')->exists($uploadFolder->$pdfField)) {
                        Storage::disk('public')->delete($uploadFolder->$pdfField);
                    }
                }
                
                $uploadFolder->delete();
            }
            
            $dataponpes->delete();
        }

        return redirect()->route('ponpes.pks.ListDataPks')->with('success', 'Data ponpes berhasil dihapus');
    }

    public function viewUploadedPDF($id)
    {
        $ponpes = Ponpes::with('uploadFolder')->findOrFail($id);

        // Cek apakah ada upload folder dan file PDF
        if (!$ponpes->uploadFolder || empty($ponpes->uploadFolder->uploaded_pdf)) {
            return abort(404, 'File PDF belum diupload');
        }

        if (!Storage::disk('public')->exists($ponpes->uploadFolder->uploaded_pdf)) {
            return abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(storage_path('app/public/' . $ponpes->uploadFolder->uploaded_pdf));
    }

    public function uploadFilePDFPonpesPks(Request $request, $id)
    {
        if (!$request->hasFile('uploaded_pdf')) {
            return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
        }

        // Validasi file PDF
        $request->validate([
            'uploaded_pdf' => 'required|file|mimes:pdf|max:5120', // 5MB
        ]);

        $ponpes = Ponpes::findOrFail($id);

        // Buat nama file unik
        $file = $request->file('uploaded_pdf');

        // Debug: Cek apakah file valid
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid!');
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan ke dalam folder storage/app/public/uploads/pdf/ponpes
        $path = $file->storeAs('ponpes/pks', $filename, 'public');

        // Cari atau buat record upload folder
        $uploadFolder = UploadFolderPonpes::firstOrCreate(
            ['ponpes_id' => $ponpes->id],
            ['uploaded_pdf' => null]
        );

        // Hapus file lama jika ada
        if ($uploadFolder->uploaded_pdf && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
            Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
        }

        // Update path di database
        $uploadFolder->uploaded_pdf = $path;
        $uploadFolder->save();

        return redirect()->back()->with('success', 'PDF berhasil di-upload!');
    }

    public function deleteFilePDF($id)
    {
        $ponpes = Ponpes::with('uploadFolder')->findOrFail($id);

        if (!$ponpes->uploadFolder || empty($ponpes->uploadFolder->uploaded_pdf)) {
            return redirect()->back()->with('error', 'File PDF belum di upload');
        }

        $uploadFolder = $ponpes->uploadFolder;

        if (Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
            Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
        }

        $uploadFolder->uploaded_pdf = null;
        $uploadFolder->save();

        return redirect()->back()->with('success', 'File PDF berhasil dihapus');
    }
}

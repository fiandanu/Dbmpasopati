<?php

namespace App\Http\Controllers\user\upt\pks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\user\Provider;
use App\Models\user\Upt;
use App\Models\db\UploadFolderUpt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PksController extends Controller
{
    public function ListDataPks(Request $request)
    {
        $query = Upt::with('uploadFolder'); // Load relasi upload folder

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
        $dataupt = Upt::find($id);
        
        // Hapus juga data upload folder terkait
        if ($dataupt && $dataupt->uploadFolder) {
            // Hapus file-file yang ada
            $uploadFolder = $dataupt->uploadFolder;
            
            // Hapus uploaded_pdf jika ada
            if ($uploadFolder->uploaded_pdf && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
            }
            
            // Hapus pdf folder files jika ada
            for ($i = 1; $i <= 10; $i++) {
                $pdfField = "pdf_folder_$i";
                if ($uploadFolder->$pdfField && Storage::disk('public')->exists($uploadFolder->$pdfField)) {
                    Storage::disk('public')->delete($uploadFolder->$pdfField);
                }
            }
            
            $uploadFolder->delete();
        }
        
        $dataupt->delete();
        return redirect()->route('pks.ListDataPks')->with('success', 'Data berhasil dihapus!');
    }

    public function viewUploadedPDF($id)
    {
        $upt = Upt::with('uploadFolder')->findOrFail($id);
        
        // Cek apakah ada upload folder dan file PDF
        if (!$upt->uploadFolder || empty($upt->uploadFolder->uploaded_pdf)) {
            return abort(404, 'File PDF belum diupload');
        }

        if (!Storage::disk('public')->exists($upt->uploadFolder->uploaded_pdf)) {
            return abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(storage_path('app/public/' . $upt->uploadFolder->uploaded_pdf));
    }

    public function uploadFilePDFPks(Request $request, $id)
    {
        if (!$request->hasFile('uploaded_pdf')) {
            return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
        }

        // Validasi file PDF
        $request->validate([
            'uploaded_pdf' => 'required|file|mimes:pdf|max:5120', // 5MB
        ]);

        $upt = Upt::findOrFail($id);

        // Buat nama file unik
        $file = $request->file('uploaded_pdf');

        // Debug: Cek apakah file valid
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid!');
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan ke dalam folder storage/app/public/uploads/pdf
        $path = $file->storeAs('upt/pks', $filename, 'public');

        // Cari atau buat record upload folder
        $uploadFolder = UploadFolderUpt::firstOrCreate(
            ['upt_id' => $upt->id],
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
        $upt = Upt::with('uploadFolder')->findOrFail($id);

        if (!$upt->uploadFolder || empty($upt->uploadFolder->uploaded_pdf)) {
            return redirect()->back()->with('error', 'File PDF belum di upload');
        }

        $uploadFolder = $upt->uploadFolder;

        if (Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
            Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
        }

        $uploadFolder->uploaded_pdf = null;
        $uploadFolder->save();

        return redirect()->back()->with('success', 'File PDF berhasil dihapus');
    }
}
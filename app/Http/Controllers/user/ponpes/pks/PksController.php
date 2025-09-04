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
        $query = Ponpes::with('uploadFolder');

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

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
            if ($dataponpes->uploadFolder) {
                $uploadFolder = $dataponpes->uploadFolder;
                
                if ($uploadFolder->uploaded_pdf && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
                    Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
                }
                
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

        $request->validate([
            'uploaded_pdf' => 'required|file|mimes:pdf|max:5120',
        ]);

        $ponpes = Ponpes::findOrFail($id);

        $file = $request->file('uploaded_pdf');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid!');
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs('ponpes/pks', $filename, 'public');

        $uploadFolder = UploadFolderPonpes::firstOrCreate(
            ['ponpes_id' => $ponpes->id],
            ['uploaded_pdf' => null]
        );

        if ($uploadFolder->uploaded_pdf && Storage::disk('public')->exists($uploadFolder->uploaded_pdf)) {
            Storage::disk('public')->delete($uploadFolder->uploaded_pdf);
        }

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

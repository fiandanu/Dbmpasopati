<?php

namespace App\Http\Controllers\user\ponpes\spp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\user\Ponpes;
use App\Models\user\Provider;
use App\Models\db\UploadFolderPonpes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SppController extends Controller
{
    public function ListDataSpp(Request $request)
    {
        $query = Ponpes::with('uploadFolder');

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        $providers = Provider::all();
        return view('db.ponpes.spp.indexSpp', compact('data', 'providers'));
    }

    public function DatabasePageDestroy($id)
    {
        try {
            $dataPonpes = Ponpes::findOrFail($id);
            
            $uploadFolder = UploadFolderPonpes::where('ponpes_id', $id)->first();
            
            if ($uploadFolder) {
                for ($i = 1; $i <= 10; $i++) {
                    $column = 'pdf_folder_' . $i;
                    if (!empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                        Storage::disk('public')->delete($uploadFolder->$column);
                    }
                }
                
                $uploadFolder->delete();
            }

            $dataPonpes->delete();
            return redirect()->route('sppPonpes.ListDataSpp')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting Ponpes: ' . $e->getMessage());
            return redirect()->route('sppPonpes.ListDataSpp')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpes::where('ponpes_id', $id)->first();
            
            if (!$uploadFolder) {
                return abort(404, 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_' . $folder;

            if (empty($uploadFolder->$column)) {
                return abort(404, 'File PDF belum diupload untuk folder ' . $folder . '.');
            }

            $filePath = storage_path('app/public/' . $uploadFolder->$column);
            
            if (!file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
            Log::error('Error viewing PDF: ' . $e->getMessage());
            return abort(500, 'Error loading PDF: ' . $e->getMessage());
        }
    }

    public function uploadFilePDF(Request $request, $id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $request->validate([
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240',
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.'
            ]);

            if (!$request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $ponpes = Ponpes::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            $uploadFolder = UploadFolderPonpes::firstOrCreate(
                ['ponpes_id' => $id],
                ['ponpes_id' => $id]
            );

            $column = 'pdf_folder_' . $folder;
            if (!empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName . '.pdf';

            $directory = 'ponpes/spp/folder_' . $folder;
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            $uploadFolder->$column = $path;
            $uploadFolder->save();

            Log::info("PDF uploaded successfully for Ponpes ID: {$id}, Folder: {$folder}, Path: {$path}");

            return redirect()->back()->with('success', 'PDF berhasil di-upload ke folder ' . $folder . '!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function deleteFilePDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $ponpes = Ponpes::findOrFail($id);
            $uploadFolder = UploadFolderPonpes::where('ponpes_id', $id)->first();
            
            if (!$uploadFolder) {
                return redirect()->back()->with('error', 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_' . $folder;

            if (empty($uploadFolder->$column)) {
                return redirect()->back()->with('error', 'File PDF belum di-upload di folder ' . $folder . '.');
            }

            if (Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            $uploadFolder->$column = null;
            $uploadFolder->save();

            return redirect()->back()->with('success', 'File PDF di folder ' . $folder . ' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
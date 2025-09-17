<?php

namespace App\Http\Controllers\user\upt\spp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Provider;
use App\Models\user\Upt;
use App\Models\db\UploadFolderUpt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SppUptController extends Controller
{
    public function ListDataSpp(Request $request)
    {
        $query = Upt::with('uploadFolder'); // Load relasi ke upload folder

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
        return view('db.upt.spp.indexSpp', compact('data', 'providers'));
    }

    public function DatabasePageDestroy($id)
    {
        try {
            $dataupt = Upt::findOrFail($id);
            
            // Cari data upload folder yang terkait
            $uploadFolder = UploadFolderUpt::where('upt_id', $id)->first();
            
            if ($uploadFolder) {
                // Hapus semua file PDF yang terkait
                for ($i = 1; $i <= 10; $i++) {
                    $column = 'pdf_folder_' . $i;
                    if (!empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                        Storage::disk('public')->delete($uploadFolder->$column);
                    }
                }
                
                // Hapus record upload folder
                $uploadFolder->delete();
            }

            $dataupt->delete();
            return redirect()->route('spp.ListDataSpp')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting UPT: ' . $e->getMessage());
            return redirect()->route('spp.ListDataSpp')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $upt = Upt::findOrFail($id);
            $uploadFolder = UploadFolderUpt::where('upt_id', $id)->first();
            
            if (!$uploadFolder) {
                return abort(404, 'Data upload folder tidak ditemukan.');
            }

            $column = 'pdf_folder_' . $folder;

            // Cek apakah file ada dan path tidak kosong
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

            // Validasi file PDF
            $request->validate([
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.'
            ]);

            if (!$request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $upt = Upt::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            // Debug: Cek apakah file valid
            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // Cari atau buat record upload folder
            $uploadFolder = UploadFolderUpt::firstOrCreate(
                ['upt_id' => $id],
                ['upt_id' => $id]
            );

            // Hapus file lama jika ada
            $column = 'pdf_folder_' . $folder;
            if (!empty($uploadFolder->$column) && Storage::disk('public')->exists($uploadFolder->$column)) {
                Storage::disk('public')->delete($uploadFolder->$column);
            }

            // Buat nama file unik dengan sanitasi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName . '.pdf';

            // Pastikan direktori ada
            $directory = 'upt/spp/folder_' . $folder;
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Simpan file
            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            // Simpan path ke database
            $uploadFolder->$column = $path;
            $uploadFolder->save();

            Log::info("PDF uploaded successfully for UPT ID: {$id}, Folder: {$folder}, Path: {$path}");

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

            $upt = Upt::findOrFail($id);
            $uploadFolder = UploadFolderUpt::where('upt_id', $id)->first();
            
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
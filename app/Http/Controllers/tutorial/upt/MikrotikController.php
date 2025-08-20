<?php

namespace App\Http\Controllers\tutorial\upt;

use App\Http\Controllers\Controller;
use App\Models\tutorial\upt\Mikrotik;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MikrotikController extends Controller
{
    public function tutorial_mikrotik()
    {
        $data = Mikrotik::all();
        return view('tutorial.mikrotik', compact('data'))->with('title', 'Tutorial Mikrotik');
    }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tutor_mikrotik' => 'required|unique:tutor_mikrotik,tutor_mikrotik',
            ],
            [
                'tutor_mikrotik.required' => 'Nama tutor wajib diisi.',
                'tutor_mikrotik.unique' => 'Nama tutor sudah ada, silakan gunakan nama lain.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // SOLUSI 1: Gunakan variabel $data yang sudah diset tanggalnya
        $data = $request->all();
        $data['tanggal'] = date('Y-m-d');
        Mikrotik::create($data); // Gunakan $data yang sudah diset tanggalnya
        return redirect()->route('mikrotik_page.ListDataSpp')->with('success', 'Data berhasil disimpan');
    }
    public function ListDataSpp(Request $request)
    {
        $query = Mikrotik::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            // Lakukan pencarian berdasarkan beberapa kolom
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tutor_mikrotik', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        return view('tutorial.mikrotik', compact('data'))->with('title', 'Tutorial Mikrotik');
    }
    public function DatabasePageDestroy($id)
    {
        try {
            $dataupt = Mikrotik::findOrFail($id);

            // Hapus semua file PDF yang terkait dengan user ini
            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_' . $i;
                if (!empty($dataupt->$column) && Storage::disk('public')->exists($dataupt->$column)) {
                    Storage::disk('public')->delete($dataupt->$column);
                }
            }

            $dataupt->delete();
            return redirect()->route('mikrotik_page.ListDataSpp')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('mikrotik_page.ListDataSpp')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function viewUploadedPDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return abort(400, 'Folder tidak valid.');
            }

            $user = Mikrotik::findOrFail($id);
            $column = 'pdf_folder_' . $folder;

            // Cek apakah file ada dan path tidak kosong
            if (empty($user->$column)) {
                return abort(404, 'File PDF belum diupload untuk folder ' . $folder . '.');
            }

            $filePath = storage_path('app/public/' . $user->$column);

            if (!file_exists($filePath)) {
                return abort(404, 'File tidak ditemukan di storage.');
            }

            return response()->file($filePath);
        } catch (\Exception $e) {
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
                'uploaded_pdf' => 'required|file|mimes:pdf|max:10240', // Increased to 10MB
            ], [
                'uploaded_pdf.required' => 'File PDF harus dipilih.',
                'uploaded_pdf.mimes' => 'File harus berformat PDF.',
                'uploaded_pdf.max' => 'Ukuran file maksimal 10MB.'
            ]);

            if (!$request->hasFile('uploaded_pdf')) {
                return redirect()->back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $user = Mikrotik::findOrFail($id);
            $file = $request->file('uploaded_pdf');

            // Debug: Cek apakah file valid
            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid!');
            }

            // Hapus file lama jika ada
            $column = 'pdf_folder_' . $folder;
            if (!empty($user->$column) && Storage::disk('public')->exists($user->$column)) {
                Storage::disk('public')->delete($user->$column);
            }

            // Buat nama file unik dengan sanitasi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName . '.pdf';

            // Pastikan direktori ada
            $directory = 'tutorial/upt/mikrotik/folder_' . $folder;
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Simpan file
            $path = $file->storeAs($directory, $filename, 'public');

            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file!');
            }

            // Simpan path ke database
            $user->$column = $path;
            $user->save();

            return redirect()->back()->with('success', 'PDF berhasil di-upload ke folder ' . $folder . '!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }
    public function deleteFilePDF($id, $folder)
    {
        try {
            if (!in_array($folder, range(1, 10))) {
                return redirect()->back()->with('error', 'Folder tidak valid.');
            }

            $user = Mikrotik::findOrFail($id);
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }
}

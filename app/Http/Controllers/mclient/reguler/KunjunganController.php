<?php

namespace App\Http\Controllers\mclient\reguler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\mclient\Reguller;
use App\Models\user\Upt;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\user\Pic;

class KunjunganController extends Controller
{

    public function ListDataMclientReguller(Request $request)
    {
        $query = Reguller::query();

        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_upt', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kanwil', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('jenis_kendala', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('detail_kendala', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pic_1', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        // $jenisKendala = Kendala::orderBy('jenis_kendala')->get();
        $picList = Pic::orderBy('nama_pic')->get();
        $uptList = Upt::select('namaupt', 'kanwil')
            ->where('tipe', 'reguler')
            ->orderBy('namaupt')
            ->get();

        return view('mclient.upt.indexKunjungan', compact('data', 'picList', 'uptList'));
    }

    public function MclientRegullerStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'kanwil' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'kanwil.string' => 'Kanwil harus berupa teks.',
                'kanwil.max' => 'Kanwil tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala Reguller harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala Reguller harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            $data = $request->all();

            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
            }

            Reguller::create($data);

            return redirect()->back()->with('success', 'Data monitoring client Reguller berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientRegullerUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_upt' => 'required|string|max:255',
                'kanwil' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'nama_upt.required' => 'Nama UPT harus diisi.',
                'nama_upt.string' => 'Nama UPT harus berupa teks.',
                'nama_upt.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'kanwil.string' => 'Kanwil harus berupa teks.',
                'kanwil.max' => 'Kanwil tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala Reguller harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala Reguller harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        if ($validator->fails()) {
            $validatedData = [];
            $invalidFields = array_keys($validator->errors()->messages());

            foreach ($request->all() as $key => $value) {
                if (!in_array($key, $invalidFields)) {
                    $validatedData[$key] = $value;
                }
            }

            try {
                if (!empty($validatedData)) {
                    $data = Reguller::findOrFail($id);

                    if (isset($validatedData['tanggal_terlapor']) && isset($validatedData['tanggal_selesai'])) {
                        $tanggalTerlapor = Carbon::parse($validatedData['tanggal_terlapor']);
                        $tanggalSelesai = Carbon::parse($validatedData['tanggal_selesai']);
                        $validatedData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
                    }

                    $data->update($validatedData);
                }
            } catch (\Exception $e) {
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('partial_success', 'Data valid telah disimpan. Silakan perbaiki field yang bermasalah.');
        }

        try {
            $data = Reguller::findOrFail($id);
            $updateData = $request->all();

            if ($request->tanggal_terlapor && $request->tanggal_selesai) {
                $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalSelesai->diffInDays($tanggalTerlapor);
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data monitoring client Reguller berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientRegullerDestroy($id)
    {
        try {
            $data = Reguller::findOrFail($id);
            $namaUpt = $data->nama_upt;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data monitoring client Reguller di UPT '{$namaUpt}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

}

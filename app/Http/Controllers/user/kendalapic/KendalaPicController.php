<?php

namespace App\Http\Controllers\user\kendalapic;

use App\Http\Controllers\Controller;
use App\Models\user\kendala\Kendala;
use App\Models\user\pic\Pic;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KendalaPicController extends Controller
{
    // INDEX - Menampilkan kedua tabel
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // PAGINATION UNTUK KENDALA
        if ($perPage === 'all') {
            $dataKendala = Kendala::orderBy('jenis_kendala', 'asc')->get();
            $dataKendala = new \Illuminate\Pagination\LengthAwarePaginator(
                $dataKendala,
                $dataKendala->count(),
                $dataKendala->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'kendala_page',
                ]
            );
        } else {
            $dataKendala = Kendala::orderBy('jenis_kendala', 'asc')
                ->paginate($perPage, ['*'], 'kendala_page');
        }

        // PAGINATION UNTUK PIC
        if ($perPage === 'all') {
            $datapic = Pic::orderBy('nama_pic', 'asc')->get();
            $datapic = new \Illuminate\Pagination\LengthAwarePaginator(
                $datapic,
                $datapic->count(),
                $datapic->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'pic_page',
                ]
            );
        } else {
            $datapic = Pic::orderBy('nama_pic', 'asc')
                ->paginate($perPage, ['*'], 'pic_page');
        }

        return view('user.indexKendalaPic', compact('dataKendala', 'datapic', 'perPage'));
    }

    // ==================== KENDALA METHODS ====================

    public function kendalaStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['jenis_kendala' => 'required|string|max:255'],
            ['jenis_kendala.required' => 'Jenis Kendala harus diisi.']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Kendala::create(['jenis_kendala' => $request->jenis_kendala]);

        return redirect()->back()->with('success', 'Data kendala berhasil ditambahkan!');
    }

    public function kendalaUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            ['jenis_kendala' => 'required|string|max:255'],
            ['jenis_kendala.required' => 'Jenis Kendala harus diisi.']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataKendala = Kendala::findOrFail($id);
        $dataKendala->update(['jenis_kendala' => $request->jenis_kendala]);

        return redirect()->back()->with('success', 'Data kendala berhasil diupdate!');
    }

    public function kendalaDestroy($id)
    {
        $dataKendala = Kendala::findOrFail($id);
        $dataKendala->delete();

        return redirect()->back()->with('success', 'Data kendala berhasil dihapus!');
    }

    public function exportKendalaCsv(Request $request): StreamedResponse
    {
        $data = Kendala::orderBy('jenis_kendala', 'asc')->get();
        $filename = 'list_kendala_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Jenis Kendala']);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [$no++, $d->jenis_kendala]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportKendalaPdf(Request $request)
    {
        $data = Kendala::orderBy('jenis_kendala', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List Kendala',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.user.kendala', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_kendala_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    // ==================== PIC METHODS ====================

    public function picStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_pic' => 'required|string|max:255'],
            ['nama_pic.required' => 'Nama PIC harus diisi.']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Pic::create(['nama_pic' => $request->nama_pic]);

        return redirect()->back()->with('success', 'Data PIC berhasil ditambahkan!');
    }

    public function picUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            ['nama_pic' => 'required|string|max:255'],
            ['nama_pic.required' => 'Nama PIC harus diisi.']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataPic = Pic::findOrFail($id);
        $dataPic->update(['nama_pic' => $request->nama_pic]);

        return redirect()->back()->with('success', 'Data PIC berhasil diupdate!');
    }

    public function picDestroy($id)
    {
        $dataPic = Pic::findOrFail($id);
        $dataPic->delete();

        return redirect()->back()->with('success', 'Data PIC berhasil dihapus!');
    }

    public function exportPicCsv(Request $request): StreamedResponse
    {
        $data = Pic::orderBy('nama_pic', 'asc')->get();
        $filename = 'list_pic_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama PIC']);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [$no++, $d->nama_pic]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportPicPdf(Request $request)
    {
        $data = Pic::orderBy('nama_pic', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List PIC',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.user.pic', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_pic_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\NamaWilayah;
use App\Models\User\Kanwil;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class NamaWilayahController extends Controller
{
    public function index()
    {
        $datakanwil = Kanwil::all();
        $datanamawilayah = NamaWilayah::all();
        return view('user.indexKanwilNamaWilayah', compact('datakanwil', 'datanamawilayah'));
    }

    public function NamaWilayahPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_wilayah' => 'required|string|max:255',
            ],
            [
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datanamawilayah = [
            'nama_wilayah' => $request->nama_wilayah,
        ];

        NamaWilayah::create($datanamawilayah);
        return redirect()->back()->with('success', 'Data Nama Wilayah berhasil ditambahkan!');
    }

    public function NamaWilayahPageDestroy($id)
    {
        $datanamawilayah = NamaWilayah::findOrFail($id);
        $datanamawilayah->delete();
        return redirect()->back()->with('success', 'Data Nama Wilayah berhasil dihapus!');
    }

    public function NamaWilayahPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_wilayah' => 'required|string|max:255',
            ],
            [
                'nama_wilayah.required' => 'Nama Wilayah harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datanamawilayah = NamaWilayah::findOrFail($id);
        $datanamawilayah->update([
            'nama_wilayah' => $request->nama_wilayah,
        ]);

        return redirect()->back()->with('success', 'Data Nama Wilayah berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = NamaWilayah::query();
        $data = $query->orderBy('nama_wilayah', 'asc')->get();

        $filename = 'list_namawilayah_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Nama Wilayah'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->nama_wilayah
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = NamaWilayah::query();
        $data = $query->orderBy('nama_wilayah', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List Nama Wilayah',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.namawilayah', $pdfData);
        $filename = 'list_namawilayah_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

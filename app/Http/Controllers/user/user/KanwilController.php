<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Kanwil;
use App\Models\user\NamaWilayah;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KanwilController extends Controller
{
    public function index()
    {
        $datakanwil = Kanwil::all();
        $datanamawilayah = NamaWilayah::all();
        return view('user.indexKanwilNamaWilayah', compact('datakanwil', 'datanamawilayah'));
    }

    public function KanwilPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kanwil' => 'required|string|max:255',
            ],
            [
                'kanwil.required' => 'Kanwil harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakanwil = [
            'kanwil' => $request->kanwil,
        ];

        Kanwil::create($datakanwil);
        return redirect()->back()->with('success', 'Data kanwil berhasil ditambahkan!');
    }

    public function KanwilPageDestroy($id)
    {
        $datakanwil = Kanwil::findOrFail($id);
        $datakanwil->delete();
        return redirect()->back()->with('success', 'Data kanwil berhasil dihapus!');
    }

    public function KanwilPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kanwil' => 'required|string|max:255',
            ],
            [
                'kanwil.required' => 'Kanwil harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakanwil = Kanwil::findOrFail($id);
        $datakanwil->update([
            'kanwil' => $request->kanwil,
        ]);

        return redirect()->back()->with('success', 'Data kanwil berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Kanwil::query();
        $data = $query->orderBy('kanwil', 'asc')->get();

        $filename = 'list_kanwil_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Kanwil'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->kanwil
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = Kanwil::query();
        $data = $query->orderBy('kanwil', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List Kanwil',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.kanwil', $pdfData);
        $filename = 'list_kanwil_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

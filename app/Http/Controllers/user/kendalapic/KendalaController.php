<?php

namespace App\Http\Controllers\user\kendalapic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\kendala\Kendala;
use App\Models\user\pic\Pic;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KendalaController extends Controller
{
    public function index()
    {
        $datakendala = Kendala::all();
        $datapic = Pic::all();
        return view('user.indexKendalaPic', compact('datakendala', 'datapic'));
    }

    public function KendalaPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_kendala' => 'required|string|max:255',
            ],
            [
                'jenis_kendala.required' => 'Jenis Kendala harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakendala = [
            'jenis_kendala' => $request->jenis_kendala,
        ];

        Kendala::create($datakendala);
        return redirect()->back()->with('success', 'Data kendala berhasil ditambahkan!');
    }

    public function KendalaPageDestroy($id)
    {
        $datakendala = Kendala::findOrFail($id);
        $datakendala->delete();
        return redirect()->back()->with('success', 'Data kendala berhasil dihapus!');
    }

    public function KendalaPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_kendala' => 'required|string|max:255',
            ],
            [
                'jenis_kendala.required' => 'Jenis Kendala harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datakendala = Kendala::findOrFail($id);
        $datakendala->update([
            'jenis_kendala' => $request->jenis_kendala,
        ]);

        return redirect()->back()->with('success', 'Data kendala berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Kendala::query();
        $data = $query->orderBy('jenis_kendala', 'asc')->get();

        $filename = 'list_kendala_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Jenis Kendala'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->jenis_kendala
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = Kendala::query();
        $data = $query->orderBy('jenis_kendala', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List Kendala',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.kendala', $pdfData);
        $filename = 'list_kendala_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

}

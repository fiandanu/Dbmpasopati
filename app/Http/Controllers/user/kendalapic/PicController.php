<?php

namespace App\Http\Controllers\user\kendalapic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Pic;
use App\Models\user\Kendala;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PicController extends Controller
{
    public function index()
    {
        $datakendala = Kendala::all();
        $datapic = Pic::all();
        return view('user.indexKendalaPic', compact('datakendala', 'datapic'));
    }

    public function PicPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_pic' => 'required|string|max:255',
            ],
            [
                'nama_pic.required' => 'Nama PIC harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datapic = [
            'nama_pic' => $request->nama_pic,
        ];

        Pic::create($datapic);
        return redirect()->back()->with('success', 'Data PIC berhasil ditambahkan!');
    }

    public function PicPageDestroy($id)
    {
        $datapic = Pic::findOrFail($id);
        $datapic->delete();
        return redirect()->back()->with('success', 'Data PIC berhasil dihapus!');
    }

    public function PicPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_pic' => 'required|string|max:255',
            ],
            [
                'nama_pic.required' => 'Nama PIC harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datapic = Pic::findOrFail($id);
        $datapic->update([
            'nama_pic' => $request->nama_pic,
        ]);

        return redirect()->back()->with('success', 'Data PIC berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Pic::query();
        $data = $query->orderBy('nama_pic', 'asc')->get();

        $filename = 'list_pic_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Nama PIC'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->nama_pic
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = Pic::query();
        $data = $query->orderBy('nama_pic', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List PIC',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.pic', $pdfData);
        $filename = 'list_pic_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers\user\user;

use App\Http\Controllers\Controller;
use App\Models\user\kanwil\Kanwil;
use App\Models\user\NamaWilayah\NamaWilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KanwilController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // PAGINATION UNTUK KANWIL
        if ($perPage === 'all') {
            $dataKanwil = Kanwil::orderBy('created_at', 'desc')->get();
            $dataKanwil = new \Illuminate\Pagination\LengthAwarePaginator(
                $dataKanwil,
                $dataKanwil->count(),
                $dataKanwil->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'kendala_page',
                ]
            );
        } else {
            $dataKanwil = Kanwil::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'kendala_page');
        }

        // PAGINATION UNTUK NAMA WILAYAH
        if ($perPage === 'all') {
            $dataNamaWilayah = NamaWilayah::orderBy('created_at', 'desc')->get();
            $dataNamaWilayah = new \Illuminate\Pagination\LengthAwarePaginator(
                $dataNamaWilayah,
                $dataNamaWilayah->count(),
                $dataNamaWilayah->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'pic_page',
                ]
            );
        } else {
            $dataNamaWilayah = NamaWilayah::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'pic_page');
        }

        return view('user.indexKanwilNamaWilayah', compact('dataKanwil', 'dataNamaWilayah', 'perPage'));
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
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['No', 'Kanwil'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->kanwil,
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
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.user.kanwil', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_kanwil_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

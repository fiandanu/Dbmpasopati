<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\provider\Provider;
use App\Models\user\vpn\Vpn;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ProviderController extends Controller
{
    public function index()
    {
        $dataprovider = Provider::all();
        $datavpn = Vpn::all();
        return view('user.indexProvider', compact('dataprovider', 'datavpn'));
    }

    public function ProviderPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_provider' => 'required|string|max:255',
            ],
            [
                'nama_provider.required' => 'Nama Provider harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataprovider = [
            'nama_provider' => $request->nama_provider,
        ];

        Provider::create($dataprovider);
        return redirect()->back()->with('success', 'Data provider berhasil ditambahkan!');
    }

    public function ProviderPageDestroy($id)
    {
        $dataprovider = Provider::findOrFail($id);
        $dataprovider->delete();
        return redirect()->back()->with('success', 'Data provider berhasil dihapus!');
    }

    public function ProviderPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_provider' => 'required|string|max:255',
            ],
            [
                'nama_provider.required' => 'Nama Provider harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataprovider = Provider::findOrFail($id);
        $dataprovider->update([
            'nama_provider' => $request->nama_provider,
        ]);

        return redirect()->back()->with('success', 'Data provider berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Provider::query();
        $data = $query->orderBy('nama_provider', 'asc')->get();

        $filename = 'list_provider_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Nama Provider'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->nama_provider
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = Provider::query();
        $data = $query->orderBy('nama_provider', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List Provider',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.provider', $pdfData);
        $filename = 'list_provider_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

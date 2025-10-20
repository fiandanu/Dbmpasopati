<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\vpn\Vpn;
use App\Models\user\provider\Provider;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class VpnController extends Controller
{
    public function index()
    {
        $dataprovider = Provider::all();
        $datavpn = Vpn::all();
        return view('user.indexProvider', compact('dataprovider', 'datavpn'));
    }

    public function VpnPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_vpn' => 'required|string|max:255',
            ],
            [
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datavpn = [
            'jenis_vpn' => $request->jenis_vpn,
        ];

        Vpn::create($datavpn);
        return redirect()->back()->with('success', 'Data VPN berhasil ditambahkan!');
    }

    public function VpnPageDestroy($id)
    {
        $datavpn = Vpn::findOrFail($id);
        $datavpn->delete();
        return redirect()->back()->with('success', 'Data VPN berhasil dihapus!');
    }

    public function VpnPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_vpn' => 'required|string|max:255',
            ],
            [
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $datavpn = Vpn::findOrFail($id);
        $datavpn->update([
            'jenis_vpn' => $request->jenis_vpn,
        ]);

        return redirect()->back()->with('success', 'Data VPN berhasil diupdate!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Vpn::query();
        $data = $query->orderBy('jenis_vpn', 'asc')->get();

        $filename = 'list_vpn_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Jenis VPN'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->jenis_vpn
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = Vpn::query();
        $data = $query->orderBy('jenis_vpn', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List VPN',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.user.vpn', $pdfData);
        $filename = 'list_vpn_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use App\Models\user\provider\Provider;
use App\Models\user\vpn\Vpn;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        // Pagination untuk Provider
        if ($perPage === 'all') {
            $dataprovider = Provider::orderBy('nama_provider', 'asc')->get();
            $dataprovider = new \Illuminate\Pagination\LengthAwarePaginator(
                $dataprovider,
                $dataprovider->count(),
                $dataprovider->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'provider_page',
                ]
            );
        } else {
            $dataprovider = Provider::orderBy('nama_provider', 'asc')
                ->paginate($perPage, ['*'], 'provider_page');
        }

        // Pagination untuk VPN
        if ($perPage === 'all') {
            $datavpn = Vpn::orderBy('jenis_vpn', 'asc')->get();
            $datavpn = new \Illuminate\Pagination\LengthAwarePaginator(
                $datavpn,
                $datavpn->count(),
                $datavpn->count(),
                1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'vpn_page',
                ]
            );
        } else {
            $datavpn = Vpn::orderBy('jenis_vpn', 'asc')
                ->paginate($perPage, ['*'], 'vpn_page');
        }

        return view('user.indexProvider', compact('dataprovider', 'datavpn', 'perPage'));
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
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['No', 'Nama Provider'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $no = 1;
            foreach ($data as $d) {
                fputcsv($file, [
                    $no++,
                    $d->nama_provider,
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
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.user.provider', $pdfData)
            ->setPaper('a4', 'landscape');
        $filename = 'list_provider_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

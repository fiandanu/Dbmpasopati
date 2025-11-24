<?php

namespace App\Http\Controllers\user\provider;

use App\Http\Controllers\Controller;
use App\Models\user\provider\Provider;
use App\Models\user\vpn\Vpn;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VpnController extends Controller
{
    private const ALLOWED_PER_PAGE = [10, 15, 20, 'all'];
    private const DEFAULT_PER_PAGE = 10;

    public function index(Request $request)
    {
        $perPage = $this->validatePerPage($request->get('per_page', self::DEFAULT_PER_PAGE));

        $dataprovider = $this->getPaginatedProvider($request, $perPage);
        $datavpn = $this->getPaginatedVpn($request, $perPage);

        return view('user.indexProvider', compact('dataprovider', 'datavpn', 'perPage'));
    }

    private function validatePerPage($perPage)
    {
        return in_array($perPage, self::ALLOWED_PER_PAGE) ? $perPage : self::DEFAULT_PER_PAGE;
    }

    private function getPaginatedProvider(Request $request, $perPage)
    {
        if ($perPage === 'all') {
            $data = Provider::orderBy('nama_provider', 'asc')->get();
            return $this->createCustomPaginator($data, $request, 'provider_page');
        }

        return Provider::orderBy('nama_provider', 'asc')
            ->paginate($perPage, ['*'], 'provider_page');
    }

    private function getPaginatedVpn(Request $request, $perPage)
    {
        if ($perPage === 'all') {
            $data = Vpn::orderBy('jenis_vpn', 'asc')->get();
            return $this->createCustomPaginator($data, $request, 'vpn_page');
        }

        return Vpn::orderBy('jenis_vpn', 'asc')
            ->paginate($perPage, ['*'], 'vpn_page');
    }

    private function createCustomPaginator($items, Request $request, $pageName)
    {
        return new LengthAwarePaginator(
            $items,
            $items->count(),
            $items->count(),
            1,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => $pageName,
            ]
        );
    }

    public function VpnPageStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis_vpn' => 'required|string|max:255|unique:vpns,jenis_vpn',
            ],
            [
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
                'jenis_vpn.unique' => 'Jenis VPN sudah terdaftar.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Vpn::create(['jenis_vpn' => $request->jenis_vpn]);

        return redirect()->back()->with('success', 'Data VPN berhasil ditambahkan!');
    }

    public function VpnPageUpdate(Request $request, $id)
    {
        $vpn = Vpn::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'jenis_vpn' => 'required|string|max:255|unique:vpns,jenis_vpn,' . $id,
            ],
            [
                'jenis_vpn.required' => 'Jenis VPN harus diisi.',
                'jenis_vpn.unique' => 'Jenis VPN sudah terdaftar.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $vpn->update(['jenis_vpn' => $request->jenis_vpn]);

        return redirect()->back()->with('success', 'Data VPN berhasil diupdate!');
    }

    public function VpnPageDestroy($id)
    {
        $vpn = Vpn::findOrFail($id);
        $vpn->delete();

        return redirect()->back()->with('success', 'Data VPN berhasil dihapus!');
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $data = Vpn::orderBy('jenis_vpn', 'asc')->get();

        $filename = 'list_vpn_' . Carbon::now()->translatedFormat('d_M_Y') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Jenis VPN']);

            $no = 1;
            foreach ($data as $item) {
                fputcsv($file, [$no++, $item->jenis_vpn]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $data = Vpn::orderBy('jenis_vpn', 'asc')->get()->toArray();

        $pdfData = [
            'title' => 'List VPN',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.user.vpn', $pdfData)
            ->setPaper('a4', 'landscape');

        $filename = 'list_vpn_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers\user\ponpes\vtren;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user\Provider;
use App\Models\user\Ponpes;
use App\Models\user\Vpn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class VtrenController extends Controller
{
    private $optionalFields = [
        'pic_ponpes',
        'no_telpon',
        'alamat',
        'jumlah_wbp',
        'jumlah_line',
        'provider_internet',
        'kecepatan_internet',
        'tarif_wartel',
        'status_wartel',
        'akses_topup_pulsa',
        'password_topup',
        'akses_download_rekaman',
        'password_download',
        'internet_protocol',
        'vpn_user',
        'vpn_password',
        'jenis_vpn',
        'jumlah_extension',
        'no_extension',
        'extension_password',
        'pin_tes',
        'no_pemanggil',
        'email_airdroid',
        'password'
    ];

    private function calculateStatus($dataOpsional)
    {
        if (!$dataOpsional) {
            return 'Belum di Update';
        }
        $filledFields = 0;
        foreach ($this->optionalFields as $field) {
            if (!empty($dataOpsional->$field)) {
                $filledFields++;
            }
        }
        $totalFields = count($this->optionalFields);
        $percentage = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;

        if ($filledFields == 0) {
            return 'Belum di Update';
        } elseif ($filledFields == $totalFields) {
            return 'Sudah Update';
        } else {
            return "Sebagian ({$percentage}%)";
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Global search
        if ($request->has('table_search') && !empty($request->table_search)) {
            $searchTerm = $request->table_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ponpes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_wilayah', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('dataOpsional', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('pic_ponpes', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('provider_internet', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        // Column-specific searches
        if ($request->has('search_namaponpes') && !empty($request->search_namaponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_namaponpes . '%');
        }
        if ($request->has('search_wilayah') && !empty($request->search_wilayah)) {
            $query->where('nama_wilayah', 'LIKE', '%' . $request->search_wilayah . '%');
        }
        if ($request->has('search_tipe') && !empty($request->search_tipe)) {
            $query->where('tipe', 'LIKE', '%' . $request->search_tipe . '%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_dari') && !empty($request->search_tanggal_dari)) {
            $query->whereDate('tanggal', '>=', $request->search_tanggal_dari);
        }
        if ($request->has('search_tanggal_sampai') && !empty($request->search_tanggal_sampai)) {
            $query->whereDate('tanggal', '<=', $request->search_tanggal_sampai);
        }

        return $query;
    }

    private function applyStatusFilter($data, Request $request)
    {
        if ($request->has('search_status') && !empty($request->search_status)) {
            $statusSearch = strtolower($request->search_status);
            return $data->filter(function ($d) use ($statusSearch) {
                $status = strtolower($this->calculateStatus($d->dataOpsional));
                return strpos($status, $statusSearch) !== false;
            });
        }
        return $data;
    }

    public function ListDataVtrend(Request $request)
    {
        $query = Ponpes::with('dataOpsional')->where('tipe', 'vtren');

        // Apply database filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 20;
        }

        // Handle pagination
        if ($perPage == 'all') {
            $data = $query->orderBy('tanggal', 'desc')->get();

            // Apply status filter to collection
            $data = $this->applyStatusFilter(collect($data), $request);

            // Create a mock paginator for "all" option
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $data->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // For paginated results, we need to get all data first, apply status filter, then paginate
            $allData = $query->orderBy('tanggal', 'desc')->get();
            $filteredData = $this->applyStatusFilter($allData, $request);

            // Manual pagination of filtered data
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
            $offset = ($currentPage - 1) * $perPage;
            $itemsForCurrentPage = $filteredData->slice($offset, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsForCurrentPage,
                $filteredData->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'page'
                ]
            );
        }

        $providers = Provider::all();
        $vpns = Vpn::all();

        return view('db.ponpes.vtren.indexVtren', compact('data', 'providers', 'vpns'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with('dataOpsional')->where('tipe', 'vtren');
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyStatusFilter($data, $request);

        // Additional sorting if date filter is applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $data = $data->sortBy('tanggal')->values();
        }

        $filename = 'list_ponpes_vtren_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [['No', 'Nama Ponpes', 'Nama Wilayah', 'Tipe', 'Tanggal Dibuat', 'Status Update']];
        $no = 1;
        foreach ($data as $d) {
            $status = $this->calculateStatus($d->dataOpsional);
            $rows[] = [
                $no++,
                $d->nama_ponpes,
                $d->nama_wilayah,
                ucfirst($d->tipe),
                \Carbon\Carbon::parse($d->tanggal)->format('d M Y'),
                $status
            ];
        }

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportListPdf(Request $request)
    {
        $query = Ponpes::with('dataOpsional')->where('tipe', 'vtren');
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            $query = $query->orderBy('tanggal', 'asc');
        }

        $data = $query->get();

        // Apply status filter
        $data = $this->applyStatusFilter($data, $request);

        // Convert collection to array with calculated status
        $dataArray = [];
        foreach ($data as $d) {
            $dataItem = $d->toArray();
            $dataItem['calculated_status'] = $this->calculateStatus($d->dataOpsional);
            $dataArray[] = $dataItem;
        }

        // Additional sorting using correct field name
        if ($request->filled('search_tanggal_dari') || $request->filled('search_tanggal_sampai')) {
            usort($dataArray, function ($a, $b) {
                $dateA = strtotime($a['tanggal']);
                $dateB = strtotime($b['tanggal']);
                return $dateA - $dateB;
            });
        }

        $pdfData = [
            'title' => 'List Data Ponpes Vtren',
            'data' => $dataArray,
            'optionalFields' => $this->optionalFields,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.db.ponpes.indexVtren', $pdfData);
        $filename = 'list_ponpes_vtren_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function ListDataPonpesUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // Field Wajib Form Ponpes (tidak diupdate karena readonly di form)
                'nama_ponpes' => 'nullable|string|max:255',
                'nama_wilayah' => 'nullable|string|max:255',
                'tipe' => 'nullable|string|max:255',
                'tanggal' => 'nullable|date',

                // Data Opsional
                'pic_ponpes' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',
                'jumlah_wbp' => 'nullable|integer|min:0',
                'jumlah_line' => 'nullable|integer|min:0',
                'provider_internet' => 'nullable|string|max:255',
                'kecepatan_internet' => 'nullable|string|max:255',
                'tarif_wartel' => 'nullable|string|max:255',
                'status_wartel' => 'nullable|string',

                // IMC PAS
                'akses_topup_pulsa' => 'nullable|string',
                'password_topup' => 'nullable|string|max:255',
                'akses_download_rekaman' => 'nullable|string',
                'password_download' => 'nullable|string|max:255',

                // VPN
                'internet_protocol' => 'nullable|string|max:255',
                'vpn_user' => 'nullable|string|max:255',
                'vpn_password' => 'nullable|string|max:255',
                'jenis_vpn' => 'nullable|string|max:255',

                // Extension Reguler
                'jumlah_extension' => 'nullable|integer|min:0',
                'no_extension' => 'nullable|string',
                'extension_password' => 'nullable|string',
                'pin_tes' => 'nullable|string',
                'no_pemanggil' => 'nullable|string',
                'email_airdroid' => 'nullable|string',
                'password' => 'nullable|string',
            ],
            [
                'nama_ponpes.string' => 'Nama Ponpes harus berupa teks.',
                'nama_wilayah.string' => 'Nama Wilayah harus berupa teks.',
                'tanggal.date' => 'Format tanggal harus sesuai (YYYY-MM-DD).',

                'pic_ponpes.string' => 'PIC Ponpes harus berupa teks.',
                'no_telpon.regex' => 'Format nomor telepon tidak valid.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'jumlah_wbp.integer' => 'Jumlah Santri harus berupa angka.',
                'jumlah_wbp.min' => 'Jumlah Santri tidak boleh negatif.',
                'jumlah_line.integer' => 'Jumlah line reguler harus berupa angka.',
                'jumlah_line.min' => 'Jumlah line reguler tidak boleh negatif.',
                'provider_internet.string' => 'Provider internet harus berupa teks.',
                'kecepatan_internet.string' => 'Kecepatan internet harus berupa teks.',
                'tarif_wartel.string' => 'Tarif wartel harus berupa teks.',
                'status_wartel.string' => 'Status wartel harus Aktif atau Tidak Aktif.',

                'akses_topup_pulsa.string' => 'Akses top up pulsa harus berupa teks.',
                'password_topup.string' => 'Password top up harus berupa teks.',
                'akses_download_rekaman.string' => 'Akses download rekaman harus berupa teks.',
                'password_download.string' => 'Password download rekaman harus berupa teks.',

                'internet_protocol.string' => 'Internet Protocol harus berupa teks.',
                'vpn_user.string' => 'User VPN harus berupa teks.',
                'vpn_password.string' => 'Password VPN harus berupa teks.',
                'jenis_vpn.string' => 'Jenis VPN harus berupa teks.',

                'jumlah_extension.integer' => 'Jumlah extension harus berupa angka.',
                'jumlah_extension.min' => 'Jumlah extension tidak boleh negatif.',
                'no_extension.string' => 'Nomor extension harus berupa teks.',
                'extension_password.string' => 'Password extension harus berupa teks.',
                'pin_tes.string' => 'PIN Tes harus berupa teks.',
                'no_pemanggil.string' => 'No Pemanggil harus berupa teks.',
                'email_airdroid.string' => 'Email Airdroid harus berupa teks.',
                'password.string' => 'Password harus berupa teks.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa input Anda.');
        }

        try {
            DB::beginTransaction();

            $ponpes = Ponpes::findOrFail($id);

            $ponpesData = [
                'nama_ponpes' => $request->nama_ponpes,
                'nama_wilayah' => $request->nama_wilayah,
                'tipe' => $request->tipe,
                'tanggal' => $request->tanggal ?? $ponpes->tanggal,
            ];

            $ponpes->update($ponpesData);

            $opsionalData = [
                'pic_ponpes' => $request->pic_ponpes,
                'no_telpon' => $request->no_telpon,
                'alamat' => $request->alamat,
                'jumlah_wbp' => $request->jumlah_wbp,
                'jumlah_line' => $request->jumlah_line,
                'provider_internet' => $request->provider_internet,
                'kecepatan_internet' => $request->kecepatan_internet,
                'tarif_wartel' => $request->tarif_wartel,
                'status_wartel' => $request->status_wartel,
                'akses_topup_pulsa' => $request->akses_topup_pulsa,
                'password_topup' => $request->password_topup,
                'akses_download_rekaman' => $request->akses_download_rekaman,
                'password_download' => $request->password_download,
                'internet_protocol' => $request->internet_protocol,
                'vpn_user' => $request->vpn_user,
                'vpn_password' => $request->vpn_password,
                'jenis_vpn' => $request->jenis_vpn,
                'jumlah_extension' => $request->jumlah_extension,
                'pin_tes' => $request->pin_tes,
                'no_extension' => $request->no_extension,
                'extension_password' => $request->extension_password,
                'no_pemanggil' => $request->no_pemanggil,
                'email_airdroid' => $request->email_airdroid,
                'password' => $request->password,
            ];

            $ponpes->dataOpsional()->updateOrCreate(
                ['ponpes_id' => $ponpes->id],
                $opsionalData
            );

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function PonpesPageDestroy($id)
    {
        try {
            DB::beginTransaction();

            $ponpes = Ponpes::findOrFail($id);

            if ($ponpes->dataOpsional) {
                $ponpes->dataOpsional->delete();
            }

            $ponpes->delete();

            DB::commit();

            return redirect()->route('ListDataVtrend')->with('success', 'Data Ponpes berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportPonpesCsv($id): StreamedResponse
    {
        $ponpes = Ponpes::with('dataOpsional')->findOrFail($id);
        $dataOpsional = $ponpes->dataOpsional;

        $filename = 'data_ponpes_vtren_' . str_replace(' ', '_', $ponpes->nama_ponpes) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [
            ['Field', 'Value'],
            ['Nama Ponpes', $ponpes->nama_ponpes],
            ['Nama Wilayah', $ponpes->nama_wilayah],
            ['Tipe', $ponpes->tipe],
            ['Tanggal', $ponpes->tanggal],
            ['PIC Ponpes', $dataOpsional ? $dataOpsional->pic_ponpes : ''],
            ['No. Telpon', $dataOpsional ? $dataOpsional->no_telpon : ''],
            ['Alamat', $dataOpsional ? $dataOpsional->alamat : ''],
            ['Jumlah Santri', $dataOpsional ? $dataOpsional->jumlah_wbp : ''],
            ['Jumlah Line Reguler Terpasang', $dataOpsional ? $dataOpsional->jumlah_line : ''],
            ['Provider Internet', $dataOpsional ? $dataOpsional->provider_internet : ''],
            ['Kecepatan Internet (Mbps)', $dataOpsional ? $dataOpsional->kecepatan_internet : ''],
            ['Tarif Wartel Reguler', $dataOpsional ? $dataOpsional->tarif_wartel : ''],
            ['Status Wartel', $this->formatStatusWartel($dataOpsional->status_wartel ?? '')],
            ['Akses Topup Pulsa', $dataOpsional ? ($dataOpsional->akses_topup_pulsa) : ''],
            ['Password Topup', $dataOpsional ? $dataOpsional->password_topup : ''],
            ['Akses Download Rekaman', $dataOpsional ? ($dataOpsional->akses_download_rekaman) : ''],
            ['Password Download Rekaman', $dataOpsional ? $dataOpsional->password_download : ''],
            ['Internet Protocol', $dataOpsional ? $dataOpsional->internet_protocol : ''],
            ['VPN User', $dataOpsional ? $dataOpsional->vpn_user : ''],
            ['VPN Password', $dataOpsional ? $dataOpsional->vpn_password : ''],
            ['Jenis VPN', $dataOpsional ? $dataOpsional->jenis_vpn : ''],
            ['Jumlah Extension', $dataOpsional ? $dataOpsional->jumlah_extension : ''],
            ['No Extension', $dataOpsional ? $dataOpsional->no_extension : ''],
            ['Extension Password', $dataOpsional ? $dataOpsional->extension_password : ''],
            ['PIN Tes', $dataOpsional ? $dataOpsional->pin_tes : ''],
            ['No Pemanggil', $dataOpsional ? $dataOpsional->no_pemanggil : ''],
            ['Email Airdroid', $dataOpsional ? $dataOpsional->email_airdroid : ''],
            ['Password', $dataOpsional ? $dataOpsional->password : ''],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Data Ponpes Vtren Export - ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function formatStatusWartel($status)
    {
        if (empty($status)) {
            return '';
        }

        // Normalisasi status ke format yang benar
        $status = strtolower(trim($status));

        if ($status === 'aktif' || $status === '1' || $status === 'active') {
            return 'Aktif';
        } elseif ($status === 'tidak aktif' || $status === 'nonaktif' || $status === '0' || $status === 'inactive') {
            return 'Tidak Aktif';
        }

        // Jika sudah dalam format yang benar, kembalikan seperti semula
        return ucfirst($status);
    }

    public function exportPonpesPdf($id)
    {
        $ponpes = Ponpes::with('dataOpsional')->findOrFail($id);

        $data = [
            'title' => 'Data PONPES VTREN ' . $ponpes->nama_ponpes,
            'ponpes' => $ponpes,
        ];

        $pdf = Pdf::loadView('export.private.ponpes.indexVtren', $data);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'data_ponpes_vtren_' . str_replace(' ', '_', $ponpes->nama_ponpes) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}

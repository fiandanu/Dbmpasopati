<?php

namespace App\Http\Controllers\user\ponpes\reguller;

use App\Http\Controllers\Controller;
use App\Models\db\ponpes\DataOpsionalPonpes;
use App\Models\user\Provider;
use App\Models\user\Ponpes;
use App\Models\user\Vpn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class RegullerController extends Controller
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
        'pin_tes'
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
        if ($request->has('search_nama_ponpes') && !empty($request->search_nama_ponpes)) {
            $query->where('nama_ponpes', 'LIKE', '%' . $request->search_nama_ponpes . '%');
        }
        if ($request->has('search_nama_wilayah') && !empty($request->search_nama_wilayah)) {
            $query->where('nama_wilayah', 'LIKE', '%' . $request->search_nama_wilayah . '%');
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

    public function ListDataPonpes(Request $request)
    {
        $query = Ponpes::with('dataOpsional')->where('tipe', 'reguler');

        // Apply database filters
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (!in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
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

        return view('db.ponpes.reguller.indexReguller', compact('data', 'providers', 'vpns'));
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Ponpes::with('dataOpsional')->where('tipe', 'reguler');
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
            $data = $data->sortBy('tanggal')->values(); // Re-sort collection by tanggal
        }

        $filename = 'list_ponpes_reguler_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

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

    public function exportListPdf(Request $request)
    {
        $query = Ponpes::with('dataOpsional')->where('tipe', 'reguler');
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
                // Using 'tanggal' field
                $dateA = strtotime($a['tanggal']);
                $dateB = strtotime($b['tanggal']);
                return $dateA - $dateB; // Ascending order (oldest first)
            });
        }

        $pdfData = [
            'title' => 'List Data Ponpes Reguler',
            'data' => $dataArray,
            'optionalFields' => $this->optionalFields,
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('export.public.db.ponpes.indexReguler', $pdfData);
        $filename = 'list_ponpes_reguler_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function UserPage(Request $request)
    {
        $dataponpes = Ponpes::with(['dataOpsional', 'uploadFolder'])->get();

        return view('user.indexPonpes', compact('dataponpes'));
    }

    public function ListDataPonpesUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // Field Wajib Form Ponpes (tidak diupdate karena readonly di form)
                'nama_ponpes' => 'nullable|string|max:255',
                'nama_wilayah' => 'nullable|string|max:255',
                'tanggal' => 'nullable|date',

                // Data Opsional (Form VPAS)
                'pic_ponpes' => 'nullable|string|max:255',
                'no_telpon' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
                'alamat' => 'nullable|string',
                'jumlah_wbp' => 'nullable|integer|min:0',
                'jumlah_line' => 'nullable|integer|min:0',
                'provider_internet' => 'nullable|string|max:255',
                'kecepatan_internet' => 'nullable|string|max:255',
                'tarif_wartel' => 'nullable|numeric|min:0',
                'status_wartel' => 'nullable|string',

                // IMC PAS
                'akses_topup_pulsa' => 'nullable|string',
                'password_topup' => 'nullable|string|max:255',
                'akses_download_rekaman' => 'nullable|string',
                'password_download' => 'nullable|string|max:255',

                // AKSES VPN
                'internet_protocol' => 'nullable|string|max:255',
                'vpn_user' => 'nullable|string|max:255',
                'vpn_password' => 'nullable|string|max:255',
                'jenis_vpn' => 'nullable|string|max:255',

                // Extension Reguler
                'jumlah_extension' => 'nullable|integer|min:0',
                'no_extension' => 'nullable|string',
                'extension_password' => 'nullable|string',
                'pin_tes' => 'nullable|string|max:255',
            ],
            // Pesan Validasi
            [
                // Data Opsional (Form VPAS)
                'pic_ponpes.string' => 'PIC Ponpes harus berupa teks.',
                'no_telpon.regex' => 'Nomor telepon harus berupa angka.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'jumlah_wbp.integer' => 'Jumlah Santri harus berupa angka.',
                'jumlah_wbp.min' => 'Jumlah Santri tidak boleh negatif.',
                'jumlah_line.integer' => 'Jumlah line reguler harus berupa angka.',
                'jumlah_line.min' => 'Jumlah line reguler tidak boleh negatif.',
                'provider_internet.string' => 'Provider internet harus berupa teks.',
                'kecepatan_internet.string' => 'Kecepatan internet harus berupa teks.',
                'tarif_wartel.numeric' => 'Tarif wartel harus berupa angka.',
                'tarif_wartel.min' => 'Tarif wartel tidak boleh negatif.',
                'status_wartel.string' => 'Status wartel harus berupa string.',

                // IMC PAS
                'akses_topup_pulsa.string' => 'Akses top up pulsa harus berupa String.',
                'password_topup.string' => 'Password top up harus berupa teks.',
                'akses_download_rekaman.string' => 'Akses download rekaman harus berupa string.',
                'password_download.string' => 'Password download rekaman harus berupa teks.',

                // AKSES VPN
                'internet_protocol.string' => 'Internet Protocol harus berupa teks.',
                'vpn_user.string' => 'User VPN harus berupa teks.',
                'vpn_password.string' => 'Password VPN harus berupa teks.',
                'jenis_vpn.string' => 'Jenis VPN harus berupa teks.',

                // Extension Reguler
                'jumlah_extension.integer' => 'Jumlah extension harus berupa angka.',
                'jumlah_extension.min' => 'Jumlah extension tidak boleh negatif.',
                'no_extension.string' => 'Nomor extension harus berupa teks.',
                'extension_password.string' => 'Password extension harus berupa teks.',
                'pin_tes.string' => 'PIN Tes harus berupa teks.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam validasi data. Silakan periksa kembali.');
        }

        // Mulai database transaction
        DB::beginTransaction();

        try {
            // Find Ponpes data
            $ponpes = Ponpes::findOrFail($id);

            // Prepare data for data_opsional_ponpes table
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
                'no_extension' => $request->no_extension,
                'extension_password' => $request->extension_password,
                'pin_tes' => $request->pin_tes,
            ];

            // Update or create data_opsional_ponpes record
            $dataOpsional = DataOpsionalPonpes::updateOrCreate(
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
        $dataponpes = Ponpes::find($id);

        if (!$dataponpes) {
            return redirect()->route('ponpes.UserPage')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil nama Ponpes tanpa suffix (VtrenReg) untuk pengecekan
        $namaPonpesBase = $this->removeVtrenRegSuffix($dataponpes->nama_ponpes);

        // Hapus data yang dipilih
        $dataponpes->delete();

        // Update nama Ponpes yang tersisa berdasarkan jumlah data
        $this->updatePonpesNamesBySuffix($namaPonpesBase);

        return redirect()->route('ponpes.UserPage')->with('success', 'Data berhasil dihapus!');
    }

    public function UserPageStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => 'required|string',
                'nama_wilayah' => 'required|string',
                'tipe' => 'required|array|min:1',
                'tipe.*' => 'in:reguler,vtren',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi',
                'tipe.required' => 'Tipe harus dipilih minimal satu',
                'tipe.array' => 'Tipe harus berupa array',
                'tipe.min' => 'Pilih minimal satu tipe',
                'tipe.*.in' => 'Tipe hanya boleh reguler atau vtren',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data tipe yang dipilih
        $selectedTypes = $request->tipe;
        $createdRecords = [];

        // Bersihkan nama Ponpes dari suffix ganda yang mungkin ada
        $cleanNamaPonpes = $this->removeVtrenRegSuffix($request->nama_ponpes);

        // Tentukan nama Ponpes berdasarkan jumlah tipe yang dipilih
        $namaPonpes = $cleanNamaPonpes;
        if (count($selectedTypes) == 2 && in_array('reguler', $selectedTypes) && in_array('vtren', $selectedTypes)) {
            $namaPonpes = $cleanNamaPonpes . ' (VtrenReg)';
        }

        // Validasi manual untuk kombinasi nama Ponpes + tipe
        foreach ($selectedTypes as $tipeValue) {
            $existingRecord = Ponpes::where('nama_ponpes', $namaPonpes)
                ->where('tipe', $tipeValue)
                ->first();

            if ($existingRecord) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Data Ponpes '{$namaPonpes}' dengan tipe '{$tipeValue}' sudah ada!");
            }
        }

        // Loop untuk setiap tipe yang dipilih
        foreach ($selectedTypes as $tipeValue) {
            // Buat record baru untuk setiap tipe
            $dataponpes = [
                'nama_ponpes' => $namaPonpes,
                'nama_wilayah' => $request->nama_wilayah,
                'tipe' => $tipeValue,
                'tanggal' => Carbon::now()->format('Y-m-d'),
            ];

            $newRecord = Ponpes::create($dataponpes);
            $createdRecords[] = $tipeValue;
        }

        // Berikan pesan berdasarkan hasil
        if (count($createdRecords) > 0) {
            $message = 'Data Ponpes berhasil ditambahkan untuk tipe: ' . implode(', ', $createdRecords);
            return redirect()->route('ponpes.UserPage')->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data Ponpes');
        }
    }

    public function UserPageUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_ponpes' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($id, $request) {
                        // Cek apakah ada record lain dengan nama yang sama dan tipe yang sama
                        $existingRecord = Ponpes::where('nama_ponpes', $value)
                            ->where('id', '!=', $id)
                            ->where('tipe', $request->tipe)
                            ->first();

                        if ($existingRecord) {
                            $fail("Nama Ponpes '{$value}' dengan tipe '{$request->tipe}' sudah ada.");
                        }
                    }
                ],
                'nama_wilayah' => 'required|string',
                'tipe' => 'required|string|in:reguler,vtren',
            ],
            [
                'nama_ponpes.required' => 'Nama Ponpes harus diisi',
                'nama_wilayah.required' => 'Nama Wilayah harus diisi',
                'tipe.required' => 'Tipe harus diisi',
                'tipe.in' => 'Tipe hanya boleh reguler atau vtren',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataponpes = Ponpes::findOrFail($id);
        $dataponpes->nama_ponpes = $request->nama_ponpes;
        $dataponpes->nama_wilayah = $request->nama_wilayah;
        $dataponpes->tipe = $request->tipe;
        $dataponpes->save();

        return redirect()->route('ponpes.UserPage')->with('success', 'Data Ponpes berhasil diupdate!');
    }

    private function removeVtrenRegSuffix($namaPonpes)
    {
        // Hapus semua kemungkinan suffix (VtrenReg) yang mungkin ganda
        return preg_replace('/\s*\(VtrenReg\)+/', '', $namaPonpes);
    }

    private function updatePonpesNamesBySuffix($namaPonpesBase)
    {
        $relatedData = Ponpes::where('nama_ponpes', 'LIKE', $namaPonpesBase . '%')->get();

        // Jika ada 2 atau lebih data dengan nama base yang sama, pastikan ada suffix
        if ($relatedData->count() >= 2) {
            foreach ($relatedData as $data) {
                if (!str_contains($data->nama_ponpes, '(VtrenReg)')) {
                    $data->update([
                        'nama_ponpes' => $namaPonpesBase . ' (VtrenReg)'
                    ]);
                }
            }
        }
        // Jika hanya ada 1 data tersisa, hapus suffix
        elseif ($relatedData->count() == 1) {
            $remainingData = $relatedData->first();
            if (str_contains($remainingData->nama_ponpes, '(VtrenReg)')) {
                $remainingData->update([
                    'nama_ponpes' => $namaPonpesBase
                ]);
            }
        }
    }

    public function exportPonpesCsv($id): StreamedResponse
    {
        $ponpes = Ponpes::with('dataOpsional')->findOrFail($id);
        $dataOpsional = $ponpes->dataOpsional;

        $filename = 'data_ponpes_' . $ponpes->nama_ponpes . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $rows = [
            ['PIC Ponpes', $dataOpsional->pic_ponpes ?? ''],
            ['No. Telpon', $dataOpsional->no_telpon ?? ''],
            ['Alamat', $dataOpsional->alamat ?? ''],
            ['Nama Wilayah', $ponpes->nama_wilayah],
            ['Jumlah Santri', $dataOpsional->jumlah_wbp ?? ''],
            ['Jumlah Line Reguler Terpasang', $dataOpsional->jumlah_line ?? ''],
            ['Provider Internet', $dataOpsional->provider_internet ?? ''],
            ['Kecepatan Internet (mbps)', $dataOpsional->kecepatan_internet ?? ''],
            ['Tarif Wartel Reguler', $dataOpsional->tarif_wartel ?? ''],
            ['Status Wartel', $this->formatStatusWartel($dataOpsional->status_wartel ?? '')],
            ['Akses Topup Pulsa', $dataOpsional->akses_topup_pulsa ?? ''],
            ['Password Topup', $dataOpsional->password_topup ?? ''],
            ['Akses Download Rekaman', $dataOpsional->akses_download_rekaman ?? ''],
            ['Password Download Rekaman', $dataOpsional->password_download ?? ''],
            ['Internet Protocol', $dataOpsional->internet_protocol ?? ''],
            ['VPN User', $dataOpsional->vpn_user ?? ''],
            ['VPN Password', $dataOpsional->vpn_password ?? ''],
            ['Jenis VPN', $dataOpsional->jenis_vpn ?? ''],
            ['Jumlah Extension', $dataOpsional->jumlah_extension ?? ''],
            ['No Extension', $dataOpsional->no_extension ?? ''],
            ['Extension Password', $dataOpsional->extension_password ?? ''],
            ['PIN Tes', $dataOpsional->pin_tes ?? ''],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPonpesPdf($id)
    {
        $ponpes = Ponpes::with('dataOpsional')->findOrFail($id);

        $data = [
            'title' => 'Data Pondok Pesantren Reguler ' . $ponpes->nama_ponpes,
            'ponpes' => $ponpes,
        ];

        $pdf = Pdf::loadView('export.private.ponpes.indexPonpes', $data);
        return $pdf->download('data_ponpes_' . $ponpes->nama_ponpes . '.pdf');
    }
}

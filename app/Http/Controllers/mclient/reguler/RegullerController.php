<?php

namespace App\Http\Controllers\mclient\reguler;

use App\Http\Controllers\Controller;
use App\Models\mclient\Kunjungan;
use App\Models\mclient\Pengiriman;
use App\Models\mclient\Reguller;
use App\Models\mclient\SettingAlat;
use App\Models\mclient\Vpas;
use App\Models\user\kendala\Kendala;
use App\Models\user\pic\Pic;
use App\Models\user\upt\Upt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegullerController extends Controller
{
    public function monitoringClientUptOverview(Request $request)
    {
        // Collect data from all monitoring client tables
        $regulerData = Reguller::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Komplain Reguler',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_terlapor,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        $vpasData = Vpas::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Komplain Vpas',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_terlapor,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        $kunjunganData = Kunjungan::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Kunjungan upt',
                'jenis_kendala' => $item->keterangan ?? 'Monitoring rutin',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->jadwal,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        $pengirimanData = Pengiriman::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Pengiriman Alat',
                'jenis_kendala' => $item->keterangan ?? 'Pengiriman alat',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_pengiriman,
                'tanggal_selesai' => $item->tanggal_sampai,
                'created_at' => $item->created_at,
            ];
        });

        $settingAlatData = SettingAlat::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Setting Alat',
                'jenis_kendala' => $item->keterangan ?? 'Setting alat',
                'status' => $item->status ?? 'Belum ditentukan',
                'tanggal_terlapor' => $item->tanggal_terlapor,
                'tanggal_selesai' => $item->tanggal_selesai,
                'created_at' => $item->created_at,
            ];
        });

        // Merge all data
        $allData = collect()
            ->merge($regulerData)
            ->merge($vpasData)
            ->merge($kunjunganData)
            ->merge($pengirimanData)
            ->merge($settingAlatData);

        // Apply filters
        $allData = $this->applyMonitoringFilters($allData, $request);

        // Sort by created_at desc
        $allData = $allData->sortByDesc('created_at')->values();

        // Calculate statistics
        $totalKomplain = $allData->count();
        $totalVpas = $vpasData->count();
        $totalReguler = $regulerData->count();
        $totalKunjungan = $kunjunganData->count();
        $totalPengiriman = $pengirimanData->count();
        $totalSettingAlat = $settingAlatData->count();

        // Pagination
        $perPage = $request->get('per_page', 10);

        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 10;
        }

        if ($perPage == 'all') {
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $allData,
                $allData->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $currentItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $allData->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('mclient.indexUpt', compact(
            'data',
            'totalKomplain',
            'totalVpas',
            'totalReguler',
            'totalKunjungan',
            'totalPengiriman',
            'totalSettingAlat'
        ));
    }

    private function applyMonitoringFilters(Collection $collection, Request $request)
    {
        // Filter by nama_upt
        if ($request->has('search_nama_upt') && ! empty($request->search_nama_upt)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['nama_upt'], $request->search_nama_upt) !== false;
            });
        }

        // Filter by kanwil
        if ($request->has('search_kanwil') && ! empty($request->search_kanwil)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['kanwil'], $request->search_kanwil) !== false;
            });
        }

        // Filter by tipe
        if ($request->has('search_tipe') && ! empty($request->search_tipe)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['tipe'], $request->search_tipe) !== false;
            });
        }

        // Filter by jenis_layanan
        if ($request->has('search_jenis_layanan') && !empty($request->search_jenis_layanan)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['jenis_layanan'], $request->search_jenis_layanan) !== false;
            });
        }

        // Filter by jenis_kendala
        if ($request->has('search_jenis_kendala') && ! empty($request->search_jenis_kendala)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return stripos($item['jenis_kendala'], $request->search_jenis_kendala) !== false;
            });
        }

        // Filter by status
        if ($request->has('search_status') && ! empty($request->search_status)) {
            $searchStatus = strtolower($request->search_status);
            $collection = $collection->filter(function ($item) use ($searchStatus) {
                $itemStatus = strtolower($item['status']);

                if (stripos($itemStatus, $searchStatus) !== false) {
                    return true;
                }

                if ((str_contains($searchStatus, 'belum') || str_contains($searchStatus, 'ditentukan'))
                    && ($itemStatus == '' || $itemStatus == 'belum ditentukan')
                ) {
                    return true;
                }

                return false;
            });
        }

        // Date range filters
        if ($request->has('search_tanggal_dari') && ! empty($request->search_tanggal_dari)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return $item['tanggal_terlapor'] &&
                    $item['tanggal_terlapor']->format('Y-m-d') >= $request->search_tanggal_dari;
            });
        }

        if ($request->has('search_tanggal_sampai') && ! empty($request->search_tanggal_sampai)) {
            $collection = $collection->filter(function ($item) use ($request) {
                return $item['tanggal_terlapor'] &&
                    $item['tanggal_terlapor']->format('Y-m-d') <= $request->search_tanggal_sampai;
            });
        }

        return $collection;
    }

    public function ListDataMclientReguller(Request $request)
    {
        $query = Reguller::with(['upt.kanwil']);

        // Apply filter
        $query = $this->applyFilters($query, $request);

        // Get per_page from request, default 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page
        if (! in_array($perPage, [10, 15, 20, 'all'])) {
            $perPage = 20;
        }

        // Handle pagination
        if ($perPage == 'all') {
            $data = $query->orderBy('created_at', 'desc')->get();

            // Create a mock paginator for "all" option
            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $data->count(),
                99999,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
        }

        $jenisKendala = Kendala::orderBy('jenis_kendala')->get();
        $picList = Pic::orderBy('nama_pic')->get();

        $uptList = Upt::with('kanwil')
            ->where('tipe', 'reguler')
            ->orderBy('namaupt')
            ->get();

        return view('mclient.upt.indexReguller', compact('data', 'jenisKendala', 'picList', 'uptList'));
    }

    private function applyFilters($query, Request $request)
    {
        // Column-specific searches
        if ($request->has('search_nama_upt') && ! empty($request->search_nama_upt)) {
            $query->whereHas('upt', function ($q) use ($request) {
                $q->where('namaupt', 'LIKE', '%' . $request->search_nama_upt . '%');
            });
        }

        if ($request->has('search_kanwil') && ! empty($request->search_kanwil)) {
            $query->whereHas('upt.kanwil', function ($q) use ($request) {
                $q->where('kanwil', 'LIKE', '%' . $request->search_kanwil . '%');
            });
        }

        if ($request->has('search_detail_kendala') && ! empty($request->search_detail_kendala)) {
            $query->where('detail_kendala', 'LIKE', '%' . $request->search_detail_kendala . '%');
        }

        if ($request->has('search_jenis_kendala') && ! empty($request->search_jenis_kendala)) {
            $searchJenisKendala = strtolower($request->search_jenis_kendala);
            $query->where(function ($q) use ($searchJenisKendala) {
                $q->where('jenis_kendala', 'LIKE', '%' . $searchJenisKendala . '%');
                // Jika mencari "belum" atau "ditentukan", include yang NULL/empty
                if (str_contains($searchJenisKendala, 'belum') || str_contains($searchJenisKendala, 'ditentukan')) {
                    $q->orWhereNull('jenis_kendala')
                        ->orWhere('jenis_kendala', '');
                }
            });
        }

        if ($request->has('search_status') && ! empty($request->search_status)) {
            $searchStatus = strtolower($request->search_status);

            $query->where(function ($q) use ($searchStatus) {
                $q->where('status', 'LIKE', '%' . $searchStatus . '%');

                // Jika mencari "belum" atau "ditentukan", include yang NULL/empty
                if (str_contains($searchStatus, 'belum') || str_contains($searchStatus, 'ditentukan')) {
                    $q->orWhereNull('status')
                        ->orWhere('status', '');
                }
            });
        }

        if ($request->has('search_pic_1') && ! empty($request->search_pic_1)) {
            $query->where('pic_1', 'LIKE', '%' . $request->search_pic_1 . '%');
        }
        if ($request->has('search_pic_2') && ! empty($request->search_pic_2)) {
            $query->where('pic_2', 'LIKE', '%' . $request->search_pic_2 . '%');
        }

        // Date range filtering
        if ($request->has('search_tanggal_terlapor_dari') && ! empty($request->search_tanggal_terlapor_dari)) {
            $query->whereDate('tanggal_terlapor', '>=', $request->search_tanggal_terlapor_dari);
        }
        if ($request->has('search_tanggal_terlapor_sampai') && ! empty($request->search_tanggal_terlapor_sampai)) {
            $query->whereDate('tanggal_terlapor', '<=', $request->search_tanggal_terlapor_sampai);
        }
        if ($request->has('search_tanggal_selesai_dari') && ! empty($request->search_tanggal_selesai_dari)) {
            $query->whereDate('tanggal_selesai', '>=', $request->search_tanggal_selesai_dari);
        }
        if ($request->has('search_tanggal_selesai_sampai') && ! empty($request->search_tanggal_selesai_sampai)) {
            $query->whereDate('tanggal_selesai', '<=', $request->search_tanggal_selesai_sampai);
        }

        return $query;
    }

    public function MclientRegullerStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data_upt_id' => 'required|exists:data_upt,id',
                'kanwil' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'data_upt_id.required' => 'Nama UPT harus diisi.',
                'data_upt_id.string' => 'Nama UPT harus berupa teks.',
                'data_upt_id.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'kanwil.string' => 'Kanwil harus berupa teks.',
                'kanwil.max' => 'Kanwil tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala Reguller harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala Reguller harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            $data = $request->all();

            if ($request->tanggal_selesai) {
                if ($request->tanggal_terlapor) {
                    $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                } else {
                    $tanggalTerlapor = Carbon::now();
                    $data['tanggal_terlapor'] = $tanggalTerlapor;
                }

                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $data['durasi_hari'] = $tanggalTerlapor->diffInDays($tanggalSelesai);
            } else {
                $data['durasi_hari'] = null;
            }

            Reguller::create($data);

            return redirect()->back()->with('success', 'Data monitoring client Reguller berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function MclientRegullerUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data_upt_id' => 'required|exists:data_upt,id',
                'kanwil' => 'nullable|string|max:255',
                'jenis_kendala' => 'nullable|string',
                'detail_kendala' => 'nullable|string',
                'tanggal_terlapor' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_terlapor',
                'durasi_hari' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:pending,proses,selesai,terjadwal',
                'pic_1' => 'nullable|string|max:255',
                'pic_2' => 'nullable|string|max:255',
            ],
            [
                'data_upt_id.required' => 'Nama UPT harus diisi.',
                'data_upt_id.string' => 'Nama UPT harus berupa teks.',
                'data_upt_id.max' => 'Nama UPT tidak boleh lebih dari 255 karakter.',
                'kanwil.string' => 'Kanwil harus berupa teks.',
                'kanwil.max' => 'Kanwil tidak boleh lebih dari 255 karakter.',
                'jenis_kendala.string' => 'Kendala Reguller harus berupa teks.',
                'detail_kendala.string' => 'Detail kendala Reguller harus berupa teks.',
                'tanggal_terlapor.date' => 'Format tanggal terlapor harus valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai harus valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal terlapor.',
                'durasi_hari.integer' => 'Durasi hari harus berupa angka.',
                'durasi_hari.min' => 'Durasi hari tidak boleh negatif.',
                'status.in' => 'Status harus salah satu dari: pending, proses, selesai, atau terjadwal.',
                'pic_1.string' => 'PIC 1 harus berupa teks.',
                'pic_1.max' => 'PIC 1 tidak boleh lebih dari 255 karakter.',
                'pic_2.string' => 'PIC 2 harus berupa teks.',
                'pic_2.max' => 'PIC 2 tidak boleh lebih dari 255 karakter.',
            ]
        );

        // Jika validation gagal, LANGSUNG return tanpa update apapun
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan perbaiki data yang bermasalah.');
        }

        try {
            $data = Reguller::findOrFail($id);
            $updateData = $request->all();

            if ($request->tanggal_selesai) {
                if ($request->tanggal_terlapor) {
                    $tanggalTerlapor = Carbon::parse($request->tanggal_terlapor);
                } else {
                    $tanggalTerlapor = Carbon::parse($data->tanggal_terlapor ?? $data->created_at);
                }
                $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
                $updateData['durasi_hari'] = $tanggalTerlapor->diffInDays($tanggalSelesai);
            } elseif ($request->has('tanggal_selesai') && empty($request->tanggal_selesai)) {
                $updateData['durasi_hari'] = null;
            }

            $data->update($updateData);

            return redirect()->back()->with('success', 'Data monitoring client Reguller berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function MclientRegullerDestroy($id)
    {
        try {
            $data = Reguller::findOrFail($id);
            $namaUpt = $data->nama_upt;
            $data->delete();

            return redirect()->back()
                ->with('success', "Data monitoring client Reguller di UPT '{$namaUpt}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }



    // EXPORT DATA MONIORING CLIENT
    public function exportMonitoringClientCsv(Request $request): StreamedResponse
    {
        // Get all data
        $regulerData = Reguller::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Reguler',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $vpasData = Vpas::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Vpas',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $kunjunganData = Kunjungan::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Kunjungan',
                'jenis_kendala' => $item->keterangan ?? 'Monitoring rutin',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $pengirimanData = Pengiriman::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Pengiriman Alat',
                'jenis_kendala' => $item->keterangan ?? 'Pengiriman alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $settingAlatData = SettingAlat::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Setting Alat',
                'jenis_kendala' => $item->keterangan ?? 'Setting alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $allData = collect()
            ->merge($regulerData)
            ->merge($vpasData)
            ->merge($kunjunganData)
            ->merge($pengirimanData)
            ->merge($settingAlatData);

        // Apply filters
        $allData = $this->applyMonitoringFilters($allData, $request);

        $filename = 'monitoring_client_upt_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Nama UPT', 'Kanwil', 'Tipe', 'Jenis Layanan', 'Jenis Kendala', 'Status']];
        $no = 1;

        foreach ($allData as $row) {
            $rows[] = [
                $no++,
                $row['nama_upt'],
                $row['kanwil'],
                ucfirst($row['tipe']),
                $row['jenis_layanan'],
                $row['jenis_kendala'],
                ucfirst($row['status']),
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

    public function exportMonitoringClientPdf(Request $request)
    {
        // Similar to CSV but return PDF
        $regulerData = Reguller::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Reguler',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $vpasData = Vpas::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Vpas',
                'jenis_kendala' => $item->jenis_kendala ?? 'Belum ditentukan',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $kunjunganData = Kunjungan::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Kunjungan',
                'jenis_kendala' => $item->keterangan ?? 'Monitoring rutin',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $pengirimanData = Pengiriman::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Pengiriman Alat',
                'jenis_kendala' => $item->keterangan ?? 'Pengiriman alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $settingAlatData = SettingAlat::with(['upt.kanwil'])->get()->map(function ($item) {
            return [
                'nama_upt' => $item->upt->namaupt ?? '-',
                'kanwil' => $item->upt->kanwil->kanwil ?? '-',
                'tipe' => $item->upt->tipe ?? '-',
                'jenis_layanan' => 'Setting Alat',
                'jenis_kendala' => $item->keterangan ?? 'Setting alat',
                'status' => $item->status ?? 'Belum ditentukan',
            ];
        });

        $allData = collect()
            ->merge($regulerData)
            ->merge($vpasData)
            ->merge($kunjunganData)
            ->merge($pengirimanData)
            ->merge($settingAlatData);

        $allData = $this->applyMonitoringFilters($allData, $request);

        $pdfData = [
            'title' => 'Monitoring Client UPT',
            'data' => $allData,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.mclient.MclientUpt', $pdfData);
        $filename = 'monitoring_client_upt_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    // EXPORT GLOBAL DATA PDF DAN CSV
    public function exportListPdf(Request $request)
    {
        $query = Reguller::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_terlapor_dari') || $request->filled('search_tanggal_terlapor_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_terlapor', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $pdfData = [
            'title' => 'List Data Monitoring Client Reguler',
            'data' => $data,
            'generated_at' => Carbon::now()->format('d M Y H:i:s'),
        ];

        $pdf = Pdf::loadView('export.public.mclient.upt.indexReguller', $pdfData);
        $filename = 'list_monitoring_client_reguler_' . Carbon::now()->translatedFormat('d_M_Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportListCsv(Request $request): StreamedResponse
    {
        $query = Reguller::query();
        $query = $this->applyFilters($query, $request);

        // Add date sorting when date filters are applied
        if (
            $request->filled('search_tanggal_terlapor_dari') || $request->filled('search_tanggal_terlapor_sampai') ||
            $request->filled('search_tanggal_selesai_dari') || $request->filled('search_tanggal_selesai_sampai')
        ) {
            $query = $query->orderBy('tanggal_terlapor', 'asc');
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $filename = 'list_monitoring_client_reguler_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $rows = [['No', 'Nama UPT', 'Kanwil', 'Jenis Kendala', 'Detail Kendala', 'Tanggal Terlapor', 'Tanggal Selesai', 'Durasi (Hari)', 'Status', 'PIC 1', 'PIC 2', 'Dibuat Pada']];
        $no = 1;
        foreach ($data as $row) {
            $rows[] = [
                $no++,
                $row->upt->namaupt,
                $row->upt->kanwil->kanwil,
                $row->jenis_kendala,
                $row->detail_kendala,
                $row->tanggal_terlapor ? $row->tanggal_terlapor->format('Y-m-d') : '',
                $row->tanggal_selesai ? $row->tanggal_selesai->format('Y-m-d') : '',
                $row->durasi_hari,
                $row->status,
                $row->pic_1,
                $row->pic_2,
                $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
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
}

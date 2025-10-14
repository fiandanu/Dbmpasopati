@extends('layout.sidebar')
@section('content')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <!-- Left navbar links -->
                        <div class="d-flex justify-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data UPT VPAS</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Export Buttons -->
                            <div class="d-flex gap-2" id="export-buttons">
                                <button onclick="downloadCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download PDF"
                                    title="Download PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-start align-items-center gap-12 mb-2">
                    <div class="btn-searchbar column-search">
                        <input type="date" id="search-tanggal-dari" name="search_tanggal_dari" title="Tanggal Dari">
                    </div>
                    <div class="btn-searchbar column-search">
                        <input type="date" id="search-tanggal-sampai" name="search_tanggal_sampai"
                            title="Tanggal Sampai">
                    </div>
                </div>
            </div>
        </section>

        <!-- Alert messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Berhasil!</div>
                        <div class="small">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session('partial_success'))
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Sebagian Data Tersimpan</div>
                        <div class="small">{{ session('partial_success') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Periksa kembali Data yang dimasukkan</div>
                        <div class="small">
                            @foreach ($errors->all() as $error)
                                <div class="mb-1">• {{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Error!</div>
                        <div class="small">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="Table">
                            <thead>
                                <tr>
                                    <th class="text-center align-top">
                                        <div class="d-flex flex-column gap-12">
                                            <span>No</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn-purple w-auto" onclick="applyFilters()"
                                                    title="Cari Semua Filter">
                                                    <i class="fas fa-search"></i> Cari
                                                </button>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="align-top">
                                        <div class="d-flex flex-column gap-12">
                                            <span>Nama UPT</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-namaupt" name="search_namaupt">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="align-top">
                                        <div class="d-flex flex-column gap-12">
                                            <span>Nama Kanwil</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-kanwil" name="search_kanwil">
                                            </div>
                                        </div>
                                    </th>
                                    {{-- <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Tipe</span>
                                        </div>
                                    </th> --}}
                                    <th class="text-center align-top">
                                        <span>Tanggal</span>
                                    </th>
                                    <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Status Update</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-status" name="search_status">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center align-top">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Calculate starting number for pagination
                                    if (request('per_page') == 'all') {
                                        $no = 1;
                                    } else {
                                        $no = ($data->currentPage() - 1) * $data->perPage() + 1;
                                    }
                                @endphp
                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $d->namaupt }}</td>
                                        <td><span class="tag tag-success">{{ $d->kanwil->kanwil }}</span></td>
                                        {{-- <td class="text-center">
                                            <span class="Tipevpas">{{ ucfirst($d->tipe) }}</span>
                                        </td> --}}
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('M d Y') }}
                                        </td>
                                        <td class="d-flex justify-center align-items-center">
                                            @php
                                                $dataOpsional = $d->dataOpsional;
                                                $optionalFields = [
                                                    'vpns_id',
                                                    'pic_upt',
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
                                                    'jumlah_extension',
                                                    'no_pemanggil',
                                                    'email_airdroid',
                                                    'password',
                                                    'pin_tes',
                                                ];
                                                $filledFields = 0;
                                                if ($dataOpsional) {
                                                    foreach ($optionalFields as $field) {
                                                        if (!empty($dataOpsional->$field)) {
                                                            $filledFields++;
                                                        }
                                                    }
                                                }
                                                $totalFields = count($optionalFields);
                                                $percentage =
                                                    $totalFields > 0 ? ($filledFields / $totalFields) * 100 : 0;
                                            @endphp
                                            @if ($filledFields == 0)
                                                <span class="badge body-small-12">Belum di Update</span>
                                            @elseif($filledFields == $totalFields)
                                                <span class="badge-succes">Sudah Update</span>
                                            @else
                                                <span class="badge-prosses">Sebagian ({{ round($percentage) }}%)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button href="#editModal{{ $d->id }}" data-toggle="modal"
                                                data-target="#editModal{{ $d->id }}" title="Edit">
                                                <ion-icon name="pencil-outline"></ion-icon>
                                            </button>
                                            <a href="{{ route('vpas.export.vpas.pdf', $d->id) }}" title="Unduh PDF">
                                                <button>
                                                    <ion-icon name="document-outline"></ion-icon>
                                                </button>
                                            </a>
                                            <a href="{{ route('vpas.export.vpas.csv', $d->id) }}" title="Unduh CSV">
                                                <button>
                                                    <ion-icon name="document-text-outline"></ion-icon>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($data->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>Tidak ada data UPT VPAS yang tersedia</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal edit for each item -->
                    @foreach ($data as $d)
                        <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                            <form id="editForm{{ $d->id }}"
                                action="{{ route('vpas.ListUpdateVpas', ['id' => $d->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit
                                                Data VPAS</label>
                                            <button type="button" class="btn-close-custom" data-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="{{ $d->id }}">
                                            @php
                                                $dataOpsional = $d->dataOpsional;
                                            @endphp
                                            <!-- Data Wajib Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Data Wajib</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="namaupt{{ $d->id }}" class="form-label">Nama
                                                        UPT</label>
                                                    <input type="text" class="form-control"
                                                        id="namaupt{{ $d->id }}" name="namaupt"
                                                        value="{{ $d->namaupt }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kanwil{{ $d->id }}"
                                                        class="form-label">Kanwil</label>
                                                    <input type="text" class="form-control"
                                                        id="kanwil{{ $d->id }}" name="kanwil"
                                                        value="{{ $d->kanwil->kanwil }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tipe{{ $d->id }}" class="form-label">Tipe</label>
                                                    <input type="text" class="form-control"
                                                        id="tipe{{ $d->id }}" name="tipe"
                                                        value="{{ ucfirst($d->tipe) }}" readonly>
                                                </div>
                                            </div>
                                            <!-- Data Opsional Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Data Opsional</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="pic_upt{{ $d->id }}" class="form-label">PIC
                                                        UPT</label>
                                                    <input type="text" class="form-control"
                                                        id="pic_upt{{ $d->id }}" name="pic_upt"
                                                        value="{{ $dataOpsional->pic_upt ?? '' }}"
                                                        placeholder="Masukkan nama PIC UPT">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="no_telpon{{ $d->id }}" class="form-label">No
                                                        Telepon</label>
                                                    <input type="text" class="form-control"
                                                        id="no_telpon{{ $d->id }}" name="no_telpon"
                                                        value="{{ $dataOpsional->no_telpon ?? '' }}"
                                                        placeholder="Masukkan nomor telepon">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="alamat{{ $d->id }}"
                                                        class="form-label">Alamat</label>
                                                    <input type="text" class="form-control"
                                                        id="alamat{{ $d->id }}" name="alamat"
                                                        value="{{ $dataOpsional->alamat ?? '' }}"
                                                        placeholder="Masukkan alamat lengkap">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_wbp{{ $d->id }}" class="form-label">Jumlah
                                                        WBP</label>
                                                    <input type="number" class="form-control"
                                                        id="jumlah_wbp{{ $d->id }}" name="jumlah_wbp"
                                                        value="{{ $dataOpsional->jumlah_wbp ?? '' }}"
                                                        placeholder="Masukkan jumlah WBP">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_line{{ $d->id }}" class="form-label">Jumlah
                                                        Line VPAS Terpasang</label>
                                                    <input type="number" class="form-control"
                                                        id="jumlah_line{{ $d->id }}" name="jumlah_line"
                                                        value="{{ $dataOpsional->jumlah_line ?? '' }}"
                                                        placeholder="Masukkan jumlah line VPAS">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="provider_internet{{ $d->id }}"
                                                        class="form-label">Provider
                                                        Internet</label>
                                                    <select class="form-control"
                                                        id="provider_internet{{ $d->id }}"
                                                        name="provider_internet">
                                                        <option value="">-- Pilih Provider --</option>
                                                        @foreach ($providers as $p)
                                                            <option value="{{ $p->nama_provider }}"
                                                                {{ ($dataOpsional->provider_internet ?? '') == $p->nama_provider ? 'selected' : '' }}>
                                                                {{ $p->nama_provider }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kecepatan_internet{{ $d->id }}"
                                                        class="form-label">Kecepatan
                                                        Internet (Mbps)</label>
                                                    <input type="text" class="form-control"
                                                        id="kecepatan_internet{{ $d->id }}"
                                                        name="kecepatan_internet"
                                                        value="{{ $dataOpsional->kecepatan_internet ?? '' }}"
                                                        placeholder="Contoh: 20 Mbps">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tarif_wartel{{ $d->id }}" class="form-label">Tarif
                                                        Wartel VPAS</label>
                                                    <input type="text" class="form-control"
                                                        id="tarif_wartel{{ $d->id }}" name="tarif_wartel"
                                                        value="{{ $dataOpsional->tarif_wartel ?? '' }}"
                                                        placeholder="Contoh: 3000">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status_wartel{{ $d->id }}"
                                                        class="form-label">Status
                                                        Wartel</label>
                                                    <select class="form-control" id="status_wartel{{ $d->id }}"
                                                        name="status_wartel">
                                                        <option value="">-- Pilih Status --</option>
                                                        <option value="1"
                                                            {{ ($dataOpsional->status_wartel ?? 0) == 1 ? 'selected' : '' }}>
                                                            Aktif
                                                        </option>
                                                        <option value="0"
                                                            {{ ($dataOpsional->status_wartel ?? 0) == 0 ? 'selected' : '' }}>
                                                            Tidak
                                                            Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- IMC PAS Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>IMC PAS</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="akses_topup_pulsa{{ $d->id }}"
                                                        class="form-label">Akses Top Up
                                                        Pulsa</label>
                                                    <input type="text" class="form-control"
                                                        id="akses_topup_pulsa{{ $d->id }}"
                                                        name="akses_topup_pulsa"
                                                        value="{{ $dataOpsional->akses_topup_pulsa ?? '' }}"
                                                        placeholder="Masukkan akses top up pulsa">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password_topup{{ $d->id }}"
                                                        class="form-label">Password Top Up
                                                        Pulsa</label>
                                                    <input type="text" class="form-control"
                                                        id="password_topup{{ $d->id }}" name="password_topup"
                                                        value="{{ $dataOpsional->password_topup ?? '' }}"
                                                        placeholder="Masukkan password top up">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="akses_download_rekaman{{ $d->id }}"
                                                        class="form-label">Akses
                                                        Download Rekaman</label>
                                                    <input type="text" class="form-control"
                                                        id="akses_download_rekaman{{ $d->id }}"
                                                        name="akses_download_rekaman"
                                                        value="{{ $dataOpsional->akses_download_rekaman ?? '' }}"
                                                        placeholder="Masukkan akses download rekaman">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password_download{{ $d->id }}"
                                                        class="form-label">Password
                                                        Download Rekaman</label>
                                                    <input type="text" class="form-control"
                                                        id="password_download{{ $d->id }}"
                                                        name="password_download"
                                                        value="{{ $dataOpsional->password_download ?? '' }}"
                                                        placeholder="Masukkan password download">
                                                </div>
                                            </div>
                                            <!-- Akses VPN Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Akses VPN</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="internet_protocol{{ $d->id }}"
                                                        class="form-label">Internet
                                                        Protocol</label>
                                                    <input type="text" class="form-control"
                                                        id="internet_protocol{{ $d->id }}"
                                                        name="internet_protocol"
                                                        value="{{ $dataOpsional->internet_protocol ?? '' }}"
                                                        placeholder="Masukkan alamat IP atau domain">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="vpn_user{{ $d->id }}" class="form-label">User
                                                        VPN</label>
                                                    <input type="text" class="form-control"
                                                        id="vpn_user{{ $d->id }}" name="vpn_user"
                                                        value="{{ $dataOpsional->vpn_user ?? '' }}"
                                                        placeholder="Masukkan username VPN">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="vpn_password{{ $d->id }}"
                                                        class="form-label">Password
                                                        VPN</label>
                                                    <input type="text" class="form-control"
                                                        id="vpn_password{{ $d->id }}" name="vpn_password"
                                                        value="{{ $dataOpsional->vpn_password ?? '' }}"
                                                        placeholder="Masukkan password VPN">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="vpns_id{{ $d->id }}" class="form-label">Jenis
                                                        VPN</label>
                                                    <select class="form-control" id="vpns_id{{ $d->id }}"
                                                        name="vpns_id">
                                                        <option value="">-- Pilih Jenis VPN --</option>
                                                        @if (isset($vpns) && $vpns->count() > 0)
                                                            @foreach ($vpns as $p)
                                                                <option value="{{ $p->id }}"
                                                                    {{ ($dataOpsional->vpns_id ?? '') == $p->id ? 'selected' : '' }}>
                                                                    {{ $p->jenis_vpn }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option value="" disabled>Tidak ada data VPN tersedia
                                                            </option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Extension VPAS -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Extension VPAS</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_extension{{ $d->id }}"
                                                        class="form-label">Jumlah
                                                        Extension</label>
                                                    <input type="number" class="form-control"
                                                        id="jumlah_extension{{ $d->id }}" name="jumlah_extension"
                                                        value="{{ $dataOpsional->jumlah_extension ?? '' }}"
                                                        placeholder="Masukkan jumlah">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="pin_tes{{ $d->id }}" class="form-label">Pin
                                                        Test</label>
                                                    <input type="text" class="form-control"
                                                        id="pin_tes{{ $d->id }}" name="pin_tes"
                                                        value="{{ $dataOpsional->pin_tes ?? '' }}"
                                                        placeholder="Masukkan Pin Test">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="no_pemanggil{{ $d->id }}" class="form-label">No
                                                        Pemanggil</label>
                                                    <small class="text-muted d-block mb-2">Masukkan setiap nomor pemanggil
                                                        pada baris
                                                        terpisah</small>
                                                    <textarea class="form-control" id="no_pemanggil{{ $d->id }}" name="no_pemanggil" rows="6"
                                                        placeholder="Contoh:&#10;201&#10;202&#10;203">{{ $dataOpsional->no_pemanggil ?? '' }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email_airdroid{{ $d->id }}"
                                                        class="form-label">Email AirDroid</label>
                                                    <small class="text-muted d-block mb-2">Masukkan setiap email AirDroid
                                                        pada baris terpisah</small>
                                                    <textarea class="form-control" id="email_airdroid{{ $d->id }}" name="email_airdroid" rows="6"
                                                        placeholder="Contoh:&#10;email1@example.com&#10;email2@example.com&#10;email3@example.com">{{ $dataOpsional->email_airdroid ?? '' }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password{{ $d->id }}" class="form-label">Password
                                                    </label>
                                                    <small class="text-muted d-block mb-2">Masukkan setiap password
                                                        baris terpisah (sesuai urutan nomor AirDroid di atas)</small>
                                                    <textarea class="form-control" id="password{{ $d->id }}" name="password" rows="6"
                                                        placeholder="Contoh:&#10;password1&#10;password2&#10;password3">{{ $dataOpsional->password ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-cancel-modal"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-purple">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach

                </div>

                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                <!-- Preserve all search parameters -->
                                @if (request('search_namaupt'))
                                    <input type="hidden" name="search_namaupt"
                                        value="{{ request('search_namaupt') }}">
                                @endif
                                @if (request('search_kanwil'))
                                    <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
                                @endif
                                @if (request('search_tipe'))
                                    <input type="hidden" name="search_tipe" value="{{ request('search_tipe') }}">
                                @endif
                                @if (request('search_tanggal_dari'))
                                    <input type="hidden" name="search_tanggal_dari"
                                        value="{{ request('search_tanggal_dari') }}">
                                @endif
                                @if (request('search_tanggal_sampai'))
                                    <input type="hidden" name="search_tanggal_sampai"
                                        value="{{ request('search_tanggal_sampai') }}">
                                @endif
                                @if (request('search_status'))
                                    <input type="hidden" name="search_status" value="{{ request('search_status') }}">
                                @endif

                                <div class="d-flex align-items-center">
                                    <select name="per_page" class="form-control form-control-sm pr-2"
                                        style="width: auto;" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15
                                        </option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20
                                        </option>
                                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                                        </option>
                                    </select>
                                    <span>Rows</span>
                                </div>
                            </form>
                        </div>

                        <div class="text-muted">
                            @if (request('per_page') != 'all')
                                Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }}
                                dari {{ $data->total() }} data
                            @else
                                Menampilkan semua {{ $data->total() }} data
                            @endif
                        </div>
                    </div>

                    <!-- Right: Navigation (hanya tampil jika tidak pilih "Semua") -->
                    @if (request('per_page') != 'all' && $data->lastPage() > 1)
                        <div class="pagination-controls d-flex align-items-center gap-12">
                            @if ($data->onFirstPage())
                                <button class="btn-page" disabled>&laquo; Previous</button>
                            @else
                                <button class="btn-datakolom w-auto p-3">
                                    <a href="{{ $data->appends(request()->query())->previousPageUrl() }}">&laquo;
                                        Previous</a>
                                </button>
                            @endif

                            <span id="page-info">Page {{ $data->currentPage() }} of {{ $data->lastPage() }}</span>

                            @if ($data->hasMorePages())
                                <button class="btn-datakolom w-auto p-3">
                                    <a href="{{ $data->appends(request()->query())->nextPageUrl() }}">Next&raquo;</a>
                                </button>
                            @else
                                <button class="btn-page" disabled>Next &raquo;</button>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    <!-- jQuery Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Search By Column JavaScript -->
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_namaupt: $('#search-namaupt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_tanggal_dari: $('#search-tanggal-dari').val().trim(),
                    search_tanggal_sampai: $('#search-tanggal-sampai').val().trim(),
                    search_status: $('#search-status').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect (GLOBAL - bisa dipanggil dari tombol)
            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove existing filter parameters
                url.searchParams.delete('search_namaupt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('search_status');
                url.searchParams.delete('page'); // Reset to page 1

                // Add non-empty filters
                Object.keys(filters).forEach(key => {
                    if (filters[key] && filters[key].trim() !== '' && key !== 'per_page') {
                        url.searchParams.set(key, filters[key]);
                    }
                });

                window.location.href = url.toString();
            };

            // Function to clear all search filters (GLOBAL - bisa dipanggil dari tombol Reset)
            window.clearAllFilters = function() {
                // Clear semua input field dulu
                $('#search-namaupt').val('');
                $('#search-kanwil').val('');
                $('#search-tanggal-dari').val('');
                $('#search-tanggal-sampai').val('');
                $('#search-status').val('');

                let url = new URL(window.location.href);

                // Remove all search parameters
                url.searchParams.delete('search_namaupt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('search_status');
                url.searchParams.delete('page');

                window.location.href = url.toString();
            };

            // Bind keypress event to all search input fields (Enter masih berfungsi)
            $('.column-search input').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    applyFilters();
                }
            });

            // Clear individual column search when input is emptied
            $('.column-search input').on('keyup', function(e) {
                if (e.which === 13 && $(this).val().trim() === '') {
                    applyFilters(); // Apply filters to update URL (removing empty filter)
                }
            });

            // Download functions with current filters
            window.downloadCsv = function() {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('vpas.export.vpas.list.csv') }}';
                form.target = '_blank';

                Object.keys(filters).forEach(key => {
                    if (filters[key] && key !== 'per_page') {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = filters[key];
                        form.appendChild(input);
                    }
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadPdf = function() {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('vpas.export.vpas.list.pdf') }}';
                form.target = '_blank';

                Object.keys(filters).forEach(key => {
                    if (filters[key] && key !== 'per_page') {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = filters[key];
                        form.appendChild(input);
                    }
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Load filter values from URL on page load
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('search_namaupt')) {
                $('#search-namaupt').val(urlParams.get('search_namaupt'));
            }
            if (urlParams.get('search_kanwil')) {
                $('#search-kanwil').val(urlParams.get('search_kanwil'));
            }
            if (urlParams.get('search_tanggal_dari')) {
                $('#search-tanggal-dari').val(urlParams.get('search_tanggal_dari'));
            }
            if (urlParams.get('search_tanggal_sampai')) {
                $('#search-tanggal-sampai').val(urlParams.get('search_tanggal_sampai'));
            }
            if (urlParams.get('search_status')) {
                $('#search-status').val(urlParams.get('search_status'));
            }

            // Show export buttons if there's data
            if ($("#Table tbody tr").length > 0 && !$("#Table tbody tr").find('td[colspan="7"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }
        });
    </script>

@endsection

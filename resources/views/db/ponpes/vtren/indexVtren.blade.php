@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <!-- Left navbar links -->
                        <div class="d-flex justify-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data Vtren</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Search bar -->
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        {{-- Tampilkan pesan sukses total --}}
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

        {{-- Tampilkan pesan sukses parsial --}}
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

        {{-- Tampilkan error validasi --}}
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

        {{-- Tampilkan error sistem --}}
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
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        @if (request('table_search'))
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Hasil pencarian untuk: "<strong>{{ request('table_search') }}</strong>"
                                    <span class="ml-2">({{ $data->total() }} hasil ditemukan)</span>
                                    <a href="{{ route('ListDataVtrend') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            {{-- Index Form Html --}}

                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="Table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Ponpes</th>
                                            <th>nama wilayah</th>
                                            <th class="text-center">Tipe</th>
                                            <th class="text-center">Tanggal Dibuat</th>
                                            <th class="text-center">Status Update</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $d->nama_ponpes }}</td>
                                                <td><span class="tag tag-success">{{ $d->nama_wilayah }}</span></td>
                                                <td>
                                                    <span class="Tipevpas">
                                                        {{ ucfirst($d->tipe) }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        // Cek apakah data opsional sudah diisi
                                                        $optionalFields = [
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
                                                        ];

                                                        $filledFields = 0;
                                                        if ($d->dataOpsional) {
                                                            foreach ($optionalFields as $field) {
                                                                if (!empty($d->dataOpsional->$field)) {
                                                                    $filledFields++;
                                                                }
                                                            }
                                                        }

                                                        $totalFields = count($optionalFields);
                                                        $percentage =
                                                            $totalFields > 0 ? ($filledFields / $totalFields) * 100 : 0;
                                                    @endphp

                                                    @if ($filledFields == 0)
                                                        <span class="badge">Belum di Update</span>
                                                    @elseif($filledFields == $totalFields)
                                                        <span class="badge-succes">Sudah di Update (100%)</span>
                                                    @else
                                                        <span class="badge-prosses">Sebagian
                                                            ({{ round($percentage) }}%)
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon></button>

                                                    <a href="{{ route('exportPonpesPdf', $d->id) }}" title="Unduh PDF">
                                                        <button>
                                                            <ion-icon name="document-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    <a href="{{ route('exportPonpesCsv', $d->id) }}" title="Unduh CSV">
                                                        <button>
                                                            <ion-icon name="document-text-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    {{-- <button data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon></button> --}}
                                                </td>
                                            </tr>

                                            {{-- Delete Modal --}}
                                            {{-- <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <p>Apakah <b>{{ $d->nama_ponpes }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('PonpesPageDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div> --}}
                                        @endforeach

                                        {{-- Tampilkan pesan jika tidak ada data VPAS --}}
                                        @if ($data->count() == 0)
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                        <p>Tidak ada data VPAS yang tersedia</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- Index Form Html --}}

                            {{-- User Edit Modal --}}
                            @foreach ($data as $d)
                                {{-- User Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <form id="editForm" action="{{ route('ListDataPonpesUpdate', ['id' => $d->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <label id="editModalLabel">Edit Data</label>
                                                    <button type="button" class="btn-close-custom"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="editId" name="id">
                                                    <!-- Data Wajib Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <label>Data Wajib</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nama_ponpes">Nama
                                                                Ponpes</label>
                                                            <input type="text" class="form-control" id="nama_ponpes"
                                                                name="nama_ponpes" value="{{ $d->nama_ponpes }}"
                                                                readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nama_wilayah">Nama
                                                                Daerah</label>
                                                            <input type="text" class="form-control" id="nama_wilayah"
                                                                name="nama_wilayah" value="{{ $d->nama_wilayah }}"
                                                                readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tipe">Tipe</label>
                                                            <input type="text" class="form-control" id="tipe"
                                                                name="tipe" value="{{ ucfirst($d->tipe) }}" readonly>
                                                        </div>
                                                    </div>

                                                    <!-- Data Opsional Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <label>Data Opsional</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="pic_ponpes">PIC Ponpes</label>
                                                            <input type="text" class="form-control" id="pic_ponpes"
                                                                name="pic_ponpes"
                                                                value="{{ old('pic_ponpes', $d->dataOpsional->pic_ponpes ?? '') }}"
                                                                placeholder="Masukkan nama PIC Ponpes">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="no_telpon">No Telepon</label>
                                                            <input type="tel" class="form-control" id="no_telpon"
                                                                name="no_telpon"
                                                                value="{{ old('no_telpon', $d->dataOpsional->no_telpon ?? '') }}"
                                                                placeholder="Masukkan nomor telepon">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="alamat">Alamat</label>
                                                            <input type="text" class="form-control" id="alamat"
                                                                name="alamat"
                                                                value="{{ old('alamat', $d->dataOpsional->alamat ?? '') }}"
                                                                placeholder="Masukkan alamat lengkap">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah_wbp">Jumlah
                                                                Santri</label>
                                                            <input type="number" class="form-control" id="jumlah_wbp"
                                                                name="jumlah_wbp"
                                                                value="{{ old('jumlah_wbp', $d->dataOpsional->jumlah_wbp ?? '') }}"
                                                                placeholder="Masukkan Jumlah Santri">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah_line">Jumlah Line
                                                                Reguler Terpasang</label>
                                                            <input type="number" class="form-control" id="jumlah_line"
                                                                name="jumlah_line"
                                                                value="{{ old('jumlah_line', $d->dataOpsional->jumlah_line ?? '') }}"
                                                                placeholder="Masukkan jumlah line reguler">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="provider_internet">Provider
                                                                Internet</label>
                                                            <select class="form-control" id="provider_internet"
                                                                name="provider_internet">
                                                                <option value="">-- Pilih Provider --</option>
                                                                @foreach ($providers as $p)
                                                                    <option value="{{ $p->nama_provider }}"
                                                                        {{ old('provider_internet', $d->dataOpsional->provider_internet ?? '') == $p->nama_provider ? 'selected' : '' }}>
                                                                        {{ $p->nama_provider }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kecepatan_internet">Kecepatan
                                                                Internet (Mbps)</label>
                                                            <input type="text" class="form-control"
                                                                id="kecepatan_internet" name="kecepatan_internet"
                                                                value="{{ old('kecepatan_internet', $d->dataOpsional->kecepatan_internet ?? '') }}"
                                                                placeholder="Contoh: 20">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tarif_wartel">Tarif Wartel
                                                                Reguler</label>
                                                            <input type="text" class="form-control" id="tarif_wartel"
                                                                name="tarif_wartel"
                                                                value="{{ old('tarif_wartel', $d->dataOpsional->tarif_wartel ?? '') }}"
                                                                placeholder="Contoh: Rp 2.000 / menit">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status_wartel">Status
                                                                Wartel</label>
                                                            <select class="form-control" id="status_wartel"
                                                                name="status_wartel">
                                                                <option value="">-- Pilih Status --</option>
                                                                <option value="Aktif"
                                                                    {{ old('status_wartel', $d->dataOpsional && $d->dataOpsional->status_wartel ? 'Aktif' : '') == 'Aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="Tidak Aktif"
                                                                    {{ old('status_wartel', $d->dataOpsional && !$d->dataOpsional->status_wartel ? 'Tidak Aktif' : '') == 'Tidak Aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- IMC PAS Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <label>IMC PAS</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="akses_topup_pulsa">Akses Top Up
                                                                Pulsa</label>
                                                            <input type="text" class="form-control"
                                                                id="akses_topup_pulsa" name="akses_topup_pulsa"
                                                                value="{{ old('akses_topup_pulsa', $d->dataOpsional->akses_topup_pulsa ?? '') }}"
                                                                placeholder="Masukkan akses top up pulsa">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password_topup">Password Top Up
                                                                Pulsa</label>
                                                            <input type="text" class="form-control"
                                                                id="password_topup" name="password_topup"
                                                                value="{{ old('password_topup', $d->dataOpsional->password_topup ?? '') }}"
                                                                placeholder="Masukkan password top up">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="akses_download_rekaman">Akses
                                                                Download Rekaman</label>
                                                            <input type="text" class="form-control"
                                                                id="akses_download_rekaman" name="akses_download_rekaman"
                                                                value="{{ old('akses_download_rekaman', $d->dataOpsional->akses_download_rekaman ?? '') }}"
                                                                placeholder="Masukkan akses download rekaman">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password_download">Password
                                                                Download
                                                                Rekaman</label>
                                                            <input type="text" class="form-control"
                                                                id="password_download" name="password_download"
                                                                value="{{ old('password_download', $d->dataOpsional->password_download ?? '') }}"
                                                                placeholder="Masukkan password download">
                                                        </div>
                                                    </div>

                                                    <!-- Akses VPN Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <label> VPN</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="internet_protocol">Internet
                                                                Protocol</label>
                                                            <input type="text" class="form-control"
                                                                id="internet_protocol" name="internet_protocol"
                                                                value="{{ old('internet_protocol', $d->dataOpsional->internet_protocol ?? '') }}"
                                                                placeholder="Masukkan alamat IP atau domain">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="vpn_user">User</label>
                                                            <input type="text" class="form-control" id="vpn_user"
                                                                name="vpn_user"
                                                                value="{{ old('vpn_user', $d->dataOpsional->vpn_user ?? '') }}"
                                                                placeholder="Masukkan username VPN">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="vpn_password">Password</label>
                                                            <input type="text" class="form-control" id="vpn_password"
                                                                name="vpn_password"
                                                                value="{{ old('vpn_password', $d->dataOpsional->vpn_password ?? '') }}"
                                                                placeholder="Masukkan password VPN">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jenis_vpn">Jenis VPN</label>
                                                            <select class="form-control" id="jenis_vpn" name="jenis_vpn">
                                                                <option value="">-- Pilih Jenis VPN --</option>
                                                                @if (isset($vpns) && $vpns->count() > 0)
                                                                    @foreach ($vpns as $p)
                                                                        <option value="{{ $p->jenis_vpn }}"
                                                                            {{ old('jenis_vpn', $d->dataOpsional->jenis_vpn ?? '') == $p->jenis_vpn ? 'selected' : '' }}>
                                                                            {{ $p->jenis_vpn }}
                                                                        </option>
                                                                    @endforeach
                                                                @else
                                                                    <option value="" disabled>Tidak ada data VPN
                                                                        tersedia</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Ekstension Reguler Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <label> Reguler</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah_extension">Jumlah
                                                                Extension</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_extension" name="jumlah_extension"
                                                                value="{{ old('jumlah_extension', $d->dataOpsional->jumlah_extension ?? '') }}"
                                                                placeholder="Masukkan jumlah">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="pin_tes">Pin Test</label>
                                                            <input type="text" class="form-control" id="pin_tes"
                                                                name="pin_tes"
                                                                value="{{ old('pin_tes', $d->dataOpsional->pin_tes ?? '') }}"
                                                                placeholder="Masukkan Pin Tes">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="no_extension">No
                                                                Extension</label>
                                                            <small class="text-muted d-block mb-2">Masukkan setiap nomor
                                                                extension pada baris terpisah</small>
                                                            <textarea class="form-control" id="no_extension" name="no_extension" rows="6"
                                                                placeholder="Contoh:
                                No Extension
                                No Extension
                                No Extension;">{{ old('no_extension', $d->dataOpsional->no_extension ?? '') }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="extension_password">Password
                                                                Extension</label>
                                                            <small class="text-muted d-block mb-2">Masukkan setiap password
                                                                extension pada baris terpisah (sesuai urutan nomor extension
                                                                di
                                                                atas)</small>
                                                            <textarea class="form-control" id="extension_password" name="extension_password" rows="6"
                                                                placeholder="Contoh:
                                password
                                password
                                password">{{ old('extension_password', $d->dataOpsional->extension_password ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn-cancel-modal"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn-purple">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                            {{-- User Edit Modal --}}

                        </div>
                    </div>
                </div>
                <!-- /.row -->


                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                <!-- Preserve search parameter -->
                                @if (request('table_search'))
                                    <input type="hidden" name="table_search" value="{{ request('table_search') }}">
                                @endif

                                <select name="per_page" class="form-control form-control-sm" style="width: auto;"
                                    onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20
                                    </option>
                                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                                    </option>
                                </select>
                                <span class="ml-2">data per halaman</span>
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
                                <a href="{{ $data->appends(request()->query())->previousPageUrl() }}"
                                    class="btn-page">&laquo; Previous</a>
                            @endif

                            <span id="page-info">Page {{ $data->currentPage() }} of {{ $data->lastPage() }}</span>

                            @if ($data->hasMorePages())
                                <a href="{{ $data->appends(request()->query())->nextPageUrl() }}" class="btn-page">Next
                                    &raquo;</a>
                            @else
                                <button class="btn-page" disabled>Next &raquo;</button>
                            @endif
                        </div>
                    @endif
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Search and Pagination JavaScript - Same as Ponpes --}}
    <script>
        $(document).ready(function() {
            // Hanya handle search dengan server-side
            $("#btn-search").on("keyup", function() {
                let value = $(this).val();

                if (value === '') {
                    // Clear search, redirect ke halaman utama
                    let url = new URL(window.location.href);
                    url.searchParams.delete('table_search');
                    window.location.href = url.toString();
                }
                // Untuk search akan dihandle oleh form submit atau AJAX
            });

            // Handle modal events
            $('.modal').on('show.bs.modal', function(e) {
                console.log('Modal is opening');
            });
        });
    </script>

    {{-- Search JS --}}
    <script>
        $(document).ready(function() {
            // Search dengan Enter key
            $("#btn-search").on("keypress", function(e) {
                if (e.which === 13) { // Enter key
                    performSearch();
                }
            });

            // Clear search ketika input kosong
            $("#btn-search").on("keyup", function() {
                if ($(this).val() === '') {
                    clearSearch();
                }
            });

            // Function untuk perform search
            function performSearch() {
                let searchValue = $("#btn-search").val();
                let url = new URL(window.location.href);

                if (searchValue && searchValue.length > 0) {
                    url.searchParams.set('table_search', searchValue);
                } else {
                    url.searchParams.delete('table_search');
                }

                // Reset ke halaman 1
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }

            // Function untuk clear search
            function clearSearch() {
                let url = new URL(window.location.href);
                url.searchParams.delete('table_search');
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }

            // Handle modal events
            $('.modal').on('show.bs.modal', function(e) {
                console.log('Modal is opening');
            });
        });
    </script>


@endsection

@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data Reguler Ponpes
                    </div>
                </div>
            </div><!-- /.container-fluid -->
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
                                    <a href="{{ route('ListDataPonpes') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            {{-- Index Form Html --}}
                            <div class="card-header">
                                <h3 class="card-title mt-2">Data VPAS</h3>
                                <div class="card-tools">
                                    <form action="{{ route('ListDataPonpes') }}" method="GET">
                                        <div class="input-group input-group-sm mt-2 mr-3" style="width: 200px;">
                                            <input type="text" name="table_search" class="form-control"
                                                placeholder="Search" value="{{ request('table_search') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Ponpes</th>
                                            <th>nama_wilayah</th>
                                            <th>Tipe</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Status Update</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td><strong>{{ $d->nama_ponpes }}</strong></td>
                                                <td><span class="tag tag-success">{{ $d->nama_wilayah }}</span></td>
                                                <td>{{ ucfirst($d->tipe) }}</td>
                                                <td>{{ $d->tanggal }}</td>
                                                <td>
                                                    @php
                                                        // Cek apakah data opsional sudah diisi
                                                        $optionalFields = [
                                                            'pic_upt',
                                                            'no_telpon',
                                                            'alamat',
                                                            'jumlah_wbp',
                                                            'jumlah_line_reguler',
                                                            'provider_internet',
                                                            'kecepatan_internet',
                                                            'tarif_wartel_reguler',
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
                                                        foreach ($optionalFields as $field) {
                                                            if (!empty($d->$field)) {
                                                                $filledFields++;
                                                            }
                                                        }

                                                        $totalFields = count($optionalFields);
                                                        $percentage = ($filledFields / $totalFields) * 100;
                                                    @endphp

                                                    @if ($filledFields == 0)
                                                        <span class="badge badge-danger py-2">Belum di Update</span>
                                                    @elseif($filledFields == $totalFields)
                                                        <span class="badge badge-success py-2">Sudah di Update
                                                            (100%)
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning py-2">Sebagian
                                                            ({{ round($percentage) }}%)
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <i class="fa fa-edit"></i> edit</a>

                                                    <a href="{{ route('exportPonpesPdf', $d->id) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fa fa-file-pdf"></i> pdf</a>

                                                    <a href="{{ route('exportPonpesCsv', $d->id) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fa fa-file-csv"></i> csv</a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}"
                                                        class="btn btn-sm btn-danger"><i class="fas fa-trash-alt">
                                                            delete</i></a>
                                                </td>
                                            </tr>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah <b>{{ $d->nama_ponpes }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('PonpesPageDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div>
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
                                    <form id="editForm"
                                        action="{{ route('ListDataPonpesUpdate', ['id' => $d->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
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
                                                            <h5 class="section-title text-primary">Data Wajib</h5>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nama_ponpes" class="form-label">Nama
                                                                Ponpes</label>
                                                            <input type="text" class="form-control" id="nama_ponpes"
                                                                name="nama_ponpes" value="{{ $d->nama_ponpes }}"
                                                                readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nama_wilayah" class="form-label">Nama
                                                                Daerah</label>
                                                            <input type="text" class="form-control" id="nama_wilayah"
                                                                name="nama_wilayah" value="{{ $d->nama_wilayah }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Data Opsional Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5 class="section-title text-success">Data Opsional</h5>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="pic_ponpes" class="form-label">PIC Ponpes</label>
                                                            <input type="text" class="form-control" id="pic_ponpes"
                                                                name="pic_ponpes" value="{{ $d->pic_ponpes }}"
                                                                placeholder="Masukkan nama PIC Ponpes">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="no_telpon" class="form-label">No Telepon</label>
                                                            <input type="tel" class="form-control" id="no_telpon"
                                                                name="no_telpon"
                                                                value="{{ old('no_telpon', $d->no_telpon) }}"
                                                                placeholder="Masukkan nomor telepon">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="alamat" class="form-label">Alamat</label>
                                                            <input type="text" class="form-control" id="alamat"
                                                                name="alamat" value="{{ $d->alamat }}"
                                                                placeholder="Masukkan alamat lengkap">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah_wbp" class="form-label">Jumlah
                                                                Santri</label>
                                                            <input type="number" class="form-control" id="jumlah_wbp"
                                                                name="jumlah_wbp" value="{{ $d->jumlah_wbp }}"
                                                                placeholder="Masukkan Jumlah WBP">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah_line_reguler" class="form-label">Jumlah
                                                                Line
                                                                Reguler Terpasang</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_line_reguler" name="jumlah_line_reguler"
                                                                value="{{ $d->jumlah_line_reguler }}"
                                                                placeholder="Masukkan jumlah line reguler">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="provider_internet" class="form-label">Provider
                                                                Internet
                                                                & Jenis Internet</label>
                                                            <input type="text" class="form-control"
                                                                id="provider_internet" name="provider_internet"
                                                                value="{{ $d->provider_internet }}"
                                                                placeholder="Contoh: Indihome - Dinamis">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="kecepatan_internet" class="form-label">Kecepatan
                                                                Internet (Mbps)</label>
                                                            <input type="number" class="form-control"
                                                                id="kecepatan_internet" name="kecepatan_internet"
                                                                value="{{ $d->kecepatan_internet }}"
                                                                placeholder="Contoh: 20">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tarif_wartel_reguler" class="form-label">Tarif
                                                                Wartel
                                                                Reguler</label>
                                                            <input type="text" class="form-control"
                                                                id="tarif_wartel_reguler" name="tarif_wartel_reguler"
                                                                value="{{ $d->tarif_wartel_reguler }}"
                                                                placeholder="Contoh: Rp 2.000 / menit">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status_wartel" class="form-label">Status
                                                                Wartel</label>
                                                            <select class="form-control" id="status_wartel"
                                                                name="status_wartel">
                                                                <option value="">-- Pilih Status --</option>
                                                                <option value="Aktif"
                                                                    {{ $d->status_wartel == 'Aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="Tidak Aktif"
                                                                    {{ $d->status_wartel == 'Tidak Aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- IMC PAS Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5 class="section-title text-warning">IMC PAS</h5>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="akses_topup_pulsa" class="form-label">Akses Top Up
                                                                Pulsa</label>
                                                            <input type="text" class="form-control"
                                                                id="akses_topup_pulsa" name="akses_topup_pulsa"
                                                                value="{{ $d->akses_topup_pulsa }}"
                                                                placeholder="Masukkan akses top up pulsa">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password_topup" class="form-label">Password Top Up
                                                                Pulsa</label>
                                                            <input type="text" class="form-control"
                                                                id="password_topup" name="password_topup"
                                                                value="{{ $d->password_topup }}"
                                                                placeholder="Masukkan password top up">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="akses_download_rekaman" class="form-label">Akses
                                                                Download Rekaman</label>
                                                            <input type="text" class="form-control"
                                                                id="akses_download_rekaman" name="akses_download_rekaman"
                                                                value="{{ $d->akses_download_rekaman }}"
                                                                placeholder="Masukkan akses download rekaman">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password_download" class="form-label">Password
                                                                Download
                                                                Rekaman</label>
                                                            <input type="text" class="form-control"
                                                                id="password_download" name="password_download"
                                                                value="{{ $d->password_download }}"
                                                                placeholder="Masukkan password download">
                                                        </div>
                                                    </div>
                                                    <!-- Akses VPN Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5 class="section-title text-info">Akses VPN</h5>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="internet_protocol" class="form-label">Internet
                                                                Protocol</label>
                                                            <input type="text" class="form-control"
                                                                id="internet_protocol" name="internet_protocol"
                                                                value="{{ $d->internet_protocol }}"
                                                                placeholder="Masukkan alamat IP atau domain">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="vpn_user" class="form-label">User</label>
                                                            <input type="text" class="form-control" id="vpn_user"
                                                                name="vpn_user" value="{{ $d->vpn_user }}"
                                                                placeholder="Masukkan username VPN">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="vpn_password" class="form-label">Password</label>
                                                            <input type="text" class="form-control" id="vpn_password"
                                                                name="vpn_password" value="{{ $d->vpn_password }}"
                                                                placeholder="Masukkan password VPN">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                                            <input type="text" class="form-control" id="jenis_vpn"
                                                                name="jenis_vpn" value="{{ $d->jenis_vpn }}"
                                                                placeholder="Contoh: PPTP, L2TP, OpenVPN, dsb">
                                                        </div>
                                                    </div>
                                                    <!-- Ekstension Reguler Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5 class="section-title text-info">Ekstension Reguler</h5>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah_extension" class="form-label">Jumlah
                                                                Extension</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_extension" name="jumlah_extension"
                                                                value="{{ $d->jumlah_extension }}"
                                                                placeholder="Masukkan jumlah">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="pin_tes" class="form-label">Pin Test</label>
                                                            <input type="text" class="form-control" id="pin_tes"
                                                                name="pin_tes" value="{{ $d->pin_tes }}"
                                                                placeholder="Masukkan Pin Tes">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="no_extension" class="form-label">No
                                                                Extension</label>
                                                            <small class="text-muted d-block mb-2">Masukkan setiap nomor
                                                                extension pada baris terpisah</small>
                                                            <textarea class="form-control" id="no_extension" name="no_extension" rows="6"
                                                                placeholder="Contoh:
                                                No Extension
                                                No Extension
                                                No Extension;">{{ $d->no_extension }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="extension_password" class="form-label">Password
                                                                Extension</label>
                                                            <small class="text-muted d-block mb-2">Masukkan setiap password
                                                                extension pada baris terpisah (sesuai urutan nomor extension
                                                                di
                                                                atas)</small>
                                                            <textarea class="form-control" id="extension_password" name="extension_password" rows="6"
                                                                placeholder="Contoh:
                                                password
                                                password
                                                password">{{ $d->extension_password }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

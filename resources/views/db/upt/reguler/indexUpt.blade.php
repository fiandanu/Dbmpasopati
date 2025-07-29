@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data Reguler </h1>
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
                                    <a href="{{ route('ListDataVpas') }}" class="btn btn-sm btn-secondary ml-2">
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
                                    <form action="{{ route('ListDataVpas') }}" method="GET">
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
                                            <th>Nama UPT</th>
                                            <th>Kanwil</th>
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
                                            {{-- Filter hanya untuk tipe vpas atau vpas/reguler --}}
                                            @if ($d->tipe == 'reguler')
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td><strong>{{ $d->namaupt }}</strong></td>
                                                    <td><span class="tag tag-success">{{ $d->kanwil }}</span></td>
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
                                                        {{-- Edit Button - Konsisten dengan Bootstrap 4 --}}
                                                        <a href="#editModal{{ $d->id }}"
                                                            class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#editModal{{ $d->id }}">
                                                            <i class="fa fa-edit"></i> edit
                                                        </a>

                                                        <a href="{{ route('export.vpas.pdf', $d->id) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fa fa-file-pdf"></i> pdf
                                                        </a>

                                                        <a href="{{ route('export.vpas.csv', $d->id) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fa fa-file-csv"></i> csv
                                                        </a>

                                                        {{-- Delete Button --}}
                                                        <a data-toggle="modal"
                                                            data-target="#modal-default{{ $d->id }}"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash-alt"></i> delete
                                                        </a>
                                                    </td>
                                                </tr>

                                                {{-- Delete Modal --}}
                                                <div class="modal fade" id="modal-default{{ $d->id }}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus Data</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah <b>{{ $d->namaupt }}</b> ingin dihapus?</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Tutup</button>
                                                                <form action="{{ route('DataBasePageDestroy', $d->id) }}"
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
                                            @endif
                                        @endforeach

                                        {{-- Tampilkan pesan jika tidak ada data VPAS --}}
                                        @php
                                            $hasVpasData =
                                                $data->where('tipe', 'vpas')->count() +
                                                $data->where('tipe', 'vpas/reguler')->count();
                                        @endphp
                                        @if ($hasVpasData == 0)
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
                                @if ($d->tipe == 'reguler')
                                    <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editModalLabel{{ $d->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form id="editForm{{ $d->id }}"
                                                    action="{{ route('ListDataUpdate', ['id' => $d->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $d->id }}">
                                                            Edit Data - {{ $d->namaupt }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="id"
                                                            value="{{ $d->id }}">

                                                        <!-- Data Wajib Section -->
                                                        <div class="mb-4">
                                                            <div class="mb-3 border-bottom pb-2 text-center">
                                                                <h5 class="text-primary">Data Wajib</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="namaupt{{ $d->id }}">Nama UPT</label>
                                                                <input type="text" class="form-control"
                                                                    id="namaupt{{ $d->id }}" name="namaupt"
                                                                    value="{{ $d->namaupt }}" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="kanwil{{ $d->id }}">Kanwil</label>
                                                                <input type="text" class="form-control"
                                                                    id="kanwil{{ $d->id }}" name="kanwil"
                                                                    value="{{ $d->kanwil }}" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="tipe{{ $d->id }}">Tipe</label>
                                                                <input type="text" class="form-control"
                                                                    id="tipe{{ $d->id }}" name="tipe"
                                                                    value="{{ ucfirst($d->tipe) }}" readonly>
                                                            </div>
                                                        </div>

                                                        <!-- Data Opsional Section -->
                                                        <div class="mb-4">
                                                            <div class="mb-3 border-bottom pb-2 text-center">
                                                                <h5 class="text-success">Data Opsional</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="pic_upt{{ $d->id }}">PIC UPT</label>
                                                                <input type="text" class="form-control"
                                                                    id="pic_upt{{ $d->id }}" name="pic_upt"
                                                                    value="{{ $d->pic_upt }}"
                                                                    placeholder="Masukkan nama PIC UPT">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="no_telpon{{ $d->id }}">No
                                                                    Telepon</label>
                                                                <input type="text" class="form-control"
                                                                    id="no_telpon{{ $d->id }}" name="no_telpon"
                                                                    value="{{ $d->no_telpon }}"
                                                                    placeholder="Masukkan nomor telepon">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="alamat{{ $d->id }}">Alamat</label>
                                                                <input type="text" class="form-control"
                                                                    id="alamat{{ $d->id }}" name="alamat"
                                                                    value="{{ $d->alamat }}"
                                                                    placeholder="Masukkan alamat lengkap">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="jumlah_wbp{{ $d->id }}">Jumlah
                                                                    WBP</label>
                                                                <input type="text" class="form-control"
                                                                    id="jumlah_wbp{{ $d->id }}" name="jumlah_wbp"
                                                                    value="{{ $d->jumlah_wbp }}"
                                                                    placeholder="Masukkan jumlah WBP">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="jumlah_line_reguler{{ $d->id }}">Jumlah
                                                                    Line Reguler</label>
                                                                <input type="text" class="form-control"
                                                                    id="jumlah_line_reguler{{ $d->id }}"
                                                                    name="jumlah_line_reguler"
                                                                    value="{{ $d->jumlah_line_reguler }}"
                                                                    placeholder="Masukkan jumlah line reguler">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="provider_internet{{ $d->id }}">Provider
                                                                    Internet</label>
                                                                <select class="form-control"
                                                                    id="provider_internet{{ $d->id }}"
                                                                    name="provider_internet">
                                                                    <option value="">-- Pilih Provider --</option>
                                                                    @foreach ($providers as $p)
                                                                        <option value="{{ $p->nama_provider }}"
                                                                            {{ $d->provider_internet == $p->nama_provider ? 'selected' : '' }}>
                                                                            {{ $p->nama_provider }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label
                                                                    for="kecepatan_internet{{ $d->id }}">Kecepatan
                                                                    Internet (Mbps)</label>
                                                                <input type="text" class="form-control"
                                                                    id="kecepatan_internet{{ $d->id }}"
                                                                    name="kecepatan_internet"
                                                                    value="{{ $d->kecepatan_internet }}"
                                                                    placeholder="Contoh: 20 Mbps">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="tarif_wartel_reguler{{ $d->id }}">Tarif
                                                                    Wartel Reguler</label>
                                                                <input type="text" class="form-control"
                                                                    id="tarif_wartel_reguler{{ $d->id }}"
                                                                    name="tarif_wartel_reguler"
                                                                    value="{{ $d->tarif_wartel_reguler }}"
                                                                    placeholder="Contoh: Rp 2.000 / menit">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="status_wartel{{ $d->id }}">Status
                                                                    Wartel</label>
                                                                <select class="form-control"
                                                                    id="status_wartel{{ $d->id }}"
                                                                    name="status_wartel">
                                                                    <option value="">-- Pilih Status --</option>
                                                                    <option value="Aktif"
                                                                        {{ $d->status_wartel == 'Aktif' ? 'selected' : '' }}>
                                                                        Aktif
                                                                    </option>
                                                                    <option value="Tidak Aktif"
                                                                        {{ $d->status_wartel == 'Tidak Aktif' ? 'selected' : '' }}>
                                                                        Tidak Aktif
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- IMC PAS Section -->
                                                        <div class="mb-4">
                                                            <div class="mb-3 border-bottom pb-2 text-center">
                                                                <h5 class="text-warning">IMC PAS</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="akses_topup_pulsa{{ $d->id }}">Akses
                                                                    Top Up Pulsa</label>
                                                                <input type="text" class="form-control"
                                                                    id="akses_topup_pulsa{{ $d->id }}"
                                                                    name="akses_topup_pulsa"
                                                                    value="{{ $d->akses_topup_pulsa }}"
                                                                    placeholder="Masukkan akses top up pulsa">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="password_topup{{ $d->id }}">Password
                                                                    Top Up Pulsa</label>
                                                                <input type="text" class="form-control"
                                                                    id="password_topup{{ $d->id }}"
                                                                    name="password_topup"
                                                                    value="{{ $d->password_topup }}"
                                                                    placeholder="Masukkan password top up">
                                                            </div>

                                                            <div class="form-group">
                                                                <label
                                                                    for="akses_download_rekaman{{ $d->id }}">Akses
                                                                    Download Rekaman</label>
                                                                <input type="text" class="form-control"
                                                                    id="akses_download_rekaman{{ $d->id }}"
                                                                    name="akses_download_rekaman"
                                                                    value="{{ $d->akses_download_rekaman }}"
                                                                    placeholder="Masukkan akses download rekaman">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="password_download{{ $d->id }}">Password
                                                                    Download Rekaman</label>
                                                                <input type="text" class="form-control"
                                                                    id="password_download{{ $d->id }}"
                                                                    name="password_download"
                                                                    value="{{ $d->password_download }}"
                                                                    placeholder="Masukkan password download">
                                                            </div>
                                                        </div>

                                                        <!-- Akses VPN Section -->
                                                        <div class="mb-4">
                                                            <div class="mb-3 border-bottom pb-2 text-center">
                                                                <h5 class="text-info">Akses VPN</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="internet_protocol{{ $d->id }}">Internet
                                                                    Protocol</label>
                                                                <input type="text" class="form-control"
                                                                    id="internet_protocol{{ $d->id }}"
                                                                    name="internet_protocol"
                                                                    value="{{ $d->internet_protocol }}"
                                                                    placeholder="Masukkan alamat IP atau domain">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="vpn_user{{ $d->id }}">User VPN</label>
                                                                <input type="text" class="form-control"
                                                                    id="vpn_user{{ $d->id }}" name="vpn_user"
                                                                    value="{{ $d->vpn_user }}"
                                                                    placeholder="Masukkan username VPN">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="vpn_password{{ $d->id }}">Password
                                                                    VPN</label>
                                                                <input type="text" class="form-control"
                                                                    id="vpn_password{{ $d->id }}"
                                                                    name="vpn_password" value="{{ $d->vpn_password }}"
                                                                    placeholder="Masukkan password VPN">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="jenis_vpn{{ $d->id }}">Jenis
                                                                    VPN</label>
                                                                <select class="form-control"
                                                                    id="jenis_vpn{{ $d->id }}" name="jenis_vpn">
                                                                    <option value="">-- Pilih Jenis VPN --</option>
                                                                    @foreach ($providers as $p)
                                                                        @if ($p->jenis_vpn)
                                                                            <option value="{{ $p->jenis_vpn }}"
                                                                                {{ $d->jenis_vpn == $p->jenis_vpn ? 'selected' : '' }}>
                                                                                {{ $p->jenis_vpn }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Extension Reguler Section -->
                                                        <div class="mb-4">
                                                            <div class="mb-3 border-bottom pb-2 text-center">
                                                                <h5 class="text-info">Extension Reguler</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="jumlah_extension{{ $d->id }}">Jumlah
                                                                    Extension</label>
                                                                <input type="text" class="form-control"
                                                                    id="jumlah_extension{{ $d->id }}"
                                                                    name="jumlah_extension"
                                                                    value="{{ $d->jumlah_extension }}"
                                                                    placeholder="Masukkan jumlah extension">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="pin_tes{{ $d->id }}">Pin Test</label>
                                                                <input type="text" class="form-control"
                                                                    id="pin_tes{{ $d->id }}" name="pin_tes"
                                                                    value="{{ $d->pin_tes }}"
                                                                    placeholder="Masukkan Pin Test">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="no_extension{{ $d->id }}">No
                                                                    Extension</label>
                                                                <small class="text-muted d-block mb-2">
                                                                    Masukkan setiap nomor extension pada baris terpisah
                                                                </small>
                                                                <textarea class="form-control" id="no_extension{{ $d->id }}" name="no_extension" rows="6"
                                                                    placeholder="Contoh:&#10;Extension 1&#10;Extension 2&#10;Extension 3">{{ $d->no_extension }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label
                                                                    for="extension_password{{ $d->id }}">Password
                                                                    Extension</label>
                                                                <small class="text-muted d-block mb-2">
                                                                    Masukkan setiap password extension pada baris terpisah
                                                                    (sesuai urutan nomor extension di atas)
                                                                </small>
                                                                <textarea class="form-control" id="extension_password{{ $d->id }}" name="extension_password" rows="6"
                                                                    placeholder="Contoh:&#10;password1&#10;password2&#10;password3">{{ $d->extension_password }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Update Data
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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

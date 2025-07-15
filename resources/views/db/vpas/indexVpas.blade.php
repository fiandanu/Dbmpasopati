@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data Upt</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">List Data Upt</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        {{-- <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button> --}}
                        <div class="card">
                            {{-- Index Form Html --}}
                            <div class="card-header">
                                <h3 class="card-title">Data Upt</h3>
                                <div class="card-tools">
                                    <form action="{{ route('ListDataUpt') }}" method="GET">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="table_search" class="form-control float-right"
                                                placeholder="Search">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
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
                                            <th>Tanggal Dibuat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration}}</td>
                                                <td><strong>{{$d->namaupt}}</strong></td>
                                                <td><span class="tag tag-success">{{$d->kanwil}}</span></td>
                                                <td>{{$d->tanggal}}</td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $d->id}}"><i
                                                            class="fa fa-edit"></i> edit</a>
                                                    
                                                    <a href="{{ route('export.upt.pdf', $d->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fa fa-file-pdf"></i> pdf</a>

                                                    <a href="{{ route('export.upt.csv', $d->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fa fa-file-csv"></i> csv</a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal" data-target="#modal-default{{ $d->id }}"
                                                        class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"> delete</i></a>
                                                </td>
                                            </tr>
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
                                                            <p>Apakah <b>{{ $d->namaupt }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('UserPageDestroy', $d->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- Index Form Html --}}


                            {{-- User Create Modal --}}
                            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                                aria-hidden="true">
                                <form id="addForm" action="{{ route('UserPageStore')}}" method="POST">
                                    @csrf
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addModalLabel">Tambah Data</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                {{-- Input Nama UPT --}}
                                                <div class="mb-3">
                                                    <label for="namaupt" class="form-label">Nama UPT</label>
                                                    <input type="text" class="form-control" id="namaupt" name="namaupt"
                                                        required>
                                                </div>
                                                @error('namaupt')
                                                    <small>{{ $message}}</small>
                                                @enderror
                                                {{-- Input Nama UPT --}}

                                                {{-- Input Nama Kanwil --}}
                                                <div class="mb-3">
                                                    <label for="kanwil" class="form-label">Kanwil</label>
                                                    <input type="text" class="form-control" id="kanwil" name="kanwil"
                                                        required>
                                                </div>
                                                @error('kanwil')
                                                    <small>{{ $message}}</small>
                                                @enderror
                                                {{-- Input Nama Kanwil --}}

                                                {{-- Input Tanggal Hidden --}}
                                                <input type="hidden" id="addTanggal" name="tanggal">
                                                {{-- Input Tanggal Hidden --}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{-- User Create Modal --}}


                            {{-- User Edit Modal --}}
                            @foreach ($data as $d)
                            {{-- User Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <form id="editForm" action="{{ route('ListDataUpdate', ['id' => $d->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" id="editId" name="id">

                                                <!-- Tampilkan pesan kesalahan jika ada -->
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <!-- Data Wajib Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5 class="fw-semibold text-primary">Data Wajib</h5>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="namaupt" class="form-label">Nama UPT</label>
                                                        <input type="text" class="form-control" id="namaupt" name="namaupt" value="{{ $d->namaupt }}" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="kanwil" class="form-label">Kanwil</label>
                                                        <input type="text" class="form-control" id="kanwil" name="kanwil" value="{{ $d->kanwil }}" readonly>
                                                    </div>
                                                </div>

                                                <!-- Data Opsional Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5 class="fw-semibold text-success">Data Opsional</h5>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="pic_upt" class="form-label">PIC UPT</label>
                                                        <input type="text" class="form-control" id="pic_upt" name="pic_upt" value="{{ $d->pic_upt }}" placeholder="Masukkan nama PIC UPT">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="no_telpon" class="form-label">No Telepon</label>
                                                        <input type="integer" class="form-control" id="no_telpon" name="no_telpon" 
                                                            value="{{ old('no_telpon', $d->no_telpon) }}" placeholder="Masukkan nomor telepon">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="alamat" class="form-label">Alamat</label>
                                                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $d->alamat }}" placeholder="Masukkan alamat lengkap">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jumlah_wbp" class="form-label">Jumlah BWP</label>
                                                        <input type="text" class="form-control" id="jumlah_wbp" name="jumlah_wbp" value="{{ $d->jumlah_wbp }}" placeholder="Masukkan jumlah BWP">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jumlah_line_reguler" class="form-label">Jumlah Line Reguler Terpasang</label>
                                                        <input type="text" class="form-control" id="jumlah_line_reguler" name="jumlah_line_reguler" value="{{ $d->jumlah_line_reguler }}" placeholder="Masukkan jumlah line reguler">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="provider_internet" class="form-label">Provider Internet & Jenis Internet Dinamis/Statis</label>
                                                        <input type="text" class="form-control" id="provider_internet" name="provider_internet" value="{{ $d->provider_internet }}" placeholder="Contoh: Indihome - Dinamis">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="kecepatan_internet" class="form-label">Kecepatan Internet (Mbps)</label>
                                                        <input type="text" class="form-control" id="kecepatan_internet" name="kecepatan_internet" value="{{ $d->kecepatan_internet }}" placeholder="Contoh: 20 Mbps">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="tarif_wartel_reguler" class="form-label">Tarif Wartel Reguler</label>
                                                        <input type="text" class="form-control" id="tarif_wartel_reguler" name="tarif_wartel_reguler" value="{{ $d->tarif_wartel_reguler }}" placeholder="Contoh: Rp 2.000 / menit">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="status_wartel" class="form-label">Status Wartel</label>
                                                        <input type="text" class="form-control" id="status_wartel" name="status_wartel" value="{{ $d->status_wartel }}" placeholder="Contoh: Aktif / Tidak Aktif">
                                                    </div>
                                                </div>

                                                <!-- IMC PAS Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5 class="fw-semibold text-warning">IMC PAS</h5>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="akses_topup_pulsa" class="form-label">Akses Top Up Pulsa</label>
                                                        <input type="text" class="form-control" id="akses_topup_pulsa" name="akses_topup_pulsa" value="{{ $d->akses_topup_pulsa }}" placeholder="Masukkan akses top up pulsa">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="password_topup" class="form-label">Password Top Up Pulsa</label>
                                                        <input type="text" class="form-control" id="password_topup" name="password_topup" value="{{ $d->password_topup }}" placeholder="Masukkan password top up">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="akses_download_rekaman" class="form-label">Akses Download Rekaman</label>
                                                        <input type="text" class="form-control" id="akses_download_rekaman" name="akses_download_rekaman" value="{{ $d->akses_download_rekaman }}" placeholder="Masukkan akses download rekaman">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="password_download" class="form-label">Password Download Rekaman</label>
                                                        <input type="text" class="form-control" id="password_download" name="password_download" value="{{ $d->password_download }}" placeholder="Masukkan password download">
                                                    </div>
                                                </div>

                                                <!-- Akses VPN Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5 class="fw-semibold text-info">Akses VPN</h5>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="internet_protocol" class="form-label">Internet Protocol</label>
                                                        <input type="text" class="form-control" id="internet_protocol" name="internet_protocol" value="{{ $d->internet_protocol }}" placeholder="Masukkan alamat IP atau domain">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="vpn_user" class="form-label">User </label>
                                                        <input type="text" class="form-control" id="vpn_user" name="vpn_user" value="{{ $d->vpn_user }}" placeholder="Masukkan username VPN">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="vpn_password" class="form-label">Password</label>
                                                        <input type="text" class="form-control" id="vpn_password" name="vpn_password" value="{{ $d->vpn_password }}" placeholder="Masukkan password VPN">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                                        <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn" value="{{ $d->jenis_vpn }}" placeholder="Contoh: PPTP, L2TP, OpenVPN, dsb">
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5 class="fw-semibold text-info">Ekstension Reguler</h5>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jumlah_extension" class="form-label">Jumlah Extension</label>
                                                        <input type="text" class="form-control" id="jumlah_extension" name="jumlah_extension" value="{{ $d->jumlah_extension }}" placeholder="Masukkan jumlah">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="no_extension" class="form-label">No Extension</label>
                                                        <input type="text" class="form-control" id="no_extension" name="no_extension" value="{{ $d->no_extension }}" placeholder="Masukkan No Extension">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="extension_password" class="form-label">Password</label>
                                                        <input type="text" class="form-control" id="extension_password" name="extension_password" value="{{ $d->extension_password }}" placeholder="Masukkan Password">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="pin_tes" class="form-label">Pin Test</label>
                                                        <input type="text" class="form-control" id="pin_tes" name="pin_tes" value="{{ $d->pin_tes }}" placeholder="Masukkan Pin Tes">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
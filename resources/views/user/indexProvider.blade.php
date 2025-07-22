@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data Provider</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">List Data Provider</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        {{-- Tampilkan pesan sukses --}}
        @if(session('success'))
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                        <div class="card">
                            {{-- Index Form Html --}}
                            <div class="card-header">
                                <h3 class="card-title">Data Provider</h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right"
                                            placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provider</th>
                                            <th>Jenis VPN</th>
                                            <th>Tanggal Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataprovider as $d)
                                            <tr>
                                                <td>{{ $loop->iteration}}</td>
                                                <td><strong>{{$d->nama_provider}}</strong></td>
                                                <td><span class="tag tag-success">{{$d->jenis_vpn}}</span></td>
                                                <td>{{$d->tanggal_update}}</td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $d->id}}"><i
                                                            class="fa fa-edit"></i></a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal" data-target="#modal-default{{ $d->id }}"
                                                        class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
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
                                                            <p>Apakah <b>{{ $d->nama_provider }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('ProviderPageDestroy', $d->id) }}" method="POST">
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
                            
                            {{-- Provider Create Modal --}}
                            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                                aria-hidden="true">
                                <form id="addForm" action="{{ route('ProviderPageStore')}}" method="POST">
                                    @csrf
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addModalLabel">Tambah Data Provider</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                {{-- Input Nama Provider --}}
                                                <div class="mb-3">
                                                    <label for="nama_provider" class="form-label">Nama Provider</label>
                                                    <input type="text" class="form-control" id="nama_provider" name="nama_provider"
                                                        required>
                                                </div>
                                                @error('nama_provider')
                                                    <small class="text-danger">{{ $message}}</small>
                                                @enderror
                                                {{-- Input Nama Provider --}}

                                                {{-- Input Jenis VPN --}}
                                                <div class="mb-3">
                                                    <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                                    <select class="form-control" id="jenis_vpn" name="jenis_vpn" required>
                                                        <option value="">Pilih Jenis VPN</option>
                                                        <option value="PPTP">PPTP</option>
                                                        <option value="L2TP">L2TP</option>
                                                        <option value="OpenVPN">OpenVPN</option>
                                                        <option value="IKEv2">IKEv2</option>
                                                        <option value="WireGuard">WireGuard</option>
                                                        <option value="SSTP">SSTP</option>
                                                    </select>
                                                </div>
                                                @error('jenis_vpn')
                                                    <small class="text-danger">{{ $message}}</small>
                                                @enderror
                                                {{-- Input Jenis VPN --}}

                                                {{-- Input Tanggal Hidden --}}
                                                <input type="hidden" id="addTanggal" name="tanggal_update">
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
                            {{-- Provider Create Modal --}}

                            @foreach ($dataprovider as $d)
                            {{-- Provider Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1" aria-labelledby="editModalLabel"
                                aria-hidden="true">
                                <form id="editForm" action="{{ route('ProviderPageUpdate', ['id' => $d->id])}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data Provider</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" id="editId" name="id">

                                                <div class="mb-3">
                                                    <label for="nama_provider" class="form-label">Nama Provider</label>
                                                    <input type="text" class="form-control" id="nama_provider" name="nama_provider"
                                                        value="{{ $d->nama_provider}}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                                    <select class="form-control" id="jenis_vpn" name="jenis_vpn" required>
                                                        <option value="">Pilih Jenis VPN</option>
                                                        <option value="PPTP" {{ $d->jenis_vpn == 'PPTP' ? 'selected' : '' }}>PPTP</option>
                                                        <option value="L2TP" {{ $d->jenis_vpn == 'L2TP' ? 'selected' : '' }}>L2TP</option>
                                                        <option value="OpenVPN" {{ $d->jenis_vpn == 'OpenVPN' ? 'selected' : '' }}>OpenVPN</option>
                                                        <option value="IKEv2" {{ $d->jenis_vpn == 'IKEv2' ? 'selected' : '' }}>IKEv2</option>
                                                        <option value="WireGuard" {{ $d->jenis_vpn == 'WireGuard' ? 'selected' : '' }}>WireGuard</option>
                                                        <option value="SSTP" {{ $d->jenis_vpn == 'SSTP' ? 'selected' : '' }}>SSTP</option>
                                                    </select>
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
                            {{-- Provider Edit Modal --}}

                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
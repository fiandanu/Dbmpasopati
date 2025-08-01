@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data Provider/VPN</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">List Data Provider/VPN</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        {{-- Tampilkan pesan sukses --}}
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
                <div class="row">
                    <!-- Tabel Provider -->
                    <div class="col-md-6">
                        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addProviderModal">
                            <i class="fa fa-plus"></i> Tambah Provider
                        </button>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Table Provider</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provider</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataprovider as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $d->nama_provider }}</strong></td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editProviderModal{{ $d->id }}"
                                                        class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editProviderModal{{ $d->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal"
                                                        data-target="#deleteProviderModal{{ $d->id }}"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Provider -->
                                            <div class="modal fade" id="deleteProviderModal{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data Provider</h4>
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
                                                            <form
                                                                action="{{ route('provider.ProviderPageDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel VPN -->
                    <div class="col-md-6">
                        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addVpnModal">
                            <i class="fa fa-plus"></i> Tambah VPN
                        </button>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Table VPN</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis VPN</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datavpn as $v)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><span class="tag tag-success">{{ $v->jenis_vpn }}</span></td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editVpnModal{{ $v->id }}"
                                                        class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editVpnModal{{ $v->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal" data-target="#deleteVpnModal{{ $v->id }}"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal VPN -->
                                            <div class="modal fade" id="deleteVpnModal{{ $v->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data VPN</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah <b>{{ $v->jenis_vpn }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('vpn.VpnPageDestroy', $v->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Provider Create Modal --}}
        <div class="modal fade" id="addProviderModal" tabindex="-1" aria-labelledby="addProviderModalLabel"
            aria-hidden="true">
            <form id="addProviderForm" action="{{ route('provider.ProviderPageStore') }}" method="POST">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProviderModalLabel">Tambah Data Provider</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_provider" class="form-label">Nama Provider</label>
                                <input type="text" class="form-control" id="nama_provider" name="nama_provider"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        {{-- Provider Edit Modals --}}
        @foreach ($dataprovider as $d)
            <div class="modal fade" id="editProviderModal{{ $d->id }}" tabindex="-1"
                aria-labelledby="editProviderModalLabel" aria-hidden="true">
                <form id="editProviderForm" action="{{ route('provider.ProviderPageUpdate', ['id' => $d->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProviderModalLabel">Edit Data Provider</h5>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_provider" class="form-label">Nama Provider</label>
                                    <input type="text" class="form-control" id="nama_provider" name="nama_provider"
                                        value="{{ $d->nama_provider }}">
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

        {{-- VPN Create Modal --}}
        <div class="modal fade" id="addVpnModal" tabindex="-1" aria-labelledby="addVpnModalLabel" aria-hidden="true">
            <form id="addVpnForm" action="{{ route('vpn.VpnPageStore') }}" method="POST">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addVpnModalLabel">Tambah Data VPN</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- VPN Edit Modals --}}
        @foreach ($datavpn as $v)
            <div class="modal fade" id="editVpnModal{{ $v->id }}" tabindex="-1"
                aria-labelledby="editVpnModalLabel" aria-hidden="true">
                <form id="editVpnForm" action="{{ route('vpn.VpnPageUpdate', ['id' => $v->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVpnModalLabel">Edit Data VPN</h5>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                    <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn"
                                        value="{{ $v->jenis_vpn }}">
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
    </div>

@endsection

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
                        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                        <div class="card">
                            {{-- Index Form Html --}}
                            <div class="card-header">
                                <h3 class="card-title">Data Upt</h3>
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
                                            <th>Nama UPT</th>
                                            <th>Kanwil</th>
                                            <th>Tipe</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataupt as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $d->namaupt }}</strong></td>
                                                <td><span class="tag tag-success">{{ $d->kanwil }}</span></td>
                                                <td>{{ ucfirst($d->tipe) }}</td>
                                                <td>{{ $d->tanggal }}</td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}"><i
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
                                                            <p>Apakah <b>{{ $d->namaupt }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('upt.UserPageDestroy', $d->id) }}"
                                                                method="POST">
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
                                <form id="addForm" action="{{ route('upt.UserPageStore') }}" method="POST">
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
                                                    <small>{{ $message }}</small>
                                                @enderror
                                                {{-- Input Nama UPT --}}

                                                {{-- Input Nama Kanwil --}}
                                                <div class="mb-3">
                                                    <label for="kanwil" class="form-label">Kanwil</label>
                                                    <input type="text" class="form-control" id="kanwil" name="kanwil"
                                                        required>
                                                </div>
                                                @error('kanwil')
                                                    <small>{{ $message }}</small>
                                                @enderror
                                                {{-- Input Nama Kanwil --}}

                                                {{-- Input Tipe --}}
                                                <div class="mb-3">
                                                    <label for="tipe" class="form-label">Tipe</label>
                                                    <select class="form-control" id="tipe" name="tipe" required>
                                                        <option value="" disabled selected>Pilih Tipe</option>
                                                        <option value="reguler">Reguler</option>
                                                        <option value="vpas">VPAS</option>
                                                        <option value="vpas/reguler">VPAS/Reguler</option>
                                                    </select>
                                                </div>
                                                @error('tipe')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                {{-- Input Tipe --}}


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


                            @foreach ($dataupt as $d)
                                {{-- User Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <form id="editForm" action="{{ route('upt.UserPageUpdate', ['id' => $d->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" id="editId" name="id">

                                                    <div class="mb-3">
                                                        <label for="namaupt" class="form-label">Nama UPT</label>
                                                        <input type="text" class="form-control" id="namaupt"
                                                            name="namaupt" value="{{ $d->namaupt }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="kanwil" class="form-label">Kanwil</label>
                                                        <input type="text" class="form-control" id="kanwil"
                                                            name="kanwil" value="{{ $d->kanwil }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="tipe" class="form-label">Tipe</label>
                                                        <select class="form-control" id="tipe" name="tipe"
                                                            required>
                                                            <option value="reguler"
                                                                {{ $d->tipe == 'reguler' ? 'selected' : '' }}>Reguler
                                                            </option>
                                                            <option value="vpas"
                                                                {{ $d->tipe == 'vpas' ? 'selected' : '' }}>VPAS</option>
                                                            <option value="vpas/reguler"
                                                                {{ $d->tipe == 'vpas/reguler' ? 'selected' : '' }}>
                                                                VPAS/Reguler</option>
                                                        </select>
                                                    </div>
                                                    @error('tipe')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror

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

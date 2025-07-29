@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data UPT PKS(Perjanjian Kerja Sama)</h1>
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
                                    <a href="{{ route('ListDataUpt') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            {{-- Index Form Html --}}
                            <div class="card-header">
                                <h3 class="card-title mt-2">Data Reguler</h3>
                                <div class="card-tools">
                                    <form action="{{ route('ListDataUpt') }}" method="GET">
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
                                            <th>Tanggal Dibuat</th>
                                            <th>Status Update</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $d->namaupt }}</strong></td>
                                                <td><span class="tag tag-success">{{ $d->kanwil }}</span></td>
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
                                                        <span class="badge badge-success py-2">Sudah di Update (100%)</span>
                                                    @else
                                                        <span class="badge badge-warning py-2">Sebagian
                                                            ({{ round($percentage) }}%)
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="d-flex">
                                                    {{-- Edit Button --}}

                                                    {{-- <a href="#editModal{{ $d->id }}" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <i class="fa fa-edit"></i> edit</a> --}}

                                                    <div class="d-flex justify-content-start">
                                                        <form action="{{ route('uploadFilePDF', $d->id) }}" method="POST"
                                                            enctype="multipart/form-data"
                                                            id="uploadForm{{ $d->id }}" class="d-none">
                                                            @csrf
                                                            <input type="file" name="uploaded_pdf"
                                                                id="uploadInput{{ $d->id }}" class="d-none"
                                                                required>
                                                        </form>
                                                        <button class="btn btn-sm btn-primary"
                                                            onclick="triggerUpload({{ $d->id }})">Upload
                                                            PDF</button>
                                                    </div>

                                                    <a href="{{ route('viewpdf', $d->id) }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> View PDF
                                                    </a>



                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}"
                                                        class="btn btn-sm btn-danger"><i class="fas fa-trash-alt">
                                                            delete</i></a>
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
                                                            <form action="{{ route('pks.DataBasePageDestroy', $d->id) }}"
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
                                    </tbody>
                                </table>
                            </div>
                            {{-- Index Form Html --}}


                            {{-- User Edit Modal --}}
                            @foreach ($data as $d)
                                {{-- User Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <form id="editForm" action="{{ route('pks.ListDataPks', ['id' => $d->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog">
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
                                                    <!-- Tampilkan pesan kesalahan jika ada -->


                                                    <!-- Data Wajib Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5 class="fw-semibold text-primary">Data Wajib</h5>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="namaupt" class="form-label">Nama UPT</label>
                                                            <input type="text" class="form-control" id="namaupt"
                                                                name="namaupt" value="{{ $d->namaupt }}" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kanwil" class="form-label">Kanwil</label>
                                                            <input type="text" class="form-control" id="kanwil"
                                                                name="kanwil" value="{{ $d->kanwil }}" readonly>
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

    <script>
        function triggerUpload(id) {
            document.getElementById('uploadInput' + id).click();
            document.getElementById('uploadInput' + id).onchange = function() {
                if (this.files.length > 0) {
                    document.getElementById('uploadForm' + id).submit();
                }
            };
        }
    </script>

@endsection

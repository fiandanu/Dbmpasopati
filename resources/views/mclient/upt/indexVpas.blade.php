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
                                <i class="fas fa-bars"></i>
                            </button>
                            <h1 class="headline-large-32 mb-0">Keluhan VPAS</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Export Buttons -->
                            <div class="d-flex gap-2" id="export-buttons">
                                <button onclick="downloadCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>

                            <!-- Search bar -->
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search">
                            </div>

                            <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fa fa-plus"></i> Add Data
                            </button>
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
                                <div class="mb-1">â€¢ {{ $error }}</div>
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
                                    <a href="{{ route('mcvpas.ListDataMclientVpas') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="card mt-3">
                            <!-- /.card-header -->
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
                                            <th>
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Nama UPT</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-nama-upt" name="search_nama_upt">
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kanwil</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kanwil" name="search_kanwil">
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Jenis Kendala</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-jenis-kendala" name="search_jenis_kendala">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Tanggal Terlapor</span>
                                                    <div class="d-flex justify-content-center align-items-center gap-12">
                                                        <div class="btn-searchbar column-search">
                                                            <input type="date" id="search-tanggal-terlapor-dari" name="search_tanggal_terlapor_dari"
                                                                title="Tanggal Dari">
                                                        </div>
                                                        <div class="btn-searchbar column-search">
                                                            <input type="date" id="search-tanggal-terlapor-sampai"
                                                                name="search_tanggal_terlapor_sampai" title="Tanggal Sampai">
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Tanggal Selesai</span>
                                                    <div class="d-flex justify-content-center align-items-center gap-12">
                                                        <div class="btn-searchbar column-search">
                                                            <input type="date" id="search-tanggal-selesai-dari" name="search_tanggal_selesai_dari"
                                                                title="Tanggal Dari">
                                                        </div>
                                                        <div class="btn-searchbar column-search">
                                                            <input type="date" id="search-tanggal-selesai-sampai"
                                                                name="search_tanggal_selesai_sampai" title="Tanggal Sampai">
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">Durasi (Hari)</th>
                                            <th class="text-center">
                                                <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                    <span>Status</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-status" name="search_status">
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="d-flex flex-column gap-12">
                                                    <span>PIC 1</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-pic-1" name="search_pic_1">
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="d-flex flex-column gap-12">
                                                    <span>PIC 2</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-pic-2" name="search_pic_2">
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
                                        @forelse ($data as $d)
                                            <tr>
                                                <td class="text-center">{{ $no++ }}</td>
                                                <td>{{ $d->nama_upt ?? '-' }}</td>
                                                <td>{{ $d->kanwil ?? '-' }}</td>
                                                <td class="text-center">
                                                    <span class="Tipereguller">
                                                        {{ Str::limit($d->jenis_kendala ?? 'Belum ditentukan', 30) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal_terlapor ? \Carbon\Carbon::parse($d->tanggal_terlapor)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->durasi_hari)
                                                        <span class="Tipereguller">{{ $d->durasi_hari }} hari</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = '';
                                                        switch (strtolower($d->status ?? '')) {
                                                            case 'selesai':
                                                                $statusClass = 'badge-succes';
                                                                break;
                                                            case 'proses':
                                                                $statusClass = 'badge-prosses';
                                                                break;
                                                            case 'pending':
                                                                $statusClass = 'badge-danger';
                                                                break;
                                                            case 'terjadwal':
                                                                $statusClass = 'badge-prosses';
                                                                break;
                                                            default:
                                                                $statusClass = 'badge-secondary';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ ucfirst($d->status ?? 'Belum ditentukan') }}
                                                    </span>
                                                </td>
                                                <td>{{ $d->pic_1 ?? '-' }}</td>
                                                <td>{{ $d->pic_2 ?? '-' }}</td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <button>
                                                            <ion-icon name="pencil-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}" class="">
                                                        <button>
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah Data Keluhan <b> {{ $d->nama_upt }} </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('mcvpas.MclientVpasDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    <div class="text-muted">
                                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                        <p>Tidak ada data monitoring client VPAS yang tersedia</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Custom Pagination dengan Dropdown -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- Left: Data info + Dropdown per page -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="btn-datakolom">
                                        <form method="GET" class="d-flex align-items-center">
                                            <!-- Preserve all search parameters -->
                                            @if (request('table_search'))
                                                <input type="hidden" name="table_search" value="{{ request('table_search') }}">
                                            @endif
                                            @if (request('search_nama_upt'))
                                                <input type="hidden" name="search_nama_upt" value="{{ request('search_nama_upt') }}">
                                            @endif
                                            @if (request('search_kanwil'))
                                                <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
                                            @endif
                                            @if (request('search_jenis_kendala'))
                                                <input type="hidden" name="search_jenis_kendala" value="{{ request('search_jenis_kendala') }}">
                                            @endif
                                            @if (request('search_status'))
                                                <input type="hidden" name="search_status" value="{{ request('search_status') }}">
                                            @endif
                                            @if (request('search_pic_1'))
                                                <input type="hidden" name="search_pic_1" value="{{ request('search_pic_1') }}">
                                            @endif
                                            @if (request('search_pic_2'))
                                                <input type="hidden" name="search_pic_2" value="{{ request('search_pic_2') }}">
                                            @endif
                                            @if (request('search_tanggal_terlapor_dari'))
                                                <input type="hidden" name="search_tanggal_terlapor_dari"
                                                    value="{{ request('search_tanggal_terlapor_dari') }}">
                                            @endif
                                            @if (request('search_tanggal_terlapor_sampai'))
                                                <input type="hidden" name="search_tanggal_terlapor_sampai"
                                                    value="{{ request('search_tanggal_terlapor_sampai') }}">
                                            @endif
                                            @if (request('search_tanggal_selesai_dari'))
                                                <input type="hidden" name="search_tanggal_selesai_dari"
                                                    value="{{ request('search_tanggal_selesai_dari') }}">
                                            @endif
                                            @if (request('search_tanggal_selesai_sampai'))
                                                <input type="hidden" name="search_tanggal_selesai_sampai"
                                                    value="{{ request('search_tanggal_selesai_sampai') }}">
                                            @endif

                                            <div class="d-flex align-items-center">
                                                <select name="per_page" class="form-control form-control-sm pr-2" style="width: auto;"
                                                    onchange="this.form.submit()">
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

                        {{-- Add Modal --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form id="addForm" action="{{ route('mcvpas.MclientVpasStore') }}" method="POST">
                                @csrf
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label class="modal-title" id="addModalLabel">Tambah Data
                                                VPAS</label>
                                            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Informasi UPT Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Informasi UPT</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="nama_upt" class="form-label">Nama UPT
                                                        </label>
                                                        <div class="dropdown">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="upt_search" placeholder="Cari UPT..."
                                                                    autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        onclick="toggleUptDropdown()">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-menu w-100" id="uptDropdownMenu"
                                                                style="max-height: 200px; overflow-y: auto; display: none;">
                                                                @foreach ($uptList as $upt)
                                                                    <a class="dropdown-item upt-option" href="#"
                                                                        data-value="{{ $upt->namaupt }}"
                                                                        data-kanwil="{{ $upt->kanwil }}"
                                                                        onclick="selectUpt('{{ $upt->namaupt }}', '{{ $upt->kanwil }}')">
                                                                        {{ $upt->namaupt }} - {{ $upt->kanwil }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="nama_upt" name="nama_upt" required>
                                                        <small class="form-text text-muted">Ketik untuk mencari UPT</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kanwil" class="form-label">Kanwil</label>
                                                        <input type="text" class="form-control" id="kanwil"
                                                            name="kanwil" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Kendala Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Detail Kendala</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="jenis_kendala" class="form-label">Jenis
                                                            Kendala</label>
                                                        <select class="form-control" id="jenis_kendala"
                                                            name="jenis_kendala">
                                                            <option value="">-- Pilih Jenis Kendala --</option>
                                                            @foreach ($jenisKendala as $kendala)
                                                                <option value="{{ $kendala->jenis_kendala }}">
                                                                    {{ $kendala->jenis_kendala }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="detail_kendala" class="form-label">Detail
                                                            Kendala</label>
                                                        <textarea class="form-control" id="detail_kendala" name="detail_kendala" rows="3"
                                                            placeholder="Jelaskan detail kendala lebih spesifik (opsional)"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tanggal & Status Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Tanggal & Status</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="tanggal_terlapor" class="form-label">Tanggal
                                                            Terlapor</label>
                                                        <input type="date" class="form-control" id="tanggal_terlapor"
                                                            name="tanggal_terlapor">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tanggal_selesai" class="form-label">Tanggal
                                                            Selesai</label>
                                                        <input type="date" class="form-control" id="tanggal_selesai"
                                                            name="tanggal_selesai">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="durasi_hari" class="form-label">Durasi (Hari)</label>
                                                        <input type="number" class="form-control" id="durasi_hari"
                                                            name="durasi_hari" min="0"
                                                            placeholder="Masukkan durasi dalam hari">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select class="form-control" id="status" name="status">
                                                            <option value="">-- Pilih Status --</option>
                                                            <option value="pending">Pending</option>
                                                            <option value="proses">Proses</option>
                                                            <option value="selesai">Selesai</option>
                                                            <option value="terjadwal">Terjadwal</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PIC Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>PIC</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="pic_1" class="form-label">PIC 1</label>
                                                        <select class="form-control" id="pic_1" name="pic_1">
                                                            <option value="">-- Pilih PIC 1 --</option>
                                                            @foreach ($picList as $pic)
                                                                <option value="{{ $pic->nama_pic }}">
                                                                    {{ $pic->nama_pic }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="pic_2" class="form-label">PIC 2</label>
                                                        <select class="form-control" id="pic_2" name="pic_2">
                                                            <option value="">-- Pilih PIC 2 --</option>
                                                            @foreach ($picList as $pic)
                                                                <option value="{{ $pic->nama_pic }}">
                                                                    {{ $pic->nama_pic }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn-cancel-modal"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-purple">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Edit Modals --}}
                        @foreach ($data as $d)
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                                <form id="editForm{{ $d->id }}"
                                    action="{{ route('mcvpas.MclientVpasUpdate', ['id' => $d->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit
                                                    Data</label>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $d->id }}">

                                                <!-- Informasi UPT Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Informasi UPT</label>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="nama_upt_edit_{{ $d->id }}"
                                                                class="form-label">Nama UPT</label>
                                                                <input type="text" class="form-control"
                                                                id="nama_upt_edit_{{ $d->id }}" name="nama_upt"
                                                                value="{{ $d->nama_upt }}" readonly>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="kanwil_edit_{{ $d->id }}"
                                                                class="form-label">Kanwil</label>
                                                            <input type="text" class="form-control"
                                                                id="kanwil_edit_{{ $d->id }}" name="kanwil"
                                                                value="{{ $d->kanwil }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Detail Kendala Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Detail Kendala</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="mb-3">
                                                                <label for="jenis_kendala{{ $d->id }}"
                                                                    class="form-label">Jenis Kendala</label>
                                                                <select class="form-control"
                                                                    id="jenis_kendala{{ $d->id }}"
                                                                    name="jenis_kendala">
                                                                    <option value="">-- Pilih Jenis Kendala --
                                                                    </option>
                                                                    @foreach ($jenisKendala as $kendala)
                                                                        <option value="{{ $kendala->jenis_kendala }}"
                                                                            {{ $d->jenis_kendala == $kendala->jenis_kendala ? 'selected' : '' }}>
                                                                            {{ $kendala->jenis_kendala }}
                                                                        </option>
                                                                    @endforeach
                                                                    @php
                                                                        $existingKendala = $jenisKendala
                                                                            ->pluck('jenis_kendala')
                                                                            ->toArray();
                                                                    @endphp
                                                                    @if ($d->jenis_kendala && !in_array($d->jenis_kendala, $existingKendala) && $d->jenis_kendala != 'lainnya')
                                                                        <option value="{{ $d->jenis_kendala }}" selected>
                                                                            {{ $d->jenis_kendala }} (Custom)
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="detail_kendala{{ $d->id }}"
                                                                    class="form-label">Detail Kendala</label>
                                                                <textarea class="form-control" id="detail_kendala{{ $d->id }}" name="detail_kendala" rows="3"
                                                                    placeholder="Jelaskan detail kendala lebih spesifik (opsional)">{{ $d->detail_kendala ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tanggal & Status Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Tanggal & Status</label>
                                                    </div>

                                                    <div class="">
                                                        <div class="mb-3">
                                                            <label for="tanggal_terlapor{{ $d->id }}"
                                                                class="form-label">Tanggal
                                                                Terlapor</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_terlapor{{ $d->id }}"
                                                                name="tanggal_terlapor"
                                                                value="{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai{{ $d->id }}"
                                                                class="form-label">Tanggal
                                                                Selesai</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_selesai{{ $d->id }}"
                                                                name="tanggal_selesai"
                                                                value="{{ $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="durasi_hari{{ $d->id }}"
                                                            class="form-label">Durasi (Hari)</label>
                                                        <input type="number" class="form-control"
                                                            id="durasi_hari{{ $d->id }}" name="durasi_hari"
                                                            min="0" value="{{ $d->durasi_hari }}"
                                                            placeholder="Masukkan durasi dalam hari">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status{{ $d->id }}"
                                                            class="form-label">Status</label>
                                                        <select class="form-control" id="status{{ $d->id }}"
                                                            name="status">
                                                            <option value="">-- Pilih Status --</option>
                                                            <option value="pending"
                                                                {{ $d->status == 'pending' ? 'selected' : '' }}>
                                                                Pending</option>
                                                            <option value="proses"
                                                                {{ $d->status == 'proses' ? 'selected' : '' }}>
                                                                Proses</option>
                                                            <option value="selesai"
                                                                {{ $d->status == 'selesai' ? 'selected' : '' }}>
                                                                Selesai</option>
                                                            <option value="terjadwal"
                                                                {{ $d->status == 'terjadwal' ? 'selected' : '' }}>
                                                                Terjadwal</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- PIC Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>PIC</label>
                                                    </div>
                                                    <div class="">
                                                        <div class="mb-3">
                                                            <label for="pic_1{{ $d->id }}" class="form-label">PIC
                                                                1</label>
                                                            <select class="form-control" id="pic_1{{ $d->id }}"
                                                                name="pic_1">
                                                                <option value="">-- Pilih PIC 1 --</option>
                                                                @foreach ($picList as $pic)
                                                                    <option value="{{ $pic->nama_pic }}"
                                                                        {{ $d->pic_1 == $pic->nama_pic ? 'selected' : '' }}>
                                                                        {{ $pic->nama_pic }}
                                                                    </option>
                                                                @endforeach
                                                                @php
                                                                    $existingPics = $picList
                                                                        ->pluck('nama_pic')
                                                                        ->toArray();
                                                                @endphp
                                                                @if ($d->pic_1 && !in_array($d->pic_1, $existingPics))
                                                                    <option value="{{ $d->pic_1 }}" selected>
                                                                        {{ $d->pic_1 }} (Custom)
                                                                    </option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="pic_2{{ $d->id }}" class="form-label">PIC
                                                                2</label>
                                                            <select class="form-control" id="pic_2{{ $d->id }}"
                                                                name="pic_2">
                                                                <option value="">-- Pilih PIC 2 --</option>
                                                                @foreach ($picList as $pic)
                                                                    <option value="{{ $pic->nama_pic }}"
                                                                        {{ $d->pic_2 == $pic->nama_pic ? 'selected' : '' }}>
                                                                        {{ $pic->nama_pic }}
                                                                    </option>
                                                                @endforeach
                                                                @if ($d->pic_2 && !in_array($d->pic_2, $existingPics))
                                                                    <option value="{{ $d->pic_2 }}" selected>
                                                                        {{ $d->pic_2 }} (Custom)
                                                                    </option>
                                                                @endif
                                                            </select>
                                                        </div>
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
                    </div>
                </div>
                <!-- /.row -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- JS Modal --}}
    <script>
        // Function untuk update kanwil pada Add Modal
        function updateKanwil(namaUpt) {
            if (namaUpt === '') {
                document.getElementById('kanwil').value = '';
                return;
            }

            const selectElement = document.getElementById('nama_upt');
            const selectedOption = selectElement.querySelector(`option[value="${namaUpt}"]`);

            if (selectedOption) {
                const kanwil = selectedOption.getAttribute('data-kanwil');
                document.getElementById('kanwil').value = kanwil || '';
            }
        }

        // Function untuk update kanwil pada Edit Modal
        function updateKanwilEdit(namaUpt, id) {
            if (namaUpt === '') {
                document.getElementById(`kanwil_edit_${id}`).value = '';
                return;
            }

            const selectElement = document.getElementById(`nama_upt_edit_${id}`);
            const selectedOption = selectElement.querySelector(`option[value="${namaUpt}"]`);

            if (selectedOption) {
                const kanwil = selectedOption.getAttribute('data-kanwil');
                document.getElementById(`kanwil_edit_${id}`).value = kanwil || '';
            }
        }

        // Set kanwil untuk edit modal saat modal dibuka
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial kanwil values for all edit modals
            @foreach ($data as $d)
                const selectEdit{{ $d->id }} = document.getElementById(
                    'nama_upt_edit_{{ $d->id }}');
                if (selectEdit{{ $d->id }}) {
                    const selectedOptionEdit{{ $d->id }} = selectEdit{{ $d->id }}.querySelector(
                        'option:checked');
                    if (selectedOptionEdit{{ $d->id }}) {
                        const kanwilEdit{{ $d->id }} = selectedOptionEdit{{ $d->id }}.getAttribute(
                            'data-kanwil');
                        if (kanwilEdit{{ $d->id }}) {
                            document.getElementById('kanwil_edit_{{ $d->id }}').value =
                                kanwilEdit{{ $d->id }};
                        }
                    }
                }
            @endforeach
        });

        // Searchable UPT dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const uptSearch = document.getElementById('upt_search');
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = document.querySelectorAll('.upt-option');

            // Filter UPT options based on search input
            uptSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisibleOption = false;

                uptOptions.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = 'block';
                        hasVisibleOption = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Show dropdown if there are visible options and search term is not empty
                if (searchTerm.length > 0 && hasVisibleOption) {
                    uptDropdown.style.display = 'block';
                } else if (searchTerm.length === 0) {
                    uptDropdown.style.display = 'none';
                }
            });

            // Show all options when clicking on search input
            uptSearch.addEventListener('focus', function() {
                if (this.value.length > 0) {
                    const searchTerm = this.value.toLowerCase();
                    let hasVisibleOption = false;

                    uptOptions.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            option.style.display = 'block';
                            hasVisibleOption = true;
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    if (hasVisibleOption) {
                        uptDropdown.style.display = 'block';
                    }
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    uptDropdown.style.display = 'none';
                }
            });
        });

        // Toggle dropdown visibility
        function toggleUptDropdown() {
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = document.querySelectorAll('.upt-option');

            if (uptDropdown.style.display === 'none' || uptDropdown.style.display === '') {
                // Show all options
                uptOptions.forEach(option => {
                    option.style.display = 'block';
                });
                uptDropdown.style.display = 'block';
            } else {
                uptDropdown.style.display = 'none';
            }
        }

        // Select UPT option
        function selectUpt(namaUpt, kanwil) {
            document.getElementById('upt_search').value = namaUpt;
            document.getElementById('nama_upt').value = namaUpt;
            document.getElementById('kanwil').value = kanwil;
            document.getElementById('uptDropdownMenu').style.display = 'none';
        }

        // Clear UPT selection when search is cleared
        document.getElementById('upt_search').addEventListener('input', function() {
            if (this.value === '') {
                document.getElementById('nama_upt').value = '';
                document.getElementById('kanwil').value = '';
            }
        });

        // Reset form when modal is closed
        $('#addModal').on('hidden.bs.modal', function() {
            document.getElementById('upt_search').value = '';
            document.getElementById('nama_upt').value = '';
            document.getElementById('kanwil').value = '';
            document.getElementById('uptDropdownMenu').style.display = 'none';
        });
    </script>

    {{-- Search and Filter JavaScript --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_nama_upt: $('#search-nama-upt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_jenis_kendala: $('#search-jenis-kendala').val().trim(),
                    search_status: $('#search-status').val().trim(),
                    search_pic_1: $('#search-pic-1').val().trim(),
                    search_pic_2: $('#search-pic-2').val().trim(),
                    search_tanggal_terlapor_dari: $('#search-tanggal-terlapor-dari').val().trim(),
                    search_tanggal_terlapor_sampai: $('#search-tanggal-terlapor-sampai').val().trim(),
                    search_tanggal_selesai_dari: $('#search-tanggal-selesai-dari').val().trim(),
                    search_tanggal_selesai_sampai: $('#search-tanggal-selesai-sampai').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect (GLOBAL - bisa dipanggil dari tombol)
            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove existing filter parameters
                url.searchParams.delete('search_nama_upt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_jenis_kendala');
                url.searchParams.delete('search_status');
                url.searchParams.delete('search_pic_1');
                url.searchParams.delete('search_pic_2');
                url.searchParams.delete('search_tanggal_terlapor_dari');
                url.searchParams.delete('search_tanggal_terlapor_sampai');
                url.searchParams.delete('search_tanggal_selesai_dari');
                url.searchParams.delete('search_tanggal_selesai_sampai');
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
                $('#search-nama-upt').val('');
                $('#search-kanwil').val('');
                $('#search-jenis-kendala').val('');
                $('#search-status').val('');
                $('#search-pic-1').val('');
                $('#search-pic-2').val('');
                $('#search-tanggal-terlapor-dari').val('');
                $('#search-tanggal-terlapor-sampai').val('');
                $('#search-tanggal-selesai-dari').val('');
                $('#search-tanggal-selesai-sampai').val('');

                let url = new URL(window.location.href);

                // Remove all search parameters
                url.searchParams.delete('table_search');
                url.searchParams.delete('search_nama_upt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_jenis_kendala');
                url.searchParams.delete('search_status');
                url.searchParams.delete('search_pic_1');
                url.searchParams.delete('search_pic_2');
                url.searchParams.delete('search_tanggal_terlapor_dari');
                url.searchParams.delete('search_tanggal_terlapor_sampai');
                url.searchParams.delete('search_tanggal_selesai_dari');
                url.searchParams.delete('search_tanggal_selesai_sampai');
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
                form.action = '{{ route('mcvpas.export.list.csv') }}';
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
                form.action = '{{ route('mcvpas.export.list.pdf') }}';
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
            if (urlParams.get('search_nama_upt')) {
                $('#search-nama-upt').val(urlParams.get('search_nama_upt'));
            }
            if (urlParams.get('search_kanwil')) {
                $('#search-kanwil').val(urlParams.get('search_kanwil'));
            }
            if (urlParams.get('search_jenis_kendala')) {
                $('#search-jenis-kendala').val(urlParams.get('search_jenis_kendala'));
            }
            if (urlParams.get('search_status')) {
                $('#search-status').val(urlParams.get('search_status'));
            }
            if (urlParams.get('search_pic_1')) {
                $('#search-pic-1').val(urlParams.get('search_pic_1'));
            }
            if (urlParams.get('search_pic_2')) {
                $('#search-pic-2').val(urlParams.get('search_pic_2'));
            }
            if (urlParams.get('search_tanggal_terlapor_dari')) {
                $('#search-tanggal-terlapor-dari').val(urlParams.get('search_tanggal_terlapor_dari'));
            }
            if (urlParams.get('search_tanggal_terlapor_sampai')) {
                $('#search-tanggal-terlapor-sampai').val(urlParams.get('search_tanggal_terlapor_sampai'));
            }
            if (urlParams.get('search_tanggal_selesai_dari')) {
                $('#search-tanggal-selesai-dari').val(urlParams.get('search_tanggal_selesai_dari'));
            }
            if (urlParams.get('search_tanggal_selesai_sampai')) {
                $('#search-tanggal-selesai-sampai').val(urlParams.get('search_tanggal_selesai_sampai'));
            }

            // Show export buttons if there's data
            if ($("#Table tbody tr").length > 0 && !$("#Table tbody tr").find('td[colspan="11"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }

            // Handle modal events
            $('.modal').on('show.bs.modal', function(e) {
                console.log('Modal is opening');
            });

            $('.modal').on('shown.bs.modal', function(e) {
                console.log('Modal is fully visible');
            });

            $('.modal').on('hide.bs.modal', function(e) {
                console.log('Modal is closing');
            });
        });
    </script>

@endsection
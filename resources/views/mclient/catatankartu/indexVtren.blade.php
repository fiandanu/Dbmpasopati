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
                            <h1 class="headline-large-32 mb-0">Catatan Kartu Vtren</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">

                            <div class="d-flex gap-12">
                                <div class="btn-page">
                                    <input type="date" id="search-tanggal-dari" name="search_tanggal_dari"
                                        title="Tanggal Dari">
                                </div>
                                <div class="btn-page">
                                    <input type="date" id="search-tanggal-sampai" name="search_tanggal_sampai"
                                        title="Tanggal Sampai">
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="d-flex gap-2" id="export-buttons">
                                {{-- Button Export CSV --}}
                                <button onclick="downloadCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>

                                {{-- Button Export PDF --}}
                                <button onclick="downloadPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>

                            {{-- Button Add Data --}}
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">

                        <div class="row mt-3 mb-3">
                            <!-- Row 1 -->
                            <div class="col-md-4 mb-2">
                                <div class="card-total">
                                    <div class="w-full">
                                        <h1 class="title-medium-18">Kartu Baru</h1>
                                        <span class="display-medium-48">{{ number_format($totals['kartu_baru']) }}</span>
                                    </div>
                                    <div class="icon-card-total">
                                        <span class="material-symbols-outlined">
                                            credit_card
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card-total">
                                    <div class="w-full">
                                        <h1 class="title-medium-18">Kartu Bekas</h1>
                                        <span class="display-medium-48">{{ number_format($totals['kartu_bekas']) }}</span>
                                    </div>
                                    <div class="icon-card-total">
                                        <span class="material-symbols-outlined">
                                            credit_card_off
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card-total">
                                    <div class="w-full">
                                        <h1 class="title-medium-18">Kartu GOIP</h1>
                                        <span class="display-medium-48">{{ number_format($totals['kartu_goip']) }}</span>
                                    </div>
                                    <div class="icon-card-total">
                                        <span class="material-symbols-outlined">
                                            sim_card
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="col-md-4 mb-2">
                                <div class="card-total">
                                    <div class="w-full">
                                        <h1 class="title-medium-18">Belum Register</h1>
                                        <span
                                            class="display-medium-48">{{ number_format($totals['kartu_belum_register']) }}</span>
                                    </div>
                                    <div class="icon-card-total">
                                        <span class="material-symbols-outlined">
                                            pending
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card-total">
                                    <div class="w-full">
                                        <h1 class="title-medium-18">WA Terpakai</h1>
                                        <span
                                            class="display-medium-48">{{ number_format($totals['whatsapp_terpakai']) }}</span>
                                    </div>
                                    <div class="icon-card-total">
                                        <span class="material-symbols-outlined">
                                            chat
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card-total">
                                    <div class="w-full">
                                        <h1 class="title-medium-18">Total Terpakai</h1>
                                        <span
                                            class="display-medium-48">{{ number_format($totals['kartu_terpakai_perhari']) }}</span>
                                    </div>
                                    <div class="icon-card-total">
                                        <span class="material-symbols-outlined">
                                            assessment
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>


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
                                                        <button type="button" class="btn-purple w-auto"
                                                            onclick="applyFilters()" title="Cari Semua Filter">
                                                            <i class="fas fa-search"></i> Cari
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <span>Tanggal</span>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Nama Ponpes</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-nama-ponpes"
                                                            name="search_nama_ponpes" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Nama Wilayah</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-nama-wilayah"
                                                            name="search_nama_wilayah" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kartu (Baru)</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kartu-baru"
                                                            name="search_kartu_baru" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kartu (Bekas)</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kartu-bekas"
                                                            name="search_kartu_bekas" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kartu (GOIP)</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kartu-goip"
                                                            name="search_kartu_goip" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kartu Belum Register</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kartu-belum-register"
                                                            name="search_kartu_belum_register" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>WhatsApp Terpakai</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-whatsapp-terpakai"
                                                            name="search_whatsapp_terpakai" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kartu Terpakai/Hari</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kartu-terpakai"
                                                            name="search_kartu_terpakai" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Card Supporting</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-card-supporting"
                                                            name="search_card_supporting" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>PIC</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-pic" name="search_pic"
                                                            placeholder="Search">
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
                                                <td class="text-center">
                                                    {{ $d->tanggal ? \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td>{{ $d->ponpes->nama_ponpes ?? '-' }}</td>
                                                <td>{{ $d->ponpes->namaWilayah->nama_wilayah ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $d->spam_vtren_kartu_baru ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->spam_vtren_kartu_bekas ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->spam_vtren_kartu_goip ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->kartu_belum_teregister ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->whatsapp_telah_terpakai ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->jumlah_kartu_terpakai_perhari ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->card_supporting ?? '-' }}
                                                </td>
                                                <td>{{ $d->pic ?? '-' }}</td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <button>
                                                            <ion-icon name="pencil-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    {{-- DELETE BUTTON --}}
                                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                        <a data-bs-toggle="modal"
                                                            data-bs-target="#modal-default{{ $d->id }}"
                                                            class="">
                                                            <button>
                                                                <ion-icon name="trash-outline"></ion-icon>
                                                            </button>
                                                        </a>
                                                    @endif
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
                                                            <label>Apakah Data Catatan <b> {{ $d->nama_ponpes }} </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                            <form
                                                                action="{{ route('mccatatanvtren.MclientCatatanDestroyVtren', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    <div class="text-muted">
                                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                        <p>Tidak ada data catatan kartu VTREN yang tersedia</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Add Modal --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form id="addForm" action="{{ route('mccatatanvtren.MclientCatatanStoreVtren') }}"
                                method="POST">
                                @csrf
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label class="modal-title" id="addModalLabel">Tambah Data Catatan
                                                Kartu</label>
                                            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Informasi Ponpes Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Informasi UPT</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama UPT <span
                                                                class="text-danger">*</span></label>
                                                        <div class="dropdown">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="upt_search" placeholder="Cari UPT..."
                                                                    autocomplete="off" required>
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
                                                                @foreach ($ponpesList as $upt)
                                                                    <a class="dropdown-item upt-option"
                                                                        href="javascript:void(0)"
                                                                        data-id="{{ $upt->id }}"
                                                                        data-nama="{{ $upt->nama_ponpes }}"
                                                                        data-ponpes="{{ $upt->namaWilayah->nama_wilayah ?? '' }}"
                                                                        onclick="selectUpt(this)">
                                                                        {{ $upt->nama_ponpes }} -
                                                                        {{ $upt->namaWilayah->nama_wilayah ?? '-' }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <!-- INI YANG PENTING: Kirim ID bukan nama -->
                                                        <input type="hidden" id="data_ponpes_id" name="data_ponpes_id"
                                                            required>
                                                        <small class="form-text text-muted">Ketik untuk mencari UPT atau
                                                            klik tombol dropdown</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kanwil" class="form-label">Kanwil</label>
                                                        <input type="text" class="form-control" id="kanwil_display"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Data Spam VTREN Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Data Spam VTREN Tertangani</label>
                                                </div>
                                                <div class="">
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="spam_vtren_kartu_baru" class="form-label">Kartu
                                                                Baru</label>
                                                            <input type="text" class="form-control"
                                                                id="spam_vtren_kartu_baru" name="spam_vtren_kartu_baru"
                                                                value="" placeholder="Jumlah kartu baru">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="spam_vtren_kartu_bekas" class="form-label">Kartu
                                                                Bekas</label>
                                                            <input type="text" class="form-control"
                                                                id="spam_vtren_kartu_bekas" name="spam_vtren_kartu_bekas"
                                                                value="" placeholder="Jumlah kartu bekas">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="spam_vtren_kartu_goip" class="form-label">Kartu
                                                                GOIP</label>
                                                            <input type="text" class="form-control"
                                                                id="spam_vtren_kartu_goip" name="spam_vtren_kartu_goip"
                                                                value="" placeholder="Jumlah kartu GOIP">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Data Kartu Lainnya Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Data Kartu Lainnya</label>
                                                </div>
                                                <div class="">
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="kartu_belum_teregister" class="form-label">Kartu
                                                                Belum Teregister</label>
                                                            <input type="text" class="form-control"
                                                                id="kartu_belum_teregister" name="kartu_belum_teregister"
                                                                value=""
                                                                placeholder="Jumlah kartu belum teregister">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="whatsapp_telah_terpakai"
                                                                class="form-label">WhatsApp
                                                                Telah Terpakai</label>
                                                            <input type="text" class="form-control"
                                                                id="whatsapp_telah_terpakai"
                                                                name="whatsapp_telah_terpakai" value=""
                                                                placeholder="Jumlah WhatsApp terpakai">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="jumlah_kartu_terpakai_perhari"
                                                            class="form-label">Jumlah
                                                            Kartu Terpakai Per Hari</label>
                                                        <input type="text" class="form-control"
                                                            id="jumlah_kartu_terpakai_perhari"
                                                            name="jumlah_kartu_terpakai_perhari" value=""
                                                            placeholder="Auto calculated" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PIC & Tanggal Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>PIC & Tanggal</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="pic" class="form-label">PIC</label>
                                                        <select class="form-control" id="pic" name="pic">
                                                            <option value="">-- Pilih PIC --</option>
                                                            @foreach ($picList as $pic)
                                                                <option value="{{ $pic->nama_pic }}">
                                                                    {{ $pic->nama_pic }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="card_supporting" class="form-label">Card
                                                            Supporting</label>
                                                        <select class="form-control" id="card_supporting"
                                                            name="card_supporting">
                                                            <option value="">-- Pilih Card Supporting --</option>
                                                            @foreach ($cardSupportingList as $cardSupporting)
                                                                <option value="{{ $cardSupporting }}">
                                                                    {{ $cardSupporting }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="tanggal" class="form-label">Tanggal</label>
                                                        <input type="date" class="form-control" id="tanggal"
                                                            name="tanggal">
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
                                    action="{{ route('mccatatanvtren.MclientCatatanUpdateVtren', ['id' => $d->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit
                                                    Data Catatan Kartu</label>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $d->id }}">

                                                <!-- Informasi Ponpes Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Informasi UPT</label>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="data_upt_id_edit_{{ $d->id }}"
                                                                class="form-label">Nama UPT <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control text-muted"
                                                                id="data_upt_id_edit_{{ $d->id }}"
                                                                name="data_ponpes_id"
                                                                onchange="updateKanwilEdit(this.value, {{ $d->id }})"
                                                                required>
                                                                <option value="">-- Pilih UPT --</option>
                                                                @foreach ($ponpesList as $upt)
                                                                    <option value="{{ $upt->id }}"
                                                                        data-ponpes="{{ $upt->namaWilayah->nama_wilayah ?? '' }}"
                                                                        {{ $d->data_ponpes_id == $upt->id ? 'selected' : '' }}>
                                                                        {{ $upt->nama_ponpes }} -
                                                                        {{ $upt->namaWilayah->nama_wilayah ?? '-' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="kanwil_edit_{{ $d->id }}"
                                                                class="form-label">Kanwil</label>
                                                            <input type="text" class="form-control"
                                                                id="kanwil_edit_{{ $d->id }}"
                                                                value="{{ $d->upt->kanwil->kanwil ?? '' }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Spam VTREN Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Data Spam VTREN Tertangani</label>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="spam_vtren_kartu_baru{{ $d->id }}"
                                                                class="form-label">Kartu Baru</label>
                                                            <input type="text" class="form-control"
                                                                id="spam_vtren_kartu_baru{{ $d->id }}"
                                                                name="spam_vtren_kartu_baru"
                                                                value="{{ $d->spam_vtren_kartu_baru ?? '' }}"
                                                                placeholder="Jumlah kartu baru">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="spam_vtren_kartu_bekas{{ $d->id }}"
                                                                class="form-label">Kartu Bekas</label>
                                                            <input type="text" class="form-control"
                                                                id="spam_vtren_kartu_bekas{{ $d->id }}"
                                                                name="spam_vtren_kartu_bekas"
                                                                value="{{ $d->spam_vtren_kartu_bekas ?? '' }}"
                                                                placeholder="Jumlah kartu bekas">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="spam_vtren_kartu_goip{{ $d->id }}"
                                                                class="form-label">Kartu GOIP</label>
                                                            <input type="text" class="form-control"
                                                                id="spam_vtren_kartu_goip{{ $d->id }}"
                                                                name="spam_vtren_kartu_goip"
                                                                value="{{ $d->spam_vtren_kartu_goip ?? '' }}"
                                                                placeholder="Jumlah kartu GOIP">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Kartu Lainnya Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Data Kartu Lainnya</label>
                                                    </div>

                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="kartu_belum_teregister{{ $d->id }}"
                                                                class="form-label">Kartu Belum Teregister</label>
                                                            <input type="text" class="form-control"
                                                                id="kartu_belum_teregister{{ $d->id }}"
                                                                name="kartu_belum_teregister"
                                                                value="{{ $d->kartu_belum_teregister ?? '' }}"
                                                                placeholder="Jumlah kartu belum teregister">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="whatsapp_telah_terpakai{{ $d->id }}"
                                                                class="form-label">WhatsApp Telah Terpakai</label>
                                                            <input type="text" class="form-control"
                                                                id="whatsapp_telah_terpakai{{ $d->id }}"
                                                                name="whatsapp_telah_terpakai"
                                                                value="{{ $d->whatsapp_telah_terpakai ?? '' }}"
                                                                placeholder="Jumlah WhatsApp terpakai">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="jumlah_kartu_terpakai_perhari{{ $d->id }}"
                                                                class="form-label">Jumlah Kartu Terpakai Per
                                                                Hari</label>
                                                            <input type="text" class="form-control"
                                                                id="jumlah_kartu_terpakai_perhari{{ $d->id }}"
                                                                name="jumlah_kartu_terpakai_perhari"
                                                                value="{{ $d->jumlah_kartu_terpakai_perhari ?? '' }}"
                                                                placeholder="Auto calculated" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- PIC & Tanggal Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>PIC & Tanggal</label>
                                                    </div>

                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="pic{{ $d->id }}"
                                                                class="form-label">PIC</label>
                                                            <select class="form-control" id="pic{{ $d->id }}"
                                                                name="pic">
                                                                <option value="">-- Pilih PIC --</option>
                                                                @foreach ($picList as $pic)
                                                                    <option value="{{ $pic->nama_pic }}"
                                                                        {{ $d->pic == $pic->nama_pic ? 'selected' : '' }}>
                                                                        {{ $pic->nama_pic }}
                                                                    </option>
                                                                @endforeach
                                                                @php
                                                                    $existingPics = $picList
                                                                        ->pluck('nama_pic')
                                                                        ->toArray();
                                                                @endphp
                                                                @if ($d->pic && !in_array($d->pic, $existingPics))
                                                                    <option value="{{ $d->pic }}" selected>
                                                                        {{ $d->pic }} (Custom)
                                                                    </option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="card_supporting{{ $d->id }}"
                                                                class="form-label">Card
                                                                Supporting</label>
                                                            <select class="form-control"
                                                                id="card_supporting{{ $d->id }}"
                                                                name="card_supporting">
                                                                <option value="">-- Pilih Card Supporting --
                                                                </option>
                                                                @foreach ($cardSupportingList as $cardSupporting)
                                                                    <option value="{{ $cardSupporting }}"
                                                                        {{ $d->card_supporting == $cardSupporting ? 'selected' : '' }}>
                                                                        {{ $cardSupporting }}
                                                                    </option>
                                                                @endforeach
                                                                @php
                                                                    $existingCardSupportings = $cardSupportingList->toArray();
                                                                @endphp
                                                                @if ($d->card_supporting && !in_array($d->card_supporting, $existingCardSupportings))
                                                                    <option value="{{ $d->card_supporting }}" selected>
                                                                        {{ $d->card_supporting }} (Custom)
                                                                    </option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="tanggal{{ $d->id }}"
                                                                class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal{{ $d->id }}" name="tanggal"
                                                                value="{{ $d->tanggal ? $d->tanggal->format('Y-m-d') : '' }}">
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

                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                @if (request('search_nama_ponpes'))
                                    <input type="hidden" name="search_nama_ponpes"
                                        value="{{ request('search_nama_ponpes') }}">
                                @endif

                                @if (request('search_nama_wilayah'))
                                    <input type="hidden" name="search_nama_wilayah"
                                        value="{{ request('search_nama_wilayah') }}">
                                @endif

                                @if (request('search_kartu_baru'))
                                    <input type="hidden" name="search_kartu_baru"
                                        value="{{ request('search_kartu_baru') }}">
                                @endif
                                @if (request('search_kartu_bekas'))
                                    <input type="hidden" name="search_kartu_bekas"
                                        value="{{ request('search_kartu_bekas') }}">
                                @endif
                                @if (request('search_kartu_goip'))
                                    <input type="hidden" name="search_kartu_goip"
                                        value="{{ request('search_kartu_goip') }}">
                                @endif
                                @if (request('search_kartu_belum_register'))
                                    <input type="hidden" name="search_kartu_belum_register"
                                        value="{{ request('search_kartu_belum_register') }}">
                                @endif
                                @if (request('search_whatsapp_terpakai'))
                                    <input type="hidden" name="search_whatsapp_terpakai"
                                        value="{{ request('search_whatsapp_terpakai') }}">
                                @endif
                                @if (request('search_card_supporting'))
                                    <input type="hidden" name="search_card_supporting"
                                        value="{{ request('search_card_supporting') }}">
                                @endif
                                @if (request('search_pic'))
                                    <input type="hidden" name="search_pic" value="{{ request('search_pic') }}">
                                @endif
                                @if (request('search_kartu_terpakai'))
                                    <input type="hidden" name="search_kartu_terpakai"
                                        value="{{ request('search_kartu_terpakai') }}">
                                @endif
                                @if (request('search_tanggal_dari'))
                                    <input type="hidden" name="search_tanggal_dari"
                                        value="{{ request('search_tanggal_dari') }}">
                                @endif
                                @if (request('search_tanggal_sampai'))
                                    <input type="hidden" name="search_tanggal_sampai"
                                        value="{{ request('search_tanggal_sampai') }}">
                                @endif

                                <div class="d-flex align-items-center">
                                    <select name="per_page" class="form-control form-control-sm pr-2"
                                        style="width: auto;" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                            10
                                        </option>
                                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15
                                        </option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20
                                        </option>
                                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>
                                            Semua
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

                            <span id="page-info">Page {{ $data->currentPage() }} of
                                {{ $data->lastPage() }}</span>

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
        // =================== FUNGSI UNTUK ADD MODAL ===================
        document.addEventListener('DOMContentLoaded', function() {
            const uptSearch = document.getElementById('upt_search');
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = document.querySelectorAll('.upt-option');
            const dataUptIdHidden = document.getElementById('data_ponpes_id');
            const kanwilDisplay = document.getElementById('kanwil_display');

            // FILTERING DATA UPT BERDASARKAN INPUT PENCARIAN
            uptSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisibleOption = false;

                // RESET DATA UPT DAN KANWIL JIKA INPUT PENCARIAN KOSONG
                if (searchTerm === '') {
                    dataUptIdHidden.value = '';
                    kanwilDisplay.value = '';
                }

                // MENAMPILKAN DATA UPT BERDASARKAN TEKS YANG DIKETIK USER
                uptOptions.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = 'block';
                        hasVisibleOption = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                // MENAMPILKAN DROPDOWN SAAT USER MENGETIK DAN ADA DATA YANG COCOK, SEMBUNYIKAN JIKA INPUT KOSONG
                if (searchTerm.length > 0 && hasVisibleOption) {
                    uptDropdown.style.display = 'block';
                } else if (searchTerm.length === 0) {
                    uptDropdown.style.display = 'none';
                }
            });

            // MENAMPILKAN DROPDOWN SAAT FOCUS
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

            // UNTUK MENYEMBUNYIKAN DROPDOWN SAAT CLICK DILUAR CONTENT
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    uptDropdown.style.display = 'none';
                }
            });
        });
        // ======================================


        // PADA SAAT MEMILIH DATA UPT, DROPDOWN OTOMATIS DISEMBUNYIKAN
        function selectUpt(element) {
            const uptId = element.getAttribute('data-id');
            const namaUpt = element.getAttribute('data-nama');
            const kanwil = element.getAttribute('data-ponpes');

            console.log('Selected UPT:', {
                id: uptId,
                nama: namaUpt,
                kanwil: kanwil
            }); // Debug

            // Set visible input (untuk display)
            document.getElementById('upt_search').value = namaUpt;

            // Set hidden input dengan ID
            document.getElementById('data_ponpes_id').value = uptId;

            // Set kanwil display
            document.getElementById('kanwil_display').value = kanwil;

            // Hide dropdown
            document.getElementById('uptDropdownMenu').style.display = 'none';

            // Blur untuk memastikan perubahan tersimpan
            document.getElementById('upt_search').blur();
        }


        // FUNGSI UNTUK MENAMPILKAN DAN MENYEMBUNYIKAN DROPDOWN
        function toggleUptDropdown() {
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = document.querySelectorAll('.upt-option');
            const uptSearch = document.getElementById('upt_search');

            if (uptDropdown.style.display === 'none' || uptDropdown.style.display === '') {
                // Show all options
                uptOptions.forEach(option => {
                    option.style.display = 'block';
                });
                uptDropdown.style.display = 'block';
                uptSearch.focus();
            } else {
                uptDropdown.style.display = 'none';
            }
        }



        // ========== JAVASCRIPT UNTUK EDIT MODAL ==========
        window.updateKanwilEdit = function(uptId, recordId) {

            if (uptId === '' || uptId === null) {
                document.getElementById(`kanwil_edit_${recordId}`).value = '';
                return;
            }

            const selectElement = document.getElementById(`data_upt_id_edit_${recordId}`);
            const selectedOption = selectElement.querySelector(`option[value="${uptId}"]`);

            if (selectedOption) {
                const kanwil = selectedOption.getAttribute('data-ponpes');
                document.getElementById(`kanwil_edit_${recordId}`).value = kanwil || '';
            }
        };

        // Set initial kanwil values for all edit modals on page load
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($data as $d)
                const selectEdit{{ $d->id }} = document.getElementById(
                    'data_upt_id_edit_{{ $d->id }}');
                if (selectEdit{{ $d->id }}) {
                    const selectedOptionEdit{{ $d->id }} = selectEdit{{ $d->id }}.querySelector(
                        'option:checked');
                    if (selectedOptionEdit{{ $d->id }}) {
                        const kanwilEdit{{ $d->id }} = selectedOptionEdit{{ $d->id }}
                            .getAttribute('data-ponpes');
                        if (kanwilEdit{{ $d->id }}) {
                            document.getElementById('kanwil_edit_{{ $d->id }}').value =
                                kanwilEdit{{ $d->id }};
                        }
                    }
                }
            @endforeach

            // Form validation before submit untuk semua edit form
            @foreach ($data as $d)
                const editForm{{ $d->id }} = document.getElementById('editForm{{ $d->id }}');
                if (editForm{{ $d->id }}) {
                    editForm{{ $d->id }}.addEventListener('submit', function(e) {
                        const uptId = document.getElementById('data_upt_id_edit_{{ $d->id }}')
                            .value;
                        if (!uptId || uptId.trim() === '') {
                            e.preventDefault();
                            alert('Silakan pilih Nama UPT terlebih dahulu');
                            return false;
                        }
                    });
                }
            @endforeach
        });

        // Reset form when modal is closed
        $('#addModal').on('hidden.bs.modal', function() {
            document.getElementById('upt_search').value = '';
            document.getElementById('data_ponpes_id').value = '';
            document.getElementById('kanwil_display').value = '';
            document.getElementById('uptDropdownMenu').style.display = 'none';
            document.getElementById('addForm').reset();
        });
    </script>

    {{-- Search and Filter JavaScript --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_nama_ponpes: $('#search-nama-ponpes').val().trim(),
                    search_nama_wilayah: $('#search-nama-wilayah').val().trim(),
                    search_kartu_baru: $('#search-kartu-baru').val().trim(),
                    search_kartu_bekas: $('#search-kartu-bekas').val().trim(),
                    search_kartu_goip: $('#search-kartu-goip').val().trim(),
                    search_kartu_belum_register: $('#search-kartu-belum-register').val().trim(),
                    search_whatsapp_terpakai: $('#search-whatsapp-terpakai').val().trim(),
                    search_card_supporting: $('#search-card-supporting').val().trim(),
                    search_pic: $('#search-pic').val().trim(),
                    search_kartu_terpakai: $('#search-kartu-terpakai').val().trim(),
                    search_tanggal_dari: $('#search-tanggal-dari').val().trim(),
                    search_tanggal_sampai: $('#search-tanggal-sampai').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect (GLOBAL - bisa dipanggil dari tombol)
            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove existing filter parameters
                url.searchParams.delete('search_nama_ponpes');
                url.searchParams.delete('search_nama_wilayah');
                url.searchParams.delete('search_kartu_baru');
                url.searchParams.delete('search_kartu_bekas');
                url.searchParams.delete('search_kartu_goip');
                url.searchParams.delete('search_kartu_belum_register');
                url.searchParams.delete('search_whatsapp_terpakai');
                url.searchParams.delete('search_card_supporting');
                url.searchParams.delete('search_pic');
                url.searchParams.delete('search_kartu_terpakai');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
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
                $('#search-nama-ponpes').val('');
                $('#search-nama-wilayah').val('');
                $('#search-kartu-baru').val('');
                $('#search-kartu-bekas').val('');
                $('#search-kartu-goip').val('');
                $('#search-kartu-belum-register').val('');
                $('#search-whatsapp-terpakai').val('');
                $('#search-card-supporting').val('');
                $('#search-pic').val('');
                $('#search-kartu-terpakai').val('');
                $('#search-tanggal-dari').val('');
                $('#search-tanggal-sampai').val('');

                let url = new URL(window.location.href);

                // Remove all search parameters
                url.searchParams.delete('search_nama_ponpes');
                url.searchParams.delete('search_nama_wilayah');
                url.searchParams.delete('search_kartu_baru');
                url.searchParams.delete('search_kartu_bekas');
                url.searchParams.delete('search_kartu_goip');
                url.searchParams.delete('search_kartu_belum_register');
                url.searchParams.delete('search_whatsapp_terpakai');
                url.searchParams.delete('search_card_supporting');
                url.searchParams.delete('search_pic');
                url.searchParams.delete('search_kartu_terpakai');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
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
                form.action = '{{ route('mccatatanvtren.export.list.csv') }}';
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
                form.action = '{{ route('mccatatanvtren.export.list.pdf') }}';
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
            if (urlParams.get('search_nama_ponpes')) {
                $('#search-nama-ponpes').val(urlParams.get('search_nama_ponpes'));
            }
            if (urlParams.get('search_nama_wilayah')) {
                $('#search-nama-wilayah').val(urlParams.get('search_nama_wilayah'));
            }
            if (urlParams.get('search_kartu_baru')) {
                $('#search-kartu-baru').val(urlParams.get('search_kartu_baru'));
            }
            if (urlParams.get('search_kartu_bekas')) {
                $('#search-kartu-bekas').val(urlParams.get('search_kartu_bekas'));
            }
            if (urlParams.get('search_kartu_goip')) {
                $('#search-kartu-goip').val(urlParams.get('search_kartu_goip'));
            }
            if (urlParams.get('search_kartu_belum_register')) {
                $('#search-kartu-belum-register').val(urlParams.get('search_kartu_belum_register'));
            }
            if (urlParams.get('search_whatsapp_terpakai')) {
                $('#search-whatsapp-terpakai').val(urlParams.get('search_whatsapp_terpakai'));
            }
            if (urlParams.get('search_card_supporting')) {
                $('#search-card-supporting').val(urlParams.get('search_card_supporting'));
            }
            if (urlParams.get('search_pic')) {
                $('#search-pic').val(urlParams.get('search_pic'));
            }
            if (urlParams.get('search_kartu_terpakai')) {
                $('#search-kartu-terpakai').val(urlParams.get('search_kartu_terpakai'));
            }
            if (urlParams.get('search_tanggal_dari')) {
                $('#search-tanggal-dari').val(urlParams.get('search_tanggal_dari'));
            }
            if (urlParams.get('search_tanggal_sampai')) {
                $('#search-tanggal-sampai').val(urlParams.get('search_tanggal_sampai'));
            }

            // Show export buttons if there's data
            if ($("#Table tbody tr").length > 0 && !$("#Table tbody tr").find('td[colspan="12"]').length) {
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

    <script>
        // ========== AUTO CALCULATE KARTU TERPAKAI - ADD MODAL ==========
        document.addEventListener('DOMContentLoaded', function() {
            // Untuk Add Modal
            const addInputs = [
                'spam_vtren_kartu_baru',
                'spam_vtren_kartu_bekas',
                'spam_vtren_kartu_goip',
                'kartu_belum_teregister',
                'whatsapp_telah_terpakai'
            ];

            function calculateAddModal() {
                let total = 0;
                addInputs.forEach(inputId => {
                    const value = parseInt(document.getElementById(inputId).value) || 0;
                    total += value;
                });
                document.getElementById('jumlah_kartu_terpakai_perhari').value = total;
            }

            // Attach event listeners untuk Add Modal
            addInputs.forEach(inputId => {
                const element = document.getElementById(inputId);
                if (element) {
                    element.addEventListener('input', calculateAddModal);
                    element.addEventListener('change', calculateAddModal);
                }
            });

            // ========== AUTO CALCULATE KARTU TERPAKAI - EDIT MODALS ==========
            @foreach ($data as $d)
                (function() {
                    const recordId = {{ $d->id }};
                    const editInputs = [
                        'spam_vtren_kartu_baru' + recordId,
                        'spam_vtren_kartu_bekas' + recordId,
                        'spam_vtren_kartu_goip' + recordId,
                        'kartu_belum_teregister' + recordId,
                        'whatsapp_telah_terpakai' + recordId
                    ];

                    function calculateEditModal() {
                        let total = 0;
                        editInputs.forEach(inputId => {
                            const element = document.getElementById(inputId);
                            if (element) {
                                const value = parseInt(element.value) || 0;
                                total += value;
                            }
                        });
                        const resultElement = document.getElementById('jumlah_kartu_terpakai_perhari' +
                            recordId);
                        if (resultElement) {
                            resultElement.value = total;
                        }
                    }

                    // Attach event listeners untuk Edit Modal
                    editInputs.forEach(inputId => {
                        const element = document.getElementById(inputId);
                        if (element) {
                            element.addEventListener('input', calculateEditModal);
                            element.addEventListener('change', calculateEditModal);
                        }
                    });

                    // Calculate initial value when modal is shown
                    $('#editModal' + recordId).on('shown.bs.modal', function() {
                        calculateEditModal();
                    });
                })();
            @endforeach

            // Reset Add Modal calculation when opened
            $('#addModal').on('shown.bs.modal', function() {
                calculateAddModal();
            });
        });
    </script>

@endsection

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
                                                    <span>Tanggal</span>
                                                    <div
                                                        class="d-flex flex-column justify-content-center align-items-center gap-12">
                                                        <div class="btn-searchbar column-search">
                                                            <input type="date" id="search-tanggal-dari"
                                                                name="search_tanggal_dari" title="Tanggal Dari">
                                                        </div>
                                                        <div class="btn-searchbar column-search">
                                                            <input type="date" id="search-tanggal-sampai"
                                                                name="search_tanggal_sampai" title="Tanggal Sampai">
                                                        </div>
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
                                                <td>{{ $d->nama_ponpes ?? '-' }}</td>
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
                                                    {{ $d->card_supporting ?? '-' }}
                                                </td>
                                                <td>{{ $d->pic ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $d->jumlah_kartu_terpakai_perhari ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal ? \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <button>
                                                            <ion-icon name="pencil-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-bs-toggle="modal"
                                                        data-bs-target="#modal-default{{ $d->id }}"
                                                        class="">
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
                                                    <label>Informasi Ponpes</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="nama_ponpes" class="form-label">Nama Ponpes</label>
                                                        <div class="dropdown">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="upt_search" placeholder="Cari nama Ponpes"
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
                                                                    <a href="#" class="dropdown-item upt-option"
                                                                        data-value="{{ $upt->nama_ponpes }}"
                                                                        onclick="selectUpt('{{ $upt->nama_ponpes }}')">
                                                                        {{ $upt->nama_ponpes }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="nama_ponpes" name="nama_ponpes"
                                                            required>
                                                        <small class="form-text text-muted">Ketik untuk mencari
                                                            Ponpes</small>
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
                                                                class="form-label">WhatsApp Telah Terpakai</label>
                                                            <input type="text" class="form-control"
                                                                id="whatsapp_telah_terpakai"
                                                                name="whatsapp_telah_terpakai" value=""
                                                                placeholder="Jumlah WhatsApp terpakai">
                                                        </div>
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
                                                                    {{ $cardSupporting }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="jumlah_kartu_terpakai_perhari"
                                                            class="form-label">Jumlah Kartu Terpakai Per Hari</label>
                                                        <input type="text" class="form-control"
                                                            id="jumlah_kartu_terpakai_perhari"
                                                            name="jumlah_kartu_terpakai_perhari" value=""
                                                            placeholder="Jumlah kartu terpakai per hari">
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
                                                                    {{ $pic->nama_pic }}</option>
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
                                                        <label>Informasi Ponpes</label>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="nama_ponpes_edit_{{ $d->id }}"
                                                                class="form-label">Nama Ponpes <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="dropdown">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        id="upt_search_edit_{{ $d->id }}"
                                                                        placeholder="Cari Ponpes..." autocomplete="off"
                                                                        value="{{ $d->nama_ponpes ?? '' }}">
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            onclick="toggleUptDropdownEdit({{ $d->id }})">
                                                                            <i class="fas fa-chevron-down"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-menu w-100"
                                                                    id="uptDropdownMenuEdit{{ $d->id }}"
                                                                    style="max-height: 200px; overflow-y: auto; display: none;">
                                                                    @foreach ($uptList as $upt)
                                                                        <a class="dropdown-item upt-option" href="#"
                                                                            data-value="{{ $upt->nama_ponpes }}"
                                                                            onclick="selectUptEdit('{{ $upt->nama_ponpes }}', {{ $d->id }})">
                                                                            {{ $upt->nama_ponpes }}
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <input type="hidden"
                                                                id="nama_ponpes_edit_{{ $d->id }}"
                                                                name="nama_ponpes" value="{{ $d->nama_ponpes ?? '' }}"
                                                                required>
                                                            <small class="form-text text-muted">Ketik untuk mencari
                                                                Ponpes</small>
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
                                                            <label for="card_supporting{{ $d->id }}"
                                                                class="form-label">Card Supporting</label>
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
                                                            <label for="jumlah_kartu_terpakai_perhari{{ $d->id }}"
                                                                class="form-label">Jumlah Kartu Terpakai Per
                                                                Hari</label>
                                                            <input type="text" class="form-control"
                                                                id="jumlah_kartu_terpakai_perhari{{ $d->id }}"
                                                                name="jumlah_kartu_terpakai_perhari"
                                                                value="{{ $d->jumlah_kartu_terpakai_perhari ?? '' }}"
                                                                placeholder="Jumlah kartu terpakai per hari">
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

    {{-- Search UPT DROPDOWN UNTUK ADD MODAL --}}
    <script>
        // Start Add Modal JS
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

        // Toggle dropdown visibility for Add Modal
        function toggleUptDropdown() {
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = document.querySelectorAll('.upt-option');

            if (uptDropdown.style.display === 'none' || uptDropdown.style.display === '') {
                uptOptions.forEach(option => {
                    option.style.display = 'block';
                });
                uptDropdown.style.display = 'block';
            } else {
                uptDropdown.style.display = 'none';
            }
        }

        // Select UPT option for Add Modal
        function selectUpt(namaPonpes) {
            document.getElementById('upt_search').value = namaPonpes;
            document.getElementById('nama_ponpes').value = namaPonpes;
            document.getElementById('uptDropdownMenu').style.display = 'none';
        }

        // Clear UPT selection when search is cleared for Add Modal
        document.getElementById('upt_search').addEventListener('input', function() {
            if (this.value === '') {
                document.getElementById('nama_ponpes').value = '';
            }
        });

        // Reset form when modal is closed for Add Modal
        $('#addModal').on('hidden.bs.modal', function() {
            document.getElementById('upt_search').value = '';
            document.getElementById('nama_ponpes').value = '';
            document.getElementById('uptDropdownMenu').style.display = 'none';
        });

        // End Add Modal JS

        // Start Edit Modal JS
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($data as $d)
                const uptSearchEdit{{ $d->id }} = document.getElementById(
                    'upt_search_edit_{{ $d->id }}');
                const uptDropdownEdit{{ $d->id }} = document.getElementById(
                    'uptDropdownMenuEdit{{ $d->id }}');
                const uptOptionsEdit{{ $d->id }} = document.querySelectorAll(
                    `#uptDropdownMenuEdit{{ $d->id }} .upt-option`);

                if (uptSearchEdit{{ $d->id }}) {
                    // Filter Upt Berdasarkan Pencarian Input
                    uptSearchEdit{{ $d->id }}.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        let hasVisibleOption = false;

                        uptOptionsEdit{{ $d->id }}.forEach(option => {
                            const text = option.textContent.toLowerCase();
                            if (text.includes(searchTerm)) {
                                option.style.display = 'block';
                                hasVisibleOption = true;
                            } else {
                                option.style.display = 'none';
                            }
                        });

                        // Memunculkan Dropdown jika tidak kosong
                        if (searchTerm.length > 0 && hasVisibleOption) {
                            uptDropdownEdit{{ $d->id }}.style.display = 'block';
                        } else if (searchTerm.length === 0) {
                            uptDropdownEdit{{ $d->id }}.style.display = 'none';
                        }
                    });

                    // Menampilkan Semua UPT
                    uptSearchEdit{{ $d->id }}.addEventListener('focus', function() {
                        if (this.value.length > 0) {
                            const searchTerm = this.value.toLowerCase();
                            let hasVisibleOption = false;

                            uptOptionsEdit{{ $d->id }}.forEach(option => {
                                const text = option.textContent.toLowerCase();
                                if (text.includes(searchTerm)) {
                                    option.style.display = 'block';
                                    hasVisibleOption = true;
                                } else {
                                    option.style.display = 'none';
                                }
                            });

                            if (hasVisibleOption) {
                                uptDropdownEdit{{ $d->id }}.style.display = 'block';
                            }
                        }
                    });

                    // Sembunyikan Ketika Klik diluar dropdown
                    document.addEventListener('click', function(event) {
                        if (!event.target.closest(`#editModal{{ $d->id }} .dropdown`)) {
                            uptDropdownEdit{{ $d->id }}.style.display = 'none';
                        }
                    });
                }
            @endforeach
        });

        // Toggle dropdown visibility for Edit Modal
        function toggleUptDropdownEdit(id) {
            const uptDropdown = document.getElementById(`uptDropdownMenuEdit${id}`);
            const uptOptions = document.querySelectorAll(`#uptDropdownMenuEdit${id} .upt-option`);

            if (uptDropdown.style.display === 'none' || uptDropdown.style.display === '') {
                uptOptions.forEach(option => {
                    option.style.display = 'block';
                });
                uptDropdown.style.display = 'block';
            } else {
                uptDropdown.style.display = 'none';
            }
        }

        // Select UPT option for Edit Modal
        function selectUptEdit(namaPonpes, id) {
            document.getElementById(`upt_search_edit_${id}`).value = namaPonpes;
            document.getElementById(`nama_ponpes_edit_${id}`).value = namaPonpes;
            document.getElementById(`uptDropdownMenuEdit${id}`).style.display = 'none';
        }

        // Clear UPT selection when search is cleared for Edit Modal
        @foreach ($data as $d)
            document.getElementById(`upt_search_edit_{{ $d->id }}`).addEventListener('input', function() {
                if (this.value === '') {
                    document.getElementById(`nama_ponpes_edit_{{ $d->id }}`).value = '';
                }
            });
        @endforeach

        // Reset Ketika Edit Modal Ditutup
        @foreach ($data as $d)
            $(`#editModal{{ $d->id }}`).on('hidden.bs.modal', function() {
                document.getElementById(`upt_search_edit_{{ $d->id }}`).value =
                    '{{ $d->nama_ponpes ?? '' }}';
                document.getElementById(`uptDropdownMenuEdit{{ $d->id }}`).style.display = 'none';
            });
        @endforeach
        // End Edit Modal JS
    </script>

    {{-- Search and Filter JavaScript --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_nama_ponpes: $('#search-nama-ponpes').val().trim(),
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

@endsection

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
                            <h1 class="headline-large-32 mb-0">Setting Alat UPT</h1>
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

                            <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fa fa-plus"></i> Add Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Alert Messages --}}
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
                <div class="row">
                    <div class="col-12">

                        <div class="d-flex gap-12">

                            <div class="gap-12 w-fit text-center">
                                <h3>Terlapor</h3>
                                <div class="d-flex justify-content-center align-items-center gap-12">
                                    <div class="flex-column btn-searchbar column-search">
                                        <label for="search-tanggal-terlapor-dari"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;">Awal</label>
                                        <input type="date" id="search-tanggal-terlapor-dari"
                                            name="search_tanggal_terlapor_dari" title="Tanggal Dari">
                                    </div>
                                    <div class="flex-column btn-searchbar column-search">
                                        <label for="search-tanggal-terlapor-sampai"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;">Akhir</label>
                                        <input type="date" id="search-tanggal-terlapor-sampai"
                                            name="search_tanggal_terlapor_sampai" title="Tanggal Sampai">
                                    </div>
                                </div>
                            </div>

                            <div class="gap-12 w-fit text-center">
                                <h3>Selesai</h3>
                                <div class="d-flex justify-content-center align-items-center gap-12">
                                    <div class="flex-column btn-searchbar column-search">
                                        <label for="search-tanggal-selesai-dari"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;">Awal</label>
                                        <input type="date" id="search-tanggal-selesai-dari"
                                            name="search_tanggal_selesai_dari" title="Tanggal Dari">
                                    </div>
                                    <div class="flex-column btn-searchbar column-search">
                                        <label for="search-tanggal-selesai-sampai"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;">Akhir</label>
                                        <input type="date" id="search-tanggal-selesai-sampai"
                                            name="search_tanggal_selesai_sampai" title="Tanggal Sampai">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card mt-3">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="Table">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>No</span>
                                                    <div class="d-flex justify-content-center align-items-center gap-12">
                                                        <button type="button" class="btn-purple w-auto"
                                                            onclick="applyFilters()">
                                                            <i class="fas fa-search"></i> Cari
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Nama UPT</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-nama_upt" name="search_nama_upt"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Kanwil</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-kanwil" name="search_kanwil"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div
                                                    class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                    <span>Jenis Layanan</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-jenis_layanan"
                                                            name="search_jenis_layanan" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div
                                                    class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                    <span>Keterangan</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-keterangan"
                                                            name="search_keterangan" placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <span>Tanggal Terlapor</span>
                                            </th>
                                            <th class="text-center align-top">
                                                <span>Tanggal Selesai</span>
                                            </th>
                                            <th class="text-center align-top">Durasi (Hari)</th>
                                            <th class="text-center align-top">
                                                <div
                                                    class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                    <span>Status</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-status" name="search_status"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>PIC 1</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-pic-1" name="search_pic_1"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>PIC 2</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-pic-2" name="search_pic_2"
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
                                                <td>{{ $d->upt->namaupt ?? '-' }}</td>
                                                <td>{{ $d->upt->kanwil->kanwil ?? '-' }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $layananClass = match (strtolower($d->jenis_layanan ?? '')) {
                                                            'vpas' => 'Tipevpas',
                                                            'reguler' => 'Tipereguller',
                                                            'vpasreg' => 'badge-prosses',
                                                            default => '',
                                                        };
                                                    @endphp
                                                    <span class="{{ $layananClass }}">
                                                        {{ $d->formatted_jenis_layanan }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($d->keterangan && strlen($d->keterangan) > 20)
                                                        <div id="short-text-{{ $d->id }}">
                                                            <div>{{ Str::limit($d->keterangan, 20) }}</div>
                                                            <a href="javascript:void(0)"
                                                                onclick="toggleDetail({{ $d->id }})"
                                                                class="text-primary">
                                                                <small>Show</small>
                                                            </a>
                                                        </div>
                                                        <div id="full-text-{{ $d->id }}" style="display: none;">
                                                            <div
                                                                style="white-space: pre-wrap; word-wrap: break-word; max-width: 300px;">
                                                                {{ $d->keterangan }}
                                                            </div>
                                                            <a href="javascript:void(0)"
                                                                onclick="toggleDetail({{ $d->id }})"
                                                                class="text-primary">
                                                                <small>Hide</small>
                                                            </a>
                                                        </div>
                                                    @else
                                                        {{ $d->keterangan ?? '-' }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal_terlapor ? \Carbon\Carbon::parse($d->tanggal_terlapor)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->tanggal_selesai)
                                                        <span class="Tipereguller">{{ $d->durasi_hari }} hari</span>
                                                    @else
                                                        <span class="Tipereguller durasi-realtime"
                                                            data-created="{{ $d->created_at->format('Y-m-d H:i:s') }}">
                                                            <span class="durasi-text">Menghitung...</span>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = match (strtolower($d->status ?? '')) {
                                                            'selesai' => 'badge-succes',
                                                            'proses' => 'badge-prosses',
                                                            'pending' => 'badge-danger',
                                                            'terjadwal' => 'badge-prosses',
                                                            default => 'badge-secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ ucfirst($d->status ?? 'Belum ditentukan') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">{{ $d->pic_1 ?? '-' }}</td>
                                                <td class="text-center">{{ $d->pic_2 ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <button>
                                                            <ion-icon name="pencil-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    <a data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}">
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
                                                            <label>Apakah Data Setting Alat <b>{{ $d->nama_upt }}
                                                                    ({{ $d->formatted_jenis_layanan }})
                                                                </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form
                                                                action="{{ route('mclientsettingalatupt.MclientSettingAlatDestroy', $d->id) }}"
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
                                                <td colspan="11" class="text-center">Tidak ada data yang ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Add Modal --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form id="addForm" action="{{ route('mclientsettingalatupt.MclientSettingAlatStore') }}"
                                method="POST">
                                @csrf
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label class="modal-title" id="addModalLabel">Tambah Data</label>
                                            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Jenis Layanan & UPT -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">Informasi UPT</label>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="jenis_layanan">Jenis Layanan <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="jenis_layanan" name="jenis_layanan"
                                                        required onchange="updateUptOptions()">
                                                        <option value="">-- Pilih Jenis Layanan --</option>
                                                        @foreach ($jenisLayananOptions as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="nama_upt">Nama UPT <span
                                                            class="text-danger">*</span></label>
                                                    <div class="dropdown">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="upt_search"
                                                                placeholder="Pilih jenis layanan dulu..."
                                                                autocomplete="off" disabled>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="toggleUptDropdown()" disabled
                                                                    id="dropdown-btn">
                                                                    <i class="fas fa-chevron-down"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-menu w-100" id="uptDropdownMenu"
                                                            style="max-height: 200px; overflow-y: auto; display: none;">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="nama_upt" name="nama_upt" required>
                                                    <small class="form-text text-muted">Pilih jenis layanan terlebih
                                                        dahulu</small>
                                                </div>
                                            </div>

                                            <!-- Detail Setting Alat -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">Detail Setting Alat</label>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                                        placeholder="Masukkan keterangan setting alat (opsional)"></textarea>
                                                </div>
                                            </div>

                                            <!-- Jadwal & Status -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">Jadwal & Status</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="tanggal_terlapor">Tanggal Terlapor</label>
                                                        <input type="date" class="form-control" id="tanggal_terlapor"
                                                            name="tanggal_terlapor">
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="tanggal_selesai">Tanggal Selesai</label>
                                                        <input type="date" class="form-control" id="tanggal_selesai"
                                                            name="tanggal_selesai">
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="status">Status</label>
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

                                            <!-- PIC -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">PIC</label>
                                                </div>

                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="pic_1">PIC 1</label>
                                                        <select class="form-control" id="pic_1" name="pic_1">
                                                            <option value="">-- Pilih PIC 1 --</option>
                                                            @foreach ($picList as $pic)
                                                                <option value="{{ $pic->nama_pic }}">
                                                                    {{ $pic->nama_pic }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="pic_2">PIC 2</label>
                                                        <select class="form-control" id="pic_2" name="pic_2">
                                                            <option value="">-- Pilih PIC 2 --</option>
                                                            @foreach ($picList as $pic)
                                                                <option value="{{ $pic->nama_pic }}">
                                                                    {{ $pic->nama_pic }}</option>
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
                                    action="{{ route('mclientsettingalatupt.MclientSettingAlatUpdate', ['id' => $d->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit
                                                    Data Setting Alat</label>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $d->id }}">

                                                <!-- Jenis Layanan & UPT -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Informasi UPT</label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jenis_layanan_edit_{{ $d->id }}">Jenis Layanan
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control text-muted"
                                                            id="jenis_layanan_edit_{{ $d->id }}"
                                                            name="jenis_layanan_display"
                                                            onchange="updateUptOptionsEdit({{ $d->id }})" disabled>
                                                            <option value="">-- Pilih Jenis Layanan --</option>
                                                            @foreach ($jenisLayananOptions as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ $d->jenis_layanan == $key ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <!-- Hidden input untuk mengirim nilai jenis_layanan -->
                                                        <input type="hidden" name="jenis_layanan"
                                                            value="{{ $d->jenis_layanan }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nama_upt_edit_{{ $d->id }}">Nama UPT <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control text-muted"
                                                            id="nama_upt_edit_{{ $d->id }}"
                                                            name="nama_upt_display" disabled>
                                                            <option value="">-- Pilih UPT --</option>
                                                        </select>
                                                        <!-- Hidden input untuk mengirim nilai nama_upt -->
                                                        <input type="hidden" name="nama_upt"
                                                            value="{{ $d->upt->namaupt ?? '' }}">
                                                    </div>

                                                </div>

                                                <!-- Detail Setting Alat -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Detail Setting Alat</label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="keterangan{{ $d->id }}">Keterangan</label>
                                                        <textarea class="form-control" id="keterangan{{ $d->id }}" name="keterangan" rows="3"
                                                            placeholder="Masukkan keterangan setting alat (opsional)">{{ $d->keterangan ?? '' }}</textarea>
                                                    </div>
                                                </div>

                                                <!-- Jadwal & Status -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Jadwal & Status</label>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="tanggal_terlapor{{ $d->id }}">Tanggal
                                                                Terlapor</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_terlapor{{ $d->id }}"
                                                                name="tanggal_terlapor"
                                                                value="{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai{{ $d->id }}">Tanggal
                                                                Selesai</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_selesai{{ $d->id }}"
                                                                name="tanggal_selesai"
                                                                value="{{ $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="status{{ $d->id }}">Status</label>
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
                                                </div>

                                                <!-- PIC -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">PIC</label>
                                                    </div>

                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="pic_1{{ $d->id }}">PIC 1</label>
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
                                                                        {{ $d->pic_1 }} (Custom)</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="pic_2{{ $d->id }}">PIC 2</label>
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
                                                                        {{ $d->pic_2 }} (Custom)</option>
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

                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                @if (request('search_nama_upt'))
                                    <input type="hidden" name="search_nama_upt"
                                        value="{{ request('search_nama_upt') }}">
                                @endif
                                @if (request('search_kanwil'))
                                    <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
                                @endif
                                @if (request('search_jenis_layanan'))
                                    <input type="hidden" name="search_jenis_layanan"
                                        value="{{ request('search_jenis_layanan') }}">
                                @endif
                                @if (request('search_keterangan'))
                                    <input type="hidden" name="search_keterangan"
                                        value="{{ request('search_keterangan') }}">
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

            </div>
        </section>
    </div>

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    {{-- JS Real-time Duration Counter --}}
    <script>
        // Fungsi untuk menghitung dan format durasi real-time
        function calculateDuration(createdAtStr) {

            const createdAt = new Date(createdAtStr);
            const now = new Date();
            const diffMs = now - createdAt;

            if (diffMs < 0) return {
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                formatted: '0 hari 00:00:00'
            }

            // Hitung komponen waktu
            const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diffMs % (1000 * 60)) / 1000);

            return {
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds,
                formatted: $ {
                    days
                }
                hari $ {
                    hours.toString().padStart(2, '0')
                }: $ {
                    minutes.toString().padStart(2, '0')
                }: $ {
                    seconds.toString().padStart(2, '0')
                }
            };
        }

        // Update semua durasi yang masih berjalan di tabel
        function updateDurasiRealtime() {
            document.querySelectorAll('.durasi-realtime').forEach(function(element) {
                const createdAtStr = element.getAttribute('data-created');

                if (createdAtStr) {
                    const duration = calculateDuration(createdAtStr);
                    const textElement = element.querySelector('.durasi-text');

                    if (textElement) {
                        textElement.textContent = duration.formatted;
                    }
                }
            });
        }

        // Update durasi di modal edit yang terbuka
        function updateDurasiModal() {
            document.querySelectorAll('.durasi-realtime-modal').forEach(function(element) {
                const createdAtStr = element.getAttribute('data-created');

                if (createdAtStr) {
                    const duration = calculateDuration(createdAtStr);
                    element.value = duration.formatted;
                }
            });
        }

        // Update setiap 1 detik
        let durasiInterval;

        document.addEventListener('DOMContentLoaded', function() {
            // Update pertama kali
            updateDurasiRealtime();
            updateDurasiModal();

            // Set interval untuk update setiap detik
            durasiInterval = setInterval(function() {
                updateDurasiRealtime();
                updateDurasiModal();
            }, 1000);
        });

        // Update saat modal dibuka
        $('.modal').on('shown.bs.modal', function() {
            updateDurasiModal();
        });

        // Bersihkan interval saat halaman di-unload
        window.addEventListener('beforeunload', function() {
            if (durasiInterval) {
                clearInterval(durasiInterval);
            }
        });
    </script>

    {{-- DROPDOWN UNTUK ADD MODAL --}}
    <script>
        // UPT Lists for different service types
        const uptListVpas = @json($uptListVpas);
        const uptListReguler = @json($uptListReguler);
        const uptListAll = @json($uptListAll);

        // JS UNTUK ADD MODAL
        function updateUptOptions() {
            const jenisLayanan = document.getElementById('jenis_layanan').value;
            const uptSearch = document.getElementById('upt_search');
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const namaUptInput = document.getElementById('nama_upt');
            const dropdownBtn = document.getElementById('dropdown-btn');

            // Clear previous selections
            namaUptInput.value = '';
            uptSearch.value = '';
            uptDropdown.innerHTML = '';

            if (jenisLayanan === '') {
                uptSearch.disabled = true;
                dropdownBtn.disabled = true;
                uptSearch.placeholder = 'Pilih jenis layanan dulu...';
                return;
            }

            // Enable UPT search
            uptSearch.disabled = false;
            dropdownBtn.disabled = false;
            uptSearch.placeholder = 'Cari UPT...';

            // Determine which UPT list to use
            let uptList = [];
            switch (jenisLayanan) {
                case 'vpas':
                    uptList = uptListVpas;
                    break;
                case 'reguler':
                    uptList = uptListReguler;
                    break;
                case 'vpasreg':
                    uptList = uptListAll;
                    break;
            }

            // Populate dropdown
            uptList.forEach(upt => {
                const option = document.createElement('a');
                option.className = 'dropdown-item upt-option';
                option.href = '#';
                option.textContent = `${upt.namaupt} - ${upt.kanwil}`;
                option.setAttribute('data-value', upt.namaupt);
                option.setAttribute('data-kanwil', upt.kanwil);
                option.onclick = function() {
                    selectUpt(upt.namaupt, upt.kanwil);
                };
                uptDropdown.appendChild(option);
            });
        }


        // JS UNTUK EDIT MODAL
        function updateUptOptionsEdit(id) {
            const jenisLayanan = document.getElementById(`jenis_layanan_edit_${id}`).value;
            const namaUptSelect = document.getElementById(`nama_upt_edit_${id}`);

            // Clear previous options
            namaUptSelect.innerHTML = '<option value="">-- Pilih UPT --</option>';

            if (jenisLayanan === '') {
                return;
            }

            // Determine which UPT list to use
            let uptList = [];
            switch (jenisLayanan) {
                case 'vpas':
                    uptList = uptListVpas;
                    break;
                case 'reguler':
                    uptList = uptListReguler;
                    break;
                case 'vpasreg':
                    uptList = uptListAll;
                    break;
            }

            // Populate select options
            uptList.forEach(upt => {
                const option = document.createElement('option');
                option.value = upt.namaupt;
                option.textContent = upt.namaupt;
                option.setAttribute('data-kanwil', upt.kanwil);
                namaUptSelect.appendChild(option);
            });
        }


        // Select UPT option
        function selectUpt(namaUpt, kanwil) {
            document.getElementById('upt_search').value = namaUpt;
            document.getElementById('nama_upt').value = namaUpt;
            document.getElementById('uptDropdownMenu').style.display = 'none';
        }

        // Toggle dropdown visibility
        function toggleUptDropdown() {
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = uptDropdown.querySelectorAll('.upt-option');

            if (uptDropdown.style.display === 'none' || uptDropdown.style.display === '') {
                uptOptions.forEach(option => {
                    option.style.display = 'block';
                });
                uptDropdown.style.display = 'block';
            } else {
                uptDropdown.style.display = 'none';
            }
        }

        // Initialize edit modals on page load
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($data as $d)
                updateUptOptionsEdit({{ $d->id }});
                // Set current value
                const currentUpt{{ $d->id }} = '{{ $d->upt->namaupt ?? '' }}';
                const selectElement{{ $d->id }} = document.getElementById(
                    'nama_upt_edit_{{ $d->id }}');
                if (selectElement{{ $d->id }} && currentUpt{{ $d->id }}) {
                    selectElement{{ $d->id }}.value = currentUpt{{ $d->id }};
                }
            @endforeach

            // Searchable UPT dropdown functionality
            const uptSearch = document.getElementById('upt_search');
            const uptDropdown = document.getElementById('uptDropdownMenu');

            // Filter UPT options based on search input
            uptSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const uptOptions = uptDropdown.querySelectorAll('.upt-option');
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

                if (searchTerm.length > 0 && hasVisibleOption) {
                    uptDropdown.style.display = 'block';
                } else if (searchTerm.length === 0) {
                    uptDropdown.style.display = 'none';
                }
            });

            // Show options when clicking on search input
            uptSearch.addEventListener('focus', function() {
                if (this.value.length > 0 && !this.disabled) {
                    const searchTerm = this.value.toLowerCase();
                    const uptOptions = uptDropdown.querySelectorAll('.upt-option');
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

            // Clear UPT selection when search is cleared
            uptSearch.addEventListener('input', function() {
                if (this.value === '') {
                    document.getElementById('nama_upt').value = '';
                }
            });
        });

        // Reset form when modal is closed
        $('#addModal').on('hidden.bs.modal', function() {
            document.getElementById('jenis_layanan').value = '';
            document.getElementById('upt_search').value = '';
            document.getElementById('nama_upt').value = '';
            document.getElementById('uptDropdownMenu').style.display = 'none';
            document.getElementById('upt_search').disabled = true;
            document.getElementById('dropdown-btn').disabled = true;
            document.getElementById('upt_search').placeholder = 'Pilih jenis layanan dulu...';
        });
    </script>


    {{-- Search and Filter JavaScript --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_nama_upt: $('#search-nama_upt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_jenis_layanan: $('#search-jenis_layanan').val().trim(),
                    search_keterangan: $('#search-keterangan').val().trim(),
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
                url.searchParams.delete('search_jenis_layanan');
                url.searchParams.delete('search_keterangan');
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
                $('#search-nama_upt').val('');
                $('#search-kanwil').val('');
                $('#search-jenis_layanan').val('');
                $('#search-keterangan').val('');
                $('#search-status').val('');
                $('#search-pic-1').val('');
                $('#search-pic-2').val('');
                $('#search-tanggal-terlapor-dari').val('');
                $('#search-tanggal-terlapor-sampai').val('');
                $('#search-tanggal-selesai-dari').val('');
                $('#search-tanggal-selesai-sampai').val('');

                let url = new URL(window.location.href);

                // Remove all search parameters
                url.searchParams.delete('search_nama_upt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_jenis_layanan');
                url.searchParams.delete('search_keterangan');
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

            // AUTO REFRESH KETIKA INPUT PENCARIAN DIKOSONGKAN
            $('.column-search input').on('input', function() {
                if ($(this).val().trim() === '') {
                    let inputName = $(this).attr('name');
                    let url = new URL(window.location.href);

                    if (url.searchParams.has(inputName)) {
                        url.searchParams.delete(inputName);
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    }
                }
            });


            // Download functions with current filters
            window.downloadCsv = function() {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('mclientsettingalatupt.mcsettingalat.export.list.csv') }}';
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
                form.action = '{{ route('mclientsettingalatupt.mcsettingalat.export.list.pdf') }}';
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
                $('#search-nama_upt').val(urlParams.get('search_nama_upt'));
            }
            if (urlParams.get('search_kanwil')) {
                $('#search-kanwil').val(urlParams.get('search_kanwil'));
            }
            if (urlParams.get('search_jenis_layanan')) {
                $('#search-jenis_layanan').val(urlParams.get('search_jenis_layanan'));
            }
            if (urlParams.get('search_keterangan')) {
                $('#search-keterangan').val(urlParams.get('search_keterangan'));
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

        function toggleDetail(id) {
            const shortText = document.getElementById('short-text-' + id);
            const fullText = document.getElementById('full-text-' + id);

            if (shortText.style.display === 'none') {
                shortText.style.display = 'inline';
                fullText.style.display = 'none';
            } else {
                shortText.style.display = 'none';
                fullText.style.display = 'inline';
            }
        }
    </script>

@endsection

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
                            <h1 class="headline-large-32 mb-0">Kunjungan Ponpes</h1>
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
                                <h3>Jadwal</h3>
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
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Nama Ponpes</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-nama_ponpes"
                                                            name="search_nama_ponpes" placeholder="Search">
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
                                                <span>Jadwal</span>
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
                                                <td>{{ $d->nama_ponpes ?? '-' }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $layananClass = match (strtolower($d->jenis_layanan ?? '')) {
                                                            'vtren' => 'Tipevpas',
                                                            'reguler' => 'Tipereguller',
                                                            'vtrenreg' => 'badge-prosses',
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
                                                    {{ $d->jadwal ? \Carbon\Carbon::parse($d->jadwal)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->tanggal_selesai)
                                                        {{-- Jika sudah selesai, tampilkan durasi final dalam hari saja --}}
                                                        <span class="Tipereguller">{{ $d->durasi_hari }} hari</span>
                                                    @else
                                                        {{-- Jika belum selesai, tampilkan durasi real-time --}}
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
                                                            <label>Apakah Data Kunjungan <b>{{ $d->nama_ponpes }}
                                                                    ({{ $d->formatted_jenis_layanan }})
                                                                </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form
                                                                action="{{ route('mckunjunganponpes.MclientPonpesKunjunganDestroy', $d->id) }}"
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

                            @if ($data->hasPages())
                                <div class="card-footer">
                                    {{ $data->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>
                        {{-- Add Modal --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form id="addForm" action="{{ route('mckunjunganponpes.MclientPonpesKunjunganStore') }}"
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
                                            <!-- Jenis Layanan & Ponpes -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">Informasi Ponpes</label>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="jenis_layanan">Jenis Layanan <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="jenis_layanan" name="jenis_layanan"
                                                        required onchange="updatePonpesOptions()">
                                                        <option value="">-- Pilih Jenis Layanan --</option>
                                                        @foreach ($jenisLayananOptions as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="nama_ponpes">Nama Ponpes <span
                                                            class="text-danger">*</span></label>
                                                    <div class="dropdown">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="ponpes_search"
                                                                placeholder="Pilih jenis layanan dulu..."
                                                                autocomplete="off" disabled>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="togglePonpesDropdown()" disabled
                                                                    id="dropdown-btn">
                                                                    <i class="fas fa-chevron-down"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-menu w-100" id="ponpesDropdownMenu"
                                                            style="max-height: 200px; overflow-y: auto; display: none;">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="nama_ponpes" name="nama_ponpes" required>
                                                    <small class="form-text text-muted">Pilih jenis layanan terlebih
                                                        dahulu</small>
                                                </div>
                                            </div>

                                            <!-- Detail Kunjungan -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">Detail Kunjungan</label>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                                        placeholder="Masukkan keterangan kunjungan (opsional)"></textarea>
                                                </div>
                                            </div>

                                            <!-- Jadwal & Status -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label class="fw-bold">Jadwal & Status</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="jadwal">Jadwal</label>
                                                        <input type="date" class="form-control" id="jadwal"
                                                            name="jadwal">
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="tanggal_selesai">Tanggal
                                                            Selesai</label>
                                                        <input type="date" class="form-control" id="tanggal_selesai"
                                                            name="tanggal_selesai">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="durasi_hari{{ $d->id }}"
                                                        class="form-label">Durasi</label>
                                                    @if ($d->tanggal_selesai)
                                                        <input type="text" class="form-control"
                                                            id="durasi_hari{{ $d->id }}"
                                                            value="{{ $d->durasi_hari }} hari (Final)" readonly>
                                                        <small class="form-text text-muted">Durasi final telah
                                                            ditetapkan</small>
                                                    @else
                                                        <input type="text" class="form-control durasi-realtime-modal"
                                                            id="durasi_hari{{ $d->id }}"
                                                            data-created="{{ $d->created_at->format('Y-m-d H:i:s') }}"
                                                            value="Menghitung..." readonly>
                                                        <small class="form-text text-muted">Durasi masih berjalan secara
                                                            real-time</small>
                                                    @endif
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
                                                                    {{ $pic->nama_pic }}
                                                                </option>
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

                        {{-- Edit Modals - Will be generated for each data row --}}
                        @foreach ($data as $d)
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                                <form id="editForm{{ $d->id }}"
                                    action="{{ route('mckunjunganponpes.MclientPonpesKunjunganUpdate', ['id' => $d->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit
                                                    Data Kunjungan</label>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $d->id }}">

                                                <!-- Jenis Layanan & Ponpes -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Informasi Ponpes</label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jenis_layanan_edit_{{ $d->id }}">Jenis Layanan
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control"
                                                            id="jenis_layanan_edit_{{ $d->id }}"
                                                            name="jenis_layanan" required
                                                            onchange="updatePonpesOptionsEdit({{ $d->id }})">
                                                            <option value="">-- Pilih Jenis Layanan --</option>
                                                            @foreach ($jenisLayananOptions as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ $d->jenis_layanan == $key ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nama_ponpes_edit_{{ $d->id }}">Nama Ponpes
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control"
                                                            id="nama_ponpes_edit_{{ $d->id }}" name="nama_ponpes"
                                                            required>
                                                            <option value="">-- Pilih Ponpes --</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Detail Kunjungan -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Detail Kunjungan</label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="keterangan{{ $d->id }}">Keterangan</label>
                                                        <textarea class="form-control" id="keterangan{{ $d->id }}" name="keterangan" rows="3"
                                                            placeholder="Masukkan keterangan kunjungan (opsional)">{{ $d->keterangan ?? '' }}</textarea>
                                                    </div>
                                                </div>

                                                <!-- Jadwal & Status -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Jadwal & Status</label>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="jadwal{{ $d->id }}">Jadwal</label>
                                                            <input type="date" class="form-control"
                                                                id="jadwal{{ $d->id }}" name="jadwal"
                                                                value="{{ $d->jadwal ? $d->jadwal->format('Y-m-d') : '' }}">
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
                                                            <label for="durasi_hari{{ $d->id }}">Durasi
                                                                (Hari)
                                                            </label>
                                                            <input type="number" class="form-control"
                                                                id="durasi_hari{{ $d->id }}" name="durasi_hari"
                                                                min="0" value="{{ $d->durasi_hari }}"
                                                                placeholder="Masukkan durasi dalam hari">
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
                                                                        {{ $d->pic_1 }} (Custom)
                                                                    </option>
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
            const createdAt = new Date(createdAtStr + ' UTC');
            const now = new Date();
            const diffMs = now - createdAt;

            const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diffMs % (1000 * 60)) / 1000);

            return {
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds,
                formatted: `${days} hari ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
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
            updateDurasiRealtime();
            updateDurasiModal();

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
        // Ponpes Lists for different service types
        const ponpesListVtren = @json($ponpesListVtren);
        const ponpesListReguler = @json($ponpesListReguler);
        const ponpesListAll = @json($ponpesListAll);

        // Update Ponpes options based on selected service type for Add Modal
        function updatePonpesOptions() {
            const jenisLayanan = document.getElementById('jenis_layanan').value;
            const ponpesSearch = document.getElementById('ponpes_search');
            const ponpesDropdown = document.getElementById('ponpesDropdownMenu');
            const namaPonpesInput = document.getElementById('nama_ponpes');
            const dropdownBtn = document.getElementById('dropdown-btn');

            // Clear previous selections
            namaPonpesInput.value = '';
            ponpesSearch.value = '';
            ponpesDropdown.innerHTML = '';

            if (jenisLayanan === '') {
                ponpesSearch.disabled = true;
                dropdownBtn.disabled = true;
                ponpesSearch.placeholder = 'Pilih jenis layanan dulu...';
                return;
            }

            // Enable Ponpes search
            ponpesSearch.disabled = false;
            dropdownBtn.disabled = false;
            ponpesSearch.placeholder = 'Cari Ponpes...';

            // Determine which Ponpes list to use
            let ponpesList = [];
            switch (jenisLayanan) {
                case 'vtren':
                    ponpesList = ponpesListVtren;
                    break;
                case 'reguler':
                    ponpesList = ponpesListReguler;
                    break;
                case 'vtrenreg':
                    ponpesList = ponpesListAll;
                    break;
            }

            // Populate dropdown
            ponpesList.forEach(ponpes => {
                const option = document.createElement('a');
                option.className = 'dropdown-item ponpes-option';
                option.href = '#';
                option.textContent = `${ponpes.nama_ponpes} - ${ponpes.nama_wilayah}`;
                option.setAttribute('data-value', ponpes.nama_ponpes);
                option.setAttribute('data-nama-wilayah', ponpes.nama_wilayah);
                option.onclick = function() {
                    selectPonpes(ponpes.nama_ponpes, ponpes.nama_wilayah);
                };
                ponpesDropdown.appendChild(option);
            });
        }

        // Update Ponpes options for Edit Modal
        function updatePonpesOptionsEdit(id) {
            const jenisLayanan = document.getElementById(`jenis_layanan_edit_${id}`).value;
            const namaPonpesSelect = document.getElementById(`nama_ponpes_edit_${id}`);

            // Clear previous options
            namaPonpesSelect.innerHTML = '<option value="">-- Pilih Ponpes --</option>';

            if (jenisLayanan === '') {
                return;
            }

            // Determine which Ponpes list to use
            let ponpesList = [];
            switch (jenisLayanan) {
                case 'vtren':
                    ponpesList = ponpesListVtren;
                    break;
                case 'reguler':
                    ponpesList = ponpesListReguler;
                    break;
                case 'vtrenreg':
                    ponpesList = ponpesListAll;
                    break;
            }

            // Populate select options
            ponpesList.forEach(ponpes => {
                const option = document.createElement('option');
                option.value = ponpes.nama_ponpes;
                option.textContent = ponpes.nama_ponpes;
                option.setAttribute('data-nama-wilayah', ponpes.nama_wilayah);
                namaPonpesSelect.appendChild(option);
            });
        }

        // Select Ponpes option
        function selectPonpes(namaPonpes, namaWilayah) {
            document.getElementById('ponpes_search').value = namaPonpes;
            document.getElementById('nama_ponpes').value = namaPonpes;
            document.getElementById('ponpesDropdownMenu').style.display = 'none';
        }

        // Toggle dropdown visibility
        function togglePonpesDropdown() {
            const ponpesDropdown = document.getElementById('ponpesDropdownMenu');
            const ponpesOptions = ponpesDropdown.querySelectorAll('.ponpes-option');

            if (ponpesDropdown.style.display === 'none' || ponpesDropdown.style.display === '') {
                ponpesOptions.forEach(option => {
                    option.style.display = 'block';
                });
                ponpesDropdown.style.display = 'block';
            } else {
                ponpesDropdown.style.display = 'none';
            }
        }

        // Initialize edit modals on page load
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($data as $d)
                updatePonpesOptionsEdit({{ $d->id }});
                // Set current value
                const currentPonpes{{ $d->id }} = '{{ $d->nama_ponpes }}';
                const selectElement{{ $d->id }} = document.getElementById(
                    'nama_ponpes_edit_{{ $d->id }}');
                if (selectElement{{ $d->id }} && currentPonpes{{ $d->id }}) {
                    selectElement{{ $d->id }}.value = currentPonpes{{ $d->id }};
                }
            @endforeach

            // Searchable Ponpes dropdown functionality
            const ponpesSearch = document.getElementById('ponpes_search');
            const ponpesDropdown = document.getElementById('ponpesDropdownMenu');

            // Filter Ponpes options based on search input
            ponpesSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const ponpesOptions = ponpesDropdown.querySelectorAll('.ponpes-option');
                let hasVisibleOption = false;

                ponpesOptions.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = 'block';
                        hasVisibleOption = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (searchTerm.length > 0 && hasVisibleOption) {
                    ponpesDropdown.style.display = 'block';
                } else if (searchTerm.length === 0) {
                    ponpesDropdown.style.display = 'none';
                }
            });

            // Show options when clicking on search input
            ponpesSearch.addEventListener('focus', function() {
                if (this.value.length > 0 && !this.disabled) {
                    const searchTerm = this.value.toLowerCase();
                    const ponpesOptions = ponpesDropdown.querySelectorAll('.ponpes-option');
                    let hasVisibleOption = false;

                    ponpesOptions.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            option.style.display = 'block';
                            hasVisibleOption = true;
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    if (hasVisibleOption) {
                        ponpesDropdown.style.display = 'block';
                    }
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    ponpesDropdown.style.display = 'none';
                }
            });

            // Clear Ponpes selection when search is cleared
            ponpesSearch.addEventListener('input', function() {
                if (this.value === '') {
                    document.getElementById('nama_ponpes').value = '';
                }
            });
        });

        // Reset form when modal is closed
        $('#addModal').on('hidden.bs.modal', function() {
            document.getElementById('jenis_layanan').value = '';
            document.getElementById('ponpes_search').value = '';
            document.getElementById('nama_ponpes').value = '';
            document.getElementById('ponpesDropdownMenu').style.display = 'none';
            document.getElementById('ponpes_search').disabled = true;
            document.getElementById('dropdown-btn').disabled = true;
            document.getElementById('ponpes_search').placeholder = 'Pilih jenis layanan dulu...';
        });
    </script>

    {{-- Search and Filter JavaScript --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_nama_ponpes: $('#search-nama_ponpes').val().trim(),
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
                url.searchParams.delete('search_nama_ponpes');
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
                $('#search-nama_ponpes').val('');
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
                url.searchParams.delete('search_nama_ponpes');
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
                form.action = '{{ route('mckunjunganponpes.mclientkunjunganponpes.export.list.csv') }}';
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
                form.action = '{{ route('mckunjunganponpes.mclientkunjunganponpes.export.list.pdf') }}';
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
                $('#search-nama_ponpes').val(urlParams.get('search_nama_ponpes'));
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

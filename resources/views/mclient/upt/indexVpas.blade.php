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
                            <h1 class="headline-large-32 mb-0">Komplain VPAS</h1>
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
                        <div class="d-flex gap-12">
                            <div class="gap-12 w-fit text-center">
                                <h3>Tanggal Terlapor</h3>
                                <div class="d-flex justify-start align-items-center gap-12">
                                    <div class="flex-column btn-searchbar column-search w-auto">
                                        <label for="search-tanggal-terlapor-dari"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;"> Awal</label>
                                        <input type="date" id="search-tanggal-terlapor-dari"
                                            name="search_tanggal_terlapor_dari" title="Tanggal Dari">
                                    </div>
                                    <div class="flex-column btn-searchbar column-search w-auto">
                                        <label for="search-tanggal-terlapor-sampai"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;"> Akhir</label>
                                        <input type="date" id="search-tanggal-terlapor-sampai"
                                            name="search_tanggal_terlapor_sampai" title="Tanggal Sampai">
                                    </div>
                                </div>
                            </div>
                            <div class="gap-12 w-fit text-center">
                                <h3>Tanggal Selesai</h3>
                                <div class="d-flex justify-start align-items-center gap-12">
                                    <div class="flex-column btn-searchbar column-search w-auto">
                                        <label for="search-tanggal-selesai-dari"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;"> Awal</label>
                                        <input type="date" id="search-tanggal-selesai-dari"
                                            name="search_tanggal_selesai_dari" title="Tanggal Dari">
                                    </div>
                                    <div class="flex-column btn-searchbar column-search w-auto">
                                        <label for="search-tanggal-selesai-sampai"
                                            style="display: block; margin-bottom: 4px; font-size: 14px;"> Akhir</label>
                                        <input type="date" id="search-tanggal-selesai-sampai"
                                            name="search_tanggal_selesai_sampai" title="Tanggal Sampai">
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
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Nama UPT</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-nama-upt"
                                                            name="search_nama_upt">
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
                                                        <input type="text" id="search-kanwil" name="search_kanwil">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Jenis Kendala</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-jenis-kendala"
                                                            name="search_jenis_kendala">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Detail Kendala</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-detail-kendala"
                                                            name="search_detail_kendala">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <span>Tanggal Terlapor</span>
                                            </th>
                                            <th class="text-center align-top">
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Tanggal Selesai</span>
                                                </div>
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
                                                        <input type="text" id="search-status" name="search_status">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-top">
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
                                            <th class="align-top">
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
                                                <td>{{ $d->upt->namaupt ?? '-' }}</td>
                                                <td>{{ $d->upt->kanwil->kanwil ?? '-' }}</td>
                                                <td class="text-center">
                                                    <span class="Tipereguller">
                                                        {{ Str::limit($d->jenis_kendala ?? 'Belum ditentukan', 30) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($d->detail_kendala && strlen($d->detail_kendala) > 20)
                                                        <div id="short-text-{{ $d->id }}">
                                                            <div>{{ Str::limit($d->detail_kendala, 20) }}</div>
                                                            <a href="javascript:void(0)"
                                                                onclick="toggleDetail({{ $d->id }})"
                                                                class="text-primary">
                                                                <small>Show</small>
                                                            </a>
                                                        </div>
                                                        <div id="full-text-{{ $d->id }}" style="display: none;">
                                                            <div
                                                                style="white-space: pre-wrap; word-wrap: break-word; max-width: 300px;">
                                                                {{ $d->detail_kendala }}
                                                            </div>
                                                            <a href="javascript:void(0)"
                                                                onclick="toggleDetail({{ $d->id }})"
                                                                class="text-primary">
                                                                <small>Hide</small>
                                                            </a>
                                                        </div>
                                                    @else
                                                        {{ $d->detail_kendala ?? '-' }}
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
                                                            data-created="{{ ($d->tanggal_terlapor ?? $d->created_at)->format('Y-m-d H:i:s') }}">
                                                            <span class="durasi-text">Menghitung...</span>
                                                        </span>
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

                                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                        {{-- DELETE BUTTON --}}
                                                        <a data-toggle="modal"
                                                            data-target="#modal-default{{ $d->id }}"
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
                                                            <label>Apakah Data Komplain <b> {{ $d->upt->namaupt }} </b>
                                                                ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form
                                                                action="{{ route('mcvpas.MclientVpasDestroy', $d->id) }}"
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
                                                                @foreach ($uptList as $upt)
                                                                    <a class="dropdown-item upt-option"
                                                                        href="javascript:void(0)"
                                                                        data-id="{{ $upt->id }}"
                                                                        data-nama="{{ $upt->namaupt }}"
                                                                        data-kanwil="{{ $upt->kanwil->kanwil ?? '' }}"
                                                                        onclick="selectUpt({{ $upt->id }}, '{{ $upt->namaupt }}', '{{ addslashes($upt->kanwil->kanwil ?? '') }}')">
                                                                        {{ $upt->namaupt }} -
                                                                        {{ $upt->kanwil->kanwil ?? '-' }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <!-- INI YANG PENTING: Kirim ID bukan nama -->
                                                        <input type="hidden" id="data_upt_id" name="data_upt_id"
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

                                            <!-- Detail Kendala Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <label>Detail Kendala</label>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="kendala_id" class="form-label">Jenis Kendala</label>
                                                        <select class="form-control" id="kendala_id" name="kendala_id">
                                                            <option value="">-- Pilih Jenis Kendala --</option>
                                                            @foreach ($jenisKendala as $kendala)
                                                                <option value="{{ $kendala->id }}">
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

                        {{-- Edit Modals - --}}
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
                                                            <label for="data_upt_id_edit_{{ $d->id }}"
                                                                class="form-label">Nama UPT <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control"
                                                                id="data_upt_id_edit_{{ $d->id }}"
                                                                name="data_upt_id"
                                                                onchange="updateKanwilEdit(this.value, {{ $d->id }})"
                                                                required>
                                                                <option value="">-- Pilih UPT --</option>
                                                                @foreach ($uptList as $upt)
                                                                    <option value="{{ $upt->id }}"
                                                                        data-kanwil="{{ $upt->kanwil->kanwil ?? '' }}"
                                                                        {{ $d->data_upt_id == $upt->id ? 'selected' : '' }}>
                                                                        {{ $upt->namaupt }} -
                                                                        {{ $upt->kanwil->kanwil ?? '-' }}
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

                                                <!-- Detail Kendala Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label>Detail Kendala</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="mb-3">
                                                                <label for="kendala_id_edit_{{ $d->id }}"
                                                                    class="form-label">Jenis Kendala</label>
                                                                <select class="form-control"
                                                                    id="kendala_id_edit_{{ $d->id }}"
                                                                    name="kendala_id">
                                                                    <option value="">-- Pilih Jenis Kendala --
                                                                    </option>
                                                                    @foreach ($jenisKendala as $kendala)
                                                                        <option value="{{ $kendala->id }}"
                                                                            {{ $d->kendala_id == $kendala->id ? 'selected' : '' }}>
                                                                            {{ $kendala->jenis_kendala }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="detail_kendala_edit_{{ $d->id }}"
                                                                    class="form-label">Detail Kendala</label>
                                                                <textarea class="form-control" id="detail_kendala_edit_{{ $d->id }}" name="detail_kendala" rows="3"
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
                                                            <label for="tanggal_terlapor_edit_{{ $d->id }}"
                                                                class="form-label">Tanggal Terlapor</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_terlapor_edit_{{ $d->id }}"
                                                                name="tanggal_terlapor"
                                                                value="{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai_edit_{{ $d->id }}"
                                                                class="form-label">Tanggal Selesai</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_selesai_edit_{{ $d->id }}"
                                                                name="tanggal_selesai"
                                                                value="{{ $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="status_edit_{{ $d->id }}"
                                                            class="form-label">Status</label>
                                                        <select class="form-control" id="status_edit_{{ $d->id }}"
                                                            name="status">
                                                            <option value="">-- Pilih Status --</option>
                                                            <option value="pending"
                                                                {{ $d->status == 'pending' ? 'selected' : '' }}>Pending
                                                            </option>
                                                            <option value="proses"
                                                                {{ $d->status == 'proses' ? 'selected' : '' }}>Proses
                                                            </option>
                                                            <option value="selesai"
                                                                {{ $d->status == 'selesai' ? 'selected' : '' }}>Selesai
                                                            </option>
                                                            <option value="terjadwal"
                                                                {{ $d->status == 'terjadwal' ? 'selected' : '' }}>Terjadwal
                                                            </option>
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
                                                            <label for="pic_1_edit_{{ $d->id }}"
                                                                class="form-label">PIC 1</label>
                                                            <select class="form-control"
                                                                id="pic_1_edit_{{ $d->id }}" name="pic_1">
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
                                                            <label for="pic_2_edit_{{ $d->id }}"
                                                                class="form-label">PIC 2</label>
                                                            <select class="form-control"
                                                                id="pic_2_edit_{{ $d->id }}" name="pic_2">
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

                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                <!-- Preserve all search parameters -->
                                @if (request('search_nama_upt'))
                                    <input type="hidden" name="search_nama_upt"
                                        value="{{ request('search_nama_upt') }}">
                                @endif

                                @if (request('search_kanwil'))
                                    <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
                                @endif

                                @if (request('search_jenis_kendala'))
                                    <input type="hidden" name="search_jenis_kendala"
                                        value="{{ request('search_jenis_kendala') }}">
                                @endif

                                @if (request('search_detail_kendala'))
                                    <input type="hidden" name="search_detail_kendala"
                                        value="{{ request('search_detail_kendala') }}">
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

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

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

            if (diffMs < 0) {
                return {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0,
                    formatted: '0 hari 00:00:00'
                };
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

    {{-- JS Modal --}}
    <script>
        // =================== FUNGSI UNTUK ADD MODAL ===================
        document.addEventListener('DOMContentLoaded', function() {
            const uptSearch = document.getElementById('upt_search');
            const uptDropdown = document.getElementById('uptDropdownMenu');
            const uptOptions = document.querySelectorAll('.upt-option');
            const dataUptIdHidden = document.getElementById('data_upt_id');
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

            // VALIDASI FORM SEBELUM SUBMIT
            const addForm = document.getElementById('addForm');
            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    const uptId = dataUptIdHidden.value;
                    if (!uptId || uptId.trim() === '') {
                        e.preventDefault();
                        alert('Silakan pilih Nama UPT terlebih dahulu');
                        uptSearch.focus();
                        return false;
                    }
                });
            }
        });
        // ======================================


        // PADA SAAT MEMILIH DATA UPT, DROPDOWN OTOMATIS DISEMBUNYIKAN
        function selectUpt(uptId, namaUpt, kanwil) {
            console.log('Selected UPT:', {
                id: uptId,
                nama: namaUpt,
                kanwil: kanwil
            }); // Debug

            // Set visible input (untuk display)
            document.getElementById('upt_search').value = namaUpt;

            // Set hidden input dengan ID (INI YANG PENTING!)
            document.getElementById('data_upt_id').value = uptId;

            // Set kanwil display
            document.getElementById('kanwil_display').value = kanwil;

            // Hide dropdown
            document.getElementById('uptDropdownMenu').style.display = 'none';
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
                const kanwil = selectedOption.getAttribute('data-kanwil');
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
                            .getAttribute('data-kanwil');
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
            document.getElementById('data_upt_id').value = '';
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
                    search_nama_upt: $('#search-nama-upt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_jenis_kendala: $('#search-jenis-kendala').val().trim(),
                    search_detail_kendala: $('#search-detail-kendala').val().trim(),
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
                url.searchParams.delete('search_detail_kendala');
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
                $('#search-detail-kendala').val('');
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
                url.searchParams.delete('search_jenis_kendala');
                url.searchParams.delete('search_detail_kendala');
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
            if (urlParams.get('search_detail_kendala')) {
                $('#search-detail-kendala').val(urlParams.get('search_detail_kendala'));
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

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
                        @if (request('table_search'))
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Hasil pencarian untuk: "<strong>{{ request('table_search') }}</strong>"
                                    <a href="{{ route('ListDataMclientSettingAlat') }}"
                                        class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="card mt-3">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="Table">
                                    <thead>
                                        <tr>
                                            <th>Nama UPT</th>
                                            <th>Jenis Layanan</th>
                                            <th class="text-center">Keterangan</th>
                                            <th>tanggal Terlapor</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Durasi (Hari)</th>
                                            <th class="text-center">Status</th>
                                            <th>PIC 1</th>
                                            <th>PIC 2</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $d)
                                            <tr>
                                                <td>{{ $d->nama_upt ?? '-' }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $layananClass = match (strtolower($d->jenis_layanan ?? '')) {
                                                            'vpas' => 'Tipevpas',
                                                            'reguler' => 'Tipereguller',
                                                            'vpasreg' => 'badge-prosses', // default => '',
                                                        };
                                                    @endphp
                                                    <span class="{{ $layananClass }}">
                                                        {{ $d->formatted_jenis_layanan }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="Tipereguller">
                                                        {{ Str::limit($d->keterangan ?? 'Tidak ada keterangan', 40) }}
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
                                                <td>{{ $d->pic_1 ?? '-' }}</td>
                                                <td>{{ $d->pic_2 ?? '-' }}</td>
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
                                                            <label>Apakah Data Kunjungan <b>{{ $d->nama_upt }}
                                                                    ({{ $d->formatted_jenis_layanan }})
                                                                </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('MclientSettingAlatDestroy', $d->id) }}"
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
                                                <td colspan="10" class="text-center">Tidak ada data yang ditemukan</td>
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
                            <form id="addForm" action="{{ route('MclientSettingAlatStore') }}" method="POST">
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
                                                        <label for="tanggal_terlapor">Tanggal Terlapor</label>
                                                        <input type="date" class="form-control"
                                                            id="tanggal_terlapor" name="tanggal_terlapor">
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
                                                        <label for="durasi_hari">Durasi
                                                            (Hari)</label>
                                                        <input type="number" class="form-control" id="durasi_hari"
                                                            name="durasi_hari" min="0"
                                                            placeholder="Masukkan durasi dalam hari">
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
                                    action="{{ route('MclientSettingAlatUpdate', ['id' => $d->id]) }}" method="POST">
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

                                                <!-- Jenis Layanan & UPT -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <label class="fw-bold">Informasi UPT</label>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jenis_layanan_edit_{{ $d->id }}">Jenis Layanan
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control"
                                                            id="jenis_layanan_edit_{{ $d->id }}"
                                                            name="jenis_layanan" required
                                                            onchange="updateUptOptionsEdit({{ $d->id }})">
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
                                                        <label for="nama_upt_edit_{{ $d->id }}">Nama UPT <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control"
                                                            id="nama_upt_edit_{{ $d->id }}" name="nama_upt"
                                                            required>
                                                            <option value="">-- Pilih UPT --</option>
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
                                                            <label for="tanggal_terlapor{{ $d->id }}">Tanggal Terlapor</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_terlapor{{ $d->id }}"
                                                                name="tanggal_terlapor"
                                                                value="{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai{{ $d->id }}">Tanggal Selesai</label>
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

                <!-- Pagination Controls -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="btn-datakolom">
                        <button class="btn-select d-flex align-items-center">
                            <select id="row-limit">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="9999">Semua</option>
                            </select>
                            Kolom
                        </button>
                    </div>

                    <div class="pagination-controls d-flex align-items-center gap-12">
                        <button class="btn-page" id="prev-page" disabled>&laquo; Previous</button>
                        <span id="page-info">Page 1 of 5</span>
                        <button class="btn-page" id="next-page">Next &raquo;</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        // UPT Lists for different service types
        const uptListVpas = @json($uptListVpas);
        const uptListReguler = @json($uptListReguler);
        const uptListAll = @json($uptListAll);

        // Update UPT options based on selected service type for Add Modal
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

        // Update UPT options for Edit Modal
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
                const currentUpt{{ $d->id }} = '{{ $d->nama_upt }}';
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

        // Search and Pagination
        $(document).ready(function() {
            const $rows = $("#Table tbody tr");
            let limit = parseInt($("#row-limit").val());
            let currentPage = 1;
            let totalPages = Math.ceil($rows.length / limit);

            function updateTable() {
                $rows.hide();
                let start = (currentPage - 1) * limit;
                let end = start + limit;
                $rows.slice(start, end).show();

                $("#page-info").text(`Page ${currentPage} of ${totalPages}`);
                $("#prev-page").prop("disabled", currentPage === 1);
                $("#next-page").prop("disabled", currentPage === totalPages);
            }

            updateTable();

            $("#row-limit").on("change", function() {
                limit = parseInt($(this).val());
                currentPage = 1;
                totalPages = Math.ceil($rows.length / limit);
                updateTable();
            });

            $("#prev-page").on("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $("#next-page").on("click", function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });

            $("#btn-search").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("#Table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

                const $visibleRows = $("#Table tbody tr:visible");
                totalPages = Math.ceil($visibleRows.length / limit);
                currentPage = 1;

                if (value === '') {
                    updateTable();
                } else {
                    $("#page-info").text(`Showing ${$visibleRows.length} results`);
                    $("#prev-page").prop("disabled", true);
                    $("#next-page").prop("disabled", true);
                }
            });
        });
    </script>

@endsection

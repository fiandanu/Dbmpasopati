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
                            <h1 class="headline-large-32 mb-0">Keluhan Reguller</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Search bar -->
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search"
                                    value="{{ request('table_search') }}">
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
                                    <a href="{{ route('ListDataMclientReguller') }}" class="btn btn-sm btn-secondary ml-2">
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
                                            <th>Kanwil</th>
                                            <th>Jenis Kendala</th>
                                            <th>Tanggal Terlapor</th>
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
                                                <td>{{ $d->kanwil ?? '-' }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-warning">
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
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('MclientRegullerDestroy', $d->id) }}"
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
                                                <td colspan="10" class="text-center">Tidak ada data yang ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if ($data->hasPages())
                                <div class="card-footer">
                                    {{ $data->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>

                        {{-- Add Modal --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form id="addForm" action="{{ route('MclientRegullerStore') }}" method="POST">
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
                                            <!-- Informasi UPT Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <h5>Informasi UPT</h5>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="nama_upt" class="form-label">Nama UPT <span
                                                                class="text-danger">*</span></label>
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
                                                            name="kanwil" readonly placeholder="Kantor Wilayah">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Kendala Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <h5>Detail Kendala</h5>
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
                                                    <h5>Tanggal & Status</h5>
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
                                                    <h5>PIC</h5>
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
                                    action="{{ route('MclientRegullerUpdate', ['id' => $d->id]) }}" method="POST">
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
                                                        <h5>Informasi UPT</h5>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="nama_upt_edit_{{ $d->id }}"
                                                                class="form-label">Nama UPT <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control"
                                                                id="nama_upt_edit_{{ $d->id }}" name="nama_upt"
                                                                required
                                                                onchange="updateKanwilEdit(this.value, {{ $d->id }})">
                                                                <option value="">-- Pilih UPT --</option>
                                                                @foreach ($uptList as $upt)
                                                                    <option value="{{ $upt->namaupt }}"
                                                                        data-kanwil="{{ $upt->kanwil }}"
                                                                        {{ $d->nama_upt == $upt->namaupt ? 'selected' : '' }}>
                                                                        {{ $upt->namaupt }}
                                                                    </option>
                                                                @endforeach
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
                                                        <h5>Detail Kendala</h5>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="jenis_kendala{{ $d->id }}"
                                                                class="form-label">Jenis Kendala</label>
                                                            <select class="form-control"
                                                                id="jenis_kendala{{ $d->id }}"
                                                                name="jenis_kendala">
                                                                <option value="">-- Pilih Jenis Kendala --</option>
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

                                                <!-- Tanggal & Status Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5>Tanggal & Status</h5>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="tanggal_terlapor{{ $d->id }}"
                                                                class="form-label">Tanggal Terlapor</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_terlapor{{ $d->id }}"
                                                                name="tanggal_terlapor"
                                                                value="{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai{{ $d->id }}"
                                                                class="form-label">Tanggal Selesai</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_selesai{{ $d->id }}"
                                                                name="tanggal_selesai"
                                                                value="{{ $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '' }}">
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
                                                                    {{ $d->status == 'pending' ? 'selected' : '' }}>Pending
                                                                </option>
                                                                <option value="proses"
                                                                    {{ $d->status == 'proses' ? 'selected' : '' }}>Proses
                                                                </option>
                                                                <option value="selesai"
                                                                    {{ $d->status == 'selesai' ? 'selected' : '' }}>Selesai
                                                                </option>
                                                                <option value="terjadwal"
                                                                    {{ $d->status == 'terjadwal' ? 'selected' : '' }}>
                                                                    Terjadwal</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- PIC Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5>PIC</h5>
                                                    </div>
                                                    <div class="column">
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

                <!-- Pagination Controls -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Row limit -->
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

                    <!-- Pagination -->
                    <div class="pagination-controls d-flex align-items-center gap-12">
                        <button class="btn-page" id="prev-page" disabled>&laquo; Previous</button>
                        <span id="page-info">Page 1 of 5</span>
                        <button class="btn-page" id="next-page">Next &raquo;</button>
                    </div>
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

    {{-- Search and Pagination JavaScript --}}
    <script>
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

                // Update info halaman
                $("#page-info").text(`Page ${currentPage} of ${totalPages}`);

                // Disable prev/next sesuai kondisi
                $("#prev-page").prop("disabled", currentPage === 1);
                $("#next-page").prop("disabled", currentPage === totalPages);
            }

            // Apply awal
            updateTable();

            // Kalau ganti jumlah data
            $("#row-limit").on("change", function() {
                limit = parseInt($(this).val());
                currentPage = 1;
                totalPages = Math.ceil($rows.length / limit);
                updateTable();
            });

            // Tombol prev
            $("#prev-page").on("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            // Tombol next
            $("#next-page").on("click", function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });

            // Filter Data By Search
            $("#btn-search").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("#Table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

                // Update pagination after search
                const $visibleRows = $("#Table tbody tr:visible");
                totalPages = Math.ceil($visibleRows.length / limit);
                currentPage = 1;

                if (value === '') {
                    // If search is cleared, show all rows with pagination
                    updateTable();
                } else {
                    // If searching, hide pagination info
                    $("#page-info").text(`Showing ${$visibleRows.length} results`);
                    $("#prev-page").prop("disabled", true);
                    $("#next-page").prop("disabled", true);
                }
            });

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

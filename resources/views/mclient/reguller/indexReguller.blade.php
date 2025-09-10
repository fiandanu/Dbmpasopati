@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
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
                            <div class="card-header">
                                <h3 class="card-title mt-2">Data Keluhan Client Reguller</h3>
                                <div class="card-tools">
                                    <!-- Tombol Tambah Data -->
                                    <button type="button" class="btn btn-sm btn-primary mr-2 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#addModal">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </button>

                                    <form action="{{ route('ListDataMclientReguller') }}" method="GET" class="d-inline">
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
                                            <th>Nama UPT</th>
                                            <th>Kanwil</th>
                                            <th>Jenis Kendala</th>
                                            <th>Tanggal Terlapor</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Durasi (Hari)</th>
                                            <th>Status</th>
                                            <th>PIC 1</th>
                                            <th>PIC 2</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = ($data->currentPage() - 1) * $data->perPage() + 1;
                                        @endphp
                                        @forelse ($data as $d)
                                            <tr>
                                                <td><strong>{{ $d->nama_upt ?? '-' }}</strong></td>
                                                <td>{{ $d->kanwil ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-warning">
                                                        {{ Str::limit($d->jenis_kendala ?? 'Tidak ada kendala', 30) }}
                                                    </span>
                                                </td>
                                                <td>{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('d/m/Y') : '-' }}</td>
                                                <td>{{ $d->tanggal_selesai ? $d->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                                                <td>
                                                    @if ($d->durasi_hari)
                                                        <span class="badge badge-info">{{ $d->durasi_hari }} hari</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = '';
                                                        switch (strtolower($d->status ?? '')) {
                                                            case 'terjadwal':
                                                                $statusClass = 'badge-info';
                                                                break;          
                                                            case 'selesai':
                                                                $statusClass = 'badge-success';
                                                                break;
                                                            case 'proses':
                                                                $statusClass = 'badge-warning';
                                                                break;
                                                            case 'pending':
                                                                $statusClass = 'badge-danger';
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
                                                <a href="#editModal{{ $d->id }}" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $d->id }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>

                                                {{-- Delete Button --}}
                                                <a data-toggle="modal" data-target="#modal-default{{ $d->id }}"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
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
                                                            <p>Apakah data monitoring client Reguller di UPT
                                                                <b>{{ $d->nama_upt }}</b> ingin dihapus?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('MclientRegullerDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
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

                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form action="{{ route('MclientRegullerStore') }}" method="POST">
                                @csrf
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addModalLabel">Tambah Data Keluhan Client Reguller
                                            </h5>
                                            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="nama_upt" class="form-label">Nama UPT <span
                                                                class="text-danger">*</span></label>
                                                        <div class="dropdown">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="upt_search" 
                                                                    placeholder="Cari UPT..." autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-outline-secondary" 
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
                                                        <input type="text" class="form-control" id="kanwil" name="kanwil" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jenis_kendala" class="form-label">Jenis Kendala</label>
                                                        <select class="form-control" id="jenis_kendala" name="jenis_kendala">
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
                                                        <small class="form-text text-muted">
                                                            Isi field ini jika memilih "Lainnya" atau ingin memberikan
                                                            detail tambahan
                                                        </small>
                                                    </div>

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
                                                </div>

                                                <div class="col-md-6">
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

                                                    <!-- PIC 1 - ubah dari input text ke dropdown -->
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

                                                    <!-- PIC 2 - ubah dari input text ke dropdown -->
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
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @foreach ($data as $d)
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <form action="{{ route('MclientRegullerUpdate', ['id' => $d->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data Monitoring Client
                                                    Reguller</h5>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="nama_upt_edit" class="form-label">Nama UPT <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control" id="nama_upt_edit_{{ $d->id }}" name="nama_upt" required onchange="updateKanwilEdit(this.value, {{ $d->id }})">
                                                                <option value="">-- Pilih UPT --</option>
                                                                @foreach ($uptList as $upt)
                                                                    <option value="{{ $upt->namaupt }}" data-kanwil="{{ $upt->kanwil }}" {{ $d->nama_upt == $upt->namaupt ? 'selected' : '' }}>
                                                                        {{ $upt->namaupt }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kanwil_edit" class="form-label">Kanwil</label>
                                                            <input type="text" class="form-control" id="kanwil_edit_{{ $d->id }}" name="kanwil" value="{{ $d->kanwil }}" readonly>
                                                        </div>

                                                        <!-- Jenis Kendala - ubah dari select hardcoded ke database -->
                                                        <div class="mb-3">
                                                            <label for="jenis_kendala" class="form-label">Jenis Kendala</label>
                                                            <select class="form-control" id="jenis_kendala" name="jenis_kendala">
                                                                <option value="">-- Pilih Jenis Kendala --</option>
                                                                @foreach ($jenisKendala as $kendala)
                                                                    <option value="{{ $kendala->jenis_kendala }}" {{ $d->jenis_kendala == $kendala->jenis_kendala ? 'selected' : '' }}>
                                                                        {{ $kendala->jenis_kendala }}
                                                                    </option>
                                                                @endforeach
                                                                <!-- Jika nilai existing tidak ada dalam database, tetap tampilkan -->
                                                                @php
                                                                    $existingKendala = $jenisKendala->pluck('jenis_kendala')->toArray();
                                                                @endphp
                                                               
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="detail_kendala" class="form-label">Detail
                                                                Kendala</label>
                                                            <textarea class="form-control" id="detail_kendala" name="detail_kendala" rows="3"
                                                                placeholder="Jelaskan detail kendala lebih spesifik (opsional)">{{ $d->detail_kendala ?? '' }}</textarea>
                                                            <small class="form-text text-muted">
                                                                Isi field ini jika memilih "Lainnya" atau ingin memberikan
                                                                detail tambahan
                                                            </small>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal_terlapor" class="form-label">Tanggal
                                                                Terlapor</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_terlapor" name="tanggal_terlapor"
                                                                value="{{ $d->tanggal_terlapor ? $d->tanggal_terlapor->format('Y-m-d') : '' }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai" class="form-label">Tanggal
                                                                Selesai</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_selesai" name="tanggal_selesai"
                                                                value="{{ $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="durasi_hari" class="form-label">Durasi
                                                                (Hari)
                                                            </label>
                                                            <input type="number" class="form-control" id="durasi_hari"
                                                                name="durasi_hari" min="0"
                                                                value="{{ $d->durasi_hari }}"
                                                                placeholder="Masukkan durasi dalam hari">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-control" id="status" name="status">
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

                                                        <!-- PIC 1 - ubah dari input text ke dropdown -->
                                                        <div class="mb-3">
                                                            <label for="pic_1" class="form-label">PIC 1</label>
                                                            <select class="form-control" id="pic_1" name="pic_1">
                                                                <option value="">-- Pilih PIC 1 --</option>
                                                                @foreach ($picList as $pic)
                                                                    <option value="{{ $pic->nama_pic }}" {{ $d->pic_1 == $pic->nama_pic ? 'selected' : '' }}>
                                                                        {{ $pic->nama_pic }}
                                                                    </option>
                                                                @endforeach
                                                                <!-- Jika nilai existing tidak ada dalam database, tetap tampilkan -->
                                                                @php
                                                                    $existingPics = $picList->pluck('nama_pic')->toArray();
                                                                @endphp
                                                                @if ($d->pic_1 && !in_array($d->pic_1, $existingPics))
                                                                    <option value="{{ $d->pic_1 }}" selected>
                                                                        {{ $d->pic_1 }} (Custom)
                                                                    </option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <!-- PIC 2 - ubah dari input text ke dropdown -->
                                                        <div class="mb-3">
                                                            <label for="pic_2" class="form-label">PIC 2</label>
                                                            <select class="form-control" id="pic_2" name="pic_2">
                                                                <option value="">-- Pilih PIC 2 --</option>
                                                                @foreach ($picList as $pic)
                                                                    <option value="{{ $pic->nama_pic }}" {{ $d->pic_2 == $pic->nama_pic ? 'selected' : '' }}>
                                                                        {{ $pic->nama_pic }}
                                                                    </option>
                                                                @endforeach
                                                                <!-- Jika nilai existing tidak ada dalam database, tetap tampilkan -->
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
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
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

    <script>
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

    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($data as $d)
            const selectEdit{{ $d->id }} = document.getElementById('nama_upt_edit_{{ $d->id }}');
            if (selectEdit{{ $d->id }}) {
                const selectedOptionEdit{{ $d->id }} = selectEdit{{ $d->id }}.querySelector('option:checked');
                if (selectedOptionEdit{{ $d->id }}) {
                    const kanwilEdit{{ $d->id }} = selectedOptionEdit{{ $d->id }}.getAttribute('data-kanwil');
                    if (kanwilEdit{{ $d->id }}) {
                        document.getElementById('kanwil_edit_{{ $d->id }}').value = kanwilEdit{{ $d->id }};
                    }
                }
            }
        @endforeach
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        const uptSearch = document.getElementById('upt_search');
        const uptDropdown = document.getElementById('uptDropdownMenu');
        const uptOptions = document.querySelectorAll('.upt-option');
        
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
            
            if (searchTerm.length > 0 && hasVisibleOption) {
                uptDropdown.style.display = 'block';
            } else if (searchTerm.length === 0) {
                uptDropdown.style.display = 'none';
            }
        });
        
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
        
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                uptDropdown.style.display = 'none';
            }
        });
    });

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

    function selectUpt(namaUpt, kanwil) {
        document.getElementById('upt_search').value = namaUpt;
        document.getElementById('nama_upt').value = namaUpt;
        document.getElementById('kanwil').value = kanwil;
        document.getElementById('uptDropdownMenu').style.display = 'none';
    }

    document.getElementById('upt_search').addEventListener('input', function() {
        if (this.value === '') {
            document.getElementById('nama_upt').value = '';
            document.getElementById('kanwil').value = '';
        }
    });

    $('#addModal').on('hidden.bs.modal', function () {
        document.getElementById('upt_search').value = '';
        document.getElementById('nama_upt').value = '';
        document.getElementById('kanwil').value = '';
        document.getElementById('uptDropdownMenu').style.display = 'none';
    });
    </script>
@endsection
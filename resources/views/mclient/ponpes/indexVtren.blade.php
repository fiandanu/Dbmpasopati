@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
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
                                    <a href="{{ route('ListDataMclientPonpesVtren') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title mt-2">Data Keluhan Client Ponpes VTREN</h3>
                                <div class="card-tools">
                                    <!-- Tombol Tambah Data -->
                                    <button type="button" class="btn btn-sm btn-primary mr-2 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#addModal">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </button>

                                    <form action="{{ route('ListDataMclientPonpesVtren') }}" method="GET" class="d-inline">
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
                                            <th>Nama Ponpes</th>
                                            <th>Nama Wilayah</th>
                                            <th>Kendala VTREN</th>
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
                                                <td>{{ $no++ }}</td>
                                                <td><strong>{{ $d->nama_ponpes ?? '-' }}</strong></td>
                                                <td>{{ $d->nama_wilayah ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-warning">
                                                        {{ Str::limit($d->jenis_kendala ?? 'Tidak ada kendala', 30) }}
                                                    </span>
                                                </td>
                                                <td>{{ $d->tanggal_terlapor ?? '-' }}</td>
                                                <td>{{ $d->tanggal_selesai ?? '-' }}</td>
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

                                            {{-- Delete Modal --}}
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
                                                            <p>Apakah data monitoring client VTREN di Ponpes
                                                                <b>{{ $d->nama_ponpes }}</b> ingin dihapus?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('MclientPonpesVtrenDestroy', $d->id) }}"
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
                                                <td colspan="11" class="text-center">Tidak ada data yang ditemukan</td>
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

                        {{-- Add Modal with Searchable Ponpes --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form action="{{ route('MclientPonpesVtrenStore') }}" method="POST">
                                @csrf
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addModalLabel">Tambah Data Keluhan Client Ponpes VTREN
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
                                                        <label for="nama_ponpes" class="form-label">Nama Ponpes <span
                                                                class="text-danger">*</span></label>
                                                        <div class="dropdown">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="ponpes_search" 
                                                                    placeholder="Cari Ponpes..." autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-outline-secondary" 
                                                                            onclick="togglePonpesDropdown()">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-menu w-100" id="ponpesDropdownMenu" 
                                                                style="max-height: 200px; overflow-y: auto; display: none;">
                                                                @foreach ($ponpesList as $ponpes)
                                                                    <a class="dropdown-item ponpes-option" href="#" 
                                                                    data-value="{{ $ponpes->nama_ponpes }}" 
                                                                    data-nama_wilayah="{{ $ponpes->nama_wilayah }}"
                                                                    onclick="selectPonpes('{{ $ponpes->nama_ponpes }}', '{{ $ponpes->nama_wilayah }}')">
                                                                        {{ $ponpes->nama_ponpes }} - {{ $ponpes->nama_wilayah }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="nama_ponpes" name="nama_ponpes" required>
                                                        <small class="form-text text-muted">Ketik untuk mencari Ponpes</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nama_wilayah" class="form-label">Nama Wilayah</label>
                                                        <input type="text" class="form-control" id="nama_wilayah" name="nama_wilayah" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jenis_kendala" class="form-label">Jenis Kendala VTREN</label>
                                                        <select class="form-control" id="jenis_kendala"
                                                            name="jenis_kendala">
                                                            <option value="">-- Pilih Jenis Kendala --</option>
                                                            @foreach ($jenisKendala as $kendala)
                                                                <option value="{{ $kendala }}">
                                                                    {{ $kendala }}
                                                                </option>
                                                            @endforeach
                                                            <option value="lainnya">
                                                                Lainnya (tulis di detail kendala)
                                                            </option>
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
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="pic_1" class="form-label">PIC 1</label>
                                                        <input type="text" class="form-control" id="pic_1"
                                                            name="pic_1" placeholder="Nama PIC pertama">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="pic_2" class="form-label">PIC 2</label>
                                                        <input type="text" class="form-control" id="pic_2"
                                                            name="pic_2" placeholder="Nama PIC kedua">
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

                        {{-- Edit Modals --}}
                        @foreach ($data as $d)
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <form action="{{ route('MclientPonpesVtrenUpdate', ['id' => $d->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data Monitoring Client
                                                    Ponpes VTREN</h5>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="nama_ponpes_edit" class="form-label">Nama Ponpes <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control" id="nama_ponpes_edit_{{ $d->id }}" name="nama_ponpes" required onchange="updatenama_wilayahEdit(this.value, {{ $d->id }})">
                                                                <option value="">-- Pilih Ponpes --</option>
                                                                @foreach ($ponpesList as $ponpes)
                                                                    <option value="{{ $ponpes->nama_ponpes }}" data-nama_wilayah="{{ $ponpes->nama_wilayah }}" {{ $d->nama_ponpes == $ponpes->nama_ponpes ? 'selected' : '' }}>
                                                                        {{ $ponpes->nama_ponpes }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nama_wilayah_edit" class="form-label">Nama Wilayah</label>
                                                            <input type="text" class="form-control" id="nama_wilayah_edit_{{ $d->id }}" name="nama_wilayah" value="{{ $d->nama_wilayah }}" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jenis_kendala" class="form-label">Jenis Kendala VTREN</label>
                                                            <select class="form-control" id="jenis_kendala"
                                                                name="jenis_kendala">
                                                                <option value="">-- Pilih Jenis Kendala --</option>
                                                                @foreach ($jenisKendala as $kendala)
                                                                    <option value="{{ $kendala }}"
                                                                        {{ $d->jenis_kendala == $kendala ? 'selected' : '' }}>
                                                                        {{ $kendala }}
                                                                    </option>
                                                                @endforeach
                                                                <option value="lainnya"
                                                                    {{ $d->jenis_kendala == 'lainnya' ? 'selected' : '' }}>
                                                                    Lainnya (tulis di detail kendala)
                                                                </option>
                                                                <!-- Jika nilai existing tidak ada dalam array, tetap tampilkan -->
                                                                @if ($d->jenis_kendala && !in_array($d->jenis_kendala, $jenisKendala) && $d->jenis_kendala != 'lainnya')
                                                                    <option value="{{ $d->jenis_kendala }}" selected>
                                                                        {{ $d->jenis_kendala }} (Custom)</option>
                                                                @endif
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
                                                                value="{{ $d->tanggal_terlapor }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal_selesai" class="form-label">Tanggal
                                                                Selesai</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_selesai" name="tanggal_selesai"
                                                                value="{{ $d->tanggal_selesai }}">
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
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="pic_1" class="form-label">PIC 1</label>
                                                            <input type="text" class="form-control" id="pic_1"
                                                                name="pic_1" value="{{ $d->pic_1 }}"
                                                                placeholder="Nama PIC pertama">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="pic_2" class="form-label">PIC 2</label>
                                                            <input type="text" class="form-control" id="pic_2"
                                                                name="pic_2" value="{{ $d->pic_2 }}"
                                                                placeholder="Nama PIC kedua">
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
    // Function untuk update nama_wilayah pada Add Modal
    function updatenama_wilayah(namaPonpes) {
        if (namaPonpes === '') {
            document.getElementById('nama_wilayah').value = '';
            return;
        }
        
        const selectElement = document.getElementById('nama_ponpes');
        const selectedOption = selectElement.querySelector(`option[value="${namaPonpes}"]`);
        
        if (selectedOption) {
            const nama_wilayah = selectedOption.getAttribute('data-nama_wilayah');
            document.getElementById('nama_wilayah').value = nama_wilayah || '';
        }
    }

    // Function untuk update nama_wilayah pada Edit Modal
    function updatenama_wilayahEdit(namaPonpes, id) {
        if (namaPonpes === '') {
            document.getElementById(`nama_wilayah_edit_${id}`).value = '';
            return;
        }
        
        const selectElement = document.getElementById(`nama_ponpes_edit_${id}`);
        const selectedOption = selectElement.querySelector(`option[value="${namaPonpes}"]`);
        
        if (selectedOption) {
            const nama_wilayah = selectedOption.getAttribute('data-nama_wilayah');
            document.getElementById(`nama_wilayah_edit_${id}`).value = nama_wilayah || '';
        }
    }

    // Set nama_wilayah untuk edit modal saat modal dibuka
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial nama_wilayah values for all edit modals
        @foreach ($data as $d)
            const selectEdit{{ $d->id }} = document.getElementById('nama_ponpes_edit_{{ $d->id }}');
            if (selectEdit{{ $d->id }}) {
                const selectedOptionEdit{{ $d->id }} = selectEdit{{ $d->id }}.querySelector('option:checked');
                if (selectedOptionEdit{{ $d->id }}) {
                    const nama_wilayahEdit{{ $d->id }} = selectedOptionEdit{{ $d->id }}.getAttribute('data-nama_wilayah');
                    if (nama_wilayahEdit{{ $d->id }}) {
                        document.getElementById('nama_wilayah_edit_{{ $d->id }}').value = nama_wilayahEdit{{ $d->id }};
                    }
                }
            }
        @endforeach
    });

    // Searchable Ponpes dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const ponpesSearch = document.getElementById('ponpes_search');
        const ponpesDropdown = document.getElementById('ponpesDropdownMenu');
        const ponpesOptions = document.querySelectorAll('.ponpes-option');
        
        // Filter Ponpes options based on search input
        ponpesSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
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
            
            // Show dropdown if there are visible options and search term is not empty
            if (searchTerm.length > 0 && hasVisibleOption) {
                ponpesDropdown.style.display = 'block';
            } else if (searchTerm.length === 0) {
                ponpesDropdown.style.display = 'none';
            }
        });
        
        // Show all options when clicking on search input
        ponpesSearch.addEventListener('focus', function() {
            if (this.value.length > 0) {
                const searchTerm = this.value.toLowerCase();
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
    });

    // Toggle dropdown visibility
    function togglePonpesDropdown() {
        const ponpesDropdown = document.getElementById('ponpesDropdownMenu');
        const ponpesOptions = document.querySelectorAll('.ponpes-option');
        
        if (ponpesDropdown.style.display === 'none' || ponpesDropdown.style.display === '') {
            // Show all options
            ponpesOptions.forEach(option => {
                option.style.display = 'block';
            });
            ponpesDropdown.style.display = 'block';
        } else {
            ponpesDropdown.style.display = 'none';
        }
    }

    // Select Ponpes option
    function selectPonpes(namaPonpes, nama_wilayah) {
        document.getElementById('ponpes_search').value = namaPonpes;
        document.getElementById('nama_ponpes').value = namaPonpes;
        document.getElementById('nama_wilayah').value = nama_wilayah;
        document.getElementById('ponpesDropdownMenu').style.display = 'none';
    }

    // Clear Ponpes selection when search is cleared
    document.getElementById('ponpes_search').addEventListener('input', function() {
        if (this.value === '') {
            document.getElementById('nama_ponpes').value = '';
            document.getElementById('nama_wilayah').value = '';
        }
    });

    // Reset form when modal is closed
    $('#addModal').on('hidden.bs.modal', function () {
        document.getElementById('ponpes_search').value = '';
        document.getElementById('nama_ponpes').value = '';
        document.getElementById('nama_wilayah').value = '';
        document.getElementById('ponpesDropdownMenu').style.display = 'none';
    });
    </script>
@endsection
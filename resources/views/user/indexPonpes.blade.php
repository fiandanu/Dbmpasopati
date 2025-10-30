@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <!-- Left navbar links -->
                        <div class="d-flex justify-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i>
                            </button>
                            <h1 class="headline-large-32 mb-0">List Data Ponpes</h1>
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

                            <!-- Add Data Button -->
                            <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fa fa-plus me-1"></i> Add Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alert messages -->
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

                <div class="d-flex gap-12">
                    <div class="gap-12 w-fit">

                    </div>
                </div>

                <div class="card">
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
                                    <th class="align-top">
                                        <div class="d-flex flex-column gap-12">
                                            <span>Nama Ponpes</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-namaponpes" name="search_namaponpes">
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
                                                <input type="text" id="search-wilayah" name="search_wilayah">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Tipe</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-tipe" name="search_tipe">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center align-top">
                                        <span>Tanggal</span>
                                    </th>
                                    <th class="text-center align-top">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if (request('per_page') == 'all') {
                                        $no = 1;
                                    } else {
                                        $no = ($data->currentPage() - 1) * $data->perPage() + 1;
                                    }
                                @endphp
                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>
                                            {{ $d->nama_ponpes }}
                                            @if (str_contains($d->nama_ponpes, '(VtrenReg)'))
                                                <br><small class="text-muted"><i class="fas fa-link"></i> Data
                                                    terkait dengan Reguler & Vtren</small>
                                            @endif
                                        </td>
                                        <td><span class="tag tag-success">{{ $d->namaWilayah->nama_wilayah ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="@if ($d->tipe == 'reguler') Tipereguller @elseif($d->tipe == 'vtren') Tipevpas @endif">
                                                {{ ucfirst($d->tipe) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="text-center">
                                            <button href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $d->id }}" title="Edit">
                                                <ion-icon name="pencil-outline"></ion-icon>
                                            </button>
                                            <button data-toggle="modal" data-target="#modal-default{{ $d->id }}"
                                                title="Hapus">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($data->count() == 0)
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>Tidak ada data Ponpes yang tersedia</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Delete Modal --}}
                    @foreach ($data as $d)
                        <div class="modal fade" id="modal-default{{ $d->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body text-center align-items-center">
                                        <ion-icon name="alert-circle-outline"
                                            class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                        <p class="headline-large-32">Anda Yakin?</p>
                                        <label>Apakah Data <b>{{ $d->nama_ponpes }}</b> ingin
                                            dihapus?</label>
                                        @if (str_contains($d->nama_ponpes, '(VtrenReg)'))
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Perhatian:</strong> Data ini terkait dengan tipe
                                                lain. Jika ini adalah data terakhir yang terkait, maka
                                                suffix "(VtrenReg)" akan dihapus otomatis dari data yang
                                                tersisa.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer flex-row-reverse justify-content-between">
                                        <button type="button" class="btn-cancel-modal"
                                            data-dismiss="modal">Tutup</button>
                                        <form action="{{ route('UserPonpes.PonpesPageDestroy', $d->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- User Create Modal --}}
                    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                        aria-hidden="true">
                        <form id="addForm" action="{{ route('UserPonpes.UserPageStore') }}" method="POST">
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <label class="modal-title" id="addModalLabel">Tambah Data</label>
                                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nama_ponpes" class="form-label">Nama Ponpes</label>
                                            <input type="text" class="form-control" id="nama_ponpes"
                                                name="nama_ponpes" placeholder="Masukan Nama Ponpes" required>
                                        </div>
                                        @error('nama_ponpes')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                        <div class="mb-3">
                                            <label for="nama_wilayah_id" class="form-label">Wilayah</label>
                                            <select class="form-control" id="nama_wilayah_id" name="nama_wilayah_id"
                                                required>
                                                <option value="" selected disabled>Pilih Nama Wilayah</option>
                                                @foreach ($datanamawilayah as $wilayah)
                                                    <option value="{{ $wilayah->id }}"
                                                        {{ old('nama_wilayah_id') == $wilayah->id ? 'selected' : '' }}>
                                                        {{ $wilayah->nama_wilayah }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('nama_wilayah_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="tipe" class="form-label">Tipe</label>
                                            <div class="form-check d-flex">
                                                <input class="form-check-input" type="checkbox" name="tipe[]"
                                                    value="reguler" id="tipe_reguler">
                                                <h6 class="form-check-label" for="tipe_reguler">Reguler</h6>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tipe[]"
                                                    value="vtren" id="tipe_vtren">
                                                <h6 class="form-check-label" for="tipe_vtren">Vtren</h6>
                                            </div>
                                            <div class="d-flex justify-start gap-2 mt-3">
                                                <i class="fas fa-info-circle"></i>
                                                <h6>Dua tipe terpilih, nama Ponpes akan otomatis diberi suffix
                                                    (VtrenReg)</h6>
                                            </div>
                                        </div>
                                        @error('tipe')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                        <input type="hidden" id="addTanggal" name="tanggal">
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

                    {{-- User Edit Modal --}}
                    @foreach ($data as $d)
                        <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <form id="editForm" action="{{ route('UserPonpes.UserPageUpdate', ['id' => $d->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label id="editModalLabel">Edit Data</label>
                                            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="editId" name="id">
                                            <div class="mb-3">
                                                <label for="nama_ponpes" class="form-label">Nama Ponpes</label>
                                                <input type="text" class="form-control" id="nama_ponpes"
                                                    name="nama_ponpes" value="{{ $d->nama_ponpes }}">
                                                @if (str_contains($d->nama_ponpes, '(VtrenReg)'))
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        Data ini terkait dengan tipe lain. Harap berhati-hati saat
                                                        mengubah nama.
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama_wilayah_id" class="form-label">Wilayah</label>
                                                <select class="form-control" id="nama_wilayah_id" name="nama_wilayah_id"
                                                    required>
                                                    @foreach ($datanamawilayah as $wilayah)
                                                        <option value="{{ $wilayah->id }}"
                                                            {{ $d->nama_wilayah_id == $wilayah->id ? 'selected' : '' }}>
                                                            {{ $wilayah->nama_wilayah }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('nama_wilayah_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="tipe" class="form-label">Tipe</label>
                                                <select class="form-control" id="tipe" name="tipe" required>
                                                    <option value="reguler" {{ $d->tipe == 'reguler' ? 'selected' : '' }}>
                                                        Reguler
                                                    </option>
                                                    <option value="vtren" {{ $d->tipe == 'vtren' ? 'selected' : '' }}>
                                                        Vtren
                                                    </option>
                                                </select>
                                            </div>
                                            @error('tipe')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
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

                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                @if (request('search_namaponpes'))
                                    <input type="hidden" name="search_namaponpes"
                                        value="{{ request('search_namaponpes') }}">
                                @endif
                                @if (request('search_wilayah'))
                                    <input type="hidden" name="search_wilayah" value="{{ request('search_wilayah') }}">
                                @endif
                                @if (request('search_tipe'))
                                    <input type="hidden" name="search_tipe" value="{{ request('search_tipe') }}">
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


    {{-- JS Ajax --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipeCheckboxes = document.querySelectorAll('input[name="tipe[]"]');
            const namaPonpesInput = document.getElementById('nama_ponpes');
            let originalNamaPonpes = '';

            function cleanNamaPonpes(nama) {
                return nama.replace(/ \(VtrenReg\)/g, '').trim();
            }

            namaPonpesInput.addEventListener('input', function() {
                originalNamaPonpes = cleanNamaPonpes(this.value);
            });

            tipeCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateNamaPonpesPreview();
                });
            });

            function updateNamaPonpesPreview() {
                if (!originalNamaPonpes) return;

                const regulerChecked = document.getElementById('tipe_reguler').checked;
                const vtrenChecked = document.getElementById('tipe_vtren').checked;

                const cursorPosition = namaPonpesInput.selectionStart;

                if (regulerChecked && vtrenChecked) {
                    namaPonpesInput.value = originalNamaPonpes + ' (VtrenReg)';
                } else {
                    namaPonpesInput.value = originalNamaPonpes;
                }

                const newCursorPos = Math.min(cursorPosition, namaPonpesInput.value.length);
                namaPonpesInput.setSelectionRange(newCursorPos, newCursorPos);
            }

            document.getElementById('addModal').addEventListener('show.bs.modal', function() {
                originalNamaPonpes = '';
                namaPonpesInput.value = '';
                document.getElementById('tipe_reguler').checked = false;
                document.getElementById('tipe_vtren').checked = false;
            });
        });
    </script>

    <script>
        // Definisikan fungsi di scope global SEBELUM document ready
        window.downloadCsv = function() {
            let filters = getFiltersGlobal();
            let form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route('UserPonpes.export.list.csv') }}';
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
            let filters = getFiltersGlobal();
            let form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route('UserPonpes.export.list.pdf') }}';
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

        // Fungsi helper global untuk mengambil filter
        function getFiltersGlobal() {
            return {
                search_namaponpes: $('#search-namaponpes').val() ? $('#search-namaponpes').val().trim() : '',
                search_wilayah: $('#search-wilayah').val() ? $('#search-wilayah').val().trim() : '',
                search_tipe: $('#search-tipe').val() ? $('#search-tipe').val().trim() : '',
                search_tanggal_dari: $('#search-tanggal-dari').val() ? $('#search-tanggal-dari').val().trim() : '',
                search_tanggal_sampai: $('#search-tanggal-sampai').val() ? $('#search-tanggal-sampai').val().trim() : '',
                per_page: $('select[name="per_page"]').val()
            };
        }

        $(document).ready(function() {
            function getFilters() {
                return getFiltersGlobal();
            }

            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                url.searchParams.delete('search_namaponpes');
                url.searchParams.delete('search_wilayah');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('page');

                Object.keys(filters).forEach(key => {
                    if (filters[key] && filters[key].trim() !== '' && key !== 'per_page') {
                        url.searchParams.set(key, filters[key]);
                    }
                });

                window.location.href = url.toString();
            };

            window.clearAllFilters = function() {
                $('#search-namaponpes').val('');
                $('#search-wilayah').val('');
                $('#search-tipe').val('');
                $('#search-tanggal-dari').val('');
                $('#search-tanggal-sampai').val('');

                let url = new URL(window.location.href);

                url.searchParams.delete('search_namaponpes');
                url.searchParams.delete('search_wilayah');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('page');

                window.location.href = url.toString();
            };

            $('.column-search input').on('keypress', function(e) {
                if (e.which === 13) {
                    applyFilters();
                }
            });

            $('.column-search input').on('keyup', function(e) {
                if (e.which === 13 && $(this).val().trim() === '') {
                    applyFilters();
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('search_namaponpes')) {
                $('#search-namaponpes').val(urlParams.get('search_namaponpes'));
            }
            if (urlParams.get('search_wilayah')) {
                $('#search-wilayah').val(urlParams.get('search_wilayah'));
            }
            if (urlParams.get('search_tipe')) {
                $('#search-tipe').val(urlParams.get('search_tipe'));
            }
            if (urlParams.get('search_tanggal_dari')) {
                $('#search-tanggal-dari').val(urlParams.get('search_tanggal_dari'));
            }
            if (urlParams.get('search_tanggal_sampai')) {
                $('#search-tanggal-sampai').val(urlParams.get('search_tanggal_sampai'));
            }

            // Tampilkan tombol export - PERBAIKAN: hapus logika hide
            $("#export-buttons").show();
        });
    </script>

@endsection

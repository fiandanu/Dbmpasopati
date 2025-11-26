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
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data UPT</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">

                            <div class="d-flex justify-content-center align-items-center gap-12">
                                <div class="btn-page">
                                    <input type="date" id="search-tanggal-dari" name="search_tanggal_dari"
                                        title="Tanggal Dari">
                                </div>
                                <div class="btn-page">
                                    <input type="date" id="search-tanggal-sampai" name="search_tanggal_sampai"
                                        title="Tanggal Sampai">
                                </div>
                            </div>

                            {{-- EXPORT BUTTON --}}
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


                <div class="card mt-3">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="Table">
                            <thead>
                                <tr>
                                    <th class=" text-center align-top">
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
                                            <span>Nama UPT</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-namaupt" name="search_namaupt">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="align-top">
                                        <div class="d-flex flex-column gap-12">
                                            <span>Nama Kanwil</span>
                                            <div class="btn-searchbar column-search">
                                                <span>
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" id="search-kanwil" name="search_kanwil">
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
                                            {{ $d->namaupt }}
                                            @if (str_contains($d->namaupt, '(VpasReg)'))
                                                <br><small class="text-muted"><i class="fas fa-link"></i> Data
                                                    terkait dengan Reguler & VPAS</small>
                                            @endif
                                        </td>
                                        <td><span class="tag tag-success">{{ $d->kanwil->kanwil ?? '-' }}</span></td>
                                        <td class="text-center">
                                            @php
                                                $layananClass = match (strtolower($d->jenis_layanan ?? '')) {
                                                    'vpas' => 'Tipevpas',
                                                    'reguler' => 'Tipereguller',
                                                    'vpasreg' => 'badge-prosses',
                                                    default => '',
                                                };
                                                $layananText =
                                                    $jenisLayananOptions[$d->jenis_layanan] ??
                                                    ucfirst($d->jenis_layanan ?? '-');
                                            @endphp
                                            <span class="{{ $layananClass }}">
                                                {{ $layananText }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="text-center">
                                            {{-- Edit Button --}}
                                            <button href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $d->id }}" title="Edit">
                                                <ion-icon name="pencil-outline"></ion-icon>
                                            </button>

                                            {{-- Delete Button --}}
                                            @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                <button data-toggle="modal"
                                                    data-target="#modal-default{{ $d->id }}" title="Hapus">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($data->count() == 0)
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>Tidak ada data UPT yang tersedia</p>
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
                                        <label>Apakah Data <b> {{ $d->namaupt }} </b> ingin
                                            dihapus?</label>

                                        @if (str_contains($d->namaupt, '(VpasReg)'))
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Perhatian:</strong> Data ini terkait dengan tipe
                                                lain. Jika ini adalah data terakhir yang terkait, maka
                                                suffix "(VpasReg)" akan dihapus otomatis dari data yang
                                                tersisa.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer flex-row-reverse justify-content-between">
                                        <button type="button" class="btn-cancel-modal"
                                            data-dismiss="modal">Tutup</button>
                                        <form action="{{ route('User.UserPageDestroy', $d->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </div>
                    @endforeach

                    {{-- User Create Modal --}}
                    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                        aria-hidden="true">
                        <form id="addForm" action="{{ route('User.UserPageStore') }}" method="POST">
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
                                        {{-- Input Nama UPT --}}
                                        <div class="mb-3">
                                            <label for="namaupt" class="form-label">Nama UPT</label>
                                            <input type="text" class="form-control" id="namaupt" name="namaupt"
                                                placeholder="Masukan Nama Upt" required>
                                        </div>
                                        @error('namaupt')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        {{-- Input Nama UPT --}}

                                        {{-- Input Nama Kanwil --}}
                                        <div class="mb-3">
                                            <label for="kanwil_id" class="form-label">Kanwil</label>
                                            <select class="form-control" id="kanwil_id" name="kanwil_id" required>
                                                <option value="" selected disabled>Pilih Kanwil</option>
                                                @foreach ($datakanwil as $k)
                                                    <option value="{{ $k->id }}"
                                                        {{ old('kanwil_id') == $k->id ? 'selected' : '' }}>
                                                        {{ $k->kanwil }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kanwil_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        {{-- Input Nama Kanwil --}}

                                        {{-- Input Tipe Multiple Selection --}}
                                        <div>
                                            <label for="tipe" class="form-label">Tipe</label>
                                            <div class="form-check d-flex">
                                                <input class="form-check-input" type="checkbox" name="tipe[]"
                                                    value="reguler" id="tipe_reguler">
                                                <h6 class="form-check-label" for="tipe_reguler">
                                                    Reguler
                                                </h6>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tipe[]"
                                                    value="vpas" id="tipe_vpas">
                                                <h6 class="form-check-label" for="tipe_vpas">
                                                    VPAS
                                                </h6>
                                            </div>
                                            <div class="d-flex justify-start gap-2 mt-3">
                                                <i class="fas fa-info-circle"></i>
                                                <h6>Dua tipe terpilih, nama Ponpes akan otomatis diberi suffix
                                                    (VpasReg)</h6>
                                            </div>
                                        </div>
                                        @error('tipe')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        {{-- Input Tipe Multiple Selection --}}

                                        {{-- Input Tanggal Hidden --}}
                                        <input type="hidden" id="addTanggal" name="tanggal">
                                        {{-- Input Tanggal Hidden --}}
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
                            <form id="editForm" action="{{ route('User.UserPageUpdate', ['id' => $d->id]) }}"
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
                                                <label for="namaupt" class="form-label">Nama UPT</label>
                                                <input type="text" class="form-control" id="namaupt" name="namaupt"
                                                    value="{{ $d->namaupt }}">
                                                @if (str_contains($d->namaupt, '(VpasReg)'))
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        Data ini terkait dengan tipe lain. Harap berhati-hati saat
                                                        mengubah nama.
                                                    </small>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label for="kanwil_id" class="form-label">Kanwil</label>
                                                <select class="form-control" id="kanwil_id" name="kanwil_id" required>
                                                    @foreach ($datakanwil as $k)
                                                        <option value="{{ $k->id }}"
                                                            {{ $d->kanwil_id == $k->id ? 'selected' : '' }}>
                                                            {{ $k->kanwil }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tipe" class="form-label">Tipe</label>
                                                <select class="form-control" id="tipe" name="tipe" required>
                                                    <option value="reguler" {{ $d->tipe == 'reguler' ? 'selected' : '' }}>
                                                        Reguler
                                                    </option>
                                                    <option value="vpas" {{ $d->tipe == 'vpas' ? 'selected' : '' }}>VPAS
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
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                <!-- Preserve all search parameters -->
                                @if (request('search_namaupt'))
                                    <input type="hidden" name="search_namaupt" value="{{ request('search_namaupt') }}">
                                @endif
                                @if (request('search_kanwil'))
                                    <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
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
                                @if (request('search_status'))
                                    <input type="hidden" name="search_status" value="{{ request('search_status') }}">
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

    {{-- JavaScript untuk menangani preview nama UPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipeCheckboxes = document.querySelectorAll('input[name="tipe[]"]');
            const namaUptInput = document.getElementById('namaupt');
            let originalNamaUpt = '';

            // Function untuk membersihkan suffix
            function cleanNamaUpt(nama) {
                return nama.replace(/ \(VpasReg\)/g, '').trim();
            }

            // Update originalNamaUpt saat user mengetik
            namaUptInput.addEventListener('input', function() {
                originalNamaUpt = cleanNamaUpt(this.value);
            });

            // Update preview saat checkbox diubah
            tipeCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateNamaUptPreview();
                });
            });

            function updateNamaUptPreview() {
                if (!originalNamaUpt) return;

                const regulerChecked = document.getElementById('tipe_reguler').checked;
                const vpasChecked = document.getElementById('tipe_vpas').checked;

                // Simpan posisi cursor
                const cursorPosition = namaUptInput.selectionStart;

                if (regulerChecked && vpasChecked) {
                    namaUptInput.value = originalNamaUpt + ' (VpasReg)';
                } else {
                    namaUptInput.value = originalNamaUpt;
                }

                // Restore posisi cursor
                const newCursorPos = Math.min(cursorPosition, namaUptInput.value.length);
                namaUptInput.setSelectionRange(newCursorPos, newCursorPos);
            }

            // Reset saat modal dibuka
            document.getElementById('addModal').addEventListener('show.bs.modal', function() {
                originalNamaUpt = '';
                namaUptInput.value = '';
                document.getElementById('tipe_reguler').checked = false;
                document.getElementById('tipe_vpas').checked = false;
            });
        });
    </script>

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Search By Column JavaScript -->
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_namaupt: $('#search-namaupt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_tipe: $('#search-tipe').val().trim(),
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
                url.searchParams.delete('search_namaupt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
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
                $('#search-namaupt').val('');
                $('#search-kanwil').val('');
                $('#search-tipe').val('');
                $('#search-tanggal-sampai').val('');

                let url = new URL(window.location.href);

                // Remove all search parameters
                url.searchParams.delete('search_namaupt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
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
                form.action = '{{ route('User.export.list.csv') }}';
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
                form.action = '{{ route('User.export.list.pdf') }}';
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
            if (urlParams.get('search_namaupt')) {
                $('#search-namaupt').val(urlParams.get('search_namaupt'));
            }
            if (urlParams.get('search_kanwil')) {
                $('#search-kanwil').val(urlParams.get('search_kanwil'));
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

            // Show export buttons if there's data
            if ($("#Table tbody tr").length > 0 && !$("#Table tbody tr").find('td[colspan="6"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }
        });
    </script>

@endsection

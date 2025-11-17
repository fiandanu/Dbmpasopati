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
                            <h1 class="headline-large-32 mb-0">Tutorial Mikrotik</h1>
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

                        <div class="d-flex gap-12">
                            <div class="gap-12 w-fit">
                                <div class="d-flex justify-content-center align-items-center gap-12">
                                    <div class="btn-searchbar column-search">
                                        <input type="date" id="search-tanggal-dari" name="search_tanggal_dari"
                                            title="Tanggal Dari">
                                    </div>
                                    <div class="btn-searchbar column-search">
                                        <input type="date" id="search-tanggal-sampai" name="search_tanggal_sampai"
                                            title="Tanggal Sampai">
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
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <button type="button" class="btn-purple w-auto"
                                                            onclick="applyFilters()" title="Cari Semua Filter">
                                                            <i class="fas fa-search"></i> Cari
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="d-flex flex-column gap-12">
                                                    <span>Tutorial Mikrotik</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-judul" name="search_judul">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">
                                                <span>Tanggal</span>
                                            </th>
                                            <th class="text-center">
                                                <div
                                                    class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                    <span>Status Upload PDF</span>
                                                    <div class="btn-searchbar column-search">
                                                        <span>
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                        <input type="text" id="search-status" name="search_status">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center align-top">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                <td>{{ $d->tutor_mikrotik }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('M d Y') }}
                                                </td>
                                                <td class="text-center-status">
                                                    @php
                                                        $uploadedFolders = 0;
                                                        $totalFolders = 10;

                                                        // Check PDF folders directly from the model
                                                        for ($i = 1; $i <= 10; $i++) {
                                                            $column = 'pdf_folder_' . $i;
                                                            if (!empty($d->$column)) {
                                                                $uploadedFolders++;
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($uploadedFolders == 0)
                                                        <span class="badge body-small-12">Belum Upload</span>
                                                    @elseif($uploadedFolders == $totalFolders)
                                                        <span class="badge-succes">
                                                            10/10 Folder
                                                        </span>
                                                    @else
                                                        <span class="badge-prosses">
                                                            {{ $uploadedFolders }}/{{ $totalFolders }} Terupload
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button data-toggle="modal"
                                                            data-target="#uploadModal{{ $d->id }}"
                                                            title="Upload PDF">
                                                            <ion-icon name="folder-outline"></ion-icon>
                                                        </button>

                                                        @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                            {{-- DELETE BUTTON --}}
                                                            <button data-toggle="modal"
                                                                data-target="#modal-default{{ $d->id }}"
                                                                title="Hapus Data">
                                                                <ion-icon name="trash-outline"></ion-icon>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Upload Modal -->
                                            <div class="modal fade" id="uploadModal{{ $d->id }}" tabindex="-1"
                                                aria-labelledby="uploadModalLabel{{ $d->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('mikrotik_page.uploadFilePDF', [$d->id, 1]) }}"
                                                            method="POST" enctype="multipart/form-data"
                                                            id="uploadForm{{ $d->id }}">
                                                            @csrf
                                                            <input type="hidden" name="selected_folder"
                                                                id="selectedFolder{{ $d->id }}" value="1">
                                                            <div class="modal-header">
                                                                <label id="uploadModalLabel{{ $d->id }}">Manage PDF
                                                                </label>
                                                                <button type="button" class="btn-close-custom"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="folderSelect{{ $d->id }}">Pilih
                                                                        Folder</label>
                                                                    <select class="form-control"
                                                                        id="folderSelect{{ $d->id }}"
                                                                        name="folder"
                                                                        onchange="updateFolder({{ $d->id }}, this.value)">
                                                                        @for ($i = 1; $i <= 10; $i++)
                                                                            @php
                                                                                $column = 'pdf_folder_' . $i;
                                                                                $fileName = !empty($d->$column)
                                                                                    ? basename($d->$column)
                                                                                    : null;
                                                                            @endphp
                                                                            <option value="{{ $i }}">
                                                                                @if ($fileName)
                                                                                    Folder {{ $i }}:
                                                                                    {{ $fileName }}
                                                                                @else
                                                                                    Folder {{ $i }}: Tidak ada
                                                                                    file
                                                                                @endif
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                <!-- PDF Status and Actions for Selected Folder -->
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="label-medium-14">
                                                                            Status dan Aksi
                                                                            <span
                                                                                id="currentFolder{{ $d->id }}">1</span>
                                                                            <span id="currentFileName{{ $d->id }}"
                                                                                class="text-muted">
                                                                                @php
                                                                                    $firstFileName = null;
                                                                                    $column = 'pdf_folder_1';
                                                                                    if (!empty($d->$column)) {
                                                                                        $firstFileName = basename(
                                                                                            $d->$column,
                                                                                        );
                                                                                    }
                                                                                @endphp
                                                                                @if ($firstFileName)
                                                                                    - {{ $firstFileName }}
                                                                                @else
                                                                                    - Tidak ada file
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div id="pdfActions{{ $d->id }}">
                                                                            @for ($i = 1; $i <= 10; $i++)
                                                                                <div class="pdf-folder-actions"
                                                                                    id="folderActions{{ $d->id }}_{{ $i }}"
                                                                                    style="display: {{ $i == 1 ? 'block' : 'none' }};">
                                                                                    @php
                                                                                        $column = 'pdf_folder_' . $i;
                                                                                        $hasFile = !empty($d->$column);
                                                                                    @endphp

                                                                                    @if ($hasFile)
                                                                                        <div
                                                                                            class="badge-succes mb-3 text-center">
                                                                                            <i
                                                                                                class="fas fa-check-circle"></i>
                                                                                            PDF sudah tersedia untuk Folder
                                                                                            {{ $i }}
                                                                                        </div>
                                                                                        <div class="btn-group mb-3 gap-3"
                                                                                            role="group">
                                                                                            <a href="{{ route('mikrotik_page.viewpdf', [$d->id, $i]) }}"
                                                                                                target="_blank"
                                                                                                class="view-btn-pdf"
                                                                                                title="Lihat PDF Folder {{ $i }}">
                                                                                                <i class="fas fa-eye"></i>
                                                                                                View PDF
                                                                                            </a>
                                                                                            <button type="button"
                                                                                                class="delete-btn-pdf"
                                                                                                data-toggle="modal"
                                                                                                data-target="#deletePdfModal{{ $d->id }}_{{ $i }}"
                                                                                                title="Hapus File PDF Folder {{ $i }}">
                                                                                                <i
                                                                                                    class="fas fa-trash"></i>
                                                                                                Delete
                                                                                            </button>
                                                                                        </div>
                                                                                    @else
                                                                                        <div
                                                                                            class="badge-prosses text-center">
                                                                                            <i
                                                                                                class="fas fa-exclamation-triangle"></i>
                                                                                            Belum Upload Folder
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <label for="uploaded_pdf{{ $d->id }}"
                                                                        class="btn-upload">
                                                                        Upload PDF
                                                                    </label>
                                                                    <input type="file"
                                                                        id="uploaded_pdf{{ $d->id }}"
                                                                        name="uploaded_pdf" accept=".pdf"
                                                                        style="display: none;">
                                                                    <span id="fileNameDisplay{{ $d->id }}"
                                                                        class="text-muted"></span>
                                                                    <small class="form-text text-muted">Max Upload
                                                                        10Mb</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn-cancel-modal"
                                                                    data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn-purple">Upload
                                                                    PDF</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Delete PDF Modals for Each Folder --}}
                                            @for ($i = 1; $i <= 10; $i++)
                                                <div class="modal fade"
                                                    id="deletePdfModal{{ $d->id }}_{{ $i }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="deletePdfModalLabel{{ $d->id }}_{{ $i }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body text-center align-items-center">
                                                                <ion-icon name="alert-circle-outline"
                                                                    class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                                <p class="headline-large-32">Anda Yakin?</p>
                                                                <label>Apakah Folder <b> {{ $i }} </b> ingin
                                                                    dihapus?</label>
                                                            </div>
                                                            <div
                                                                class="modal-footer flex-row-reverse justify-content-between">
                                                                <button type="button" class="btn-cancel-modal"
                                                                    data-dismiss="modal">Batal</button>
                                                                <form
                                                                    action="{{ route('mikrotik_page.deleteFilePDF', [$d->id, $i]) }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-delete">
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor

                                            {{-- Delete Data Modal --}}
                                            <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah Data <b> {{ $d->tutor_mikrotik }} </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('mikrotik_page.DataBasePageDestroy', $d->id) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <div class="py-4">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">Tidak ada data yang ditemukan</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                    {{-- User Create Modal --}}
                                    <div class="modal fade" id="addModal" tabindex="-1"
                                        aria-labelledby="addModalLabel" aria-hidden="true">
                                        <form id="addForm" action="{{ route('mikrotik_page.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <label id="addModalLabel">Tambah Data</label>
                                                        <button type="button" class="btn-close-custom"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        {{-- Input Judul --}}
                                                        <div class="mb-3">
                                                            <label for="tutor_mikrotik">Judul
                                                                Tutorial</label>
                                                            <input type="text" class="form-control"
                                                                id="tutor_mikrotik" name="tutor_mikrotik" required
                                                                placeholder="Masukan Judul">
                                                        </div>
                                                        @error('tutor_mikrotik')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                        {{-- Input Judul --}}

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
                                    {{-- User Create Modal --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <!-- Pagination Controls -->
                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                <!-- Preserve all search parameters -->
                                @if (request('search_judul'))
                                    <input type="hidden" name="search_judul" value="{{ request('search_judul') }}">
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

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Search By Column JavaScript --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_judul: $('#search-judul').val().trim(),
                    search_tanggal_dari: $('#search-tanggal-dari').val().trim(),
                    search_tanggal_sampai: $('#search-tanggal-sampai').val().trim(),
                    search_status: $('#search-status').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect (GLOBAL)
            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove existing filter parameters
                url.searchParams.delete('search_judul');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('search_status');
                url.searchParams.delete('page');

                // Add non-empty filters
                Object.keys(filters).forEach(key => {
                    if (filters[key] && filters[key].trim() !== '' && key !== 'per_page') {
                        url.searchParams.set(key, filters[key]);
                    }
                });

                window.location.href = url.toString();
            };

            // Bind keypress event to all search input fields
            $('.column-search input').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    applyFilters();
                }
            });

            // Clear individual column search when input is emptied
            $('.column-search input').on('keyup', function(e) {
                if (e.which === 13 && $(this).val().trim() === '') {
                    applyFilters();
                }
            });

            // Download functions with current filters
            window.downloadCsv = function() {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('mikrotik_page.export.mikrotik.list.csv') }}';
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
                form.action = '{{ route('mikrotik_page.export.mikrotik.list.pdf') }}';
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
            if (urlParams.get('search_judul')) {
                $('#search-judul').val(urlParams.get('search_judul'));
            }
            if (urlParams.get('search_tanggal_dari')) {
                $('#search-tanggal-dari').val(urlParams.get('search_tanggal_dari'));
            }
            if (urlParams.get('search_tanggal_sampai')) {
                $('#search-tanggal-sampai').val(urlParams.get('search_tanggal_sampai'));
            }
            if (urlParams.get('search_status')) {
                $('#search-status').val(urlParams.get('search_status'));
            }

            // Show export buttons if there's data
            if ($("#Table tbody tr").length > 0 && !$("#Table tbody tr").find('td[colspan="5"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }
        });
    </script>

    {{-- Script yang sudah ada sebelumnya (updateFolder, dll) tetap dipertahankan --}}
@endsection

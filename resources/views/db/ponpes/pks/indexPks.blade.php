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
                            <h1 class="headline-large-32 mb-0">List Data PKS Ponpes</h1>
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
                                <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fa fa-plus"></i> Add Data
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex justify-start align-items-center gap-12 mb-2">
                    <div class="btn-searchbar column-search">
                        <input type="date" id="search-tanggal-dari" name="search_tanggal_dari" title="Tanggal Dari">
                    </div>
                    <div class="btn-searchbar column-search">
                        <input type="date" id="search-tanggal-sampai" name="search_tanggal_sampai"
                            title="Tanggal Sampai">
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
                                        <span>Tanggal Dibuat</span>
                                    </th>
                                    <th class="text-center align-top">
                                        <span>Tanggal Kontrak</span>
                                    </th>
                                    <th class="text-center align-top">
                                        <span>Tanggal Jatuh Tempo</span>
                                    </th>
                                    <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
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
                                        <td>{{ $d->nama_ponpes }}</td>
                                        <td><span
                                                class="tag tag-success">{{ $d->namaWilayah?->nama_wilayah ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('M d Y') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $d->uploadFolderPks && $d->uploadFolderPks->tanggal_kontrak ? \Carbon\Carbon::parse($d->uploadFolderPks->tanggal_kontrak)->translatedFormat('M d Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $d->uploadFolderPks && $d->uploadFolderPks->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($d->uploadFolderPks->tanggal_jatuh_tempo)->translatedFormat('M d Y') : '-' }}
                                        </td>
                                        {{-- bagian penambahan folder --}}
                                        <td class="text-center-status">
                                            @php
                                                $hasPdf1 =
                                                    $d->uploadFolderPks && !empty($d->uploadFolderPks->uploaded_pdf_1);
                                                $hasPdf2 =
                                                    $d->uploadFolderPks && !empty($d->uploadFolderPks->uploaded_pdf_2);
                                            @endphp

                                            @if (!$hasPdf1 && !$hasPdf2)
                                                <span class="badge body-small-12">Belum Upload</span>
                                            @elseif ($hasPdf1 && $hasPdf2)
                                                <span class="badge-succes">Sudah Upload (2/2)</span>
                                            @else
                                                <span class="badge-prosses">Sudah Upload (1/2)</span>
                                            @endif
                                        </td>
                                        {{-- bagian penambahan folder --}}
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Edit Button -->
                                                <a href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $d->id }}">
                                                    <button title="Edit Data">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>
                                                </a>

                                                <!-- Upload PDF Button -->
                                                <button data-toggle="modal" data-target="#uploadModal{{ $d->id }}"
                                                    title="Upload PDF">
                                                    <ion-icon name="folder-outline"></ion-icon>
                                                </button>

                                                <button data-toggle="modal"
                                                    data-target="#modal-default{{ $d->id }}">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>

                                            </div>
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
                                                    <!-- UBAH TEKS INI -->
                                                    <label>Apakah Data PKS <b>{{ $d->nama_ponpes }}</b> ingin
                                                        dihapus?</label>
                                                    <small class="text-muted d-block mt-2">
                                                        <i class="fas fa-info-circle"></i>
                                                        Hanya data PKS yang akan dihapus, data Ponpes tetap tersimpan.
                                                    </small>
                                                </div>
                                                <div class="modal-footer flex-row-reverse justify-content-between">
                                                    <button type="button" class="btn-cancel-modal"
                                                        data-dismiss="modal">Tutup</button>
                                                    <form action="{{ route('DbPonpes.pks.DataBasePageDestroy', $d->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="selected_folder"
                                                            id="selectedFolder{{ $d->id }}" value="1">
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-delete">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Upload Modal -->
                                    <div class="modal fade" id="uploadModal{{ $d->id }}" tabindex="-1"
                                        aria-labelledby="uploadModalLabel{{ $d->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form
                                                    action="{{ route('DbPonpes.pks.uploadFilePDFPonpesPks', ['id' => $d->id, 'folderNumber' => 1]) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    id="uploadForm{{ $d->id }}">
                                                    @csrf

                                                    <div class="modal-header">
                                                        <label id="uploadModalLabel{{ $d->id }}">Upload PDF PKS
                                                            Ponpes
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
                                                                id="folderSelect{{ $d->id }}" name="folder"
                                                                onchange="updateFolder({{ $d->id }}, this.value)">
                                                                @for ($i = 1; $i <= 2; $i++)
                                                                    @php
                                                                        $originalFileName = null;
                                                                        if ($d->uploadFolderPks) {
                                                                            $column = 'uploaded_pdf_' . $i;
                                                                            $originalFileName = null;
                                                                            if (!empty($d->uploadFolderPks->$column)) {
                                                                                $fullName = basename(
                                                                                    $d->uploadFolderPks->$column,
                                                                                );
                                                                                $parts = explode('_', $fullName, 3);
                                                                                $originalFileName =
                                                                                    $parts[2] ?? $fullName;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <option value="{{ $i }}">
                                                                        @if ($originalFileName)
                                                                            Folder {{ $i }}:
                                                                            {{ $originalFileName }}
                                                                        @else
                                                                            Folder {{ $i }}: Tidak ada file
                                                                        @endif
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>

                                                        <!-- PDF Status and Actions for Selected Folder -->
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <div class="label-medium-14">
                                                                    Status dan Aksi <span
                                                                        id="currentFolder{{ $d->id }}">1</span>
                                                                    <span id="currentFileName{{ $d->id }}"
                                                                        class="text-muted">
                                                                        @php
                                                                            $firstFileName = null;
                                                                            if (
                                                                                $d->uploadFolderPks &&
                                                                                !empty(
                                                                                    $d->uploadFolderPks->uploaded_pdf_1
                                                                                )
                                                                            ) {
                                                                                $fullName = basename(
                                                                                    $d->uploadFolderPks->uploaded_pdf_1,
                                                                                );
                                                                                $parts = explode('_', $fullName, 3);
                                                                                $fisrtFileName = $parts[2] ?? $fullName;
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
                                                                    @for ($i = 1; $i <= 2; $i++)
                                                                        <div class="pdf-folder-actions"
                                                                            id="folderActions{{ $d->id }}_{{ $i }}"
                                                                            style="display: {{ $i == 1 ? 'block' : 'none' }};">
                                                                            @php
                                                                                $hasFile = false;
                                                                                if ($d->uploadFolderPks) {
                                                                                    $column = 'uploaded_pdf_' . $i;
                                                                                    $hasFile = !empty(
                                                                                        $d->uploadFolderPks->$column
                                                                                    );
                                                                                }
                                                                            @endphp

                                                                            @if ($hasFile)
                                                                                <div class="badge-succes mb-3 text-center">
                                                                                    <i class="fas fa-check-circle"></i>
                                                                                    PDF sudah tersedia untuk Folder
                                                                                    {{ $i }}
                                                                                </div>
                                                                                <div class="btn-group mb-3 gap-3"
                                                                                    role="group">
                                                                                    <a href="{{ route('DbPonpes.pks.viewpdf.ponpes', ['id' => $d->id, 'folderNumber' => $i]) }}"
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
                                                                                        <i class="fas fa-trash"></i>
                                                                                        Delete
                                                                                    </button>
                                                                                </div>
                                                                            @else
                                                                                <div class="badge-prosses text-center">
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
                                                            <input type="file" id="uploaded_pdf{{ $d->id }}"
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

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                        aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                                        <form action="{{ route('DbPonpes.pks.update', $d->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <label id="editModalLabel{{ $d->id }}">Edit Data
                                                            PKS</label>
                                                        <button type="button" class="btn-close-custom"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama PONPES</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $d->nama_ponpes }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Wilayah</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $d->namaWilayah->nama_wilayah }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tipe</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ ucfirst($d->tipe) }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_kontrak_edit_{{ $d->id }}"
                                                                class="form-label">Tanggal Kontrak</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_kontrak_edit_{{ $d->id }}"
                                                                name="tanggal_kontrak"
                                                                value="{{ $d->uploadFolderPks && $d->uploadFolderPks->tanggal_kontrak ? $d->uploadFolderPks->tanggal_kontrak->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_jatuh_tempo_edit_{{ $d->id }}"
                                                                class="form-label">Tanggal Jatuh Tempo</label>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_jatuh_tempo_edit_{{ $d->id }}"
                                                                name="tanggal_jatuh_tempo"
                                                                value="{{ $d->uploadFolderPks && $d->uploadFolderPks->tanggal_jatuh_tempo ? $d->uploadFolderPks->tanggal_jatuh_tempo->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn-cancel-modal"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn-purple">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- Delete PDF Modals for Each Folder --}}
                                    @for ($i = 1; $i <= 2; $i++)
                                        <div class="modal fade"
                                            id="deletePdfModal{{ $d->id }}_{{ $i }}" tabindex="-1"
                                            role="dialog"
                                            aria-labelledby="deletePdfModalLabel{{ $d->id }}_{{ $i }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center align-items-center">
                                                        <ion-icon name="alert-circle-outline"
                                                            class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                        <p class="headline-large-32">Anda Yakin?</p>
                                                        <label>Apakah PDF Folder {{ $i }} dari
                                                            <b>{{ $d->nama_ponpes }}</b> ingin
                                                            dihapus?</label>
                                                    </div>
                                                    <div class="modal-footer flex-row-reverse justify-content-between">
                                                        <button type="button" class="btn-cancel-modal"
                                                            data-dismiss="modal">Batal</button>
                                                        <form
                                                            action="{{ route('DbPonpes.pks.deleteFilePDF.ponpes', ['id' => $d->id, 'folderNumber' => $i]) }}"
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Tidak ada data yang ditemukan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Custom Pagination dengan Dropdown -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Left: Data info + Dropdown per page -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                <!-- Preserve all search parameters -->
                                @if (request('search_namaponpes'))
                                    <input type="hidden" name="search_namaponpes"
                                        value="{{ request('search_namaponpes') }}">
                                @endif
                                @if (request('search_wilayah'))
                                    <input type="hidden" name="search_wilayah" value="{{ request('search_wilayah') }}">
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

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <form id="addForm" action="{{ route('DbPonpes.pks.store') }}" method="POST">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <label class="modal-title" id="addModalLabel">Tambah Data PKS</label>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-4">
                            <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                <label>Informasi PONPES</label>
                            </div>
                            <div class="column">
                                <div class="mb-3">
                                    <label for="nama_ponpes" class="form-label">Nama PONPES
                                    </label>
                                    <div class="dropdown">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="ponpes_search"
                                                placeholder="Cari PONPES..." autocomplete="off">
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
                                                    data-nama-wilayah="{{ $ponpes->namaWilayah ? $ponpes->namaWilayah->nama_wilayah : '' }}"
                                                    onclick="selectPonpes('{{ $ponpes->nama_ponpes }}', '{{ $ponpes->namaWilayah ? $ponpes->namaWilayah->nama_wilayah : '' }}')">
                                                    {{ $ponpes->nama_ponpes }} -
                                                    {{ $ponpes->namaWilayah ? $ponpes->namaWilayah->nama_wilayah : 'Wilayah tidak ditemukan' }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" id="nama_ponpes" name="nama_ponpes" required>
                                    <small class="form-text text-muted">Ketik untuk mencari
                                        PONPES</small>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_wilayah_id" class="form-label">Nama Wilayah</label>
                                    <input type="text" class="form-control" id="nama_wilayah" name="nama_wilayah"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Section -->
                        <div class="mb-4">
                            <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                <label>Tanggal</label>
                            </div>
                            <div class="column">
                                <div class="mb-3">
                                    <label for="tanggal_kontrak" class="form-label">Tanggal Kontrak</label>
                                    <input type="date" class="form-control" id="tanggal_kontrak"
                                        name="tanggal_kontrak">
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control" id="tanggal_jatuh_tempo"
                                        name="tanggal_jatuh_tempo">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-purple">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.content-wrapper -->

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
                    search_namaponpes: $('#search-namaponpes').val().trim(),
                    search_wilayah: $('#search-wilayah').val().trim(),
                    search_tanggal_dari: $('#search-tanggal-dari').val().trim(),
                    search_tanggal_sampai: $('#search-tanggal-sampai').val().trim(),
                    search_status: $('#search-status').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect (GLOBAL - bisa dipanggil dari tombol)
            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove existing filter parameters
                url.searchParams.delete('search_namaponpes');
                url.searchParams.delete('search_wilayah');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('search_status');
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
                $('#search-namaponpes').val('');
                $('#search-wilayah').val('');
                $('#search-tanggal-dari').val('');
                $('#search-tanggal-sampai').val('');
                $('#search-status').val('');

                let url = new URL(window.location.href);

                // Remove all search parameters
                url.searchParams.delete('search_namaponpes');
                url.searchParams.delete('search_wilayah');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('search_status');
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
                form.action = '{{ route('DbPonpes.pks.export.list.csv') }}'; // Sesuaikan dengan route ponpes
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
                form.action = '{{ route('DbPonpes.pks.export.list.pdf') }}'; // Sesuaikan dengan route ponpes
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
            if (urlParams.get('search_namaponpes')) {
                $('#search-namaponpes').val(urlParams.get('search_namaponpes'));
            }
            if (urlParams.get('search_wilayah')) {
                $('#search-wilayah').val(urlParams.get('search_wilayah'));
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
            if ($("#Table tbody tr").length > 0 && !$("#Table tbody tr").find('td[colspan="6"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }
        });
    </script>

    {{-- Update Folder --}}
    <script>
        function updateFolder(id, folder) {
            const form = document.getElementById('uploadForm' + id);
            const currentFolderSpan = document.getElementById('currentFolder' + id);

            // Update tampilan folder number
            currentFolderSpan.textContent = folder;

            // Update action URL with the selected folder - PERBAIKAN PENTING!
            form.action =
                "{{ route('DbPonpes.pks.uploadFilePDFPonpesPks', ['id' => ':id', 'folderNumber' => ':folder']) }}"
                .replace(':id', id)
                .replace(':folder', folder);

            // Hide all folder actions
            const allFolderActions = document.querySelectorAll(`[id^="folderActions${id}_"]`);
            allFolderActions.forEach(function(element) {
                element.style.display = 'none';
            });

            // Show current folder actions
            const currentFolderActions = document.getElementById(`folderActions${id}_${folder}`);
            if (currentFolderActions) {
                currentFolderActions.style.display = 'block';
            }

            // Update current file name display
            const selectElement = document.getElementById('folderSelect' + id);
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const fileName = selectedOption.text.split(': ')[1] || 'Tidak ada file';
            const currentFileNameSpan = document.getElementById('currentFileName' + id);
            if (fileName === 'Tidak ada file') {
                currentFileNameSpan.textContent = '- Tidak ada file';
            } else {
                currentFileNameSpan.textContent = '- ' + fileName;
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 150);
                    }
                });
            }, 5000);
        });
    </script>

    {{-- File Name Display --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loop melalui semua input file berdasarkan ID dinamis
            document.querySelectorAll('input[type="file"][id^="uploaded_pdf"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    const id = this.id.replace('uploaded_pdf', ''); // Ambil ID dari input
                    const fileNameDisplay = document.getElementById('fileNameDisplay' +
                        id); // Ambil elemen span
                    const fileName = this.files.length > 0 ? this.files[0].name :
                        'Tidak ada file yang dipilih';

                    // Perbarui teks di span dengan nama file
                    fileNameDisplay.textContent = fileName;
                });
            });
        });
    </script>

    {{-- JS Modal Add --}}
    <script>
        // Searchable Ponpes dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const ponpesSearch = document.getElementById('ponpes_search');
            const ponpesDropdown = document.getElementById('ponpesDropdownMenu');
            const ponpesOptions = document.querySelectorAll('.ponpes-option');

            if (ponpesSearch) {
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

                    if (searchTerm.length > 0 && hasVisibleOption) {
                        ponpesDropdown.style.display = 'block';
                    } else {
                        ponpesDropdown.style.display = 'none';
                    }
                });

                // Hapus event listener focus atau modifikasi agar tidak auto-open
                ponpesSearch.addEventListener('focus', function() {
                    // Hanya buka jika ada input
                    if (this.value.length > 0) {
                        const searchTerm = this.value.toLowerCase();
                        let hasVisibleOption = false;

                        ponpesOptions.forEach(option => {
                            const text = option.textContent.toLowerCase();
                            if (text.includes(searchTerm)) {
                                option.style.display = 'block';
                                hasVisibleOption = true;
                            }
                        });

                        if (hasVisibleOption) {
                            ponpesDropdown.style.display = 'block';
                        }
                    }
                });
            }

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    if (ponpesDropdown) {
                        ponpesDropdown.style.display = 'none';
                    }
                }
            });
        });

        function togglePonpesDropdown() {
            const ponpesDropdown = document.getElementById('ponpesDropdownMenu');
            const ponpesOptions = document.querySelectorAll('.ponpes-option');

            if (ponpesDropdown.style.display === 'none' || ponpesDropdown.style.display === '') {
                ponpesOptions.forEach(option => {
                    option.style.display = 'block';
                });
                ponpesDropdown.style.display = 'block';
            } else {
                ponpesDropdown.style.display = 'none';
            }
        }

        function selectPonpes(namaPonpes, namaWilayah) {
            document.getElementById('ponpes_search').value = namaPonpes;
            document.getElementById('nama_ponpes').value = namaPonpes;
            document.getElementById('nama_wilayah').value = namaWilayah;
            document.getElementById('ponpesDropdownMenu').style.display = 'none';

            // Tutup dropdown setelah pilihan dipilih
            event.preventDefault();
        }

        // Clear selection when search is cleared
        const ponpesSearchInput = document.getElementById('ponpes_search');
        if (ponpesSearchInput) {
            ponpesSearchInput.addEventListener('input', function() {
                if (this.value === '') {
                    document.getElementById('nama_ponpes').value = '';
                    document.getElementById('nama_wilayah').value = '';
                }
            });
        }
        // Reset form when modal is closed
        $('#addModal').on('hidden.bs.modal', function() {
            document.getElementById('ponpes_search').value = '';
            document.getElementById('nama_ponpes').value = '';
            document.getElementById('nama_wilayah').value = '';
            document.getElementById('tipe_display').value = '';
            document.getElementById('tanggal').value = '';
            document.getElementById('tanggal_kontrak').value = '';
            document.getElementById('tanggal_jatuh_tempo').value = '';
            document.getElementById('ponpesDropdownMenu').style.display = 'none';
        });
    </script>

@endsection

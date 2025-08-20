@extends('layout.sidebar')
@section('content')

    <div class="content-wrapper">
        <x-header>{{ $title }}</x-header>
        <!-- Content Header (Page header) -->
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
                        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                        @if (request('table_search'))
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Hasil pencarian untuk: "<strong>{{ request('table_search') }}</strong>"
                                    <a href="{{ route('tutor_upt.ListDataSpp') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mt-2">Data Reguler</h3>
                                <div class="card-tools">
                                    <form action="{{ route('tutor_upt.ListDataSpp') }}" method="GET">
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
                                            <th>Tutorial Reguller</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Status Upload PDF</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $d->tutor_reguller }}</strong></td>
                                                <td>{{ $d->tanggal }}</td>
                                                <td>
                                                    @php
                                                        // Daftar kolom PDF (folder 1-10)
                                                        $pdfColumns = [];
                                                        for ($i = 1; $i <= 10; $i++) {
                                                            $pdfColumns[] = 'pdf_folder_' . $i;
                                                        }

                                                        // Hitung folder yang sudah ada PDF
                                                        $uploadedFolders = 0;
                                                        foreach ($pdfColumns as $column) {
                                                            if (!empty($d->$column)) {
                                                                $uploadedFolders++;
                                                            }
                                                        }

                                                        $totalFolders = count($pdfColumns); // 10 folder
                                                        $percentage = ($uploadedFolders / $totalFolders) * 100;
                                                    @endphp

                                                    @if ($uploadedFolders == 0)
                                                        <span class="badge badge-danger py-2">Belum Upload</span>
                                                    @elseif($uploadedFolders == $totalFolders)
                                                        <span class="badge badge-success py-2">Semua Folder Lengkap
                                                            (10/10)
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning py-2">
                                                            {{ $uploadedFolders }}/{{ $totalFolders }} Folder
                                                            {{-- ({{ round($percentage) }}%) --}}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-primary mr-1" data-toggle="modal"
                                                            data-target="#uploadModal{{ $d->id }}"
                                                            title="Upload PDF">
                                                            <i class="fas fa-upload"></i> Manage PDF
                                                        </button>

                                                        <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                            data-target="#modal-default{{ $d->id }}"
                                                            title="Hapus Data">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Upload Modal -->
                                            <div class="modal fade" id="uploadModal{{ $d->id }}" tabindex="-1"
                                                aria-labelledby="uploadModalLabel{{ $d->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form action="{{ route('tutor_upt.uploadFilePDF', [$d->id, 1]) }}"
                                                            method="POST" enctype="multipart/form-data"
                                                            id="uploadForm{{ $d->id }}">
                                                            @csrf
                                                            <input type="hidden" name="selected_folder"
                                                                id="selectedFolder{{ $d->id }}" value="1">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="uploadModalLabel{{ $d->id }}">Manage PDF
                                                                    untuk {{ $d->tutor_reguller }}</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
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
                                                                            <option value="{{ $i }}">Folder
                                                                                {{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                <!-- PDF Status and Actions for Selected Folder -->
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h6 class="mb-0">Status dan Aksi untuk Folder
                                                                            <span
                                                                                id="currentFolder{{ $d->id }}">1</span>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div id="pdfActions{{ $d->id }}">
                                                                            @for ($i = 1; $i <= 10; $i++)
                                                                                @php
                                                                                    $column = 'pdf_folder_' . $i;
                                                                                @endphp
                                                                                <div class="pdf-folder-actions"
                                                                                    id="folderActions{{ $d->id }}_{{ $i }}"
                                                                                    style="display: {{ $i == 1 ? 'block' : 'none' }};">
                                                                                    @if (!empty($d->$column))
                                                                                        <div class="alert alert-success">
                                                                                            <i
                                                                                                class="fas fa-check-circle"></i>
                                                                                            PDF sudah tersedia untuk Folder
                                                                                            {{ $i }}
                                                                                        </div>
                                                                                        <div class="btn-group mb-3"
                                                                                            role="group">
                                                                                            <a href="{{ route('tutor_upt.viewpdf', [$d->id, $i]) }}"
                                                                                                target="_blank"
                                                                                                class="btn btn-info"
                                                                                                title="Lihat PDF Folder {{ $i }}">
                                                                                                <i class="fas fa-eye"></i>
                                                                                                View PDF
                                                                                            </a>
                                                                                            <button type="button"
                                                                                                class="btn btn-warning"
                                                                                                data-toggle="modal"
                                                                                                data-target="#deletePdfModal{{ $d->id }}_{{ $i }}"
                                                                                                title="Hapus File PDF Folder {{ $i }}">
                                                                                                <i
                                                                                                    class="fas fa-trash"></i>
                                                                                                Delete PDF
                                                                                            </button>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="alert alert-warning">
                                                                                            <i
                                                                                                class="fas fa-exclamation-triangle"></i>
                                                                                            Belum ada PDF yang diupload
                                                                                            untuk Folder
                                                                                            {{ $i }}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="uploaded_pdf{{ $d->id }}">Upload
                                                                        File PDF Baru</label>
                                                                    <input type="file"
                                                                        class="form-control-file btn btn-primary"
                                                                        id="uploaded_pdf" name="uploaded_pdf"
                                                                        accept=".pdf">
                                                                    <small class="form-text text-muted">Pilih file PDF
                                                                        untuk mengupload atau mengganti file yang sudah
                                                                        ada (maksimal 10MB)</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary">Upload
                                                                    PDF</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Delete PDF Modals for Each Folder --}}
                                            @for ($i = 1; $i <= 10; $i++)
                                                @php
                                                    $column = 'pdf_folder_' . $i;
                                                @endphp
                                                <div class="modal fade"
                                                    id="deletePdfModal{{ $d->id }}_{{ $i }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="deletePdfModalLabel{{ $d->id }}_{{ $i }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"
                                                                    id="deletePdfModalLabel{{ $d->id }}_{{ $i }}">
                                                                    Hapus File PDF Folder {{ $i }}</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus file PDF untuk
                                                                    <b>{{ $d->tutor_reguller }}</b> di Folder
                                                                    {{ $i }}?
                                                                </p>
                                                                <p class="text-info">
                                                                    <small><i class="fas fa-info-circle"></i> Data UPT
                                                                        tidak akan dihapus, hanya file PDF saja.</small>
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Batal</button>
                                                                <form
                                                                    action="{{ route('tutor_upt.deleteFilePDF', [$d->id, $i]) }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-warning">
                                                                        <i class="fas fa-file-pdf"></i> Hapus PDF
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
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus data
                                                                <b>{{ $d->tutor_reguller }}</b>?
                                                            </p>
                                                            <p class="text-warning"><small><i
                                                                        class="fas fa-exclamation-triangle"></i> Semua file
                                                                    PDF yang terupload juga akan dihapus.</small></p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('tutor_upt.DataBasePageDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt"></i> Hapus
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
                                        <form id="addForm" action="{{ route('tutor_upt.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addModalLabel">Tambah Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        {{-- Input Nama UPT --}}
                                                        <div class="mb-3">
                                                            <label for="tutor_reguller" class="form-label">Judul
                                                                Tutorial</label>
                                                            <input type="text" class="form-control"
                                                                id="tutor_reguller" name="tutor_reguller" required>
                                                        </div>
                                                        @error('tutor_reguller')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                        {{-- Input Nama UPT --}}

                                                        {{-- Input Tanggal Hidden --}}
                                                        <input type="hidden" id="addTanggal" name="tanggal">
                                                        {{-- Input Tanggal Hidden --}}
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
                                    {{-- User Create Modal --}}


                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        {{-- Main content --}}
    </div>

    <script>
        function updateFolder(id, folder) {
            const form = document.getElementById('uploadForm' + id);
            const hiddenInput = document.getElementById('selectedFolder' + id);
            const currentFolderSpan = document.getElementById('currentFolder' + id);

            hiddenInput.value = folder;
            currentFolderSpan.textContent = folder;

            // Update action URL with the selected folder - Fixed route name
            form.action = "{{ route('tutor_upt.uploadFilePDF', [':id', ':folder']) }}"
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

@endsection

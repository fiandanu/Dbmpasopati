@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        {{-- Left navbar Links --}}
                        <div class="d-flex justify-content-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data SPP Ponpes</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search">
                            </div>
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
                                    <a href="{{ route('sppPonpes.ListDataSpp') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Ponpes</th>
                                            <th>Nama Wilayah</th>
                                            <th class="text-center">Tanggal Dibuat</th>
                                            <th class="text-center">Status Upload PDF</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->nama_ponpes }}</td>
                                                <td><span class="tag tag-success">{{ $d->nama_wilayah }}</span></td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $uploadedFolders = 0;
                                                        $totalFolders = 10;

                                                        // Check if uploadFolder relationship exists
                                                        if ($d->uploadFolder) {
                                                            for ($i = 1; $i <= 10; $i++) {
                                                                $column = 'pdf_folder_' . $i;
                                                                if (!empty($d->uploadFolder->$column)) {
                                                                    $uploadedFolders++;
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($uploadedFolders == 0)
                                                        <span class="badge">Belum Upload</span>
                                                    @elseif($uploadedFolders == $totalFolders)
                                                        <span class="badge-sucess">Semua Folder Lengkap
                                                            (10/10)
                                                        </span>
                                                    @else
                                                        <span class="badge-prosses">
                                                            {{ $uploadedFolders }}/{{ $totalFolders }} Folder
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

                                                        <button data-toggle="modal"
                                                            data-target="#modal-default{{ $d->id }}"
                                                            title="Hapus Data">
                                                            <ion-icon name="trash-outline"></ion-icon></button>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Upload Modal -->
                                            <div class="modal fade" id="uploadModal{{ $d->id }}" tabindex="-1"
                                                aria-labelledby="uploadModalLabel{{ $d->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form action="{{ route('uploadFilePDF', [$d->id, 1]) }}"
                                                            method="POST" enctype="multipart/form-data"
                                                            id="uploadForm{{ $d->id }}">
                                                            @csrf
                                                            <input type="hidden" name="selected_folder"
                                                                id="selectedFolder{{ $d->id }}" value="1">
                                                            <div class="modal-header">
                                                                <label class="modal-title"
                                                                    id="uploadModalLabel{{ $d->id }}">Manage PDF
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
                                                                                $fileName = null;
                                                                                if ($d->uploadFolder) {
                                                                                    $column = 'pdf_folder_' . $i;
                                                                                    $fileName = !empty(
                                                                                        $d->uploadFolder->$column
                                                                                    )
                                                                                        ? basename(
                                                                                            $d->uploadFolder->$column,
                                                                                        )
                                                                                        : null;
                                                                                }
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
                                                                            Status dan Aksi <span
                                                                                id="currentFolder{{ $d->id }}">1</span>
                                                                            <span id="currentFileName{{ $d->id }}"
                                                                                class="text-muted">
                                                                                @php
                                                                                    $firstFileName = null;
                                                                                    if (
                                                                                        $d->uploadFolder &&
                                                                                        !empty(
                                                                                            $d->uploadFolder
                                                                                                ->pdf_folder_1
                                                                                        )
                                                                                    ) {
                                                                                        $firstFileName = basename(
                                                                                            $d->uploadFolder
                                                                                                ->pdf_folder_1,
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
                                                                                        $hasFile = false;
                                                                                        if ($d->uploadFolder) {
                                                                                            $column =
                                                                                                'pdf_folder_' . $i;
                                                                                            $hasFile = !empty(
                                                                                                $d->uploadFolder
                                                                                                    ->$column
                                                                                            );
                                                                                        }
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
                                                                                            <a href="{{ route('viewpdf', [$d->id, $i]) }}"
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
                                                                    <label for="uploaded_pdf{{ $d->id }}"
                                                                        class="btn-upload">Upload PDF</label>
                                                                    <input type="file" class="form-control-file"
                                                                        id="uploaded_pdf{{ $d->id }}"
                                                                        name="uploaded_pdf" accept=".pdf"
                                                                        style="display: none;">
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

                                            {{-- Delete PDF Modals --}}
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
                                                                <p>Anda yakin ingin menghapus file PDF untuk
                                                                    <b>{{ $d->nama_ponpes }}</b> di Folder
                                                                    {{ $i }}?
                                                                </p>
                                                                <label class="text-info">
                                                                    <small><i class="fas fa-info-circle"></i> Data Ponpes
                                                                        tidak akan dihapus, hanya file PDF saja.</small>
                                                                </label>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn-cancel-modal"
                                                                    data-dismiss="modal">Batal</button>
                                                                <form action="{{ route('deleteFilePDF', [$d->id, $i]) }}"
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
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus data
                                                                <b>{{ $d->nama_ponpes }}</b>?
                                                            </p>
                                                            <p class="text-warning"><small><i
                                                                        class="fas fa-exclamation-triangle"></i> Semua file
                                                                    PDF yang terupload juga akan dihapus.</small></p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('sppPonpes.DataBasePageDestroy', $d->id) }}"
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
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script>
        function updateFolder(id, folder) {
            const form = document.getElementById('uploadForm' + id);
            const hiddenInput = document.getElementById('selectedFolder' + id);
            const currentFolderSpan = document.getElementById('currentFolder' + id);

            hiddenInput.value = folder;
            currentFolderSpan.textContent = folder;

            // Update action URL with the selected folder
            form.action = "{{ route('uploadFilePDF', [':id', ':folder']) }}"
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
@endsection

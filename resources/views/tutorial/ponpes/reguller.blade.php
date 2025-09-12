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
                            <h1 class="headline-large-32 mb-0">Tutorial Reguler Ponpes</h1>
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
                                    <a href="{{ route('tutor_ponpes_reguller.ListDataSpp') }}"
                                        class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="Table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tutorial Reguler</th>
                                            <th class="text-center">Tanggal Dibuat</th>
                                            <th class="text-center">Status Upload PDF</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->tutor_ponpes_reguller }}</td>
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

                                                        <button data-toggle="modal"
                                                            data-target="#modal-default{{ $d->id }}"
                                                            title="Hapus Data">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Upload Modal -->
                                            <div class="modal fade" id="uploadModal{{ $d->id }}" tabindex="-1"
                                                aria-labelledby="uploadModalLabel{{ $d->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('tutor_ponpes_reguller.uploadFilePDF', [$d->id, 1]) }}"
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
                                                                                            <a href="{{ route('tutor_ponpes_reguller.viewpdf', [$d->id, $i]) }}"
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
                                                                    action="{{ route('tutor_ponpes_reguller.deleteFilePDF', [$d->id, $i]) }}"
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
                                                            <label>Apakah Folder <b> {{ $d->tutor_ponpes_reguller }} </b>
                                                                ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('tutor_ponpes_reguller.DataBasePageDestroy', $d->id) }}"
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
                                        <form id="addForm" action="{{ route('tutor_ponpes_reguller.store') }}"
                                            method="POST">
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
                                                            <label for="tutor_ponpes_reguller" class="form-label">Judul
                                                                Tutorial</label>
                                                            <input type="text" class="form-control"
                                                                id="tutor_ponpes_reguller" name="tutor_ponpes_reguller"
                                                                required placeholder="Masukan Judul">
                                                        </div>
                                                        @error('tutor_ponpes_reguller')
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

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Update Folder --}}
    <script>
        function updateFolder(id, folder) {
            const form = document.getElementById('uploadForm' + id);
            const hiddenInput = document.getElementById('selectedFolder' + id);
            const currentFolderSpan = document.getElementById('currentFolder' + id);

            hiddenInput.value = folder;
            currentFolderSpan.textContent = folder;

            // Update action URL with the selected folder
            form.action = "{{ route('tutor_ponpes_reguller.uploadFilePDF', [':id', ':folder']) }}"
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
@endsection

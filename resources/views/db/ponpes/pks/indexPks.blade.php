@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        {{-- Left navbar Links --}}
                        <div class="d-flex justify-content-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data PKS Ponpes</h1>
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
                                    <a href="{{ route('ponpes.pks.ListDataPks') }}" class="btn btn-sm btn-secondary ml-2">
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
                                            <th>Nama Ponpes</th>
                                            <th>Nama Wilayah</th>
                                            <th class="text-center">Tanggal Dibuat</th>
                                            <th class="text-center">Status PDF</th>
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
                                                    {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}</td>
                                                <td class="text-center">
                                                    @if (!$d->uploadFolder || empty($d->uploadFolder->uploaded_pdf))
                                                        <span class="badge">
                                                            Belum di Update
                                                        </span>
                                                    @else
                                                        <span class="badge-succes">
                                                            Sudah di Update
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group gap-2" role="group">
                                                        {{-- Upload PDF Button --}}
                                                        <form action="{{ route('uploadFilePDFPonpesPks', $d->id) }}"
                                                            method="POST" enctype="multipart/form-data"
                                                            id="uploadForm{{ $d->id }}" class="d-none">
                                                            @csrf
                                                            <input type="file" name="uploaded_pdf"
                                                                id="uploadInput{{ $d->id }}" accept=".pdf"
                                                                class="d-none" required>
                                                        </form>
                                                        <button onclick="triggerUpload({{ $d->id }})"
                                                            title="Upload PDF">
                                                            <ion-icon name="cloud-upload-outline"
                                                                class="w-6 h-6"></ion-icon>
                                                        </button>

                                                        {{-- View PDF Button --}}
                                                        @if ($d->uploadFolder && !empty($d->uploadFolder->uploaded_pdf))
                                                            <button>
                                                                <a href="{{ route('viewpdf.ponpes', $d->id) }}"
                                                                    target="_blank" title="Lihat PDF">
                                                                    <ion-icon name="eye-outline"></ion-icon>
                                                                </a>
                                                            </button>
                                                        @else
                                                            <button disabled title="Belum ada PDF yang diupload">
                                                                <ion-icon name="eye-off-outline"
                                                                    class="w-6 h-6"></ion-icon>
                                                            </button>
                                                        @endif

                                                        {{-- Delete PDF Button --}}
                                                        @if ($d->uploadFolder && !empty($d->uploadFolder->uploaded_pdf))
                                                            <button data-toggle="modal"
                                                                data-target="#deletePdfModal{{ $d->id }}"
                                                                title="Hapus File PDF">
                                                                <ion-icon name="backspace-outline"></ion-icon>
                                                            </button>
                                                        @endif

                                                        {{-- Delete Data Button --}}
                                                        <button data-toggle="modal"
                                                            data-target="#modal-default{{ $d->id }}"
                                                            title="Hapus Data">
                                                            <ion-icon name="trash-outline"></ion-icon></button>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- Delete PDF Modal --}}
                                            <div class="modal fade" id="deletePdfModal{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <p>Apakah Folder <b>{{ $d->nama_ponpes }}</b></p>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">cancel</button>
                                                            <form action="{{ route('deleteFilePDF.ponpes', $d->id) }}"
                                                                method="POST">
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

                                            {{-- Delete Data Modal --}}
                                            <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda yakin?</p>
                                                            <p>Apakah <b>{{ $d->nama_ponpes }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">cancel</button>
                                                            <form
                                                                action="{{ route('ponpes.pks.DataBasePageDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">
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
                            {{-- Index Form Html --}}

                            {{-- User Edit Modal --}}
                            @foreach ($data as $d)
                                {{-- User Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <form id="editForm" action="{{ route('ponpes.pks.ListDataPks', ['id' => $d->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                    <button type="button" class="btn-close-custom"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" id="editId" name="id">
                                                    <!-- Tampilkan pesan kesalahan jika ada -->

                                                    <!-- Data Wajib Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5 class="fw-semibold text-primary">Data Wajib</h5>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nama_ponpes" class="form-label">Nama
                                                                Ponpes</label>
                                                            <input type="text" class="form-control" id="nama_ponpes"
                                                                name="nama_ponpes" value="{{ $d->nama_ponpes }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nama_wilayah" class="form-label">Nama
                                                                Wilayah</label>
                                                            <input type="text" class="form-control" id="nama_wilayah"
                                                                name="nama_wilayah" value="{{ $d->nama_wilayah }}"
                                                                readonly>
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
                            {{-- User Edit Modal --}}

                        </div>
                    </div>
                </div>
                <!-- /.row -->

                {{-- Pagination Control --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- Row Limit --}}
                    <div class="btn-datakolom">
                        <button class="btn-select d-flex align-items-center">
                            <select id="row-limit">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="9999">semua</option>
                            </select>
                            Kolom
                        </button>
                    </div>

                    {{-- Pagination --}}
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

    {{-- Jquery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Upload PDF JS --}}
    <script>
        function triggerUpload(id) {
            const input = document.getElementById('uploadInput' + id);
            const form = document.getElementById('uploadForm' + id);

            // Reset input
            input.value = '';

            // Trigger file selection
            input.click();

            // Handle file selection
            input.onchange = function() {
                if (this.files.length > 0) {
                    const file = this.files[0];

                    // Validate file type
                    if (file.type !== 'application/pdf') {
                        alert('Hanya file PDF yang diperbolehkan!');
                        this.value = '';
                        return;
                    }

                    // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file tidak boleh lebih dari 5MB!');
                        this.value = '';
                        return;
                    }

                    // Show loading state
                    const uploadBtn = document.querySelector(`button[onclick="triggerUpload(${id})"]`);
                    const originalText = uploadBtn.innerHTML;
                    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                    uploadBtn.disabled = true;

                    // Submit form
                    form.submit();
                }
            };
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

    {{-- Search and Pagination JS --}}
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

                // update info halaman
                $("#page-info").text(`Page ${currentPage} of ${totalPages}`);

                // disable prev/next sesuai kondisi
                $("#prev-page").prop("disabled", currentPage === 1);
                $("#next-page").prop("disabled", currentPage === totalPages);
            }

            // apply awal
            updateTable();

            // kalau ganti jumlah data
            $("#row-limit").on("change", function() {
                limit = parseInt($(this).val());
                currentPage = 1;
                totalPages = Math.ceil($rows.length / limit);
                updateTable();
            });

            // tombol prev
            $("#prev-page").on("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            // tombol next
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

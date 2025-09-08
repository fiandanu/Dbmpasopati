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
                            <button class="btn-pushmenu" data-widget="pushmenu" href="#" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data UPT</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Search bar -->
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search">
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="Table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama UPT</th>
                                            <th>Kanwil</th>
                                            <th class="text-center">Tipe</th>
                                            <th class="text-center">Tanggal Dibuat</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataupt as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $d->namaupt }}
                                                    @if (str_contains($d->namaupt, '(VpasReg)'))
                                                        <br><small class="text-muted"><i class="fas fa-link"></i> Data
                                                            terkait dengan Reguler & VPAS</small>
                                                    @endif
                                                </td>
                                                <td><span class="tag tag-success">{{ $d->kanwil }}</span></td>
                                                <td class="text-center">
                                                    <span
                                                        class="
                                                        @if ($d->tipe == 'reguler') Tipereguller 
                                                        @elseif($d->tipe == 'vpas') Tipevpas @endif">
                                                        {{ ucfirst($d->tipe) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin? <br>
                                                            <h3><i>{{ $d->namaupt }}</i>
                                                                Tipe <i>{{ ucfirst($d->tipe) }}</i> ingin dihapus?
                                                            </h3>
                                                            <b>{{$d->namaupt}}</b> ingin dihapus?
                                                            </p>
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
                                                            <form action="{{ route('upt.UserPageDestroy', $d->id) }}"
                                                                method="POST">
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
                                    </tbody>
                                </table>
                            </div>
                            {{-- Index Form Html --}}

                            {{-- User Create Modal --}}
                            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                                aria-hidden="true">
                                <form id="addForm" action="{{ route('upt.UserPageStore') }}" method="POST">
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
                                                    <label for="kanwil" class="form-label">Kanwil</label>
                                                    <input type="text" class="form-control" id="kanwil"
                                                        name="kanwil" placeholder="Masukan Nama Kanwil" required>
                                                </div>
                                                @error('kanwil')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
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
                            {{-- User Create Modal --}}


                            @foreach ($dataupt as $d)
                                {{-- User Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <form id="editForm" action="{{ route('upt.UserPageUpdate', ['id' => $d->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" id="editId" name="id">

                                                    <div class="mb-3">
                                                        <label for="namaupt" class="form-label">Nama UPT</label>
                                                        <input type="text" class="form-control" id="namaupt"
                                                            name="namaupt" value="{{ $d->namaupt }}">
                                                        @if (str_contains($d->namaupt, '(VpasReg)'))
                                                            <small class="text-muted">
                                                                <i class="fas fa-info-circle"></i>
                                                                Data ini terkait dengan tipe lain. Harap berhati-hati saat
                                                                mengubah nama.
                                                            </small>
                                                        @endif
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="kanwil" class="form-label">Kanwil</label>
                                                        <input type="text" class="form-control" id="kanwil"
                                                            name="kanwil" value="{{ $d->kanwil }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="tipe" class="form-label">Tipe</label>
                                                        <select class="form-control" id="tipe" name="tipe"
                                                            required>
                                                            <option value="reguler"
                                                                {{ $d->tipe == 'reguler' ? 'selected' : '' }}>Reguler
                                                            </option>
                                                            <option value="vpas"
                                                                {{ $d->tipe == 'vpas' ? 'selected' : '' }}>VPAS</option>
                                                        </select>
                                                    </div>
                                                    @error('tipe')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror

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

                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->


                <div class="d-flex justify-content-between align-items-center mb-3">
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

    {{-- JavaScript untuk menangani preview nama UPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipeCheckboxes = document.querySelectorAll('input[name="tipe[]"]');
            const namaUptInput = document.getElementById('namaupt');
            let originalNamaUpt = '';
            let isUpdatingPreview = false;

            // Function untuk membersihkan suffix
            function cleanNamaUpt(nama) {
                return nama.replace(/ \(VpasReg\)/g, '').trim();
            }

            // Update preview saat checkbox diubah
            tipeCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateNamaUptPreview();
                });
            });

            function updateNamaUptPreview() {
                if (!originalNamaUpt) return;

                isUpdatingPreview = true;

                const regulerChecked = document.getElementById('tipe_reguler').checked;
                const vpasChecked = document.getElementById('tipe_vpas').checked;

                if (regulerChecked && vpasChecked) {
                    namaUptInput.value = originalNamaUpt + ' (VpasReg)';
                } else {
                    namaUptInput.value = originalNamaUpt;
                }

                setTimeout(() => {
                    isUpdatingPreview = false;
                }, 100);
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Ajax Search --}}
    <script>
        $(document).ready(function() {
            $("#btn-search").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("#Table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    {{-- Ajax Table Limit --}}
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
            $(document).ready(function() {
                $("#btn-search").on("keyup", function() {
                    let value = $(this).val().toLowerCase();
                    $("#Table tbody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        });
    </script>
@endsection

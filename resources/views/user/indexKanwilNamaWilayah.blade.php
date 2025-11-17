@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2 py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        {{-- left navbar --}}
                        <div class="d-flex justify-content-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" href="#" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32">List Data Kanwil dan Nama Wilayah</h1>
                        </div>

                        {{-- left navbar --}}
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addDataModal">
                                <i class="fa fa-plus"></i> Add Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Tampilkan pesan sukses --}}
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Tabel Kanwil -->
                    <div class="col-md-6">
                        <div class="mb-3 d-flex justify-end">
                            <!-- Export Buttons for Kanwil -->
                            <div class="d-flex gap-2" id="export-buttons-kanwil">
                                <button onclick="downloadKanwilCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Kanwil CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadKanwilPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Kanwil PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Kanwil</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datakanwil as $k)
                                            <tr>
                                                <td>{{ $k->kanwil }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editKanwilModal{{ $k->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editKanwilModal{{ $k->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                        {{-- Delete Button --}}
                                                        <button data-toggle="modal"
                                                            data-target="#deleteKanwilModal{{ $k->id }}"
                                                            title="Hapus">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Kanwil -->
                                            <div class="modal fade" id="deleteKanwilModal{{ $k->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $k->kanwil }}</b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('kanwil.KanwilPageDestroy', $k->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Edit Modal Kanwil -->
                                            <div class="modal fade" id="editKanwilModal{{ $k->id }}" tabindex="-1"
                                                aria-labelledby="editKanwilModalLabel" aria-hidden="true">
                                                <form id="editKanwilForm"
                                                    action="{{ route('kanwil.KanwilPageUpdate', ['id' => $k->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <label class="modal-title" id="editKanwilModalLabel">Edit
                                                                    Data Kanwil</label>
                                                                <button type="button" class="btn-close-custom"
                                                                    data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="kanwil">Kanwil</label>
                                                                    <input type="text" class="form-control"
                                                                        id="kanwil" name="kanwil"
                                                                        value="{{ $k->kanwil }}">
                                                                </div>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Tabel Nama Wilayah -->
                    <div class="col-md-6">
                        <div class="mb-3 d-flex justify-end">
                            <!-- Export Buttons for Nama Wilayah -->
                            <div class="d-flex gap-2" id="export-buttons-namawilayah">
                                <button onclick="downloadNamaWilayahCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Nama Wilayah CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadNamaWilayahPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Nama Wilayah PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Wilayah</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datanamawilayah as $n)
                                            <tr>
                                                <td>{{ $n->nama_wilayah }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editNamaWilayahModal{{ $n->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editNamaWilayahModal{{ $n->id }}"
                                                        title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>


                                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                        {{-- Delete Button --}}
                                                        <button data-toggle="modal"
                                                            data-target="#deleteNamaWilayahModal{{ $n->id }}"
                                                            title="Hapus">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Nama Wilayah -->
                                            <div class="modal fade" id="deleteNamaWilayahModal{{ $n->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $n->nama_wilayah }}</b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form
                                                                action="{{ route('namawilayah.NamaWilayahPageDestroy', $n->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Edit Modal Nama Wilayah -->
                                            <div class="modal fade" id="editNamaWilayahModal{{ $n->id }}"
                                                tabindex="-1" aria-labelledby="editNamaWilayahModalLabel"
                                                aria-hidden="true">
                                                <form id="editNamaWilayahForm"
                                                    action="{{ route('namawilayah.NamaWilayahPageUpdate', ['id' => $n->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <label class="modal-title"
                                                                    id="editNamaWilayahModalLabel">Edit Data Nama
                                                                    Wilayah</label>
                                                                <button type="button" class="btn-close-custom"
                                                                    data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nama_wilayah">Nama Wilayah</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_wilayah" name="nama_wilayah"
                                                                        value="{{ $n->nama_wilayah }}">
                                                                </div>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Modal -->
                <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <label class="modal-title" id="addDataModalLabel">Tambah Data</label>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="radio" id="kanwil_type" name="data_type" checked>
                                    <label for="kanwil_type">Kanwil</label>
                                    <input type="radio" id="nama_wilayah_type" name="data_type">
                                    <label for="nama_wilayah_type">Nama Wilayah</label>
                                </div>
                                <div id="kanwil_form">
                                    <div class="mb-3">
                                        <label for="kanwil">Kanwil</label>
                                        <input type="text" class="form-control" id="kanwil" name="kanwil">
                                    </div>
                                </div>
                                <div id="nama_wilayah_form" style="display: none;">
                                    <div class="mb-3">
                                        <label for="nama_wilayah">Nama Wilayah</label>
                                        <input type="text" class="form-control" id="nama_wilayah"
                                            name="nama_wilayah">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn-purple" id="save_btn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- JS Modal tambah Data dan export Data --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get radio buttons
            const kanwilRadio = document.getElementById('kanwil_type');
            const namaWilayahRadio = document.getElementById('nama_wilayah_type');

            // Get form containers
            const kanwilForm = document.getElementById('kanwil_form');
            const namaWilayahForm = document.getElementById('nama_wilayah_form');

            // Get save button
            const saveBtn = document.getElementById('save_btn');


            // Function hanlde enter key 
            const kanwilFormInput = kanwilForm.querySelector('input[name="kanwil"]');
            const namaWilayahFormInput = namaWilayahForm.querySelector('input[name="nama_wilayah"]');

            if (kanwilFormInput) {
                kanwilFormInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click();
                    }
                });
            }

            if (namaWilayahFormInput) {
                namaWilayahFormInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click();
                    }
                });
            }
            // Function hanlde enter key 


            // Function to toggle forms
            function toggleForms() {
                if (kanwilRadio.checked) {
                    kanwilForm.style.display = 'block';
                    namaWilayahForm.style.display = 'none';
                    // Clear Nama Wilayah form
                    const kanwilInput = kanwilForm.querySelector('input[name="kanwil"]');
                    if (kanwilInput) kanwilInput.value = '';
                    // document.getElementById('nama_wilayah').value = '';
                } else if (namaWilayahRadio.checked) {
                    kanwilForm.style.display = 'none';
                    namaWilayahForm.style.display = 'block';
                    // Clear Kanwil form
                    const namaWilayahInput = namaWilayahForm.querySelector('input[name="nama_wilayah"]');
                    if (namaWilayahInput) namaWilayahInput.value = '';
                    // document.getElementById('kanwil').value = '';
                }
            }

            // Event listeners for radio buttons
            kanwilRadio.addEventListener('change', toggleForms);
            namaWilayahRadio.addEventListener('change', toggleForms);

            // Save button click event
            saveBtn.addEventListener('click', function() {
                const isKanwil = kanwilRadio.checked;
                let form;

                if (isKanwil) {
                    // Create kanwil form
                    const formKanwilInput = kanwilForm.querySelector('input[name="kanwil"]');
                    const kanwil = formKanwilInput ? formKanwilInput.value : '';
                    // const kanwil = document.getElementById('kanwil').value;
                    if (!kanwil.trim()) {
                        alert('Kanwil harus diisi!');
                        return;
                    }

                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('kanwil.KanwilPageStore') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add kanwil
                    const kanwilInput = document.createElement('input');
                    kanwilInput.type = 'hidden';
                    kanwilInput.name = 'kanwil';
                    kanwilInput.value = kanwil;
                    form.appendChild(kanwilInput);

                } else {
                    // Create Nama Wilayah form
                    const namawilayahInput = namaWilayahForm.querySelector('input[name="nama_wilayah"]');
                    const namaWilayah = namawilayahInput ? namawilayahInput.value : '';
                    // const namaWilayah = document.getElementById('nama_wilayah').value;
                    if (!namaWilayah.trim()) {
                        alert('Nama Wilayah harus diisi!');
                        return;
                    }

                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('namawilayah.NamaWilayahPageStore') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add Nama Wilayah
                    const namaWilayahInput = document.createElement('input');
                    namaWilayahInput.type = 'hidden';
                    namaWilayahInput.name = 'nama_wilayah';
                    namaWilayahInput.value = namaWilayah;
                    form.appendChild(namaWilayahInput);
                }

                // Submit form
                document.body.appendChild(form);
                form.submit();
            });

            // Reset form when modal is closed
            document.getElementById('addDataModal').addEventListener('hidden.bs.modal', function() {
                // Reset radio to kanwil
                kanwilRadio.checked = true;
                namaWilayahRadio.checked = false;

                // Reset forms
                document.getElementById('kanwil').value = '';
                document.getElementById('nama_wilayah').value = '';

                // Show kanwil form, hide Nama Wilayah form
                toggleForms();
            });

            // Download functions for Kanwil
            window.downloadKanwilCsv = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('kanwil.export.kanwil.list.csv') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadKanwilPdf = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('kanwil.export.kanwil.list.pdf') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Download functions for Nama Wilayah
            window.downloadNamaWilayahCsv = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('namawilayah.export.namawilayah.list.csv') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadNamaWilayahPdf = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('namawilayah.export.namawilayah.list.pdf') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Show export buttons if there's data
            if ($(".table").eq(0).find("tbody tr").length > 0) {
                $("#export-buttons-kanwil").show();
            } else {
                $("#export-buttons-kanwil").hide();
            }
            if ($(".table").eq(1).find("tbody tr").length > 0) {
                $("#export-buttons-namawilayah").show();
            } else {
                $("#export-buttons-namawilayah").hide();
            }
        });
    </script>
@endsection

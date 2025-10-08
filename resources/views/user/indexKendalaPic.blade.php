@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2 py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <h1 class="headline-large-32">List Data Kendala/PIC</h1>
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
                    <!-- Tabel Kendala -->
                    <div class="col-md-6">
                        <div class="mb-3 d-flex justify-end">
                            <!-- Export Buttons for Kendala -->
                            <div class="d-flex gap-2" id="export-buttons-kendala">
                                <button onclick="downloadKendalaCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Kendala CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadKendalaPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Kendala PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Jenis Kendala</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datakendala as $k)
                                            <tr>
                                                <td>{{ $k->jenis_kendala }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editKendalaModal{{ $k->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editKendalaModal{{ $k->id }}"
                                                        title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#deleteKendalaModal{{ $k->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Kendala -->
                                            <div class="modal fade" id="deleteKendalaModal{{ $k->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $k->jenis_kendala }}</b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('kendala.KendalaPageDestroy', $k->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Edit Modal Kendala -->
                                            <div class="modal fade" id="editKendalaModal{{ $k->id }}" tabindex="-1"
                                                aria-labelledby="editKendalaModalLabel" aria-hidden="true">
                                                <form id="editKendalaForm"
                                                    action="{{ route('kendala.KendalaPageUpdate', ['id' => $k->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <label class="modal-title" id="editKendalaModalLabel">Edit
                                                                    Data
                                                                    Kendala</label>
                                                                <button type="button" class="btn-close-custom"
                                                                    data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="jenis_kendala">Jenis Kendala</label>
                                                                    <input type="text" class="form-control"
                                                                        id="jenis_kendala" name="jenis_kendala"
                                                                        value="{{ $k->jenis_kendala }}">
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
                    <!-- Tabel PIC -->
                    <div class="col-md-6">
                        <div class="d-flex mb-3 justify-end">
                            <!-- Export Buttons for PIC -->
                            <div class="d-flex gap-2" id="export-buttons-pic">
                                <button onclick="downloadPicCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download PIC CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PIC CSV
                                </button>
                                <button onclick="downloadPicPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download PIC PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PIC PDF
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama PIC</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datapic as $p)
                                            <tr>
                                                <td>{{ $p->nama_pic }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editPicModal{{ $p->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPicModal{{ $p->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#deletePicModal{{ $p->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal PIC -->
                                            <div class="modal fade" id="deletePicModal{{ $p->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $p->nama_pic }}</b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('pic.PicPageDestroy', $p->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Edit Modal PIC -->
                                            <div class="modal fade" id="editPicModal{{ $p->id }}" tabindex="-1"
                                                aria-labelledby="editPicModalLabel" aria-hidden="true">
                                                <form id="editPicForm"
                                                    action="{{ route('pic.PicPageUpdate', ['id' => $p->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <label class="modal-title" id="editPicModalLabel">Edit
                                                                    Data PIC</label>
                                                                <button type="button" class="btn-close-custom"
                                                                    data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nama_pic">Nama PIC</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_pic" name="nama_pic"
                                                                        value="{{ $p->nama_pic }}">
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
                                    <input type="radio" id="kendala_type" name="data_type" checked>
                                    <label for="kendala_type">Kendala</label>
                                    <input type="radio" id="pic_type" name="data_type">
                                    <label for="pic_type">PIC</label>
                                </div>
                                <div id="kendala_form">
                                    <div class="mb-3">
                                        <label for="jenis_kendala">Jenis Kendala</label>
                                        <input type="text" class="form-control" id="jenis_kendala"
                                            name="jenis_kendala">
                                    </div>
                                </div>
                                <div id="pic_form" style="display: none;">
                                    <div class="mb-3">
                                        <label for="nama_pic">Nama PIC</label>
                                        <input type="text" class="form-control" id="nama_pic" name="nama_pic">
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
            const kendalaRadio = document.getElementById('kendala_type');
            const picRadio = document.getElementById('pic_type');

            // Get form containers
            const kendalaForm = document.getElementById('kendala_form');
            const picForm = document.getElementById('pic_form');

            // Get save button
            const saveBtn = document.getElementById('save_btn');


            // Function Untuk Handle enter key add and edit
            const kendalaFormInput = kendalaForm.querySelector('input[name="jenis_kendala"]');
            const picFormInput = picForm.querySelector('input[name="nama_pic"]');

            if (kendalaFormInput) {
                kendalaFormInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click(); // Optional: trigger tombol Save
                    }
                });
            }

            if (picFormInput) {
                picFormInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click(); // Optional: trigger tombol Save
                    }
                });
            }
            // Function Untuk Handle enter key add and edit


            // Function to toggle forms
            function toggleForms() {
                if (kendalaRadio.checked) {
                    kendalaForm.style.display = 'block';
                    picForm.style.display = 'none';
                    // Clear PIC form
                    const namaPicInput = picForm.querySelector('input[name="nama_pic"]');
                    if (namaPicInput) namaPicInput.value = '';
                } else if (picRadio.checked) {
                    kendalaForm.style.display = 'none';
                    picForm.style.display = 'block';
                    // Clear Kendala form
                    const jenisKendalaInput = kendalaForm.querySelector('input[name="jenis_kendala"]');
                    if (jenisKendalaInput) jenisKendalaInput.value = '';
                }
            }

            // Event listeners for radio buttons
            kendalaRadio.addEventListener('change', toggleForms);
            picRadio.addEventListener('change', toggleForms);

            // Save button click event
            saveBtn.addEventListener('click', function() {
                const isKendala = kendalaRadio.checked;
                let form;

                if (isKendala) {
                    // Create kendala form
                    const jenisKendalaInput = kendalaForm.querySelector('input[name="jenis_kendala"]');
                    const jenisKendala = jenisKendalaInput ? jenisKendalaInput.value : '';

                    if (!jenisKendala.trim()) {
                        alert('Jenis Kendala harus diisi!');
                        return;
                    }

                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('kendala.KendalaPageStore') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add kendala type
                    const kendalaInput = document.createElement('input');
                    kendalaInput.type = 'hidden';
                    kendalaInput.name = 'jenis_kendala';
                    kendalaInput.value = jenisKendala;
                    form.appendChild(kendalaInput);

                } else {
                    // Create PIC form
                    const namaPicInput = picForm.querySelector('input[name="nama_pic"]');
                    const namaPic = namaPicInput ? namaPicInput.value : '';

                    if (!namaPic.trim()) {
                        alert('Nama PIC harus diisi!');
                        return;
                    }

                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('pic.PicPageStore') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add PIC name
                    const picInput = document.createElement('input');
                    picInput.type = 'hidden';
                    picInput.name = 'nama_pic';
                    picInput.value = namaPic;
                    form.appendChild(picInput);
                }

                // Submit form
                document.body.appendChild(form);
                form.submit();
            });

            // Reset form when modal is closed
            document.getElementById('addDataModal').addEventListener('hidden.bs.modal', function() {
                // Reset radio to kendala
                kendalaRadio.checked = true;
                picRadio.checked = false;

                // Reset forms
                document.getElementById('jenis_kendala').value = '';
                document.getElementById('nama_pic').value = '';

                // Show kendala form, hide PIC form
                toggleForms();
            });

            // Download functions for Kendala
            window.downloadKendalaCsv = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('kendala.export.kendala.list.csv') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadKendalaPdf = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('kendala.export.kendala.list.pdf') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Download functions for PIC
            window.downloadPicCsv = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('pic.export.pic.list.csv') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadPicPdf = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('pic.export.pic.list.pdf') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Show export buttons if there's data
            if ($(".table").eq(0).find("tbody tr").length > 0) {
                $("#export-buttons-kendala").show();
            } else {
                $("#export-buttons-kendala").hide();
            }
            if ($(".table").eq(1).find("tbody tr").length > 0) {
                $("#export-buttons-pic").show();
            } else {
                $("#export-buttons-pic").hide();
            }
        });
    </script>

@endsection

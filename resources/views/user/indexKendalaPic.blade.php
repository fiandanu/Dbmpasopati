@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List Data Kendala/PIC</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">List Data Kendala/PIC</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
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
                <!-- Single Add Button -->
                <div class="row mb-3">
                    <div class="col-12">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDataModal">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <div class="row">
                    <!-- Tabel Kendala -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Table Kendala</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Kendala</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datakendala as $k)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $k->jenis_kendala }}</strong></td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editKendalaModal{{ $k->id }}"
                                                        class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editKendalaModal{{ $k->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal"
                                                        data-target="#deleteKendalaModal{{ $k->id }}"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Kendala -->
                                            <div class="modal fade" id="deleteKendalaModal{{ $k->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data Kendala</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah <b>{{ $k->jenis_kendala }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form
                                                                action="{{ route('kendala.KendalaPageDestroy', $k->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel PIC -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Table PIC</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama PIC</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datapic as $p)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><span class="tag tag-success">{{ $p->nama_pic }}</span></td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editPicModal{{ $p->id }}"
                                                        class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editPicModal{{ $p->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal" data-target="#deletePicModal{{ $p->id }}"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal PIC -->
                                            <div class="modal fade" id="deletePicModal{{ $p->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data PIC</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah <b>{{ $p->nama_pic }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('pic.PicPageDestroy', $p->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Combined Create Modal --}}
        <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDataModalLabel">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Data Type Selection -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Jenis Data</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="data_type" id="kendala_type"
                                    value="kendala" checked>
                                <label class="form-check-label" for="kendala_type">
                                    <i class="fas fa-exclamation-triangle text-warning"></i> Kendala
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="data_type" id="pic_type"
                                    value="pic">
                                <label class="form-check-label" for="pic_type">
                                    <i class="fas fa-user text-info"></i> PIC
                                </label>
                            </div>
                        </div>

                        <!-- Dynamic Form Container -->
                        <div id="form_container">
                            <!-- Kendala Form -->
                            <div id="kendala_form" class="data-form">
                                <div class="mb-3">
                                    <label for="jenis_kendala" class="form-label">Jenis Kendala</label>
                                    <input type="text" class="form-control" id="jenis_kendala" name="jenis_kendala"
                                        placeholder="Masukkan jenis kendala">
                                </div>
                            </div>

                            <!-- PIC Form -->
                            <div id="pic_form" class="data-form" style="display: none;">
                                <div class="mb-3">
                                    <label for="nama_pic" class="form-label">Nama PIC</label>
                                    <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                        placeholder="Masukkan nama PIC">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save_btn">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kendala Edit Modals --}}
        @foreach ($datakendala as $k)
            <div class="modal fade" id="editKendalaModal{{ $k->id }}" tabindex="-1"
                aria-labelledby="editKendalaModalLabel" aria-hidden="true">
                <form id="editKendalaForm" action="{{ route('kendala.KendalaPageUpdate', ['id' => $k->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editKendalaModalLabel">Edit Data Kendala</h5>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="jenis_kendala" class="form-label">Jenis Kendala</label>
                                    <input type="text" class="form-control" id="jenis_kendala" name="jenis_kendala"
                                        value="{{ $k->jenis_kendala }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach

        {{-- PIC Edit Modals --}}
        @foreach ($datapic as $p)
            <div class="modal fade" id="editPicModal{{ $p->id }}" tabindex="-1"
                aria-labelledby="editPicModalLabel" aria-hidden="true">
                <form id="editPicForm" action="{{ route('pic.PicPageUpdate', ['id' => $p->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPicModalLabel">Edit Data PIC</h5>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_pic" class="form-label">Nama PIC</label>
                                    <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                        value="{{ $p->nama_pic }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

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

            // Function to toggle forms
            function toggleForms() {
                if (kendalaRadio.checked) {
                    kendalaForm.style.display = 'block';
                    picForm.style.display = 'none';
                    // Clear PIC form
                    document.getElementById('nama_pic').value = '';
                } else if (picRadio.checked) {
                    kendalaForm.style.display = 'none';
                    picForm.style.display = 'block';
                    // Clear Kendala form
                    document.getElementById('jenis_kendala').value = '';
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
                    const jenisKendala = document.getElementById('jenis_kendala').value;
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
                    const namaPic = document.getElementById('nama_pic').value;
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
        });
    </script>

@endsection
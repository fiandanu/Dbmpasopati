@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2 py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <h1 class="headline-large-32">List Data Kanwil/Nama Wilayah</h1>
                        <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addDataModal">
                            <i class="fa fa-plus"></i> Add Data
                        </button>
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
                                                        data-bs-target="#editKanwilModal{{ $k->id }}"
                                                        title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#deleteKanwilModal{{ $k->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
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
                                                            <label>Apakah <b> {{ $k->kanwil }} </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Nama Wilayah -->
                    <div class="col-md-6">
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
                                        @foreach ($datanamawilayah as $w)
                                            <tr>
                                                <td><span class="tag tag-success">{{ $w->nama_wilayah }}</span></td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editNamaWilayahModal{{ $w->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editNamaWilayahModal{{ $w->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#deleteNamaWilayahModal{{ $w->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Nama Wilayah -->
                                            <div class="modal fade" id="deleteNamaWilayahModal{{ $w->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b> {{ $w->nama_wilayah }} </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('namawilayah.NamaWilayahPageDestroy', $w->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn-delete">Hapus</button>
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
                        <label class="modal-title" id="addDataModalLabel">Tambah Data</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Dynamic Form Container -->
                        <div id="form_container">
                            <!-- Kanwil Form -->
                            <div id="kanwil_form" class="data-form">
                                <div class="mb-3">
                                    <label for="kanwil">Kanwil</label>
                                    <input type="text" class="form-control" id="kanwil" name="kanwil"
                                        placeholder="Masukkan kanwil">
                                </div>
                            </div>

                            <!-- Nama Wilayah Form -->
                            <div id="nama_wilayah_form" class="data-form" style="display: none;">
                                <div class="mb-3">
                                    <label for="nama_wilayah">Nama Wilayah</label>
                                    <input type="text" class="form-control" id="nama_wilayah" name="nama_wilayah"
                                        placeholder="Masukkan nama wilayah">
                                </div>
                            </div>
                        </div>

                        <!-- Data Type Selection -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Jenis Data</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="data_type" id="kanwil_type"
                                    value="kanwil" checked>
                                <h6 class="form-check-label" for="kanwil_type">
                                    Kanwil
                                </h6>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="data_type" id="nama_wilayah_type"
                                    value="nama_wilayah">
                                <h6 class="form-check-label" for="nama_wilayah_type">
                                    Nama Wilayah
                                </h6>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn-purple" id="save_btn">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanwil Edit Modals --}}
        @foreach ($datakanwil as $k)
            <div class="modal fade" id="editKanwilModal{{ $k->id }}" tabindex="-1"
                aria-labelledby="editKanwilModalLabel" aria-hidden="true">
                <form id="editKanwilForm" action="{{ route('kanwil.KanwilPageUpdate', ['id' => $k->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <label class="modal-title" id="editKanwilModalLabel">Edit Data Kanwil</label>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="kanwil">Kanwil</label>
                                    <input type="text" class="form-control" id="kanwil" name="kanwil"
                                        value="{{ $k->kanwil }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn-purple">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach

        {{-- Nama Wilayah Edit Modals --}}
        @foreach ($datanamawilayah as $w)
            <div class="modal fade" id="editNamaWilayahModal{{ $w->id }}" tabindex="-1"
                aria-labelledby="editNamaWilayahModalLabel" aria-hidden="true">
                <form id="editNamaWilayahForm" action="{{ route('namawilayah.NamaWilayahPageUpdate', ['id' => $w->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <label class="modal-title" id="editNamaWilayahModalLabel">Edit Data Nama Wilayah</label>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_wilayah" class="form-label">Nama Wilayah</label>
                                    <input type="text" class="form-control" id="nama_wilayah" name="nama_wilayah"
                                        value="{{ $w->nama_wilayah }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn-purple">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>



    {{-- JS --}}
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

            // Function to toggle forms
            function toggleForms() {
                if (kanwilRadio.checked) {
                    kanwilForm.style.display = 'block';
                    namaWilayahForm.style.display = 'none';
                    // Clear Nama Wilayah form
                    document.getElementById('nama_wilayah').value = '';
                } else if (namaWilayahRadio.checked) {
                    kanwilForm.style.display = 'none';
                    namaWilayahForm.style.display = 'block';
                    // Clear Kanwil form
                    document.getElementById('kanwil').value = '';
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
                    const kanwil = document.getElementById('kanwil').value;
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
                    const namaWilayah = document.getElementById('nama_wilayah').value;
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
        });
    </script>

@endsection

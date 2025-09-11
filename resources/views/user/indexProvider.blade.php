@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2 py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <h1 class="headline-large-32">Provider dan Vpn</h1>
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
                                <div class="mb-1">â€¢ {{ $error }}</div>
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

                <div class="row">
                    <!-- Tabel Provider -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provider</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataprovider as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->nama_provider }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editProviderModal{{ $d->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editProviderModal{{ $d->id }}"
                                                        title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#deleteProviderModal{{ $d->id }}"
                                                        title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Provider -->
                                            <div class="modal fade" id="deleteProviderModal{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $d->nama_provider }}</b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form
                                                                action="{{ route('provider.ProviderPageDestroy', $d->id) }}"
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

                    <!-- Tabel VPN -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis VPN</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datavpn as $v)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><span class="tag tag-success">{{ $v->jenis_vpn }}</span></td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editVpnModal{{ $v->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editVpnModal{{ $v->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button data-toggle="modal"
                                                        data-target="#deleteVpnModal{{ $v->id }}" title="Hapus">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Modal Vpn -->
                                            <div class="modal fade" id="deleteVpnModal{{ $v->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $v->jenis_vpn }}</b> ingin dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('vpn.VpnPageDestroy', $v->id) }}"
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
        {{-- Combined Create Modal --}}
        <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <label class="modal-title" id="addDataModalLabel">Tambah Data</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Dynamic Form Container -->
                        <div id="form_container">
                            <!-- Provider Form -->
                            <div id="provider_form" class="data-form">
                                <div class="mb-3">
                                    <label for="nama_provider" class="form-label">Nama Provider</label>
                                    <input type="text" class="form-control" id="nama_provider" name="nama_provider"
                                        placeholder="Masukkan nama provider">
                                </div>
                            </div>

                            <!-- VPN Form -->
                            <div id="vpn_form" class="data-form" style="display: none;">
                                <div class="mb-3">
                                    <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                    <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn"
                                        placeholder="Masukkan jenis VPN">
                                </div>
                            </div>
                        </div>
                        <!-- Data Type Selection - Changed to Radio Buttons -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Jenis Data</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="data_type" id="provider_type"
                                    value="provider" checked>
                                <h6 class="form-check-label" for="provider_type"> Provider </h6>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="data_type" id="vpn_type"
                                    value="vpn">
                                <h6 class="form-check-label" for="vpn_type">VPN</h6>
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

        {{-- Provider Edit Modals --}}
        @foreach ($dataprovider as $d)
            <div class="modal fade" id="editProviderModal{{ $d->id }}" tabindex="-1"
                aria-labelledby="editProviderModalLabel" aria-hidden="true">
                <form id="editProviderForm" action="{{ route('provider.ProviderPageUpdate', ['id' => $d->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProviderModalLabel">Edit Data Provider</h5>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_provider" class="form-label">Nama Provider</label>
                                    <input type="text" class="form-control" id="nama_provider" name="nama_provider"
                                        value="{{ $d->nama_provider }}">
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

        {{-- VPN Edit Modals --}}
        @foreach ($datavpn as $v)
            <div class="modal fade" id="editVpnModal{{ $v->id }}" tabindex="-1"
                aria-labelledby="editVpnModalLabel" aria-hidden="true">
                <form id="editVpnForm" action="{{ route('vpn.VpnPageUpdate', ['id' => $v->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVpnModalLabel">Edit Data VPN</h5>
                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="jenis_vpn" class="form-label">Jenis VPN</label>
                                    <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn"
                                        value="{{ $v->jenis_vpn }}">
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
            // Get checkboxes
            const providerCheckbox = document.getElementById('provider_type');
            const vpnCheckbox = document.getElementById('vpn_type');

            // Get form containers
            const providerForm = document.getElementById('provider_form');
            const vpnForm = document.getElementById('vpn_form');

            // Get save button
            const saveBtn = document.getElementById('save_btn');

            // Function to ensure only one checkbox is checked at a time
            function handleCheckboxSelection() {
                if (providerCheckbox.checked && vpnCheckbox.checked) {
                    // If both are checked, uncheck the other one
                    if (event.target === providerCheckbox) {
                        vpnCheckbox.checked = false;
                    } else {
                        providerCheckbox.checked = false;
                    }
                }

                // If none are checked, check provider as default
                if (!providerCheckbox.checked && !vpnCheckbox.checked) {
                    providerCheckbox.checked = true;
                }

                toggleForms();
            }

            // Function to toggle forms
            function toggleForms() {
                if (providerCheckbox.checked) {
                    providerForm.style.display = 'block';
                    vpnForm.style.display = 'none';
                    // Clear VPN form
                    document.getElementById('jenis_vpn').value = '';
                } else if (vpnCheckbox.checked) {
                    providerForm.style.display = 'none';
                    vpnForm.style.display = 'block';
                    // Clear Provider form
                    document.getElementById('nama_provider').value = '';
                }
            }

            // Event listeners for checkboxes
            providerCheckbox.addEventListener('change', handleCheckboxSelection);
            vpnCheckbox.addEventListener('change', handleCheckboxSelection);

            // Save button click event (same as before)
            saveBtn.addEventListener('click', function() {
                const isProvider = providerCheckbox.checked;
                let form;

                if (isProvider) {
                    // Create provider form
                    const namaProvider = document.getElementById('nama_provider').value;
                    if (!namaProvider.trim()) {
                        alert('Nama Provider harus diisi!');
                        return;
                    }

                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('provider.ProviderPageStore') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add provider name
                    const nameInput = document.createElement('input');
                    nameInput.type = 'hidden';
                    nameInput.name = 'nama_provider';
                    nameInput.value = namaProvider;
                    form.appendChild(nameInput);

                } else {
                    // Create VPN form
                    const jenisVpn = document.getElementById('jenis_vpn').value;
                    if (!jenisVpn.trim()) {
                        alert('Jenis VPN harus diisi!');
                        return;
                    }

                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('vpn.VpnPageStore') }}';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add VPN type
                    const vpnInput = document.createElement('input');
                    vpnInput.type = 'hidden';
                    vpnInput.name = 'jenis_vpn';
                    vpnInput.value = jenisVpn;
                    form.appendChild(vpnInput);
                }

                // Submit form
                document.body.appendChild(form);
                form.submit();
            });

            // Reset form when modal is closed
            document.getElementById('addDataModal').addEventListener('hidden.bs.modal', function() {
                // Reset checkbox to provider
                providerCheckbox.checked = true;
                vpnCheckbox.checked = false;

                // Reset forms
                document.getElementById('nama_provider').value = '';
                document.getElementById('jenis_vpn').value = '';

                // Show provider form, hide VPN form
                toggleForms();
            });
        });
    </script>

@endsection

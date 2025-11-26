@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2 py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <!-- Left navbar links -->
                        <div class="d-flex justify-content-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" href="#" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">Provider dan Vpn</h1>
                        </div>
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

                <!-- Single Add Button -->
                <div class="row">
                    
                    <!-- Tabel Provider -->
                    <div class="col-md-6">
                        <div class="d-flex mb-3 justify-end">
                            <!-- Export Buttons for Provider -->
                            <div class="d-flex gap-2" id="export-buttons-provider">
                                <button onclick="downloadProviderCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Provider CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadProviderPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download Provider PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>

                        </div>
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

                                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                        {{-- Delete Button --}}
                                                        <button data-toggle="modal"
                                                            data-target="#deleteProviderModal{{ $d->id }}"
                                                            title="Hapus">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    @endif
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
                                            <!-- Edit Modal Provider -->
                                            <div class="modal fade" id="editProviderModal{{ $d->id }}"
                                                tabindex="-1" aria-labelledby="editProviderModalLabel" aria-hidden="true">
                                                <form id="editProviderForm"
                                                    action="{{ route('provider.ProviderPageUpdate', ['id' => $d->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <label class="modal-title"
                                                                    id="editProviderModalLabel">Edit
                                                                    Data Provider</label>
                                                                <button type="button" class="btn-close-custom"
                                                                    data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nama_provider">Nama Provider</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_provider" name="nama_provider"
                                                                        value="{{ $d->nama_provider }}">
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

                        <!-- PAGINATION PROVIDER -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="btn-datakolom">
                                    <form method="GET" class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <select name="per_page" class="form-control form-control-sm pr-2"
                                                style="width: auto;" onchange="this.form.submit()">
                                                <option value="10"
                                                    {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>
                                                    15</option>
                                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>
                                                    20</option>
                                                <option value="all"
                                                    {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                                            </select>
                                            <span>Rows</span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Pagination Navigation -->
                            @if (request('per_page') != 'all' && $dataprovider->lastPage() > 1)
                                <div class="pagination-controls d-flex align-items-center gap-12">
                                    @if ($dataprovider->onFirstPage())
                                        <button class="btn-page" disabled>&laquo; Previous</button>
                                    @else
                                        <button class="btn-datakolom w-auto p-3">
                                            <a href="{{ $dataprovider->appends(request()->query())->previousPageUrl() }}">&laquo;
                                                Previous</a>
                                        </button>
                                    @endif

                                    <span id="page-info">Page {{ $dataprovider->currentPage() }} of
                                        {{ $dataprovider->lastPage() }}</span>

                                    @if ($dataprovider->hasMorePages())
                                        <button class="btn-datakolom w-auto p-3">
                                            <a href="{{ $dataprovider->appends(request()->query())->nextPageUrl() }}">Next
                                                &raquo;</a>
                                        </button>
                                    @else
                                        <button class="btn-page" disabled>Next &raquo;</button>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Tabel VPN -->
                    <div class="col-md-6">
                        <div class="d-flex mb-3 justify-end">
                            <!-- Export Buttons for VPN -->
                            <div class="d-flex gap-2" id="export-buttons-vpn">
                                <button onclick="downloadVpnCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download VPN CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadVpnPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center"
                                    title="Download VPN PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>
                        </div>
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
                                                <td>{{ $v->jenis_vpn }}</td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editVpnModal{{ $v->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editVpnModal{{ $v->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                                        {{-- Delete Button --}}
                                                        <button data-toggle="modal"
                                                            data-target="#deleteVpnModal{{ $v->id }}"
                                                            title="Hapus">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Delete Modal VPN -->
                                            <div class="modal fade" id="deleteVpnModal{{ $v->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah <b>{{ $v->jenis_vpn }}</b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('vpn.VpnPageDestroy', $v->id) }}"
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


                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="btn-datakolom">
                                    <form method="GET" class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <select name="per_page" class="form-control form-control-sm pr-2"
                                                style="width: auto;" onchange="this.form.submit()">
                                                <option value="10"
                                                    {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>
                                                    15</option>
                                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>
                                                    20</option>
                                                <option value="all"
                                                    {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                                            </select>
                                            <span>Rows</span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Pagination Navigation -->
                            @if (request('per_page') != 'all' && $datavpn->lastPage() > 1)
                                <div class="pagination-controls d-flex align-items-center gap-12">
                                    @if ($datavpn->onFirstPage())
                                        <button class="btn-page" disabled>&laquo; Previous</button>
                                    @else
                                        <button class="btn-datakolom w-auto p-3">
                                            <a href="{{ $datavpn->appends(request()->query())->previousPageUrl() }}">&laquo;
                                                Previous</a>
                                        </button>
                                    @endif

                                    <span id="page-info">Page {{ $datavpn->currentPage() }} of
                                        {{ $datavpn->lastPage() }}</span>

                                    @if ($datavpn->hasMorePages())
                                        <button class="btn-datakolom w-auto p-3">
                                            <a href="{{ $datavpn->appends(request()->query())->nextPageUrl() }}">Next
                                                &raquo;</a>
                                        </button>
                                    @else
                                        <button class="btn-page" disabled>Next &raquo;</button>
                                    @endif
                                </div>
                            @endif
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
                                    <input type="checkbox" id="provider_type" checked>
                                    <label for="provider_type">Provider</label>
                                    <input type="checkbox" id="vpn_type">
                                    <label for="vpn_type">VPN</label>
                                </div>
                                <div id="provider_form">
                                    <div class="mb-3">
                                        <label for="nama_provider">Nama Provider</label>
                                        <input type="text" class="form-control" id="nama_provider"
                                            name="nama_provider">
                                    </div>
                                </div>
                                <div id="vpn_form" style="display: none;">
                                    <div class="mb-3">
                                        <label for="jenis_vpn">Jenis VPN</label>
                                        <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn">
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

                {{-- VPN Edit Modals --}}
                @foreach ($datavpn as $v)
                    <div class="modal fade" id="editVpnModal{{ $v->id }}" tabindex="-1"
                        aria-labelledby="editVpnModalLabel" aria-hidden="true">
                        <form id="editVpnForm" action="{{ route('vpn.VpnPageUpdate', ['id' => $v->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <label class="modal-title" id="editVpnModalLabel">Edit Data VPN</label>
                                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="jenis_vpn">Jenis VPN</label>
                                            <input type="text" class="form-control" id="jenis_vpn" name="jenis_vpn"
                                                value="{{ $v->jenis_vpn }}">
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
            </div>
        </section>
    </div>

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- JS Modal tambah Data dan export Data --}}
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


            // Function untuk handle enter key add and edit
            const providerFormInput = providerForm.querySelector('input[name="nama_provider"]');
            const vpnFormInput = vpnForm.querySelector('input[name="jenis_vpn"]');

            if (providerFormInput) {
                providerFormInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click();
                    }
                });
            }

            if (vpnFormInput) {
                vpnFormInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click();
                    }
                });
            }
            // Function Untuk Handle enter key add and edit

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

            // Save button click event
            saveBtn.addEventListener('click', function() {
                const isProvider = providerCheckbox.checked;
                let form;


                if (isProvider) {
                    // Create provider form
                    const namaProviderInput = providerForm.querySelector('input[name="nama_provider"]');
                    const namaProvider = namaProviderInput ? namaProviderInput.value : '';
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
                    const jenisVpnInput = vpnForm.querySelector('input[name="jenis_vpn"]');
                    const jenisVpn = jenisVpnInput ? jenisVpnInput.value : '';
                    // const jenisVpn = document.getElementById('jenis_vpn').value;
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

            // Download functions for Provider
            window.downloadProviderCsv = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('provider.export.provider.list.csv') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadProviderPdf = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('provider.export.provider.list.pdf') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Download functions for VPN
            window.downloadVpnCsv = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('vpn.export.vpn.list.csv') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            window.downloadVpnPdf = function() {
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('vpn.export.vpn.list.pdf') }}';
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Show export buttons if there's data
            if ($(".table").eq(0).find("tbody tr").length > 0) {
                $("#export-buttons-provider").show();
            } else {
                $("#export-buttons-provider").hide();
            }
            if ($(".table").eq(1).find("tbody tr").length > 0) {
                $("#export-buttons-vpn").show();
            } else {
                $("#export-buttons-vpn").hide();
            }
        });
    </script>
@endsection

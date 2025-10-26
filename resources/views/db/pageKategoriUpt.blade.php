@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <!-- Left navbar links -->
                        <div class="d-flex justify-content-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i>
                            </button>
                            <h1 class="headline-large-32 mb-0">Database UPT</h1>
                        </div>
                    </div>
                </div>
        </section>

        <!-- CARD KATEGORI -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kategori PKS -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>PKS</h3>
                            <p class="text-kategori mb-2">Surat Perjanjian Kerja Sama</p>
                            {{-- <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div> --}}
                            <a href="{{ route('dbpks.ListDataPks') }}" class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori SPP -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>SPP</h3>
                            <p class="text-kategori mb-2">Surat Perintah Pemasangan</p>
                            {{-- <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div> --}}
                            <a href="{{ route('spp.ListDataSpp') }}" class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori VPAS -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>VPAS</h3>
                            <p class="text-kategori mb-2">Layanan VPAS</p>
                            {{-- <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div> --}}
                            <a href="{{ route('vpas.ListDataVpas') }}" class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori REGULER -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>REGULER</h3>
                            <p class="text-kategori mb-2">Layanan Reguler</p>
                            {{-- <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div> --}}
                            <a href="{{ route('upt.ListDataReguller') }}" class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <section class="content">


            <div class="container-fluid">
                <div class="row mt-3 mb-3">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="mb-0">Statistik Database UPT</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded p-3 h-100">
                                            <p class="mb-1 text-muted small">Total Data UPT</p>
                                            <h4 class="mb-0 font-weight-bold">{{ number_format($totalUpt) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded p-3 h-100">
                                            <p class="mb-1 text-muted small">Total Extension VPAS</p>
                                            <h4 class="mb-0 font-weight-bold">{{ number_format($totalExtensionVpas) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded p-3 h-100">
                                            <p class="mb-1 text-muted small">Total Extension Reguler</p>
                                            <h4 class="mb-0 font-weight-bold">{{ number_format($totalExtensionReguler) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <h3 class="headline-medium-24">Data UPT Keseluruhan</h3>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="d-flex gap-2" id="export-buttons">
                                <button onclick="downloadCsv()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download CSV">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export CSV
                                </button>
                                <button onclick="downloadPdf()"
                                    class="btn-page d-flex justify-content-center align-items-center" title="Download PDF">
                                    <ion-icon name="download-outline" class="w-6 h-6"></ion-icon> Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="d-flex gap-12 mb-3">
                            <div class="gap-12 w-fit text-center">
                                <div class="d-flex justify-content-center align-items-center gap-12">
                                    <div class="flex-column btn-searchbar column-search">
                                        <input type="date" id="search-tanggal-dari" name="search_tanggal_dari"
                                            title="Tanggal Dari">
                                    </div>
                                    <div class="flex-column btn-searchbar column-search">
                                        <input type="date" id="search-tanggal-sampai" name="search_tanggal_sampai"
                                            title="Tanggal Sampai">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap" id="TableDashboard">
                                <thead>
                                    <tr>
                                        <th class="text-center align-top">
                                            <div class="d-flex flex-column gap-12">
                                                <span>No</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="btn-purple w-auto" onclick="applyFilters()"
                                                        title="Cari Semua Filter">
                                                        <i class="fas fa-search"></i> Cari
                                                    </button>
                                                </div>
                                            </div>
                                        </th>
                                        <th class="align-top">
                                            <div class="d-flex flex-column gap-12">
                                                <span>Nama UPT</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-namaupt" name="search_namaupt">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="align-top">
                                            <div class="d-flex flex-column gap-12">
                                                <span>Kanwil</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-kanwil" name="search_kanwil">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="text-center align-top">
                                            <div
                                                class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                <span>Tipe</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-tipe" name="search_tipe">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="text-center align-top">
                                            <div
                                                class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                <span>Status PKS</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-status-pks" name="search_status_pks">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="text-center align-top">
                                            <div
                                                class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                <span>Status SPP</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-status-spp" name="search_status_spp">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="text-center align-top">
                                            <div
                                                class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                <span>Extension</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-extension" name="search_extension">
                                                </div>
                                            </div>
                                        </th>
                                        <th class="text-center align-top">
                                            <div
                                                class="d-flex justify-content-center align-items-center flex-column gap-12">
                                                <span>Status Wartel</span>
                                                <div class="btn-searchbar column-search">
                                                    <span><i class="fas fa-search"></i></span>
                                                    <input type="text" id="search-status-wartel"
                                                        name="search_status_wartel">
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if (request('per_page') == 'all') {
                                            $no = 1;
                                        } else {
                                            $no = ($data->currentPage() - 1) * $data->perPage() + 1;
                                        }
                                    @endphp
                                    @forelse ($data as $d)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $d->namaupt }}</td>
                                            <td><span class="tag tag-success">{{ $d->kanwil->kanwil }}</span></td>
                                            <td class="text-center">
                                                <span class="
                                                        @if ($d->tipe == 'reguler') Tipereguller
                                                        @elseif($d->tipe == 'vpas') Tipevpas @endif">
                                                    {{ ucfirst($d->tipe) }}
                                                </span>
                                            </td>
                                            <td class="text-center-status">
                                                @php
                                                    $hasPdf1 = $d->uploadFolderPks && !empty($d->uploadFolderPks->uploaded_pdf_1);
                                                    $hasPdf2 = $d->uploadFolderPks && !empty($d->uploadFolderPks->uploaded_pdf_2);
                                                @endphp

                                                @if (!$hasPdf1 && !$hasPdf2)
                                                    <span class="badge body-small-12">Belum Upload</span>
                                                @elseif ($hasPdf1 && $hasPdf2)
                                                    <span class="badge-succes">Sudah Upload (2/2)</span>
                                                @else
                                                    <span class="badge-prosses">Sudah Upload (1/2)</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @php
                                                    $uploadedFolders = 0;
                                                    $totalFolders = 10;
                                                    if ($d->uploadFolderSpp) {
                                                        for ($i = 1; $i <= 10; $i++) {
                                                            $column = 'pdf_folder_' . $i;
                                                            if (!empty($d->uploadFolderSpp->$column)) {
                                                                $uploadedFolders++;
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                @if ($uploadedFolders == 0)
                                                    <span class="badge body-small-12">Belum Upload</span>
                                                @elseif($uploadedFolders == $totalFolders)
                                                    <span class="badge-succes">10/10 Folder</span>
                                                @else
                                                    <span class="badge-prosses">{{ $uploadedFolders }}/{{ $totalFolders }}
                                                        Terupload</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($d->dataOpsional)
                                                    {{ $d->dataOpsional->jumlah_extension ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($d->dataOpsional)
                                                    @if (isset($d->dataOpsional->status_wartel))
                                                        @if ($d->dataOpsional->status_wartel == 1)
                                                            <span class="badge-succes">Aktif</span>
                                                        @else
                                                            <span class="badge body-small-12">Tidak Aktif</span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
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

                    <!-- Custom Pagination -->
                    <!-- Custom Pagination -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="btn-datakolom">
                                <form method="GET" class="d-flex align-items-center">
                                    @if (request('search_namaupt'))
                                        <input type="hidden" name="search_namaupt" value="{{ request('search_namaupt') }}">
                                    @endif
                                    @if (request('search_kanwil'))
                                        <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
                                    @endif
                                    @if (request('search_status_pks'))
                                        <input type="hidden" name="search_status_pks"
                                            value="{{ request('search_status_pks') }}">
                                    @endif
                                    @if (request('search_status_spp'))
                                        <input type="hidden" name="search_status_spp"
                                            value="{{ request('search_status_spp') }}">
                                    @endif
                                    @if (request('search_tanggal_dari'))
                                        <input type="hidden" name="search_tanggal_dari"
                                            value="{{ request('search_tanggal_dari') }}">
                                    @endif
                                    @if (request('search_tanggal_sampai'))
                                        <input type="hidden" name="search_tanggal_sampai"
                                            value="{{ request('search_tanggal_sampai') }}">
                                    @endif
                                    @if (request('search_tipe'))
                                        <input type="hidden" name="search_tipe" value="{{ request('search_tipe') }}">
                                    @endif
                                    @if (request('search_extension'))
                                        <input type="hidden" name="search_extension" value="{{ request('search_extension') }}">
                                    @endif
                                    @if (request('search_status_wartel'))
                                        <input type="hidden" name="search_status_wartel"
                                            value="{{ request('search_status_wartel') }}">
                                    @endif

                                    {{-- ===== TAMBAHKAN INI: Hidden input untuk tab ===== --}}
                                    @if (request('tab'))
                                        <input type="hidden" name="tab" value="{{ request('tab') }}">
                                    @endif
                                    {{-- ===== AKHIR PENAMBAHAN ===== --}}

                                    <div class="d-flex align-items-center">
                                        <select name="per_page" class="form-control form-control-sm pr-2"
                                            style="width: auto;" onchange="this.form.submit()">
                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                            </option>
                                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                                            </option>
                                        </select>
                                        <span>Rows</span>
                                    </div>
                                </form>
                            </div>

                            <div class="text-muted">
                                @if (request('per_page') != 'all')
                                    Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari
                                    {{ $data->total() }} data
                                @else
                                    Menampilkan semua {{ $data->total() }} data
                                @endif
                            </div>
                        </div>

                        @if (request('per_page') != 'all' && $data->lastPage() > 1)
                            <div class="pagination-controls d-flex align-items-center gap-12">
                                @if ($data->onFirstPage())
                                    <button class="btn-page" disabled>&laquo; Previous</button>
                                @else
                                    <button class="btn-datakolom w-auto p-3">
                                        <a href="{{ $data->appends(request()->query())->previousPageUrl() }}">&laquo; Previous</a>
                                    </button>
                                @endif

                                <span id="page-info">Page {{ $data->currentPage() }} of {{ $data->lastPage() }}</span>

                                @if ($data->hasMorePages())
                                    <button class="btn-datakolom w-auto p-3">
                                        <a href="{{ $data->appends(request()->query())->nextPageUrl() }}">Next&raquo;</a>
                                    </button>
                                @else
                                    <button class="btn-page" disabled>Next &raquo;</button>
                                @endif
                            </div>
                        @endif
                    </div>

            </div>
        </section>

    </div>

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function () {
            // ============ FILTER FUNCTIONALITY ============

            // Function to get current filter values
            function getFilters() {
                return {
                    search_namaupt: $('#search-namaupt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_tipe: $('#search-tipe').val().trim(),
                    search_status_pks: $('#search-status-pks').val().trim(),
                    search_status_spp: $('#search-status-spp').val().trim(),
                    search_extension: $('#search-extension').val().trim(),
                    search_status_wartel: $('#search-status-wartel').val().trim(),
                    search_tanggal_dari: $('#search-tanggal-dari').val().trim(),
                    search_tanggal_sampai: $('#search-tanggal-sampai').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect
            window.applyFilters = function () {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove ALL filter parameters first
                url.searchParams.delete('search_namaupt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_status_pks');
                url.searchParams.delete('search_status_spp');
                url.searchParams.delete('search_extension');
                url.searchParams.delete('search_status_wartel');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('page');

                // Add non-empty filters
                Object.keys(filters).forEach(key => {
                    if (filters[key] && filters[key].trim() !== '' && key !== 'per_page') {
                        url.searchParams.set(key, filters[key].trim());
                    }
                });

                window.location.href = url.toString();
            };

            // Clear individual filter when backspace/delete and field becomes empty
            $('.column-search input').on('input', function () {
                if ($(this).val().trim() === '') {
                    let inputName = $(this).attr('name');
                    let url = new URL(window.location.href);

                    if (url.searchParams.has(inputName)) {
                        url.searchParams.delete(inputName);
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    }
                }
            });

            // Function to clear all search filters
            window.clearAllFilters = function () {
                $('#search-namaupt').val('');
                $('#search-kanwil').val('');
                $('#search-tipe').val('');
                $('#search-status-pks').val('');
                $('#search-status-spp').val('');
                $('#search-extension').val('');
                $('#search-status-wartel').val('');
                $('#search-tanggal-dari').val('');
                $('#search-tanggal-sampai').val('');

                let url = new URL(window.location.href);
                url.searchParams.delete('search_namaupt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_status_pks');
                url.searchParams.delete('search_status_spp');
                url.searchParams.delete('search_extension');
                url.searchParams.delete('search_status_wartel');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('page');

                window.location.href = url.toString();
            };

            // Enter key to search
            $('.column-search input').on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    applyFilters();
                }
            });

            // ============ EXPORT FUNCTIONALITY ============

            // Download CSV function
            window.downloadCsv = function () {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('database.DbUpt.export.csv') }}';
                form.target = '_blank';

                Object.keys(filters).forEach(key => {
                    if (filters[key] && key !== 'per_page') {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = filters[key];
                        form.appendChild(input);
                    }
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // Download PDF function
            window.downloadPdf = function () {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('database.DbUpt.export.pdf') }}';
                form.target = '_blank';

                Object.keys(filters).forEach(key => {
                    if (filters[key] && key !== 'per_page') {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = filters[key];
                        form.appendChild(input);
                    }
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            };

            // ============ LOAD FILTER VALUES FROM URL ============
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get('search_namaupt')) {
                $('#search-namaupt').val(urlParams.get('search_namaupt'));
            }
            if (urlParams.get('search_kanwil')) {
                $('#search-kanwil').val(urlParams.get('search_kanwil'));
            }
            if (urlParams.get('search_tipe')) {
                $('#search-tipe').val(urlParams.get('search_tipe'));
            }
            if (urlParams.get('search_status_pks')) {
                $('#search-status-pks').val(urlParams.get('search_status_pks'));
            }
            if (urlParams.get('search_status_spp')) {
                $('#search-status-spp').val(urlParams.get('search_status_spp'));
            }
            if (urlParams.get('search_extension')) {
                $('#search-extension').val(urlParams.get('search_extension'));
            }
            if (urlParams.get('search_status_wartel')) {
                $('#search-status-wartel').val(urlParams.get('search_status_wartel'));
            }
            if (urlParams.get('search_tanggal_dari')) {
                $('#search-tanggal-dari').val(urlParams.get('search_tanggal_dari'));
            }
            if (urlParams.get('search_tanggal_sampai')) {
                $('#search-tanggal-sampai').val(urlParams.get('search_tanggal_sampai'));
            }

            // Show export buttons if there's data
            if ($("#TableDashboard tbody tr").length > 0 && !$("#TableDashboard tbody tr").find('td[colspan="8"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }
        });
    </script>
    </div>


@endsection

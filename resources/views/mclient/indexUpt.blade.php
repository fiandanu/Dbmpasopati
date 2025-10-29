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
                            <h1 class="headline-large-32 mb-0">Komplain UPT</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kategori VPAS -->
                    <div class="col-xl col-lg-4 col-md-6 mb-3">
                        <div class="card-kategori">
                            <h3>Komplain VPAS</h3>
                            <p class="text-kategori mb-2">Layanan VPAS</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ number_format($totalVpas) }} Data
                            </div>
                            <a href="{{ route('mcvpas.ListDataMclientVpas') }}" class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori Reguller -->
                    <div class="col-xl col-lg-4 col-md-6 mb-3">
                        <div class="card-kategori">
                            <h3>Komplain Reguller</h3>
                            <p class="text-kategori mb-2">Layanan Reguller</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ number_format($totalReguler) }} Data
                            </div>
                            <a href="{{ route('mcreguler.ListDataMclientReguller') }}" class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori Kunjungan UPT -->
                    <div class="col-xl col-lg-4 col-md-6 mb-3">
                        <div class="card-kategori">
                            <h3>Kunjungan UPT</h3>
                            <p class="text-kategori mb-2">Kunjungan Monitoring Client</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ number_format($totalKunjungan) }} Data
                            </div>
                            <a href="{{ route('mclientkunjunganupt.ListDataMclientKunjungan') }}"
                                class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori Pengiriman Alat UPT -->
                    <div class="col-xl col-lg-4 col-md-6 mb-3">
                        <div class="card-kategori">
                            <h3>Pengiriman Alat UPT</h3>
                            <p class="text-kategori mb-2">Layanan Pengiriman Alat UPT</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ number_format($totalPengiriman) }} Data
                            </div>
                            <a href="{{ route('mclientpengirimanupt.ListDataMclientPengirimanUpt') }}"
                                class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Kategori Setting Alat UPT -->
                    <div class="col-xl col-lg-4 col-md-6 mb-3">
                        <div class="card-kategori">
                            <h3>Setting Alat UPT</h3>
                            <p class="text-kategori mb-2">Layanan Setting Alat UPT</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ number_format($totalSettingAlat) }} Data
                            </div>
                            <a href="{{ route('mclientsettingalatupt.ListDataMclientSettingAlat') }}"
                                class="list-button">Selengkapnya</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <h3 class="headline-medium-24">Data Monitoring Client Keseluruhan</h3>
                    </div>
                </div>

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

                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="TableMonitoringClient">
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
                                                <input type="text" id="search-nama-upt" name="search_nama_upt">
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
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Jenis Kendala / Keterangan</span>
                                            <div class="btn-searchbar column-search">
                                                <span><i class="fas fa-search"></i></span>
                                                <input type="text" id="search-jenis-kendala"
                                                    name="search_jenis_kendala">
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Menu</span>
                                            <div class="btn-searchbar column-search">
                                                <select id="search-jenis-layanan" name="search_jenis_layanan">
                                                    <option value="">Semua</option>
                                                    <option value="Komplain Vpas">Komplain Vpas</option>
                                                    <option value="Komplain Reguler">Komplain Reguler</option>
                                                    <option value="Kunjungan">Kunjungan Upt</option>
                                                    <option value="Pengiriman Alat">Pengiriman Alat</option>
                                                    <option value="Setting Alat">Setting Alat</option>
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Tipe</span>
                                            <div class="btn-searchbar column-search">
                                                <select id="search-tipe" name="search_tipe">
                                                    <option value="">Semua</option>
                                                    <option value="vpas">Vpas</option>
                                                    <option value="reguler">Reguler</option>
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center align-top">
                                        <div class="d-flex justify-content-center align-items-center flex-column gap-12">
                                            <span>Status</span>
                                            <div class="btn-searchbar column-search">
                                                <select id="search-status" name="search_status">
                                                    <option value="">Semua</option>
                                                    <option value="belum ditentukan">Belum ditentukan</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="proses">Proses</option>
                                                    <option value="terjadwal">Terjadwal</option>
                                                    <option value="selesai">Selesai</option>
                                                </select>
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
                                        <td>{{ $d['nama_upt'] }}</td>
                                        <td><span class="tag tag-success">{{ $d['kanwil'] }}</span></td>
                                        <td class="text-center">
                                            <span class="Tipereguller">
                                                {{ Str::limit($d['jenis_kendala'], 30) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $layananClass = match (strtolower($d['jenis_layanan'] ?? '')) {
                                                    default => 'Tipereguller',
                                                };
                                            @endphp
                                            <span class="{{ $layananClass }}">
                                                {{ $d['jenis_layanan'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="@if ($d['tipe'] == 'reguler') Tipereguller @elseif($d['tipe'] == 'vpas') Tipevpas @endif">
                                                {{ ucfirst($d['tipe']) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = match (strtolower($d['status'] ?? '')) {
                                                    'selesai' => 'badge-succes',
                                                    'proses' => 'badge-prosses',
                                                    'pending' => 'badge-danger',
                                                    'terjadwal' => 'badge-prosses',
                                                    default => 'badge-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($d['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-datakolom">
                            <form method="GET" class="d-flex align-items-center">
                                @if (request('search_nama_upt'))
                                    <input type="hidden" name="search_nama_upt"
                                        value="{{ request('search_nama_upt') }}">
                                @endif
                                @if (request('search_kanwil'))
                                    <input type="hidden" name="search_kanwil" value="{{ request('search_kanwil') }}">
                                @endif
                                @if (request('search_tipe'))
                                    <input type="hidden" name="search_tipe" value="{{ request('search_tipe') }}">
                                @endif
                                @if (request('search_jenis_layanan'))
                                    <input type="hidden" name="search_jenis_layanan"
                                        value="{{ request('search_jenis_layanan') }}">
                                @endif
                                @if (request('search_jenis_kendala'))
                                    <input type="hidden" name="search_jenis_kendala"
                                        value="{{ request('search_jenis_kendala') }}">
                                @endif
                                @if (request('search_status'))
                                    <input type="hidden" name="search_status" value="{{ request('search_status') }}">
                                @endif
                                @if (request('search_tanggal_dari'))
                                    <input type="hidden" name="search_tanggal_dari"
                                        value="{{ request('search_tanggal_dari') }}">
                                @endif
                                @if (request('search_tanggal_sampai'))
                                    <input type="hidden" name="search_tanggal_sampai"
                                        value="{{ request('search_tanggal_sampai') }}">
                                @endif

                                <div class="d-flex align-items-center">
                                    <select name="per_page" class="form-control form-control-sm pr-2"
                                        style="width: auto;" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15
                                        </option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20
                                        </option>
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
                                    <a href="{{ $data->appends(request()->query())->previousPageUrl() }}">&laquo;
                                        Previous</a>
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

    {{-- SEARCH BY KOLOM FILTER --}}
    <script>
        $(document).ready(function() {
            // Function to get current filter values
            function getFilters() {
                return {
                    search_nama_upt: $('#search-nama-upt').val().trim(),
                    search_kanwil: $('#search-kanwil').val().trim(),
                    search_tipe: $('#search-tipe').val().trim(),
                    search_jenis_layanan: $('#search-jenis-layanan').val().trim(),
                    search_jenis_kendala: $('#search-jenis-kendala').val().trim(),
                    search_status: $('#search-status').val().trim(),
                    search_tanggal_dari: $('#search-tanggal-dari').val().trim(),
                    search_tanggal_sampai: $('#search-tanggal-sampai').val().trim(),
                    per_page: $('select[name="per_page"]').val()
                };
            }

            // Function to apply filters and redirect
            window.applyFilters = function() {
                let filters = getFilters();
                let url = new URL(window.location.href);

                // Remove ALL filter parameters first
                url.searchParams.delete('search_nama_upt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_jenis_layanan');
                url.searchParams.delete('search_jenis_kendala');
                url.searchParams.delete('search_status');
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
            $('.column-search input').on('input', function() {
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
            window.clearAllFilters = function() {
                $('#search-nama-upt').val('');
                $('#search-kanwil').val('');
                $('#search-tipe').val('');
                $('#search-jenis-layanan').val('');
                $('#search-jenis-kendala').val('');
                $('#search-status').val('');
                $('#search-tanggal-dari').val('');
                $('#search-tanggal-sampai').val('');

                let url = new URL(window.location.href);
                url.searchParams.delete('search_nama_upt');
                url.searchParams.delete('search_kanwil');
                url.searchParams.delete('search_tipe');
                url.searchParams.delete('search_jenis_layanan');
                url.searchParams.delete('search_jenis_kendala');
                url.searchParams.delete('search_status');
                url.searchParams.delete('search_tanggal_dari');
                url.searchParams.delete('search_tanggal_sampai');
                url.searchParams.delete('page');

                window.location.href = url.toString();
            };

            // Enter key to search
            $('.column-search input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    applyFilters();
                }
            });

            // Dropdown change to search
            $('#search-tipe, #search-jenis-layanan, #search-status').on('change', function() {
                applyFilters();
            });

            // Download CSV function
            window.downloadCsv = function() {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('mclient.upt.export.csv') }}';
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
            window.downloadPdf = function() {
                let filters = getFilters();
                let form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('mclient.upt.export.pdf') }}';
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

            // Load filter values from URL on page load
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get('search_nama_upt')) {
                $('#search-nama-upt').val(urlParams.get('search_nama_upt'));
            }
            if (urlParams.get('search_kanwil')) {
                $('#search-kanwil').val(urlParams.get('search_kanwil'));
            }
            if (urlParams.get('search_tipe')) {
                $('#search-tipe').val(urlParams.get('search_tipe'));
            }
            if (urlParams.get('search_jenis_layanan')) {
                $('#search-jenis-layanan').val(urlParams.get('search_jenis_layanan'));
            }
            if (urlParams.get('search_jenis_kendala')) {
                $('#search-jenis-kendala').val(urlParams.get('search_jenis_kendala'));
            }
            if (urlParams.get('search_status')) {
                $('#search-status').val(urlParams.get('search_status'));
            }
            if (urlParams.get('search_tanggal_dari')) {
                $('#search-tanggal-dari').val(urlParams.get('search_tanggal_dari'));
            }
            if (urlParams.get('search_tanggal_sampai')) {
                $('#search-tanggal-sampai').val(urlParams.get('search_tanggal_sampai'));
            }

            // Show export buttons if there's data
            if ($("#TableMonitoringClient tbody tr").length > 0 && !$("#TableMonitoringClient tbody tr").find(
                    'td[colspan="7"]').length) {
                $("#export-buttons").show();
            } else {
                $("#export-buttons").hide();
            }
        });
    </script>

@endsection

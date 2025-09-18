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
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">List Data UPT Reguller</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Search bar -->
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search All">
                            </div>
                            <!-- Export Buttons -->
                            <div class="d-flex gap-2" id="export-buttons" style="display: none;">
                                <a href="#" onclick="downloadCsv()" class="btn btn-success btn-sm"
                                    title="Download CSV">
                                    <i class="fas fa-file-csv"></i> CSV
                                </a>
                                <a href="#" onclick="downloadPdf()" class="btn btn-primary btn-sm"
                                    title="Download PDF">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alert messages tetap sama -->
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
                @if (request('table_search'))
                    <div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Hasil pencarian untuk: "<strong>{{ request('table_search') }}</strong>"
                            <a href="{{ route('ListDataReguller') }}" class="btn btn-sm btn-secondary ml-2">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="Table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama UPT
                                        <input type="text" class="form-control column-search" id="search-namaupt"
                                            name="search_namaupt" placeholder="Search Nama UPT">
                                    </th>
                                    <th>Kanwil
                                        <input type="text" class="form-control column-search" id="search-kanwil"
                                            name="search_kanwil" placeholder="Search Kanwil">
                                    </th>
                                    <th class="text-center">Tipe
                                        <input type="text" class="form-control column-search" id="search-tipe"
                                            name="search_tipe" placeholder="Search Tipe">
                                    </th>
                                    <th class="text-center">Tanggal Dibuat
                                        <input type="text" class="form-control column-search" id="search-tanggal"
                                            name="search_tanggal" placeholder="Search Tanggal">
                                    </th>
                                    <th class="text-center">Status Update
                                        <input type="text" class="form-control column-search" id="search-status"
                                            name="search_status" placeholder="Search Status">
                                    </th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $d->namaupt }}</td>
                                        <td><span class="tag tag-success">{{ $d->kanwil }}</span></td>
                                        <td class="text-center">
                                            <span class="Tipereguller">{{ ucfirst($d->tipe) }}</span>
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('M d Y') }}
                                        </td>
                                        <td class="d-flex justify-center align-items-center">
                                            @php
                                                $dataOpsional = $d->dataOpsional;
                                                $optionalFields = [
                                                    'pic_upt',
                                                    'no_telpon',
                                                    'alamat',
                                                    'jumlah_wbp',
                                                    'jumlah_line',
                                                    'provider_internet',
                                                    'kecepatan_internet',
                                                    'tarif_wartel',
                                                    'status_wartel',
                                                    'akses_topup_pulsa',
                                                    'password_topup',
                                                    'akses_download_rekaman',
                                                    'password_download',
                                                    'internet_protocol',
                                                    'vpn_user',
                                                    'vpn_password',
                                                    'jenis_vpn',
                                                    'jumlah_extension',
                                                    'no_extension',
                                                    'extension_password',
                                                    'pin_tes',
                                                ];
                                                $filledFields = 0;
                                                if ($dataOpsional) {
                                                    foreach ($optionalFields as $field) {
                                                        if (!empty($dataOpsional->$field)) {
                                                            $filledFields++;
                                                        }
                                                    }
                                                }
                                                $totalFields = count($optionalFields);
                                                $percentage =
                                                    $totalFields > 0 ? ($filledFields / $totalFields) * 100 : 0;
                                            @endphp
                                            @if ($filledFields == 0)
                                                <span class="badge body-small-12">Belum di Update</span>
                                            @elseif($filledFields == $totalFields)
                                                <span class="badge-succes">Sudah Update</span>
                                            @else
                                                <span class="badge-prosses">Sebagian ({{ round($percentage) }}%)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button href="#editModal{{ $d->id }}" data-toggle="modal"
                                                data-target="#editModal{{ $d->id }}" title="Edit">
                                                <ion-icon name="pencil-outline"></ion-icon>
                                            </button>
                                            <a href="{{ route('export.upt.pdf', $d->id) }}" title="Unduh PDF">
                                                <button>
                                                    <ion-icon name="document-outline"></ion-icon>
                                                </button>
                                            </a>
                                            <a href="{{ route('export.upt.csv', $d->id) }}" title="Unduh CSV">
                                                <button>
                                                    <ion-icon name="document-text-outline"></ion-icon>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($data->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>Tidak ada data UPT yang tersedia</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination Controls - Same as Ponpes -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Row limit -->
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

                    <!-- Pagination -->
                    <div class="pagination-controls d-flex align-items-center gap-12">
                        <button class="btn-page" id="prev-page" disabled>&laquo; Previous</button>
                        <span id="page-info">Page 1 of 5</span>
                        <button class="btn-page" id="next-page">Next &raquo;</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal edit tetap sama -->
        @foreach ($data as $d)
            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                <form id="editForm{{ $d->id }}" action="{{ route('ListUpdateReguller', ['id' => $d->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit Data</label>
                                <button type="button" class="btn-close-custom" data-dismiss="modal" aria-label="Close">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $d->id }}">
                                @php
                                    $dataOpsional = $d->dataOpsional;
                                @endphp
                                <!-- Data Wajib Section -->
                                <div class="mb-4">
                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                        <h5>Data Wajib</h5>
                                    </div>
                                    <div class="mb-3">
                                        <label for="namaupt{{ $d->id }}" class="form-label">Nama UPT</label>
                                        <input type="text" class="form-control" id="namaupt{{ $d->id }}"
                                            name="namaupt" value="{{ $d->namaupt }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kanwil{{ $d->id }}" class="form-label">Kanwil</label>
                                        <input type="text" class="form-control" id="kanwil{{ $d->id }}"
                                            name="kanwil" value="{{ $d->kanwil }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipe{{ $d->id }}" class="form-label">Tipe</label>
                                        <input type="text" class="form-control" id="tipe{{ $d->id }}"
                                            name="tipe" value="{{ ucfirst($d->tipe) }}" readonly>
                                    </div>
                                </div>
                                <!-- Data Opsional Section -->
                                <div class="mb-4">
                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                        <h5>Data Opsional</h5>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pic_upt{{ $d->id }}" class="form-label">PIC UPT</label>
                                        <input type="text" class="form-control" id="pic_upt{{ $d->id }}"
                                            name="pic_upt" value="{{ $dataOpsional->pic_upt ?? '' }}"
                                            placeholder="Masukkan nama PIC UPT">
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_telpon{{ $d->id }}" class="form-label">No Telepon</label>
                                        <input type="text" class="form-control" id="no_telpon{{ $d->id }}"
                                            name="no_telpon" value="{{ $dataOpsional->no_telpon ?? '' }}"
                                            placeholder="Masukkan nomor telepon">
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat{{ $d->id }}" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat{{ $d->id }}"
                                            name="alamat" value="{{ $dataOpsional->alamat ?? '' }}"
                                            placeholder="Masukkan alamat lengkap">
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah_wbp{{ $d->id }}" class="form-label">Jumlah WBP</label>
                                        <input type="number" class="form-control" id="jumlah_wbp{{ $d->id }}"
                                            name="jumlah_wbp" value="{{ $dataOpsional->jumlah_wbp ?? '' }}"
                                            placeholder="Masukkan jumlah WBP">
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah_line{{ $d->id }}" class="form-label">Jumlah Line
                                            Reguler Terpasang</label>
                                        <input type="number" class="form-control" id="jumlah_line{{ $d->id }}"
                                            name="jumlah_line" value="{{ $dataOpsional->jumlah_line ?? '' }}"
                                            placeholder="Masukkan jumlah line reguler">
                                    </div>
                                    <div class="mb-3">
                                        <label for="provider_internet{{ $d->id }}" class="form-label">Provider
                                            Internet</label>
                                        <select class="form-control" id="provider_internet{{ $d->id }}"
                                            name="provider_internet">
                                            <option value="">-- Pilih Provider --</option>
                                            @foreach ($providers as $p)
                                                <option value="{{ $p->nama_provider }}"
                                                    {{ ($dataOpsional->provider_internet ?? '') == $p->nama_provider ? 'selected' : '' }}>
                                                    {{ $p->nama_provider }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kecepatan_internet{{ $d->id }}" class="form-label">Kecepatan
                                            Internet (Mbps)</label>
                                        <input type="text" class="form-control"
                                            id="kecepatan_internet{{ $d->id }}" name="kecepatan_internet"
                                            value="{{ $dataOpsional->kecepatan_internet ?? '' }}"
                                            placeholder="Contoh: 20 Mbps">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tarif_wartel{{ $d->id }}" class="form-label">Tarif Wartel
                                            Reguler</label>
                                        <input type="text" class="form-control" id="tarif_wartel{{ $d->id }}"
                                            name="tarif_wartel" value="{{ $dataOpsional->tarif_wartel ?? '' }}"
                                            placeholder="Contoh: 2000">
                                    </div>
                                    <div class="mb-3">
                                        <label for="status_wartel{{ $d->id }}" class="form-label">Status
                                            Wartel</label>
                                        <select class="form-control" id="status_wartel{{ $d->id }}"
                                            name="status_wartel">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1"
                                                {{ ($dataOpsional->status_wartel ?? 0) == 1 ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="0"
                                                {{ ($dataOpsional->status_wartel ?? 0) == 0 ? 'selected' : '' }}>Tidak
                                                Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- IMC PAS Section -->
                                <div class="mb-4">
                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                        <h5>IMC PAS</h5>
                                    </div>
                                    <div class="mb-3">
                                        <label for="akses_topup_pulsa{{ $d->id }}" class="form-label">Akses Top Up
                                            Pulsa</label>
                                        <input type="text" class="form-control"
                                            id="akses_topup_pulsa{{ $d->id }}" name="akses_topup_pulsa"
                                            value="{{ $dataOpsional->akses_topup_pulsa ?? '' }}"
                                            placeholder="Masukkan akses top up pulsa">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_topup{{ $d->id }}" class="form-label">Password Top Up
                                            Pulsa</label>
                                        <input type="text" class="form-control"
                                            id="password_topup{{ $d->id }}" name="password_topup"
                                            value="{{ $dataOpsional->password_topup ?? '' }}"
                                            placeholder="Masukkan password top up">
                                    </div>
                                    <div class="mb-3">
                                        <label for="akses_download_rekaman{{ $d->id }}" class="form-label">Akses
                                            Download Rekaman</label>
                                        <input type="text" class="form-control"
                                            id="akses_download_rekaman{{ $d->id }}" name="akses_download_rekaman"
                                            value="{{ $dataOpsional->akses_download_rekaman ?? '' }}"
                                            placeholder="Masukkan akses download rekaman">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_download{{ $d->id }}" class="form-label">Password
                                            Download Rekaman</label>
                                        <input type="text" class="form-control"
                                            id="password_download{{ $d->id }}" name="password_download"
                                            value="{{ $dataOpsional->password_download ?? '' }}"
                                            placeholder="Masukkan password download">
                                    </div>
                                </div>
                                <!-- Akses VPN Section -->
                                <div class="mb-4">
                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                        <h5>Akses VPN</h5>
                                    </div>
                                    <div class="mb-3">
                                        <label for="internet_protocol{{ $d->id }}" class="form-label">Internet
                                            Protocol</label>
                                        <input type="text" class="form-control"
                                            id="internet_protocol{{ $d->id }}" name="internet_protocol"
                                            value="{{ $dataOpsional->internet_protocol ?? '' }}"
                                            placeholder="Masukkan alamat IP atau domain">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vpn_user{{ $d->id }}" class="form-label">User VPN</label>
                                        <input type="text" class="form-control" id="vpn_user{{ $d->id }}"
                                            name="vpn_user" value="{{ $dataOpsional->vpn_user ?? '' }}"
                                            placeholder="Masukkan username VPN">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vpn_password{{ $d->id }}" class="form-label">Password
                                            VPN</label>
                                        <input type="text" class="form-control" id="vpn_password{{ $d->id }}"
                                            name="vpn_password" value="{{ $dataOpsional->vpn_password ?? '' }}"
                                            placeholder="Masukkan password VPN">
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis_vpn{{ $d->id }}" class="form-label">Jenis VPN</label>
                                        <select class="form-control" id="jenis_vpn{{ $d->id }}" name="jenis_vpn">
                                            <option value="">-- Pilih Jenis VPN --</option>
                                            @if (isset($vpns) && $vpns->count() > 0)
                                                @foreach ($vpns as $p)
                                                    <option value="{{ $p->jenis_vpn }}"
                                                        {{ ($dataOpsional->jenis_vpn ?? '') == $p->jenis_vpn ? 'selected' : '' }}>
                                                        {{ $p->jenis_vpn }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>Tidak ada data VPN tersedia</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <!-- Extension Reguler -->
                                <div class="mb-4">
                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                        <h5>Extension Reguler</h5>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah_extension{{ $d->id }}" class="form-label">Jumlah
                                            Extension</label>
                                        <input type="number" class="form-control"
                                            id="jumlah_extension{{ $d->id }}" name="jumlah_extension"
                                            value="{{ $dataOpsional->jumlah_extension ?? '' }}"
                                            placeholder="Masukkan jumlah">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pin_tes{{ $d->id }}" class="form-label">Pin Test</label>
                                        <input type="text" class="form-control" id="pin_tes{{ $d->id }}"
                                            name="pin_tes" value="{{ $dataOpsional->pin_tes ?? '' }}"
                                            placeholder="Masukkan Pin Test">
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_extension{{ $d->id }}" class="form-label">No
                                            Extension</label>
                                        <small class="text-muted d-block mb-2">Masukkan setiap nomor extension pada baris
                                            terpisah</small>
                                        <textarea class="form-control" id="no_extension{{ $d->id }}" name="no_extension" rows="6"
                                            placeholder="Contoh:&#10;101&#10;102&#10;103">{{ $dataOpsional->no_extension ?? '' }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="extension_password{{ $d->id }}" class="form-label">Password
                                            Extension</label>
                                        <small class="text-muted d-block mb-2">Masukkan setiap password extension pada
                                            baris terpisah (sesuai urutan nomor extension di atas)</small>
                                        <textarea class="form-control" id="extension_password{{ $d->id }}" name="extension_password" rows="6"
                                            placeholder="Contoh:&#10;password1&#10;password2&#10;password3">{{ $dataOpsional->extension_password ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-cancel-modal" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn-purple">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach

    </div>

    <!-- jQuery Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Updated Search and Pagination JavaScript -->
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
        $("#page-info").text(`Page ${currentPage} of ${totalPages}`);
        $("#prev-page").prop("disabled", currentPage === 1);
        $("#next-page").prop("disabled", currentPage === totalPages);
    }

    updateTable();

    $("#row-limit").on("change", function() {
        limit = parseInt($(this).val());
        currentPage = 1;
        totalPages = Math.ceil($rows.length / limit);
        updateTable();
    });

    $("#prev-page").on("click", function() {
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });

    $("#next-page").on("click", function() {
        if (currentPage < totalPages) {
            currentPage++;
            updateTable();
        }
    });

    // FIXED: Function to get current filter values
    function getFilters() {
        return {
            table_search: $('#btn-search').val().trim(),
            search_namaupt: $('#search-namaupt').val().trim(),
            search_kanwil: $('#search-kanwil').val().trim(),
            search_tipe: $('#search-tipe').val().trim(),
            search_tanggal: $('#search-tanggal').val().trim(),
            search_status: $('#search-status').val().trim()
        };
    }

    // FIXED: Function to download CSV with all filters
    window.downloadCsv = function() {
        let filters = getFilters();
        let form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("export.list.csv") }}';
        form.target = '_blank';

        Object.keys(filters).forEach(key => {
            if (filters[key]) {
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

    // FIXED: Function to download PDF with all filters
    window.downloadPdf = function() {
        let filters = getFilters();
        let form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("export.list.pdf") }}';
        form.target = '_blank';

        Object.keys(filters).forEach(key => {
            if (filters[key]) {
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

    // Show/hide export buttons based on visible rows
    function toggleExportButtons() {
        const visibleRows = $("#Table tbody tr:visible").length;
        const noDataRow = $("#Table tbody tr:visible").find('td[colspan="7"]').length;
        
        // Show export buttons only if there are data rows (not just "no data" message)
        if (visibleRows > 0 && noDataRow === 0) {
            $("#export-buttons").show();
        } else {
            $("#export-buttons").hide();
        }
    }

    // FIXED: General Search with proper URL parameter handling
    $("#btn-search").on("keyup", function() {
        let value = $(this).val().toLowerCase().trim();
        
        $("#Table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        
        const $visibleRows = $("#Table tbody tr:visible");
        const noDataVisible = $visibleRows.find('td[colspan="7"]').length > 0;
        
        totalPages = Math.ceil($visibleRows.length / limit);
        currentPage = 1;
        
        if (value === '') {
            updateTable();
        } else {
            let resultCount = noDataVisible ? 0 : $visibleRows.length;
            $("#page-info").text(`Showing ${resultCount} results`);
            $("#prev-page").prop("disabled", true);
            $("#next-page").prop("disabled", true);
        }
        toggleExportButtons();
    });

    // FIXED: Column-specific Search with better filtering
    $(".column-search").on("keyup", function() {
        let filters = {
            namaupt: $("#search-namaupt").val().toLowerCase().trim(),
            kanwil: $("#search-kanwil").val().toLowerCase().trim(),
            tipe: $("#search-tipe").val().toLowerCase().trim(),
            tanggal: $("#search-tanggal").val().toLowerCase().trim(),
            status: $("#search-status").val().toLowerCase().trim()
        };

        $("#Table tbody tr").filter(function() {
            let $cells = $(this).find("td");
            
            // Skip the "no data" row
            if ($cells.eq(0).attr('colspan')) {
                $(this).hide();
                return false;
            }
            
            let matches = true;

            if (filters.namaupt) {
                matches = matches && $cells.eq(1).text().toLowerCase().indexOf(filters.namaupt) > -1;
            }
            if (filters.kanwil) {
                matches = matches && $cells.eq(2).text().toLowerCase().indexOf(filters.kanwil) > -1;
            }
            if (filters.tipe) {
                matches = matches && $cells.eq(3).text().toLowerCase().indexOf(filters.tipe) > -1;
            }
            if (filters.tanggal) {
                matches = matches && $cells.eq(4).text().toLowerCase().indexOf(filters.tanggal) > -1;
            }
            if (filters.status) {
                matches = matches && $cells.eq(5).text().toLowerCase().indexOf(filters.status) > -1;
            }

            $(this).toggle(matches);
        });

        const $visibleRows = $("#Table tbody tr:visible");
        const noDataVisible = $visibleRows.find('td[colspan="7"]').length > 0;
        
        totalPages = Math.ceil($visibleRows.length / limit);
        currentPage = 1;
        
        // Check if any filters are active
        let hasActiveFilters = Object.values(filters).some(filter => filter !== '');
        
        if (hasActiveFilters) {
            let resultCount = noDataVisible ? 0 : $visibleRows.length;
            $("#page-info").text(`Showing ${resultCount} results`);
            $("#prev-page").prop("disabled", true);
            $("#next-page").prop("disabled", true);
        } else {
            updateTable();
        }
        toggleExportButtons();
    });

    // Initial toggle
    toggleExportButtons();

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

    // FIXED: Preserve filter values from URL parameters on page load
    $(window).on('load', function() {
        // Set search values from URL parameters if they exist
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.get('table_search')) {
            $('#btn-search').val(urlParams.get('table_search'));
        }
        if (urlParams.get('search_namaupt')) {
            $('#search-namaupt').val(urlParams.get('search_namaupt'));
        }
        if (urlParams.get('search_kanwil')) {
            $('#search-kanwil').val(urlParams.get('search_kanwil'));
        }
        if (urlParams.get('search_tipe')) {
            $('#search-tipe').val(urlParams.get('search_tipe'));
        }
        if (urlParams.get('search_tanggal')) {
            $('#search-tanggal').val(urlParams.get('search_tanggal'));
        }
        if (urlParams.get('search_status')) {
            $('#search-status').val(urlParams.get('search_status'));
        }
        
        // Trigger search if there are parameters
        if (urlParams.toString()) {
            $('.column-search').trigger('keyup');
        }
        
        // Initial export button state
        toggleExportButtons();
    });
});
</script>

@endsection

@extends('layout.sidebar')
@section('content')

    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        {{-- Left Navbar Links --}}
                        <div class="d-flex justify-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i>
                            </button>
                            <h1 class="headline-large-32 mb-0">List Data UPT Vpas</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- seacrh bar --}}
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="table_search" id="btn-search" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- Tampilkan pesan sukses total --}}
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

        {{-- Tampilkan pesan sukses parsial --}}
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

        {{-- Tampilkan error sistem --}}
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
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        @if (request('table_search'))
                            <div class="">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Hasil pencarian untuk: "<strong>{{ request('table_search') }}</strong>"
                                    <a href="{{ route('ListDataVpas') }}" class="btn btn-sm btn-secondary ml-2">
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
                                            <th>Nama UPT</th>
                                            <th>Kanwil</th>
                                            <th class="text-center">Tipe</th>
                                            <th class="text-center">Tanggal Dibuat</th>
                                            <th class="text-center">Status Update</th>
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
                                                    <span class="Tipevpas">{{ ucfirst($d->tipe) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        // Cek apakah data opsional sudah diisi - sesuaikan dengan relasi
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
                                                        <span class="badge-succes">Sudah Update
                                                        </span>
                                                    @else
                                                        <span class="badge-prosses">Sebagian
                                                            ({{ round($percentage) }}%)
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- Edit Button --}}
                                                    <button href="#editModal{{ $d->id }}" data-toggle="modal"
                                                        data-target="#editModal{{ $d->id }}" title="Edit">
                                                        <ion-icon name="pencil-outline"></ion-icon>
                                                    </button>

                                                    {{-- Unduh PDF Button --}}
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

                                                    {{-- Delete Button --}}
                                                    {{-- <button data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}">
                                                        <ion-icon name="trash-outline"></ion-icon></button> --}}
                                                </td>
                                            </tr>

                                            {{-- Delete Modal --}}
                                            {{-- <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <p>Apakah <b>{{ $d->namaupt }}</b> ingin dihapus?</p>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('DataBasePageDestroy', $d->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn-delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div> --}}
                                        @endforeach

                                        {{-- Tampilkan pesan jika tidak ada data VPAS --}}
                                        @if ($data->count() == 0)
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                        <p>Tidak ada data VPAS yang tersedia</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- Index Form Html --}}

                            {{-- User Edit Modal --}}
                            @foreach ($data as $d)
                                {{-- User Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                                    <form id="editForm{{ $d->id }}"
                                        action="{{ route('ListUpdateVpas', ['id' => $d->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <label class="modal-title"
                                                        id="editModalLabel{{ $d->id }}">Edit
                                                        Data VPAS</label>
                                                    <button type="button" class="btn-close-custom" data-dismiss="modal"
                                                        aria-label="Close">
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
                                                            <label for="namaupt{{ $d->id }}"
                                                                class="form-label">Nama UPT</label>
                                                            <input type="text" class="form-control"
                                                                id="namaupt{{ $d->id }}" name="namaupt"
                                                                value="{{ $d->namaupt }}" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kanwil{{ $d->id }}"
                                                                class="form-label">Kanwil</label>
                                                            <input type="text" class="form-control"
                                                                id="kanwil{{ $d->id }}" name="kanwil"
                                                                value="{{ $d->kanwil }}" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tipe{{ $d->id }}"
                                                                class="form-label">Tipe</label>
                                                            <input type="text" class="form-control"
                                                                id="tipe{{ $d->id }}" name="tipe"
                                                                value="{{ ucfirst($d->tipe) }}" readonly>
                                                        </div>
                                                    </div>

                                                    <!-- Data Opsional Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5>Data Opsional</h5>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="pic_upt{{ $d->id }}"
                                                                class="form-label">PIC UPT</label>
                                                            <input type="text" class="form-control"
                                                                id="pic_upt{{ $d->id }}" name="pic_upt"
                                                                value="{{ $dataOpsional->pic_upt ?? '' }}"
                                                                placeholder="Masukkan nama PIC UPT">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="no_telpon{{ $d->id }}"
                                                                class="form-label">No
                                                                Telepon</label>
                                                            <input type="text" class="form-control"
                                                                id="no_telpon{{ $d->id }}" name="no_telpon"
                                                                value="{{ $dataOpsional->no_telpon ?? '' }}"
                                                                placeholder="Masukkan nomor telepon">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="alamat{{ $d->id }}"
                                                                class="form-label">Alamat</label>
                                                            <input type="text" class="form-control"
                                                                id="alamat{{ $d->id }}" name="alamat"
                                                                value="{{ $dataOpsional->alamat ?? '' }}"
                                                                placeholder="Masukkan alamat lengkap">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jumlah_wbp{{ $d->id }}"
                                                                class="form-label">Jumlah
                                                                WBP</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_wbp{{ $d->id }}" name="jumlah_wbp"
                                                                value="{{ $dataOpsional->jumlah_wbp ?? '' }}"
                                                                placeholder="Masukkan jumlah WBP">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jumlah_line{{ $d->id }}"
                                                                class="form-label">Jumlah
                                                                Line VPAS Terpasang</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_line{{ $d->id }}" name="jumlah_line"
                                                                value="{{ $dataOpsional->jumlah_line ?? '' }}"
                                                                placeholder="Masukkan jumlah line VPAS">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="provider_internet{{ $d->id }}"
                                                                class="form-label">Provider
                                                                Internet</label>
                                                            <select class="form-control"
                                                                id="provider_internet{{ $d->id }}"
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
                                                            <label for="kecepatan_internet{{ $d->id }}"
                                                                class="form-label">Kecepatan
                                                                Internet (Mbps)</label>
                                                            <input type="text" class="form-control"
                                                                id="kecepatan_internet{{ $d->id }}"
                                                                name="kecepatan_internet"
                                                                value="{{ $dataOpsional->kecepatan_internet ?? '' }}"
                                                                placeholder="Contoh: 20 Mbps">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tarif_wartel{{ $d->id }}"
                                                                class="form-label">Tarif
                                                                Wartel VPAS</label>
                                                            <input type="text" class="form-control"
                                                                id="tarif_wartel{{ $d->id }}" name="tarif_wartel"
                                                                value="{{ $dataOpsional->tarif_wartel ?? '' }}"
                                                                placeholder="Contoh: 3000">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="status_wartel{{ $d->id }}"
                                                                class="form-label">Status
                                                                Wartel</label>
                                                            <select class="form-control"
                                                                id="status_wartel{{ $d->id }}"
                                                                name="status_wartel">
                                                                <option value="">-- Pilih Status --</option>
                                                                <option value="1"
                                                                    {{ ($dataOpsional->status_wartel ?? 0) == 1 ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="0"
                                                                    {{ ($dataOpsional->status_wartel ?? 0) == 0 ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- IMC PAS Section -->
                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5>IMC PAS</h5>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="akses_topup_pulsa{{ $d->id }}"
                                                                class="form-label">Akses
                                                                Top Up
                                                                Pulsa</label>

                                                            <input type="text" class="form-control"
                                                                id="akses_topup_pulsa{{ $d->id }}"
                                                                name="akses_topup_pulsa"
                                                                value="{{ $dataOpsional->akses_topup_pulsa ?? '' }}"
                                                                placeholder="Masukan akses Topup Pulsa">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="password_topup{{ $d->id }}"
                                                                class="form-label">Password
                                                                Top Up
                                                                Pulsa</label>
                                                            <input type="text" class="form-control"
                                                                id="password_topup{{ $d->id }}"
                                                                name="password_topup"
                                                                value="{{ $dataOpsional->password_topup ?? '' }}"
                                                                placeholder="Masukkan password top up">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="akses_download_rekaman{{ $d->id }}"
                                                                class="form-label">Akses
                                                                Download Rekaman</label>
                                                            <input type="text" class="form-control"
                                                                id="akses_download_rekaman{{ $d->id }}"
                                                                name="akses_download_rekaman"
                                                                value="{{ $dataOpsional->akses_download_rekaman ?? '' }}"
                                                                placeholder="Masukan akses Topup Pulsa}}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="password_download{{ $d->id }}"
                                                                class="form-label">Password
                                                                Download Rekaman</label>
                                                            <input type="text" class="form-control"
                                                                id="password_download{{ $d->id }}"
                                                                name="password_download"
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
                                                            <label for="internet_protocol{{ $d->id }}"
                                                                class="form-label">Internet
                                                                Protocol</label>
                                                            <input type="text" class="form-control"
                                                                id="internet_protocol{{ $d->id }}"
                                                                name="internet_protocol"
                                                                value="{{ $dataOpsional->internet_protocol ?? '' }}"
                                                                placeholder="Masukkan alamat IP atau domain">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="vpn_user{{ $d->id }}"
                                                                class="form-label">User VPN</label>
                                                            <input type="text" class="form-control"
                                                                id="vpn_user{{ $d->id }}" name="vpn_user"
                                                                value="{{ $dataOpsional->vpn_user ?? '' }}"
                                                                placeholder="Masukkan username VPN">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="vpn_password{{ $d->id }}"
                                                                class="form-label">Password VPN</label>
                                                            <input type="text" class="form-control"
                                                                id="vpn_password{{ $d->id }}" name="vpn_password"
                                                                value="{{ $dataOpsional->vpn_password ?? '' }}"
                                                                placeholder="Masukkan password VPN">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jenis_vpn{{ $d->id }}"
                                                                class="form-label">Jenis VPN</label>
                                                            <select class="form-control"
                                                                id="jenis_vpn{{ $d->id }}" name="jenis_vpn">
                                                                <option value="">-- Pilih Jenis VPN --</option>
                                                                @if (isset($vpns) && $vpns->count() > 0)
                                                                    @foreach ($vpns as $p)
                                                                        <option value="{{ $p->jenis_vpn }}"
                                                                            {{ ($dataOpsional->jenis_vpn ?? '') == $p->jenis_vpn ? 'selected' : '' }}>
                                                                            {{ $p->jenis_vpn }}
                                                                        </option>
                                                                    @endforeach
                                                                @else
                                                                    <option value="" disabled>Tidak ada data VPN
                                                                        tersedia</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="mb-4">
                                                        <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                            <h5>Extension VPAS</h5>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jumlah_extension{{ $d->id }}"
                                                                class="form-label">Jumlah
                                                                Extension</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_extension{{ $d->id }}"
                                                                name="jumlah_extension"
                                                                value="{{ $dataOpsional->jumlah_extension ?? '' }}"
                                                                placeholder="Masukkan jumlah">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="pin_tes{{ $d->id }}"
                                                                class="form-label">Pin Test</label>
                                                            <input type="text" class="form-control"
                                                                id="pin_tes{{ $d->id }}" name="pin_tes"
                                                                value="{{ $dataOpsional->pin_tes ?? '' }}"
                                                                placeholder="Masukkan Pin Test">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="no_extension{{ $d->id }}"
                                                                class="form-label">No
                                                                Extension</label>
                                                            <small class="text-muted d-block mb-2">Masukkan setiap
                                                                nomor
                                                                extension pada baris terpisah</small>
                                                            <textarea class="form-control" id="no_extension{{ $d->id }}" name="no_extension" rows="6"
                                                                placeholder="Contoh:&#10;201&#10;202&#10;203">{{ $dataOpsional->no_extension ?? '' }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="extension_password{{ $d->id }}"
                                                                class="form-label">Password
                                                                Extension</label>
                                                            <small class="text-muted d-block mb-2">Masukkan setiap
                                                                password
                                                                extension pada baris terpisah (sesuai urutan nomor
                                                                extension
                                                                di atas)</small>
                                                            <textarea class="form-control" id="extension_password{{ $d->id }}" name="extension_password" rows="6"
                                                                placeholder="Contoh:&#10;password1&#10;password2&#10;password3">{{ $dataOpsional->extension_password ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn-cancel-modal"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn-purple">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                            {{-- User Edit Modal --}}

                        </div>
                    </div>
                </div>
                <!-- /.row -->




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
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Search and Pagination JavaScript --}}
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
            $("#btn-search").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("#Table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

                // Update pagination after search
                const $visibleRows = $("#Table tbody tr:visible");
                totalPages = Math.ceil($visibleRows.length / limit);
                currentPage = 1;

                if (value === '') {
                    // If search is cleared, show all rows with pagination
                    updateTable();
                } else {
                    // If searching, hide pagination info
                    $("#page-info").text(`Showing ${$visibleRows.length} results`);
                    $("#prev-page").prop("disabled", true);
                    $("#next-page").prop("disabled", true);
                }
            });

            $(document).ready(function() {
                // Handle modal events if needed
                $('.modal').on('show.bs.modal', function(e) {
                    // Modal is about to be shown
                    console.log('Modal is opening');
                });

                $('.modal').on('shown.bs.modal', function(e) {
                    // Modal is fully shown
                    console.log('Modal is fully visible');
                });

                $('.modal').on('hide.bs.modal', function(e) {
                    // Modal is about to be hidden
                    console.log('Modal is closing');
                });
            });
        });
    </script>

@endsection

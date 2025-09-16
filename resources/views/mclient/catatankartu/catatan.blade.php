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
                            <h1 class="headline-large-32 mb-0">Catatan Kartu</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Search bar -->
                            <div class="btn-searchbar">
                                <span>
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="btn-search" name="table_search" placeholder="Search">
                            </div>

                            <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fa fa-plus"></i> Add Data
                            </button>
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
                                <div class="mb-1">ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¢ {{ $error }}</div>
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
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Hasil pencarian untuk: "<strong>{{ request('table_search') }}</strong>"
                                    <a href="{{ route('ListDataMclientCatatan') }}" class="btn btn-sm btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="card mt-3">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="Table">
                                    <thead>
                                        <tr>
                                            <th>Nama UPT</th>
                                            <th>Kartu (Baru)</th>
                                            <th>Kartu (Bekas)</th>
                                            <th>Kartu (GOIP)</th>
                                            <th>Kartu Belum Register</th>
                                            <th>WhatsApp Terpakai</th>
                                            <th>Card Supporting</th>
                                            <th>PIC</th>
                                            <th>Kartu Terpakai/Hari</th>
                                            <th>Tanggal</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $d)
                                            <tr>
                                                <td>{{ $d->nama_upt ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $d->spam_vpas_kartu_baru ?? '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->spam_vpas_kartu_bekas ?? '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->spam_vpas_kartu_goip ?? '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->kartu_belum_teregister ?? '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->whatsapp_telah_terpakai ?? '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->card_supporting ?? '-' }}
                                                </td>
                                                <td>{{ $d->pic ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $d->jumlah_kartu_terpakai_perhari ?? '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $d->tanggal ? \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td>
                                                    {{-- Edit Button --}}
                                                    <a href="#editModal{{ $d->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        <button>
                                                            <ion-icon name="pencil-outline"></ion-icon>
                                                        </button>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <a data-toggle="modal"
                                                        data-target="#modal-default{{ $d->id }}" class="">
                                                        <button>
                                                            <ion-icon name="trash-outline"></ion-icon></button>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade" id="modal-default{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center align-items-center">
                                                            <ion-icon name="alert-circle-outline"
                                                                class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                                                            <p class="headline-large-32">Anda Yakin?</p>
                                                            <label>Apakah Data Catatan <b> {{ $d->nama_upt }} </b> ingin
                                                                dihapus?</label>
                                                        </div>
                                                        <div class="modal-footer flex-row-reverse justify-content-between">
                                                            <button type="button" class="btn-cancel-modal"
                                                                data-dismiss="modal">Tutup</button>
                                                            <form action="{{ route('MclientCatatanDestroy', $d->id) }}"
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
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">Tidak ada data yang ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if ($data->hasPages())
                                <div class="card-footer">
                                    {{ $data->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>

                        {{-- Add Modal --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <form id="addForm" action="{{ route('MclientCatatanStore') }}" method="POST">
                                @csrf
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <label class="modal-title" id="addModalLabel">Tambah Data Catatan Kartu</label>
                                            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Informasi UPT Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <h5>Informasi UPT</h5>
                                                </div>
                                                <div class="column">
                                                    <div class="mb-3">
                                                        <label for="nama_upt" class="form-label">Nama UPT <span class="text-danger">*</span></label>
                                                        <select class="form-control" id="nama_upt" name="nama_upt" required>
                                                            <option value="">-- Pilih UPT --</option>
                                                            @foreach ($uptList as $upt)
                                                                <option value="{{ $upt->namaupt }}">{{ $upt->namaupt }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Data Spam VPAS Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <h5>Data Spam VPAS Tertangani</h5>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="spam_vpas_kartu_baru" class="form-label">Kartu Baru</label>
                                                            <input type="text" class="form-control" id="spam_vpas_kartu_baru"
                                                                name="spam_vpas_kartu_baru" value=""
                                                                placeholder="Jumlah kartu baru">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="spam_vpas_kartu_bekas" class="form-label">Kartu Bekas</label>
                                                            <input type="text" class="form-control" id="spam_vpas_kartu_bekas"
                                                                name="spam_vpas_kartu_bekas" value=""
                                                                placeholder="Jumlah kartu bekas">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="spam_vpas_kartu_goip" class="form-label">Kartu GOIP</label>
                                                            <input type="text" class="form-control" id="spam_vpas_kartu_goip"
                                                                name="spam_vpas_kartu_goip" value=""
                                                                placeholder="Jumlah kartu GOIP">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Data Kartu Lainnya Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <h5>Data Kartu Lainnya</h5>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="kartu_belum_teregister" class="form-label">Kartu Belum Teregister</label>
                                                            <input type="text" class="form-control" id="kartu_belum_teregister"
                                                                name="kartu_belum_teregister" value=""
                                                                placeholder="Jumlah kartu belum teregister">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="whatsapp_telah_terpakai" class="form-label">WhatsApp Telah Terpakai</label>
                                                            <input type="text" class="form-control" id="whatsapp_telah_terpakai"
                                                                name="whatsapp_telah_terpakai" value=""
                                                                placeholder="Jumlah WhatsApp terpakai">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="card_supporting" class="form-label">Card Supporting</label>
                                                            <select class="form-control" id="card_supporting" name="card_supporting">
                                                                <option value="">-- Pilih Card Supporting --</option>
                                                                @foreach ($cardSupportingList as $cardSupporting)
                                                                    <option value="{{ $cardSupporting }}">{{ $cardSupporting }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="jumlah_kartu_terpakai_perhari" class="form-label">Jumlah Kartu Terpakai Per Hari</label>
                                                            <input type="text" class="form-control" id="jumlah_kartu_terpakai_perhari"
                                                                name="jumlah_kartu_terpakai_perhari" value=""
                                                                placeholder="Jumlah kartu terpakai per hari">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PIC & Tanggal Section -->
                                            <div class="mb-4">
                                                <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                    <h5>PIC & Tanggal</h5>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="pic" class="form-label">PIC</label>
                                                            <select class="form-control" id="pic" name="pic">
                                                                <option value="">-- Pilih PIC --</option>
                                                                @foreach ($picList as $pic)
                                                                    <option value="{{ $pic->nama_pic }}">{{ $pic->nama_pic }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="tanggal" class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn-cancel-modal"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-purple">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Edit Modals --}}
                        @foreach ($data as $d)
                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
                                <form id="editForm{{ $d->id }}"
                                    action="{{ route('MclientCatatanUpdate', ['id' => $d->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <label class="modal-title" id="editModalLabel{{ $d->id }}">Edit
                                                    Data Catatan Kartu</label>
                                                <button type="button" class="btn-close-custom" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $d->id }}">

                                                <!-- Informasi UPT Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5>Informasi UPT</h5>
                                                    </div>
                                                    <div class="column">
                                                        <div class="mb-3">
                                                            <label for="nama_upt_edit_{{ $d->id }}"
                                                                class="form-label">Nama UPT <span class="text-danger">*</span></label>
                                                            <select class="form-control"
                                                                id="nama_upt_edit_{{ $d->id }}" name="nama_upt" required>
                                                                <option value="">-- Pilih UPT --</option>
                                                                @foreach ($uptList as $upt)
                                                                    <option value="{{ $upt->namaupt }}"
                                                                        {{ $d->nama_upt == $upt->namaupt ? 'selected' : '' }}>
                                                                        {{ $upt->namaupt }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Spam VPAS Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5>Data Spam VPAS Tertangani</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="spam_vpas_kartu_baru{{ $d->id }}"
                                                                    class="form-label">Kartu Baru</label>
                                                                <input type="text" class="form-control"
                                                                    id="spam_vpas_kartu_baru{{ $d->id }}"
                                                                    name="spam_vpas_kartu_baru"
                                                                    value="{{ $d->spam_vpas_kartu_baru ?? '' }}"
                                                                    placeholder="Jumlah kartu baru">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="spam_vpas_kartu_bekas{{ $d->id }}"
                                                                    class="form-label">Kartu Bekas</label>
                                                                <input type="text" class="form-control"
                                                                    id="spam_vpas_kartu_bekas{{ $d->id }}"
                                                                    name="spam_vpas_kartu_bekas"
                                                                    value="{{ $d->spam_vpas_kartu_bekas ?? '' }}"
                                                                    placeholder="Jumlah kartu bekas">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="spam_vpas_kartu_goip{{ $d->id }}"
                                                                    class="form-label">Kartu GOIP</label>
                                                                <input type="text" class="form-control"
                                                                    id="spam_vpas_kartu_goip{{ $d->id }}"
                                                                    name="spam_vpas_kartu_goip"
                                                                    value="{{ $d->spam_vpas_kartu_goip ?? '' }}"
                                                                    placeholder="Jumlah kartu GOIP">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Kartu Lainnya Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5>Data Kartu Lainnya</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="kartu_belum_teregister{{ $d->id }}"
                                                                    class="form-label">Kartu Belum Teregister</label>
                                                                <input type="text" class="form-control"
                                                                    id="kartu_belum_teregister{{ $d->id }}"
                                                                    name="kartu_belum_teregister"
                                                                    value="{{ $d->kartu_belum_teregister ?? '0' }}"
                                                                    placeholder="Jumlah kartu belum teregister">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="whatsapp_telah_terpakai{{ $d->id }}"
                                                                    class="form-label">WhatsApp Telah Terpakai</label>
                                                                <input type="text" class="form-control"
                                                                    id="whatsapp_telah_terpakai{{ $d->id }}"
                                                                    name="whatsapp_telah_terpakai"
                                                                    value="{{ $d->whatsapp_telah_terpakai ?? '0' }}"
                                                                    placeholder="Jumlah WhatsApp terpakai">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="card_supporting{{ $d->id }}"
                                                                    class="form-label">Card Supporting</label>
                                                                <select class="form-control" id="card_supporting{{ $d->id }}" name="card_supporting">
                                                                    <option value="">-- Pilih Card Supporting --</option>
                                                                    @foreach ($cardSupportingList as $cardSupporting)
                                                                        <option value="{{ $cardSupporting }}"
                                                                            {{ $d->card_supporting == $cardSupporting ? 'selected' : '' }}>
                                                                            {{ $cardSupporting }}
                                                                        </option>
                                                                    @endforeach
                                                                    @php
                                                                        $existingCardSupportings = $cardSupportingList->toArray();
                                                                    @endphp
                                                                    @if ($d->card_supporting && !in_array($d->card_supporting, $existingCardSupportings))
                                                                        <option value="{{ $d->card_supporting }}" selected>
                                                                            {{ $d->card_supporting }} (Custom)
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="jumlah_kartu_terpakai_perhari{{ $d->id }}"
                                                                    class="form-label">Jumlah Kartu Terpakai Per Hari</label>
                                                                <input type="text" class="form-control"
                                                                    id="jumlah_kartu_terpakai_perhari{{ $d->id }}"
                                                                    name="jumlah_kartu_terpakai_perhari"
                                                                    value="{{ $d->jumlah_kartu_terpakai_perhari ?? '0' }}"
                                                                    placeholder="Jumlah kartu terpakai per hari">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- PIC & Tanggal Section -->
                                                <div class="mb-4">
                                                    <div class="mb-3 border-bottom pb-2 d-flex justify-content-center">
                                                        <h5>PIC & Tanggal</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="pic{{ $d->id }}" class="form-label">PIC</label>
                                                                <select class="form-control" id="pic{{ $d->id }}" name="pic">
                                                                    <option value="">-- Pilih PIC --</option>
                                                                    @foreach ($picList as $pic)
                                                                        <option value="{{ $pic->nama_pic }}"
                                                                            {{ $d->pic == $pic->nama_pic ? 'selected' : '' }}>
                                                                            {{ $pic->nama_pic }}
                                                                        </option>
                                                                    @endforeach
                                                                    @php
                                                                        $existingPics = $picList->pluck('nama_pic')->toArray();
                                                                    @endphp
                                                                    @if ($d->pic && !in_array($d->pic, $existingPics))
                                                                        <option value="{{ $d->pic }}" selected>
                                                                            {{ $d->pic }} (Custom)
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="tanggal{{ $d->id }}" class="form-label">Tanggal</label>
                                                                <input type="date" class="form-control" id="tanggal{{ $d->id }}"
                                                                    name="tanggal" value="{{ $d->tanggal ? $d->tanggal->format('Y-m-d') : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
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
                </div>
                <!-- /.row -->

                <!-- Pagination Controls -->
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
        });
    </script>

@endsection
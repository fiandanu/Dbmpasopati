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

        <!-- CARD KATEGORI -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kategori VPAS -->
                    <div class="col-md-3 mb-3">
                        <div class="card-kategori">
                            <h3>VPAS</h3>
                            <p class="text-kategori mb-2">Layanan VPAS</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ListDataMclientVpas') }}" class="list-button">List Data VPAS</a>
                        </div>
                    </div>

                    <!-- Kategori Reguller -->
                    <div class="col-md-3 mb-3">
                        <div class="card-kategori">
                            <h3>Reguller</h3>
                            <p class="text-kategori mb-2">Layanan Reguller</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ListDataMclientReguller') }}" class="list-button">List Data Reguller</a>
                        </div>
                    </div>

                    <!-- Kategori Kunjungan UPT -->
                    <div class="col-md-3 mb-3">
                        <div class="card-kategori">
                            <h3>Kunjungan UPT</h3>
                            <p class="text-kategori mb-2">Layanan Kunjungan Monitoring Client</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ $totalKunjungan ?? 0 }} Data
                            </div>
                            <a href="{{ route('ListDataMclientKunjungan') }}" class="list-button">List Data Kunjungan</a>
                        </div>
                    </div>

                    <!-- Kategori Pengiriman Alat UPT -->
                    <div class="col-md-3 mb-3">
                        <div class="card-kategori">
                            <h3>Pengiriman Alat UPT</h3>
                            <p class="text-kategori mb-2">Layanan Pengiriman Alat UPT</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ListDataMclientPengiriman')}}" class="list-button">List Data Pengiriman Alat UPT</a>
                        </div>
                    </div>

                    <!-- Kategori Setting Alat UPT -->
                    <div class="col-md-3 mb-3">
                        <div class="card-kategori">
                            <h3>Setting Alat UPT</h3>
                            <p class="text-kategori mb-2">Layanan Setting Alat UPT</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ListDataMclientSettingAlat')}}" class="list-button">List Data Setting Alat UPT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

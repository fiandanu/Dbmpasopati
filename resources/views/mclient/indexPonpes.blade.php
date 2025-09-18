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
                            <h1 class="headline-large-32 mb-0">Komplain Ponpes</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CARD KATEGORI -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kategori VTREN -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>VTREN</h3>
                            <p class="text-kategori mb-2">Layanan VTREN</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ListDataMclientPonpesVtren') }}" class="list-button">Data VTREN</a>
                        </div>
                    </div>

                    <!-- Kategori Reguller -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>Reguller</h3>
                            <p class="text-kategori mb-2">Layanan Reguller</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ListDataMclientPonpesReguller') }}" class="list-button">Data
                                Reguller</a>
                        </div>
                    </div>

                    <!-- Kategori Kunjungan Ponpes -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>Kunjungan Ponpes</h3>
                            <p class="text-kategori mb-2">Layanan Kunjungan Ponpes</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                {{ $ponpesKunjunganCount ?? 0 }} Data
                            </div>
                            <a href="{{ route('ListDataMclientPonpesKunjungan') }}" class="list-button">Kunjungan Ponpes</a>
                        </div>
                    </div>

                    <!-- Kategori Pengiriman Alat Ponpes -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>Pengiriman Alat Ponpes</h3>
                            <p class="text-kategori mb-2">Layanan Pengiriman Alat Ponpes</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="" class="list-button">Pengiriman Alat Ponpes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

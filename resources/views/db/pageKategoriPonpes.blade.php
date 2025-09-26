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
                            <h1 class="headline-large-32 mb-0">List Data Ponpes</h1>
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
                            <p class="text-kategori mb-2">Layanan Pks</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('DbPonpes.pks.ListDataPks') }}" class="list-button">List Data PKS</a>
                        </div>
                    </div>

                    <!-- Kategori SPP -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>SPP</h3>
                            <p class="text-kategori mb-2">Layanan Vtren</p>
                            <span class="data-badge mb-3">
                                100 Data
                            </span>
                            <a href="{{ route('sppPonpes.ListDataSpp') }}" class="list-button">List
                                Data SPP</a>
                        </div>
                    </div>

                    <!-- Kategori VTREN -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>VTREN</h3>
                            <p class="text-kategori mb-2">Layanan Vtren</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('DbPonpes.ListDataVtrend') }}" class="list-button">List Data
                                Vtren</a>
                        </div>
                    </div>

                    <!-- Kategori REGULER -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>REGULER</h3>
                            <p class="text-kategori mb-2">Layanan Reguler</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('ponpes.ListDataPonpes') }}" class="list-button">
                                List Data PKS
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

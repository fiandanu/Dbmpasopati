@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">DataBase UPT</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">DataBase UPT</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

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
                                <a href="{{ route('pks.ListDataPks') }}" class="list-button">List Data
                                    PKS</a>
                        </div>
                    </div>

                    <!-- Kategori SPP -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>SPP</h3>
                            <p class="text-kategori mb-2">Layanan SPP</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                                <a href="{{ route('spp.ListDataSpp') }}" class="list-button">List Data
                                    SPP</a>
                        </div>
                    </div>

                    <!-- Kategori VPAS -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                                <h3>VPAS</h3>
                                <p class="text-kategori mb-2">Layanan VPAS</p>
                                <div class="data-badge mb-3">
                                    <span class="checkmark">✓</span>
                                    100 Data
                                </div>
                                <a href="{{ route('ListDataVpas') }}" class="list-button">List Data
                                    Customer</a>
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
                                <a href="{{ route('ListDataReguller') }}" class="list-button">List Data
                                    Customer</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

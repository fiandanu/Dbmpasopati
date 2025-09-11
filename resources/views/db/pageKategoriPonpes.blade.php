@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">DataBase Ponpes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">DataBase Ponpes</li>
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
                                <a href="{{ route('ponpes.pks.ListDataPks') }}"
                                    class="list-button">List Data PKS</a>
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
                            <a href="{{ route('ListDataVtrend') }}" class="list-button">List Data
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
                            <a href="{{ route('ListDataPonpes') }}" class="list-button">
                                List Data PKS
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

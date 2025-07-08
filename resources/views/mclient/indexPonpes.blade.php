@extends('layout.sidebar')
@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Komplain Ponpes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Komplain Ponpes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD KATEGORI -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kategori VTREN -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">VTREN</div>
                            <div class="card-body">
                                <a href="" class="btn btn-primary btn-sm btn-block">List Data VTREN</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Reguller -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">Reguller</div>
                            <div class="card-body">
                                <a href="" class="btn btn-primary btn-sm btn-block">List Data Reguller</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Kunjungan Ponpes -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">Kunjungan Ponpes</div>
                            <div class="card-body">
                                <a href="" class="btn btn-primary btn-sm btn-block">List Data Kunjungan Ponpes</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Pengiriman Alat Ponpes -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">Pengiriman Alat Ponpes</div>
                            <div class="card-body">
                                <a href="" class="btn btn-primary btn-sm btn-block">List Data Pengiriman Alat Ponpes</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
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
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">PKS</div>
                            <div class="card-body">
                                <a href="{{ route('pks.ListDataPks') }}" class="btn btn-primary btn-sm btn-block">List Data
                                    PKS</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori SPP -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">SPP</div>
                            <div class="card-body">
                                <a href="{{ route('spp.ListDataSpp')}}" class="btn btn-primary btn-sm btn-block">List Data SPP</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori VPAS -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">VPAS</div>
                            <div class="card-body">
                                <a href="{{ route('ListDataVpas') }}" class="btn btn-primary btn-sm btn-block">List Data Customer</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori REGULER -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">REGULER</div>
                            <div class="card-body">
                                <a href="{{ route('ListDataReguller') }}" class="btn btn-primary btn-sm btn-block">List Data
                                    Customer</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori REGULER/VPAS -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">REGULER/VPAS</div>
                            <div class="card-body">
                                <a href="{{ route('ListDataVpr') }}" class="btn btn-primary btn-sm btn-block">List Data
                                    Customer</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

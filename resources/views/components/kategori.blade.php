@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tutorial UPT</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Tutorial UPT</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <!-- CARD KATEGORI -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kategori VPAS -->
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header font-weight-bold d-flex justify-content-center">VPAS</div>
                            <div class="card-body">
                                <a href="" class="btn btn-primary btn-sm btn-block">List Data VPAS</a>
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
                </div>
            </div>
        </div>

    </div>
@endsection

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
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">Tutorial UPT</h1>
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
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>VPAS</h3>
                            <p class="text-kategori mb-2">Layanan VPAS</p>
                            <div class="data-badge mb-3">
                                <span class="checkmark">✓</span>
                                100 Data
                            </div>
                            <a href="{{ route('tutor_vpas.ListDataSpp') }}" class="list-button">List Data VPAS</a>
                        </div>
                    </div>

                    <!-- Kategori Reguller -->
                    <div class="col-md-3">
                        <div class="card-kategori">
                            <h3>Reguller</h3>
                            <p class="text-kategori mb-2">Layanan Reguller</p>
                            <span class="data-badge mb-3">
                                100 Data
                            </span>
                            <a href="{{ route('tutor_upt.ListDataSpp') }}" class="list-button">List
                                Data Reguller</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

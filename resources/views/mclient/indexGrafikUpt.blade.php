@extends('layout.sidebar')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="col d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-center align-items-center gap-12">
                        <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="headline-large-32 mb-0">Grafik Monitoring Client UPT</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Modern Filter Card -->
                <div class="card mb-4" style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                    <div class="card-body p-4">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                    <i class="fas fa-chart-bar mr-2" style="color: #667eea;"></i>Tipe Data
                                </label>
                                <select class="form-control" id="chart-type"
                                    style="border-radius: 10px; border: 2px solid #e9ecef; height: 45px; font-size: 0.95rem;">
                                    <option value="all-cards">📊 Semua Data Kartu</option>
                                    <option value="total-monthly">📈 Total Kartu Per Bulan</option>
                                    <option value="vpas-kendala">🔧 Jenis Kendala Vpas</option>
                                    <option value="reguler-kendala">⚙️ Jenis Kendala Reguler</option>
                                    <option value="kunjungan-upt">🏢 Kunjungan UPT</option>
                                    <option value="pengiriman-upt">📦 Pengiriman Alat UPT</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                    <i class="fas fa-calendar-alt mr-2" style="color: #667eea;"></i>Rentang Waktu
                                </label>
                                <select class="form-control" id="time-range"
                                    style="border-radius: 10px; border: 2px solid #e9ecef; height: 45px; font-size: 0.95rem;">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label style="font-weight: 600; color: #495057; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                    <i class="fas fa-calendar-check mr-2" style="color: #667eea;"></i>Custom Date Range
                                </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="start-date"
                                        style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef; border-right: none; height: 45px;">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text"
                                            style="background: white; border: 2px solid #e9ecef; border-left: none; border-right: none;">→</span>
                                    </div>
                                    <input type="date" class="form-control" id="end-date"
                                        style="border-radius: 0 10px 10px 0; border: 2px solid #e9ecef; border-left: none; height: 45px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section - All Cards -->
                <div id="all-cards-chart-section">
                    <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.25rem; position: relative;">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="fas fa-chart-area mr-2"></i><span id="all-cards-title">Grafik Semua Data Kartu - 7
                                    Hari Terakhir</span>
                            </h5>
                            <button class="btn btn-light" id="export-pdf-btn" onclick="exportToPdf()"
                                style="position: absolute; top: 1.25rem; right: 1.25rem; border-radius: 20px; padding: 0.6rem 1.5rem; font-weight: 500;">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <canvas id="allCardsChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Section - Total Monthly -->
                <div id="total-monthly-chart-section" style="display: none;">
                    <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.25rem; position: relative;">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="fas fa-chart-line mr-2"></i><span id="total-monthly-title">Grafik Total Kartu
                                    Terpakai</span>
                            </h5>
                            <button class="btn btn-light" onclick="exportToPdf()"
                                style="position: absolute; top: 1.25rem; right: 1.25rem; border-radius: 20px; padding: 0.6rem 1.5rem; font-weight: 500;">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <canvas id="totalMonthlyChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Section - Vpas Kendala -->
                <div id="vpas-kendala-chart-section" style="display: none;">
                    <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #0575E6 0%, #021B79 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.25rem; position: relative;">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="fas fa-tools mr-2"></i><span id="vpas-kendala-title">Grafik Jenis Kendala
                                    Vpas</span>
                            </h5>
                            <button class="btn btn-light" onclick="exportToPdf()"
                                style="position: absolute; top: 1.25rem; right: 1.25rem; border-radius: 20px; padding: 0.6rem 1.5rem; font-weight: 500;">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <canvas id="vpasKendalaChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Section - Reguler Kendala -->
                <div id="reguler-kendala-chart-section" style="display: none;">
                    <div class="card"
                        style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.25rem; position: relative;">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="fas fa-wrench mr-2"></i><span id="reguler-kendala-title">Grafik Jenis Kendala
                                    Reguler</span>
                            </h5>
                            <button class="btn btn-light" onclick="exportToPdf()"
                                style="position: absolute; top: 1.25rem; right: 1.25rem; border-radius: 20px; padding: 0.6rem 1.5rem; font-weight: 500;">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <canvas id="regulerKendalaChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Section - Kunjungan UPT -->
                <div id="kunjungan-upt-chart-section" style="display: none;">
                    <div class="card"
                        style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.25rem; position: relative;">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="fas fa-building mr-2"></i><span id="kunjungan-upt-title">Grafik Top 10 UPT
                                    Paling Sering Dikunjungi</span>
                            </h5>
                            <button class="btn btn-light" onclick="exportToPdf()"
                                style="position: absolute; top: 1.25rem; right: 1.25rem; border-radius: 20px; padding: 0.6rem 1.5rem; font-weight: 500;">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <canvas id="kunjunganUptChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Section - Pengiriman UPT -->
                <div id="pengiriman-upt-chart-section" style="display: none;">
                    <div class="card"
                        style="border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.25rem; position: relative;">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="fas fa-truck mr-2"></i><span id="pengiriman-upt-title">Grafik Top 10 UPT
                                    Pengiriman Alat Terbanyak</span>
                            </h5>
                            <button class="btn btn-light" onclick="exportToPdf()"
                                style="position: absolute; top: 1.25rem; right: 1.25rem; border-radius: 20px; padding: 0.6rem 1.5rem; font-weight: 500;">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <canvas id="pengirimanUptChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Modern Summary Cards - Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-3" id="summary-cards">

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Kartu Baru</h1>
                            <span class="display-medium-48" id="total-kartu-baru">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">id_card</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Kartu Bekas</h1>
                            <span class="display-medium-48" id="total-kartu-bekas">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">recycling</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Kartu GOIP</h1>
                            <span class="display-medium-48" id="total-kartu-goip">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">sim_card</span>
                        </div>
                    </div>

                </div>

                <!-- Modern Summary Cards - Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-3" id="summary-cards-row2">

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Belum Register</h1>
                            <span class="display-medium-48" id="total-kartu-belum-register">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">WA Terpakai</h1>
                            <span class="display-medium-48" id="total-wa-terpakai">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">mail</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Total Keseluruhan</h1>
                            <span class="display-medium-48" id="total-keseluruhan">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">stack</span>
                        </div>
                    </div>

                </div>

                <!-- Summary Cards - Kendala -->
                <div class="gird gird-cols-1 md:grid-cols-3 gap-12 mb-3" id="kendala-summary-cards"
                    style="display: none;">

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Selesai</h1>
                            <span class="display-medium-48" id="kendala-selesai">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Proses</h1>
                            <span class="display-medium-48" id="kendala-proses">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">autorenew</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Pending</h1>
                            <span class="display-medium-48" id="kendala-pending">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">pause_circle</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Terjadwal</h1>
                            <span class="display-medium-48" id="kendala-terjadwal">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Total Kendala</h1>
                            <span class="display-medium-48" id="kendala-total">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">list</span>
                        </div>
                    </div>

                </div>

                <!-- Summary Cards - Kunjungan UPT -->
                <!-- Summary Cards - Kunjungan UPT (Row 1) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-3" id="kunjungan-summary-cards"
                    style="display: none;">

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Total Kunjungan</h1>
                            <span class="display-medium-48" id="kunjungan-total">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">business</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Kunjungan UPT Terbanyak</h1>
                            <span class="display-medium-48" id="kunjungan-top-upt" style="font-size: 1.2rem;">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">emoji_events</span>
                        </div>
                    </div>

                </div>

                <!-- Summary Cards - Kunjungan UPT (Row 2 - Status) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-3" id="kunjungan-status-cards"
                    style="display: none;">

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Selesai</h1>
                            <span class="display-medium-48" id="kunjungan-selesai">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Proses</h1>
                            <span class="display-medium-48" id="kunjungan-proses">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">autorenew</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Pending</h1>
                            <span class="display-medium-48" id="kunjungan-pending">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">pause_circle</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Terjadwal</h1>
                            <span class="display-medium-48" id="kunjungan-terjadwal">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                    </div>

                </div>

                <!-- Summary Cards - Pengiriman UPT -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-3" id="pengiriman-summary-cards"
                    style="display: none;">
                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Total Pengiriman</h1>
                            <span class="display-medium-48" id="pengiriman-total">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">local_shipping</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Pengiriman Alat UPT Terbanyak</h1>
                            <span class="display-medium-48" id="pengiriman-top-upt" style="font-size: 1.2rem;">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">emoji_events</span>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards Row 2 - Status Pengiriman -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-3" id="pengiriman-status-cards"
                    style="display: none;">

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Selesai</h1>
                            <span class="display-medium-48" id="pengiriman-selesai">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Proses</h1>
                            <span class="display-medium-48" id="pengiriman-proses">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">autorenew</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Pending</h1>
                            <span class="display-medium-48" id="pengiriman-pending">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">pause_circle</span>
                        </div>
                    </div>

                    <div class="card-total">
                        <div class="w-full">
                            <h1 class="title-medium-18">Status Terjadwal</h1>
                            <span class="display-medium-48" id="pengiriman-terjadwal">-</span>
                        </div>
                        <div class="icon-card-total">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card:hover {
            transform: translateY(-2px);
            transition: transform 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        @media (max-width: 768px) {

            .col-md-2,
            .col-md-3,
            .col-md-4,
            .col-md-6 {
                margin-bottom: 1rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        let allCardsChart = null;
        let totalMonthlyChart = null;
        let vpasKendalaChart = null;
        let regulerKendalaChart = null;
        let kunjunganUptChart = null;
        let pengirimanUptChart = null;

        const timeRangeOptions = {
            'all-cards': [{
                    value: '7-days',
                    label: '7 Hari Terakhir',
                    days: 7
                },
                {
                    value: '30-days',
                    label: '30 Hari Terakhir',
                    days: 30
                }
            ],
            'total-monthly': [{
                    value: '1-month-daily',
                    label: '1 Bulan Terakhir (Per Hari)',
                    days: 30,
                    type: 'daily'
                },
                {
                    value: '6-months',
                    label: '6 Bulan Terakhir',
                    months: 6
                },
                {
                    value: '12-months',
                    label: '12 Bulan Terakhir',
                    months: 12
                }
            ],
            'vpas-kendala': [{
                    value: '7-days',
                    label: '7 Hari Terakhir',
                    days: 7
                },
                {
                    value: '30-days',
                    label: '30 Hari Terakhir',
                    days: 30
                },
                {
                    value: '6-months',
                    label: '6 Bulan Terakhir',
                    months: 6
                },
                {
                    value: '12-months',
                    label: '12 Bulan Terakhir',
                    months: 12
                }
            ],
            'reguler-kendala': [{
                    value: '7-days',
                    label: '7 Hari Terakhir',
                    days: 7
                },
                {
                    value: '30-days',
                    label: '30 Hari Terakhir',
                    days: 30
                },
                {
                    value: '6-months',
                    label: '6 Bulan Terakhir',
                    months: 6
                },
                {
                    value: '12-months',
                    label: '12 Bulan Terakhir',
                    months: 12
                }
            ],
            'kunjungan-upt': [{
                    value: '7-days',
                    label: '7 Hari Terakhir',
                    days: 7
                },
                {
                    value: '30-days',
                    label: '30 Hari Terakhir',
                    days: 30
                },
                {
                    value: '6-months',
                    label: '6 Bulan Terakhir',
                    months: 6
                },
                {
                    value: '12-months',
                    label: '12 Bulan Terakhir',
                    months: 12
                }
            ],
            'pengiriman-upt': [{
                    value: '7-days',
                    label: '7 Hari Terakhir',
                    days: 7
                },
                {
                    value: '30-days',
                    label: '30 Hari Terakhir',
                    days: 30
                },
                {
                    value: '6-months',
                    label: '6 Bulan Terakhir',
                    months: 6
                },
                {
                    value: '12-months',
                    label: '12 Bulan Terakhir',
                    months: 12
                }
            ]
        };

        function updateTimeRangeOptions() {
            const chartType = document.getElementById('chart-type').value;
            const timeRangeSelect = document.getElementById('time-range');
            const options = timeRangeOptions[chartType];

            timeRangeSelect.innerHTML = '';
            options.forEach((option, index) => {
                const opt = document.createElement('option');
                opt.value = option.value;
                opt.textContent = option.label;
                if (index === 0) opt.selected = true;
                timeRangeSelect.appendChild(opt);
            });
        }

        function isUsingCustomDateRange() {
            return document.getElementById('start-date').value && document.getElementById('end-date').value;
        }

        function clearDateInputs() {
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';
        }

        function getDateRange() {
            const chartType = document.getElementById('chart-type').value;
            const timeRange = document.getElementById('time-range').value;
            const today = new Date();
            let startDate, endDate, requestType;

            const startDateInput = document.getElementById('start-date').value;
            const endDateInput = document.getElementById('end-date').value;

            if (startDateInput && endDateInput) {
                startDate = new Date(startDateInput);
                endDate = new Date(endDateInput);

                if (startDate > endDate) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                    return null;
                }

                if (chartType === 'all-cards') {
                    requestType = 'daily';
                } else if (chartType === 'vpas-kendala' || chartType === 'reguler-kendala') {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    requestType = diffDays <= 60 ? 'daily' : 'monthly';
                } else {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    requestType = diffDays <= 60 ? 'daily' : 'monthly';
                }
            } else {
                const options = timeRangeOptions[chartType];
                const selectedOption = options.find(opt => opt.value === timeRange);

                endDate = today;

                if (selectedOption.days) {
                    startDate = new Date();
                    startDate.setDate(today.getDate() - (selectedOption.days - 1));
                    requestType = 'daily';
                } else if (selectedOption.months) {
                    startDate = new Date();
                    startDate.setMonth(today.getMonth() - (selectedOption.months - 1));
                    startDate.setDate(1);
                    requestType = 'monthly';
                }
            }

            return {
                startDate: formatDate(startDate),
                endDate: formatDate(endDate),
                type: requestType
            };
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function formatDateDisplay(dateString) {
            const date = new Date(dateString);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        function updateChartTitle(chartType) {
            const timeRange = document.getElementById('time-range');
            let selectedText;

            if (isUsingCustomDateRange()) {
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;
                selectedText = `${formatDateDisplay(startDate)} s.d. ${formatDateDisplay(endDate)}`;
            } else {
                selectedText = timeRange.options[timeRange.selectedIndex].text;
            }

            if (chartType === 'all-cards') {
                document.getElementById('all-cards-title').textContent = `Grafik Semua Data Kartu - ${selectedText}`;
            } else if (chartType === 'total-monthly') {
                document.getElementById('total-monthly-title').textContent =
                    `Grafik Total Kartu Terpakai - ${selectedText}`;
            } else if (chartType === 'vpas-kendala') {
                document.getElementById('vpas-kendala-title').textContent = `Grafik Jenis Kendala Vpas - ${selectedText}`;
            } else if (chartType === 'reguler-kendala') {
                document.getElementById('reguler-kendala-title').textContent =
                    `Grafik Jenis Kendala Reguler - ${selectedText}`;
            } else if (chartType === 'kunjungan-upt') {
                document.getElementById('kunjungan-upt-title').textContent =
                    `Grafik Top 10 UPT Paling Sering Dikunjungi - ${selectedText}`;
            } else if (chartType === 'pengiriman-upt') { // TAMBAHKAN INI
                document.getElementById('pengiriman-upt-title').textContent =
                    `Grafik Top 10 UPT Pengiriman Alat Terbanyak - ${selectedText}`;
            }
        }

        function renderAllCardsChart(data) {
            const ctx = document.getElementById('allCardsChart').getContext('2d');
            if (allCardsChart) allCardsChart.destroy();

            allCardsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: data.datasets.map(dataset => ({
                        ...dataset,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 3
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: '#667eea',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderTotalMonthlyChart(data) {
            const ctx = document.getElementById('totalMonthlyChart').getContext('2d');
            if (totalMonthlyChart) totalMonthlyChart.destroy();

            const timeRange = document.getElementById('time-range').value;
            const isMonthlyDaily = timeRange === '1-month-daily';
            const datasetsToUse = isMonthlyDaily && data.totalDataset ? data.totalDataset : data.datasets;

            totalMonthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: datasetsToUse.map(dataset => ({
                        ...dataset,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 3
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: '#11998e',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderVpasKendalaChart(data) {
            const ctx = document.getElementById('vpasKendalaChart').getContext('2d');
            if (vpasKendalaChart) vpasKendalaChart.destroy();

            vpasKendalaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: data.datasets.map(dataset => ({
                        ...dataset,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 3
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: '#0575E6',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderRegulerKendalaChart(data) {
            const ctx = document.getElementById('regulerKendalaChart').getContext('2d');
            if (regulerKendalaChart) regulerKendalaChart.destroy();

            regulerKendalaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: data.datasets.map(dataset => ({
                        ...dataset,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 3
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: '#f5576c',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderKunjunganUptChart(data) {
            const ctx = document.getElementById('kunjunganUptChart').getContext('2d');
            if (kunjunganUptChart) kunjunganUptChart.destroy();

            kunjunganUptChart = new Chart(ctx, {
                type: 'line', // ✅ Line chart
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: data.data,
                        backgroundColor: 'rgba(250, 112, 154, 0.1)',
                        borderColor: 'rgba(250, 112, 154, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: '#fa709a',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Kunjungan: ' + context.parsed.y + ' kali';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }



        function updateKunjunganSummaryCards(data) {
            if (data.summaryData) {
                const summary = data.summaryData;
                document.getElementById('kunjungan-total').textContent = summary.total.toLocaleString();
                document.getElementById('kunjungan-top-upt').textContent = summary.topUpt || '-';

                // 🔥 TAMBAHAN: Update status cards
                document.getElementById('kunjungan-selesai').textContent = summary.selesai.toLocaleString();
                document.getElementById('kunjungan-proses').textContent = summary.proses.toLocaleString();
                document.getElementById('kunjungan-pending').textContent = summary.pending.toLocaleString();
                document.getElementById('kunjungan-terjadwal').textContent = summary.terjadwal.toLocaleString();
            }
        }

        function renderPengirimanUptChart(data) {
            const ctx = document.getElementById('pengirimanUptChart').getContext('2d');
            if (pengirimanUptChart) pengirimanUptChart.destroy();

            pengirimanUptChart = new Chart(ctx, {
                type: 'line', // ✅ Line chart
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Pengiriman',
                        data: data.data,
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: '#667eea',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Pengiriman: ' + context.parsed.y + ' kali';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        function updatePengirimanSummaryCards(data) {
            if (data.summaryData) {
                const summary = data.summaryData;
                document.getElementById('pengiriman-total').textContent = summary.total.toLocaleString();
                document.getElementById('pengiriman-top-upt').textContent = summary.topUpt || '-';
                document.getElementById('pengiriman-selesai').textContent = summary.selesai.toLocaleString();
                document.getElementById('pengiriman-proses').textContent = summary.proses.toLocaleString();
                document.getElementById('pengiriman-pending').textContent = summary.pending.toLocaleString();
                document.getElementById('pengiriman-terjadwal').textContent = summary.terjadwal.toLocaleString();
            }
        }

        function updateSummaryCards(data) {
            let totalKartuBaru = 0,
                totalKartuBekas = 0,
                totalKartuGoip = 0;
            let totalKartuBelumRegister = 0,
                totalWaTerpakai = 0;

            data.datasets.forEach(dataset => {
                const sum = dataset.data.reduce((a, b) => a + b, 0);
                if (dataset.label === 'Kartu Baru') totalKartuBaru = sum;
                else if (dataset.label === 'Kartu Bekas') totalKartuBekas = sum;
                else if (dataset.label === 'Kartu GOIP') totalKartuGoip = sum;
                else if (dataset.label === 'Kartu Belum Register') totalKartuBelumRegister = sum;
                else if (dataset.label === 'WhatsApp Terpakai') totalWaTerpakai = sum;
            });

            const totalKeseluruhan = totalKartuBaru + totalKartuBekas + totalKartuGoip + totalKartuBelumRegister +
                totalWaTerpakai;

            document.getElementById('total-kartu-baru').textContent = totalKartuBaru.toLocaleString();
            document.getElementById('total-kartu-bekas').textContent = totalKartuBekas.toLocaleString();
            document.getElementById('total-kartu-goip').textContent = totalKartuGoip.toLocaleString();
            document.getElementById('total-kartu-belum-register').textContent = totalKartuBelumRegister.toLocaleString();
            document.getElementById('total-wa-terpakai').textContent = totalWaTerpakai.toLocaleString();
            document.getElementById('total-keseluruhan').textContent = totalKeseluruhan.toLocaleString();
        }

        function updateSummaryCardsFromMonthly(data) {
            if (data.summaryData) {
                const summary = data.summaryData;
                document.getElementById('total-kartu-baru').textContent = summary.kartuBaru.toLocaleString();
                document.getElementById('total-kartu-bekas').textContent = summary.kartuBekas.toLocaleString();
                document.getElementById('total-kartu-goip').textContent = summary.kartuGoip.toLocaleString();
                document.getElementById('total-kartu-belum-register').textContent = summary.kartuBelumRegister
                    .toLocaleString();
                document.getElementById('total-wa-terpakai').textContent = summary.whatsappTerpakai.toLocaleString();

                const totalKeseluruhan = summary.kartuBaru + summary.kartuBekas + summary.kartuGoip +
                    summary.kartuBelumRegister + summary.whatsappTerpakai;
                document.getElementById('total-keseluruhan').textContent = totalKeseluruhan.toLocaleString();
            }
        }

        function updateKendalaSummaryCards(data) {
            if (data.summaryData) {
                const summary = data.summaryData;
                document.getElementById('kendala-selesai').textContent = summary.selesai.toLocaleString();
                document.getElementById('kendala-proses').textContent = summary.proses.toLocaleString();
                document.getElementById('kendala-pending').textContent = summary.pending.toLocaleString();
                document.getElementById('kendala-terjadwal').textContent = summary.terjadwal.toLocaleString();
                document.getElementById('kendala-total').textContent = summary.total.toLocaleString();
            }
        }

        function loadChartData() {
            const chartType = document.getElementById('chart-type').value;
            const dateRange = getDateRange();

            if (!dateRange) return;
            if (window.isLoadingChart) return;

            window.isLoadingChart = true;

            const showKendalaCards = chartType === 'vpas-kendala' || chartType === 'reguler-kendala';
            const showKunjunganCards = chartType === 'kunjungan-upt';
            const showPengirimanCards = chartType === 'pengiriman-upt'; // TAMBAHKAN INI

            // Hide/Show chart sections
            document.getElementById('all-cards-chart-section').style.display = chartType === 'all-cards' ? 'block' : 'none';
            document.getElementById('total-monthly-chart-section').style.display = chartType === 'total-monthly' ? 'block' :
                'none';
            document.getElementById('vpas-kendala-chart-section').style.display = chartType === 'vpas-kendala' ? 'block' :
                'none';
            document.getElementById('reguler-kendala-chart-section').style.display = chartType === 'reguler-kendala' ?
                'block' : 'none';
            document.getElementById('kunjungan-upt-chart-section').style.display = chartType === 'kunjungan-upt' ? 'block' :
                'none';
            document.getElementById('pengiriman-upt-chart-section').style.display = chartType === 'pengiriman-upt' ?
                'block' : 'none'; // TAMBAHKAN INI

            // Update summary cards visibility
            document.getElementById('summary-cards').style.display = showKendalaCards || showKunjunganCards ||
                showPengirimanCards ? 'none' : 'grid';
            document.getElementById('summary-cards-row2').style.display = showKendalaCards || showKunjunganCards ||
                showPengirimanCards ? 'none' : 'grid';
            document.getElementById('kendala-summary-cards').style.display = showKendalaCards ? 'flex' : 'none';
            document.getElementById('kunjungan-summary-cards').style.display = showKunjunganCards ? 'grid' : 'none';
            document.getElementById('kunjungan-status-cards').style.display = showKunjunganCards ? 'grid' :
                'none'; // 🔥 TAMBAHAN
            document.getElementById('pengiriman-summary-cards').style.display = showPengirimanCards ? 'grid' : 'none';
            document.getElementById('pengiriman-status-cards').style.display = showPengirimanCards ? 'grid' : 'none';

            updateChartTitle(chartType);

            let url;
            if (chartType === 'vpas-kendala') {
                url =
                    `{{ route('GrafikClient.vpasData') }}?type=${dateRange.type}&start_date=${dateRange.startDate}&end_date=${dateRange.endDate}`;
            } else if (chartType === 'reguler-kendala') {
                url =
                    `{{ route('GrafikClient.regullerData') }}?type=${dateRange.type}&start_date=${dateRange.startDate}&end_date=${dateRange.endDate}`;
            } else if (chartType === 'kunjungan-upt') {
                url =
                    `{{ route('GrafikClient.kunjunganData') }}?type=${dateRange.type}&start_date=${dateRange.startDate}&end_date=${dateRange.endDate}`;
            } else if (chartType === 'pengiriman-upt') { // TAMBAHKAN INI
                url =
                    `{{ route('GrafikClient.pengirimanData') }}?type=${dateRange.type}&start_date=${dateRange.startDate}&end_date=${dateRange.endDate}`;
            } else {
                url =
                    `{{ route('GrafikClient.data') }}?type=${dateRange.type}&start_date=${dateRange.startDate}&end_date=${dateRange.endDate}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (chartType === 'all-cards') {
                        renderAllCardsChart(data);
                        updateSummaryCards(data);
                    } else if (chartType === 'total-monthly') {
                        renderTotalMonthlyChart(data);
                        dateRange.type === 'daily' ? updateSummaryCards(data) : updateSummaryCardsFromMonthly(data);
                    } else if (chartType === 'vpas-kendala') {
                        renderVpasKendalaChart(data);
                        updateKendalaSummaryCards(data);
                    } else if (chartType === 'reguler-kendala') {
                        renderRegulerKendalaChart(data);
                        updateKendalaSummaryCards(data);
                    } else if (chartType === 'kunjungan-upt') {
                        renderKunjunganUptChart(data);
                        updateKunjunganSummaryCards(data);
                    } else if (chartType === 'pengiriman-upt') { // TAMBAHKAN INI
                        renderPengirimanUptChart(data);
                        updatePengirimanSummaryCards(data);
                    }
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                    alert('Gagal memuat data grafik: ' + error.message);
                })
                .finally(() => {
                    window.isLoadingChart = false;
                });
        }


        function exportToPdf() {
            const chartType = document.getElementById('chart-type').value;
            const dateRange = getDateRange();

            if (!dateRange) {
                alert('Silakan pilih rentang tanggal terlebih dahulu');
                return;
            }

            let activeChart;
            if (chartType === 'all-cards') activeChart = allCardsChart;
            else if (chartType === 'total-monthly') activeChart = totalMonthlyChart;
            else if (chartType === 'vpas-kendala') activeChart = vpasKendalaChart;
            else if (chartType === 'reguler-kendala') activeChart = regulerKendalaChart;
            else if (chartType === 'kunjungan-upt') activeChart = kunjunganUptChart;
            else if (chartType === 'pengiriman-upt') activeChart = pengirimanUptChart;

            if (!activeChart) {
                alert('Grafik belum dimuat');
                return;
            }

            const chartImage = activeChart.toBase64Image();

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('GrafikClient.exportPdf') }}';
            form.target = '_blank';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const imageInput = document.createElement('input');
            imageInput.type = 'hidden';
            imageInput.name = 'chart_image';
            imageInput.value = chartImage;
            form.appendChild(imageInput);

            const params = {
                chart_type: chartType,
                type: dateRange.type,
                start_date: dateRange.startDate,
                end_date: dateRange.endDate
            };

            for (const [key, value] of Object.entries(params)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateTimeRangeOptions();

            document.getElementById('chart-type').addEventListener('change', function() {
                updateTimeRangeOptions();
                clearDateInputs();
                loadChartData();
            });

            document.getElementById('time-range').addEventListener('change', function() {
                clearDateInputs();
                loadChartData();
            });

            document.getElementById('start-date').addEventListener('change', function() {
                if (document.getElementById('end-date').value) loadChartData();
            });

            document.getElementById('end-date').addEventListener('change', function() {
                if (document.getElementById('start-date').value) loadChartData();
            });

            setTimeout(() => loadChartData(), 100);
        });
    </script>
@endsection

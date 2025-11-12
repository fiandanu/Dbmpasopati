<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Grafik Monitoring Client</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
            padding: 30px;
            background: #ffffff;
        }

        /* Modern Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .header .period {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .header .print-date {
            font-size: 10px;
            color: #9ca3af;
        }

        /* Info Badge */
        .info {
            display: inline-block;
            background: #f3f4f6;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 11px;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .info strong {
            color: #111827;
            font-weight: 600;
        }

        .info-divider {
            margin: 0 10px;
            color: #d1d5db;
        }

        /* Summary Cards Grid */
        .summary-box {
            background: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }

        .summary-box h3 {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 10px 0;
        }

        .summary-row {
            display: table-row;
        }

        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 15px 10px;
            background: #f9fafb;
            border-radius: 6px;
            vertical-align: middle;
        }

        .summary-item strong {
            display: block;
            font-size: 10px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-item .value {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        /* Chart Container */
        .chart-container {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }

        .chart-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        /* Modern Table */
        .table-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .table-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
            padding-left: 2px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            padding: 12px 10px;
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #fafafa;
        }

        .total-row {
            background: #f3f4f6 !important;
            font-weight: 700;
            color: #111827;
        }

        .total-row td {
            padding: 12px 10px;
            border-top: 2px solid #e5e7eb;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            font-size: 9px;
            color: #9ca3af;
            font-style: italic;
        }

        /* Print Optimization */
        @media print {
            body {
                padding: 20px;
            }

            .page-break {
                page-break-after: always;
            }
        }

        /* Responsive adjustments for smaller content */
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>LAPORAN GRAFIK MONITORING CLIENT</h2>
        <p class="period">
            <strong>Periode:</strong> {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
        </p>
        <p class="print-date">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Info Badge -->
    <div style="text-align: center;">
        <div class="info">
            <strong>Tipe Grafik:</strong>
            @if($chartType === 'all-cards')
                Semua Data Kartu
            @elseif($chartType === 'total-monthly')
                Total Kartu Per Bulan
            @elseif($chartType === 'vpas-kendala')
                Jenis Kendala VPAS
            @elseif($chartType === 'reguler-kendala')
                Jenis Kendala Reguler
            @endif
            <span class="info-divider">|</span>
            <strong>Tampilan:</strong> {{ $type === 'daily' ? 'Harian' : 'Bulanan' }}
        </div>
    </div>

    <!-- Summary Cards -->
    @if(isset($data['summaryData']))
        <div class="summary-box">
            <h3>Ringkasan Data</h3>
            <div class="summary-grid">
                <div class="summary-row">
                    @if($chartType === 'vpas-kendala' || $chartType === 'reguler-kendala')
                        <div class="summary-item">
                            <strong>Selesai</strong>
                            <div class="value">{{ number_format($data['summaryData']['selesai']) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Proses</strong>
                            <div class="value">{{ number_format($data['summaryData']['proses']) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Pending</strong>
                            <div class="value">{{ number_format($data['summaryData']['pending']) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Terjadwal</strong>
                            <div class="value">{{ number_format($data['summaryData']['terjadwal']) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Total</strong>
                            <div class="value">{{ number_format($data['summaryData']['total']) }}</div>
                        </div>
                    @else
                        <div class="summary-item">
                            <strong>Kartu Baru</strong>
                            <div class="value">{{ number_format($data['summaryData']['kartuBaru'] ?? 0) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Kartu Bekas</strong>
                            <div class="value">{{ number_format($data['summaryData']['kartuBekas'] ?? 0) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Kartu GOIP</strong>
                            <div class="value">{{ number_format($data['summaryData']['kartuGoip'] ?? 0) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Belum Register</strong>
                            <div class="value">{{ number_format($data['summaryData']['kartuBelumRegister'] ?? 0) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>WA Terpakai</strong>
                            <div class="value">{{ number_format($data['summaryData']['whatsappTerpakai'] ?? 0) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Chart Image -->
    @if(isset($chartImage))
        <div class="chart-container">
            <img src="{{ $chartImage }}" alt="Chart">
        </div>
    @endif

    <!-- Data Table -->
    <div class="table-section">
        <div class="table-title">Data Detail</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">{{ $type === 'daily' ? 'Tanggal' : 'Bulan' }}</th>
                    @foreach($data['datasets'] as $dataset)
                        <th>{{ $dataset['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data['labels'] as $index => $label)
                    <tr>
                        <td><strong>{{ $label }}</strong></td>
                        @foreach($data['datasets'] as $dataset)
                            <td>{{ number_format($dataset['data'][$index]) }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>TOTAL</td>
                    @foreach($data['datasets'] as $dataset)
                        <td>{{ number_format(array_sum($dataset['data'])) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>
</html>

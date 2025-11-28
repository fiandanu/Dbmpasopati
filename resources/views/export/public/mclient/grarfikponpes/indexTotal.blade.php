<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Grafik Total Kartu Terpakai</title>
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
            line-height: 1.4;
            padding: 20px;
            background: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .header .period {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .header .print-date {
            font-size: 9px;
            color: #9ca3af;
        }

        .info {
            display: inline-block;
            background: #f3f4f6;
            padding: 8px 16px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 10px;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .info strong {
            color: #111827;
            font-weight: 600;
        }

        .info-divider {
            margin: 0 8px;
            color: #d1d5db;
        }

        .summary-box {
            background: #ffffff;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .summary-box h3 {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f3f4f6;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 8px 0;
        }

        .summary-row {
            display: table-row;
        }

        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 10px 8px;
            background: #f9fafb;
            border-radius: 6px;
            vertical-align: middle;
        }

        .summary-item strong {
            display: block;
            font-size: 9px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .summary-item .value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .chart-container {
            text-align: center;
            margin: 12px 0;
            padding: 12px;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .chart-container img {
            max-width: 100%;
            max-height: 400px;
            height: auto;
            border-radius: 6px;
        }

        .table-section {
            margin-top: 0;
            page-break-before: auto;
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

        .footer {
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            font-size: 9px;
            color: #9ca3af;
            font-style: italic;
        }

        .first-page {
            page-break-inside: avoid;
            page-break-after: always;
        }

        @media print {
            body {
                padding: 15px;
            }

            .chart-container img {
                max-height: 350px;
            }
        }

        @page {
            margin: 0.8cm;
            size: A4 landscape;
        }
    </style>
</head>

<body>
    <!-- Halaman Pertama: Header sampai Chart -->
    <div class="first-page">
        <div class="header">
            <h2>LAPORAN GRAFIK TOTAL KARTU TERPAKAI</h2>
            <p class="period">
                <strong>Periode:</strong> {{ date('d/m/Y', strtotime($startDate)) }} -
                {{ date('d/m/Y', strtotime($endDate)) }}
            </p>
            <p class="print-date">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        </div>

        <div style="text-align: center;">
            <div class="info">
                <strong>Tipe Grafik:</strong> Total Kartu Per Bulan
                <span class="info-divider">|</span>
                <strong>Tampilan:</strong> {{ $type === 'daily' ? 'Harian' : 'Bulanan' }}
            </div>
        </div>

        @if (isset($data['summaryData']))
            <div class="summary-box">
                <h3>Ringkasan Data</h3>
                <div class="summary-grid">
                    <div class="summary-row">
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
                            <div class="value">{{ number_format($data['summaryData']['kartuBelumRegister'] ?? 0) }}
                            </div>
                        </div>
                        <div class="summary-item">
                            <strong>WA Terpakai</strong>
                            <div class="value">{{ number_format($data['summaryData']['whatsappTerpakai'] ?? 0) }}</div>
                        </div>
                        <div class="summary-item">
                            <strong>Total</strong>
                            <div class="value">{{ number_format(($data['summaryData']['kartuBaru'] ?? 0) + ($data['summaryData']['kartuBekas'] ?? 0) + ($data['summaryData']['kartuGoip'] ?? 0) + ($data['summaryData']['kartuBelumRegister'] ?? 0) + ($data['summaryData']['whatsappTerpakai'] ?? 0)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($chartImage))
            <div class="chart-container">
                <img src="{{ $chartImage }}" alt="Chart">
            </div>
        @endif
    </div>

    <!-- Halaman Kedua: Tabel -->
    <div class="table-section">
        <div class="table-title">Data Detail</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">{{ $type === 'daily' ? 'Tanggal' : 'Bulan' }}</th>
                    @foreach ($data['datasets'] as $dataset)
                        <th>{{ $dataset['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['labels'] as $index => $label)
                    <tr>
                        <td><strong>{{ $label }}</strong></td>
                        @foreach ($data['datasets'] as $dataset)
                            <td>{{ number_format($dataset['data'][$index]) }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>TOTAL</td>
                    @foreach ($data['datasets'] as $dataset)
                        <td>{{ number_format(array_sum($dataset['data'])) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>

</html>

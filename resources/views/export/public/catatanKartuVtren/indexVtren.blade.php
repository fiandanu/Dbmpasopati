<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
            font-size: 9px;
        }

        /* Summary Cards */
        .summary-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .summary-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #333;
            text-transform: uppercase;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 8px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-card {
            display: table-cell;
            width: 16.66%;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .summary-card-label {
            font-size: 8px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .summary-card-value {
            font-size: 14px;
            font-weight: bold;
            color: #6f42c1;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead {
            background-color: #6f42c1;
            color: white;
        }

        table thead th {
            padding: 8px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        table tbody td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 8px;
            text-align: center;
        }

        table tbody td:nth-child(2) {
            text-align: left;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Laporan Data Catatan Kartu VTREN</p>
    </div>

    <div class="info">
        <strong>Tanggal Generate:</strong> {{ $generated_at }}<br>
        <strong>Total Data:</strong> {{ $total_records ?? count($data) }} record
    </div>

    @if (count($data) > 0)
        <!-- Total Summary Section -->
        <div class="summary-section">
            <div class="summary-title">Total Kalkulasi Data</div>
            <div class="summary-grid">
                <div class="summary-row">
                    <div class="summary-card">
                        <div class="summary-card-label">Kartu Baru</div>
                        <div class="summary-card-value">
                            {{ number_format($totals['kartu_baru']) }}
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-label">Kartu Bekas</div>
                        <div class="summary-card-value">
                            {{ number_format($totals['kartu_bekas']) }}
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-label">Kartu GOIP</div>
                        <div class="summary-card-value">
                            {{ number_format($totals['kartu_goip']) }}
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-label">Belum Register</div>
                        <div class="summary-card-value">
                            {{ number_format($totals['kartu_belum_register']) }}
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-label">WA Terpakai</div>
                        <div class="summary-card-value">
                            {{ number_format($totals['whatsapp_terpakai']) }}
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-card-label">Total Terpakai</div>
                        <div class="summary-card-value">
                            {{ number_format($totals['kartu_terpakai_perhari']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 14%;">Nama Ponpes</th>
                    <th style="width: 7%;">Kartu Baru</th>
                    <th style="width: 7%;">Kartu Bekas</th>
                    <th style="width: 7%;">Kartu GOIP</th>
                    <th style="width: 7%;">Belum Register</th>
                    <th style="width: 7%;">WhatsApp</th>
                    <th style="width: 7%;">Jumlah Kartu Terpakai</th>
                    <th style="width: 12%;">Card Supporting</th>
                    <th style="width: 10%;">PIC</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $no++ }}</td>
                            {{ $d->tanggal ? \Carbon\Carbon::parse($d->tanggal)->format('d M Y') : '-' }}
                        </td>
                        <td>{{ $d->ponpes->nama_ponpes ?? '-' }}</td>
                        <td>{{ $d->spam_vtren_kartu_baru ?? '-' }}</td>
                        <td>{{ $d->spam_vtren_kartu_bekas ?? '-' }}</td>
                        <td>{{ $d->spam_vtren_kartu_goip ?? '-' }}</td>
                        <td>{{ $d->kartu_belum_teregister ?? '-' }}</td>
                        <td>{{ $d->whatsapp_telah_terpakai ?? '-' }}</td>
                        <td>{{ $d->jumlah_kartu_terpakai_perhari ?? '-' }}</td>
                        <td>{{ $d->card_supporting ?? '-' }}</td>
                        <td>{{ $d->pic ?? '-' }}</td>
                        <td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Dokumen ini digenerate secara otomatis oleh sistem Database UPT</p>
            <p>&copy; {{ date('Y') }} Database UPT - All Rights Reserved</p>
        </div>
    @else
        <div class="no-data">
            <p>Tidak ada data catatan kartu VTREN yang tersedia</p>
        </div>
    @endif
</body>

</html>

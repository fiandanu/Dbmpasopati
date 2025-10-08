<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: poppins, sans-serif;
            margin: 20px;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #4a5568;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #2d3748;
            font-weight: 700;
        }

        .info {
            margin-bottom: 20px;
            font-size: 10px;
            color: #718096;
            text-align: right;
        }

        /* Total Summary Cards */
        .summary-section {
            border-radius: 6px;
            /* margin-bottom: 25px; */
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            /* background: #718096; */
            /* padding: 20px; */
            /* border-radius: 8px; */
        }

        .summary-title {
            text-align: center;
            /* color: #ffffff; */
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 10px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-card {
            display: table-cell;
            width: 16.66%;
            /* background-color: #ffffff; */
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .summary-card-label {
            font-size: 9px;
            color: #718096;
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .summary-card-value {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
/* 
        .card-blue .summary-card-value {
            color: #3182ce;
        }

        .card-green .summary-card-value {
            color: #38a169;
        }

        .card-cyan .summary-card-value {
            color: #00b5d8;
        }

        .card-orange .summary-card-value {
            color: #dd6b20;
        }

        .card-red .summary-card-value {
            color: #e53e3e;
        }

         .summary-card-value {
            color: #805ad5;
        } */

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #404650;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f7fafc;
        }

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        tbody tr:hover {
            background-color: #edf2f7;
        }

        .text-center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
            font-style: italic;
            background-color: #f7fafc;
            border-radius: 8px;
            border: 2px dashed #cbd5e0;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #718096;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="info">
        <p><strong>Generated on:</strong> {{ $generated_at }}</p>
    </div>

    @if (count($data) > 0)
        <!-- Total Summary Section -->
        <div class="summary-section">
            <div class="summary-title"> Total Kalkulasi Data</div>
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
                    <div class="summary-card ">
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
                    <th style="width: 14%;">Nama UPT</th>
                    <th style="width: 7%;">Kartu Baru</th>
                    <th style="width: 7%;">Kartu Bekas</th>
                    <th style="width: 7%;">Kartu GOIP</th>
                    <th style="width: 7%;">Belum Register</th>
                    <th style="width: 7%;">WhatsApp</th>
                    <th style="width: 12%;">Card Supporting</th>
                    <th style="width: 10%;">PIC</th>
                    <th style="width: 7%;">Terpakai</th>
                    <th style="width: 10%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d->nama_upt ?? '-' }}</td>
                        <td class="text-center">{{ $d->spam_vpas_kartu_baru ?? '-' }}</td>
                        <td class="text-center">{{ $d->spam_vpas_kartu_bekas ?? '-' }}</td>
                        <td class="text-center">{{ $d->spam_vpas_kartu_goip ?? '-' }}</td>
                        <td class="text-center">{{ $d->kartu_belum_teregister ?? '-' }}</td>
                        <td class="text-center">{{ $d->whatsapp_telah_terpakai ?? '-' }}</td>
                        <td class="text-center">{{ $d->card_supporting ?? '-' }}</td>
                        <td>{{ $d->pic ?? '-' }}</td>
                        <td class="text-center">{{ $d->jumlah_kartu_terpakai_perhari ?? '-' }}</td>
                        <td class="text-center">
                            {{ $d->tanggal ? \Carbon\Carbon::parse($d->tanggal)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Dokumen ini digenerate secara otomatis oleh sistem | Total {{ $total_records ?? count($data) }} data</p>
        </div>
    @else
        <div class="no-data">
            <p>📭 Tidak ada data catatan kartu VPAS yang tersedia</p>
        </div>
    @endif
</body>

</html>

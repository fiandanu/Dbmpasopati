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

        table tbody td:nth-child(1),
        table tbody td:nth-child(2),
        table tbody td:nth-child(3),
        table tbody td:nth-child(4) {
            text-align: left;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Laporan Data Monitoring Client PONPES VTREN</p>
    </div>

    <div class="info">
        <strong>Tanggal Generate:</strong> {{ $generated_at }}<br>
        <strong>Total Data:</strong> {{ count($data) }} record
    </div>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Nama PONPES</th>
                    <th style="width: 12%;">Nama Wilayah</th>
                    <th style="width: 13%;">Jenis Kendala</th>
                    <th style="width: 15%;">Detail Kendala</th>
                    <th style="width: 10%;">Tanggal Terlapor</th>
                    <th style="width: 10%;">Tanggal Selesai</th>
                    <th style="width: 7%;">Durasi</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 4%;">PIC 1</th>
                    <th style="width: 4%;">PIC 2</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    @php
                        // Determine badge class based on status
                        $statusClass = match (strtolower($d->status ?? '')) {
                            'selesai' => 'badge-success',
                            'proses' => 'badge-warning',
                            'terjadwal' => 'badge-info',
                            default => 'badge-secondary',
                        };
                    @endphp
                    <tr>
                        <td>{{ $d->ponpes->nama_ponpes ?? '-' }}</td>
                        <td>{{ $d->ponpes->namaWilayah->nama_wilayah ?? '-' }}</td>
                        <td>{{ Str::limit($d->jenis_kendala ?? 'Belum ditentukan', 25) }}</td>
                        <td>{{ Str::limit($d->detail_kendala ?? '-', 40) }}</td>
                        <td>
                            {{ $d->tanggal_terlapor ? \Carbon\Carbon::parse($d->tanggal_terlapor)->format('d M Y') : '-' }}
                        </td>
                        <td>
                            {{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') : '-' }}
                        </td>
                        <td>{{ $d->durasi_hari ? $d->durasi_hari . ' hari' : '-' }}</td>
                        <td>
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($d->status ?? 'Belum ditentukan') }}
                            </span>
                        </td>
                        <td>{{ $d->pic_1 ?? '-' }}</td>
                        <td>{{ $d->pic_2 ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data monitoring client VTREN yang tersedia</p>
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Monitoring Client PONPES</p>
        <p>&copy; {{ date('Y') }} Database PONPES - All Rights Reserved</p>
    </div>
</body>

</html>

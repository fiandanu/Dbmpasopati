<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
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

        table tbody td:nth-child(2),
        table tbody td:nth-child(3) {
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

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .tag {
            background-color: #6f42c1;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            display: inline-block;
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
    </div>
    <div class="info">
        <p>Generated on: {{ $generated_at }}</p>
    </div>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Nama UPT</th>
                    <th style="width: 10%;">Kanwil</th>
                    <th style="width: 12%;">Jenis Layanan</th>
                    <th style="width: 15%;">Keterangan</th>
                    <th style="width: 15%;">Jadwal</th>
                    <th style="width: 15%;">Tanggal Selesai</th>
                    <th style="width: 8%;">Durasi hari</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 8%;">PIC 1</th>
                    <th style="width: 7%;">PIC 2</th>
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
                            'pending' => 'badge-danger',
                            'terjadwal' => 'badge-warning',
                            default => 'badge-secondary',
                        };

                        // Determine layanan type styling
                        $layananText = match (strtolower($d->jenis_layanan ?? '')) {
                            'vpas' => 'VPAS',
                            'reguler' => 'Reguler',
                            'vpasreg' => 'VPAS + Reguler',
                            default => $d->jenis_layanan ?? '-',
                        };
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $d->upt->namaupt ?? '-' }}</td>
                        <td>{{ $d->upt->kanwil->kanwil ?? '-' }}</td>
                        <td>
                            <span class="tag">{{ $layananText }}</span>
                        </td>
                        <td>{{ Str::limit($d->keterangan ?? '-', 50) }}</td>
                        <td>
                            {{ $d->jadwal ? \Carbon\Carbon::parse($d->jadwal)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td>
                            {{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td>
                            @if ($d->durasi_hari)
                                {{ $d->durasi_hari }} hari
                            @else
                                -
                            @endif
                        </td>
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
            <p>Tidak ada data monitoring client Reguler yang tersedia</p>
        </div>
    @endif
</body>

</html>

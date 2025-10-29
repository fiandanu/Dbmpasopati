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

        .text-center {
            text-align: center;
        }

        /* Status Classes */
        .status-selesai {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }

        .status-proses {
            background-color: #ffc107;
            color: #333;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }

        .status-terjadwal {
            background-color: #17a2b8;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }

        .status-pending {
            background-color: #6c757d;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }

        /* Badge Classes (jika diperlukan di masa depan) */
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

        .badge-vpas {
            background-color: #6f42c1;
            color: white;
        }

        .badge-reguler {
            background-color: #17a2b8;
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

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 10px;
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
                    <th style="width: 12%;">Kanwil</th>
                    <th style="width: 15%;">Jenis Kendala</th>
                    <th style="width: 15%;">Detail Kendala</th>
                    <th style="width: 10%;">Tanggal Terlapor</th>
                    <th style="width: 10%;">Tanggal Selesai</th>
                    <th style="width: 8%;">Durasi</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 5%;">PIC 1</th>
                    <th style="width: 5%;">PIC 2</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    @php
                        // Determine CSS class based on status
                        $statusClass = 'status-pending';
                        switch (strtolower($d->status ?? '')) {
                            case 'selesai':
                                $statusClass = 'status-selesai';
                                break;
                            case 'proses':
                                $statusClass = 'status-proses';
                                break;
                            case 'terjadwal':
                                $statusClass = 'status-terjadwal';
                                break;
                            default:
                                $statusClass = 'status-pending';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d->upt->namaupt ?? '-' }}</td>
                        <td>{{ $d->upt->kanwil->kanwil ?? '-' }}</td>
                        <td>{{ Str::limit($d->jenis_kendala ?? 'Belum ditentukan', 25) }}</td>
                        <td>{{ Str::limit($d->detail_kendala ?? '-', 50) }}</td>
                        <td class="text-center">
                            {{ $d->tanggal_terlapor ? \Carbon\Carbon::parse($d->tanggal_terlapor)->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">{{ $d->durasi_hari ? $d->durasi_hari . ' hari' : '-' }}</td>
                        <td class="text-center">
                            <span class="{{ $statusClass }}">{{ ucfirst($d->status ?? 'Belum ditentukan') }}</span>
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

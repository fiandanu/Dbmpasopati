<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
        }
        .info {
            margin-bottom: 15px;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .status-pending {
            color: #dc3545;
            font-weight: bold;
        }
        .status-proses {
            color: #ffc107;
            font-weight: bold;
        }
        .status-selesai {
            color: #28a745;
            font-weight: bold;
        }
        .status-terjadwal {
            color: #007bff;
            font-weight: bold;
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
                    <th style="width: 15%;">detail Kendala</th>
                    <th style="width: 10%;">Tanggal Terlapor</th>
                    <th style="width: 10%;">Tanggal Selesai</th>
                    <th style="width: 8%;">Durasi</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 8%;">PIC 1</th>
                    <th style="width: 7%;">PIC 2</th>
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
                        <td>{{ Str::limit($d->detail_kendala ?? '-') }}</td>
                        <td class="text-center">{{ $d->tanggal_terlapor ? \Carbon\Carbon::parse($d->tanggal_terlapor)->format('d M Y') : '-' }}</td>
                        <td class="text-center">{{ $d->tanggal_selesai ? \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') : '-' }}</td>
                        <td class="text-center">{{ $d->durasi_hari ? $d->durasi_hari . ' hari' : '-' }}</td>
                        <td class="text-center {{ $statusClass }}">{{ ucfirst($d->status ?? 'Belum ditentukan') }}</td>
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

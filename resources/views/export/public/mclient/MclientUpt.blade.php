<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
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
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
        }
        .badge-vpas {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .badge-reguler {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        .badge-success {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        .badge-warning {
            background-color: #fff3e0;
            color: #f57c00;
        }
        .badge-danger {
            background-color: #ffebee;
            color: #d32f2f;
        }
        .badge-secondary {
            background-color: #f5f5f5;
            color: #616161;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Dicetak pada: {{ $generated_at }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama UPT</th>
                <th style="width: 12%;">Kanwil</th>
                <th style="width: 10%;">Tipe</th>
                <th style="width: 13%;">Jenis Layanan</th>
                <th style="width: 25%;">Jenis Kendala</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $item['nama_upt'] }}</td>
                <td>{{ $item['kanwil'] }}</td>
                <td class="text-center">
                    <span class="badge @if($item['tipe'] == 'vpas') badge-vpas @else badge-reguler @endif">
                        {{ ucfirst($item['tipe']) }}
                    </span>
                </td>
                <td class="text-center">{{ $item['jenis_layanan'] }}</td>
                <td>{{ Str::limit($item['jenis_kendala'], 40) }}</td>
                <td class="text-center">
                    @php
                        $statusClass = 'badge-secondary';
                        switch(strtolower($item['status'])) {
                            case 'selesai':
                                $statusClass = 'badge-success';
                                break;
                            case 'proses':
                                $statusClass = 'badge-warning';
                                break;
                            case 'pending':
                                $statusClass = 'badge-danger';
                                break;
                            case 'terjadwal':
                                $statusClass = 'badge-warning';
                                break;
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($item['status']) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info" style="margin-top: 20px;">
        <p><strong>Total Data:</strong> {{ $data->count() }}</p>
    </div>
</body>
</html>

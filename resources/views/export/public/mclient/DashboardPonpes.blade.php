<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
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

        table tbody td:nth-child(2),
        table tbody td:nth-child(3),
        table tbody td:nth-child(4),
        table tbody td:nth-child(5) {
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
        <h2>{{ $title }}</h2>
        <p>Dicetak pada: {{ $generated_at }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama Ponpes</th>
                <th style="width: 12%;">Nama Wilayah</th>
                <th style="width: 25%;">Jenis Kendala/Keterangan</th>
                <th style="width: 13%;">Menu</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($data as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_ponpes'] }}</td>
                    <td>{{ $item['nama_wilayah'] }}</td>
                    <td>{{ Str::limit($item['jenis_kendala'], 40) }}</td>
                    <td class="text-center">{{ $item['jenis_layanan'] }}</td>
                    <td class="text-center">
                        @php
                            $statusClass = 'badge-secondary';
                            switch (strtolower($item['status'])) {
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

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
        <p>Generated on: {{ $generated_at ?? \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 90%;">Nama Wilayah</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d['nama_wilayah'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data Nama Wilayah yang tersedia</p>
        </div>
    @endif
</body>
</html>

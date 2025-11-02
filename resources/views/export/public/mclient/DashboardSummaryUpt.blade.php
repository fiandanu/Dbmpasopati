<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4A5568;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #2D3748;
            font-size: 18pt;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #718096;
            font-size: 9pt;
        }

        .summary-info {
            background-color: #F7FAFC;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #4299E1;
        }

        .summary-info h3 {
            margin: 0 0 10px 0;
            color: #2D3748;
            font-size: 12pt;
        }

        .summary-info p {
            margin: 5px 0;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
        }

        thead {
            background-color: #4A5568;
            color: white;
        }

        th {
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #E2E8F0;
        }

        td {
            padding: 8px;
            border: 1px solid #E2E8F0;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #F7FAFC;
        }

        tbody tr:hover {
            background-color: #EDF2F7;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .font-bold {
            font-weight: bold;
        }

        .bg-blue {
            background-color: #EBF8FF !important;
        }

        .bg-green {
            background-color: #F0FFF4 !important;
        }

        .bg-yellow {
            background-color: #FFFFF0 !important;
        }

        .bg-orange {
            background-color: #FFF5F0 !important;
        }

        .bg-gray {
            background-color: #F7FAFC !important;
        }

        .footer-total {
            background-color: #2D3748 !important;
            color: white !important;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #A0AEC0;
            border-top: 1px solid #E2E8F0;
            padding-top: 15px;
        }

        .footer p {
            margin: 3px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Ringkasan Status Monitoring Client Berdasarkan Kategori Layanan</p>
    </div>

    <div class="summary-info">
        <h3>Informasi Export</h3>
        <p><strong>Tanggal Export:</strong> {{ $generated_at }}</p>
        <p><strong>Total Data Keseluruhan:</strong> {{ number_format($grandTotal) }} Data</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Kategori</th>
                <th style="width: 20%">Jenis Layanan</th>
                <th style="width: 10%">Total Data</th>
                <th style="width: 9%">Belum Ditentukan</th>
                <th style="width: 9%">Pending</th>
                <th style="width: 9%">Proses</th>
                <th style="width: 9%">Terjadwal</th>
                <th style="width: 9%">Selesai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBelumDitentukan = 0;
                $totalPending = 0;
                $totalProses = 0;
                $totalTerjadwal = 0;
                $totalSelesai = 0;
            @endphp

            @foreach ($data as $index => $item)
                @php
                    $totalBelumDitentukan += $item['belum_ditentukan'];
                    $totalPending += $item['pending'];
                    $totalProses += $item['proses'];
                    $totalTerjadwal += $item['terjadwal'];
                    $totalSelesai += $item['selesai'];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left font-bold">{{ $item['kategori'] }}</td>
                    <td class="text-left">{{ $item['layanan'] }}</td>
                    <td class="font-bold">{{ number_format($item['total']) }}</td>
                    <td class="bg-gray">{{ number_format($item['belum_ditentukan']) }}</td>
                    <td class="bg-orange">{{ number_format($item['pending']) }}</td>
                    <td class="bg-blue">{{ number_format($item['proses']) }}</td>
                    <td class="bg-yellow">{{ number_format($item['terjadwal']) }}</td>
                    <td class="bg-green">{{ number_format($item['selesai']) }}</td>
                </tr>
            @endforeach

            <!-- Total Row -->
            <tr class="footer-total">
                <td colspan="3" class="text-right font-bold">TOTAL KESELURUHAN</td>
                <td class="font-bold">{{ number_format($grandTotal) }}</td>
                <td>{{ number_format($totalBelumDitentukan) }}</td>
                <td>{{ number_format($totalPending) }}</td>
                <td>{{ number_format($totalProses) }}</td>
                <td>{{ number_format($totalTerjadwal) }}</td>
                <td>{{ number_format($totalSelesai) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem</p>
        <p>© {{ date('Y') }} Monitoring Client UPT</p>
    </div>
</body>

</html>

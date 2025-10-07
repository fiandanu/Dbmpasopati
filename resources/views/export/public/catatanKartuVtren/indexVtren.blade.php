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

        th,
        td {
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

        .status-aktif {
            color: #28a745;
            font-weight: bold;
        }

        .status-nonaktif {
            color: #dc3545;
            font-weight: bold;
        }

        .status-proses {
            color: #ffc107;
            font-weight: bold;
        }

        .status-pending {
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
                    <th style="width: 15%;">Nama Ponpes</th>
                    <th style="width: 5%;">Kartu Baru</th>
                    <th style="width: 5%;">Kartu Bekas</th>
                    <th style="width: 5%;">Kartu GOIP</th>
                    <th style="width: 5%;">Kartu Belum Register</th>
                    <th style="width: 10%;">WhatsApp</th>
                    <th style="width: 10%;">Card Supporting</th>
                    <th style="width: 5%;">PIC</th>
                    <th style="width: 5%;">Kartu Terpakai</th>
                    <th style="width: 15%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d->nama_ponpes ?? '-' }}</td>
                        <td class="text-center">{{ $d->spam_vtren_kartu_baru ?? '-' }}</td>
                        <td class="text-center">{{ $d->spam_vtren_kartu_bekas ?? '-' }}</td>
                        <td class="text-center">{{ $d->spam_vtren_kartu_goip ?? '-' }}</td>
                        <td class="text-center">{{ $d->kartu_belum_teregister ?? '-' }}</td>
                        <td class="text-center">{{ $d->whatsapp_telah_terpakai ?? '-' }}</td>
                        <td class="text-center">{{ $d->card_supporting ?? '-' }}</td>
                        <td class="text-center">{{ $d->pic ?? '-' }}</td>
                        <td class="text-center">{{ $d->jumlah_kartu_terpakai_perhari ?? '-' }}</td>
                        <td class="text-center">
                            {{ $d->tanggal ? \Carbon\Carbon::parse($d->tanggal)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data catatan kartu VPAS yang tersedia</p>
        </div>
    @endif
</body>

</html>

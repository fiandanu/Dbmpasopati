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

        .status-belum {
            color: #dc3545;
            font-weight: bold;
        }

        .status-sudah {
            color: #28a745;
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .pks-badge {
            background-color: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
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
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Ponpes</th>
                    <th style="width: 12%;">Kanwil</th>
                    <th style="width: 8%;">Tipe</th>
                    <th style="width: 10%;">Tanggal Dibuat</th>
                    <th style="width: 10%;">Tanggal Kontrak</th>
                    <th style="width: 10%;">Tanggal Jatuh Tempo</th>
                    <th style="width: 20%;">Status Upload PDF</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    @php
                        // Use calculated_status if available
                        $status = $d['calculated_status'] ?? 'Unknown';

                        // Determine CSS class based on status
                        if (str_contains(strtolower($status), 'belum')) {
                            $statusClass = 'status-belum';
                        } elseif (str_contains(strtolower($status), 'sudah')) {
                            $statusClass = 'status-sudah';
                        } else {
                            $statusClass = 'status-belum';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d['nama_ponpes'] }}</td>
                        <td>{{ $d->namaWilayah->nama_wilayah}}</td>
                        <td class="text-center">
                            <span class="pks-badge">{{ ucfirst($d['tipe']) }}</span>
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($d['tanggal'])->format('d M Y') }}</td>
                        <td>{{ $d->uploadFolderPks->tanggal_kontrak->format('d M Y') ?? '-' }}</td>
                        <td>{{ $d->uploadFolderPks->tanggal_jatuh_tempo->format('d M Y') ?? '-' }}</td>
                        <td class="text-center {{ $statusClass }}">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data PKS yang tersedia</p>
        </div>
    @endif
</body>

</html>

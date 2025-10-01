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

        .status-proses {
            color: #ffc107;
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
                    <th style="width: 45%;">Tutorial Upt</th>
                    <th style="width: 20%;">Tanggal Dibuat</th>
                    <th style="width: 30%;">Status Upload PDF</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    @php
                        // Hitung status di view
                        $uploaded = 0;
                        for ($i = 1; $i <= 10; $i++) {
                            $column = 'pdf_folder_' . $i;
                            if (!empty($d->$column)) {
                                $uploaded++;
                            }
                        }

                        if ($uploaded == 0) {
                            $status = 'Belum Upload';
                            $statusClass = 'status-belum';
                        } elseif ($uploaded == 10) {
                            $status = '10/10 Folder';
                            $statusClass = 'status-sudah';
                        } else {
                            $status = $uploaded . '/10 Terupload';
                            $statusClass = 'status-proses';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d->tutor_reguller }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}</td>
                        <td class="text-center {{ $statusClass }}">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data Tutorial Reguler yang tersedia</p>
        </div>
    @endif
</body>

</html>

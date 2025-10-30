<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .badge-reguler {
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
        <h1>{{ $title }}</h1>
        <p>Laporan Data Reguler</p>
    </div>

    <div class="info">
        <strong>Tanggal Generate:</strong> {{ $generated_at ?? \Carbon\Carbon::now()->format('d M Y H:i:s') }}<br>
        <strong>Total Data:</strong> {{ count($data) }} record
    </div>

    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 30%;">Nama UPT</th>
                    <th style="width: 15%;">Kanwil</th>
                    <th style="width: 10%;">Tipe</th>
                    <th style="width: 15%;">Tanggal Dibuat</th>
                    <th style="width: 25%;">Status Update</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    @php
                        // Use calculated_status if available, otherwise calculate
                        if (isset($d['calculated_status'])) {
                            $status = $d['calculated_status'];
                        } else {
                            // Fallback calculation if calculated_status not available
                            $dataOpsional = (object) ($d['db_opsional_upt'] ?? null);
                            $filledFields = 0;
                            if ($dataOpsional) {
                                foreach ($optionalFields as $field) {
                                    if (!empty($dataOpsional->$field ?? '')) {
                                        $filledFields++;
                                    }
                                }
                            }
                            $totalFields = count($optionalFields);
                            $percentage = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;

                            if ($filledFields == 0) {
                                $status = 'Belum di Update';
                            } elseif ($filledFields == $totalFields) {
                                $status = 'Sudah Update';
                            } else {
                                $status = "Sebagian ({$percentage}%)";
                            }
                        }

                        // Determine badge class based on status
                        if (str_contains(strtolower($status), 'belum')) {
                            $badgeClass = 'badge-secondary';
                        } elseif (str_contains(strtolower($status), 'sudah')) {
                            $badgeClass = 'badge-success';
                        } else {
                            $badgeClass = 'badge-warning';
                        }
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $d->namaupt }}</td>
                        <td>{{ $d->kanwil->kanwil }}</td>
                        <td>
                            <span class="badge badge-reguler">{{ ucfirst($d->tipe) }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p><strong>Tidak ada data yang ditemukan</strong></p>
            <p>Tidak ada record yang sesuai dengan kriteria filter saat ini.</p>
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Database UPT</p>
        <p>&copy; {{ date('Y') }} Database UPT - All Rights Reserved</p>
    </div>
</body>

</html>

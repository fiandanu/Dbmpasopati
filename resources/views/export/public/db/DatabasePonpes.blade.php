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

        .tag {
            background-color: #6f42c1;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            display: inline-block;
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
        <p>Laporan Data PONPES Keseluruhan</p>
    </div>

    <div class="info">
        <strong>Tanggal Generate:</strong> {{ $generated_at }}<br>
        <strong>Total Data:</strong> {{ $data->count() }} record
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 22%;">Nama PONPES</th>
                <th style="width: 12%;">Nama Wilayah</th>
                <th style="width: 13%;">Status PKS</th>
                <th style="width: 13%;">Status SPP</th>
                <th style="width: 10%;">Extension</th>
                <th style="width: 19%;">Status Wartel</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($data as $d)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $d->nama_ponpes }}</td>
                    <td>
                        <span>{{ $d->namaWilayah->nama_wilayah }}</span>
                    </td>
                    <td>
                        @php
                            $hasPdf1 = $d->uploadFolderPks && !empty($d->uploadFolderPks->uploaded_pdf_1);
                            $hasPdf2 = $d->uploadFolderPks && !empty($d->uploadFolderPks->uploaded_pdf_2);
                        @endphp

                        @if (!$hasPdf1 && !$hasPdf2)
                            <span class="badge badge-secondary">Belum Upload</span>
                        @elseif ($hasPdf1 && $hasPdf2)
                            <span class="badge badge-success">Sudah Upload (2/2)</span>
                        @else
                            <span class="badge badge-warning">Sudah Upload (1/2)</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $uploadedFolders = 0;
                            if ($d->uploadFolderSpp) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $column = 'pdf_folder_' . $i;
                                    if (!empty($d->uploadFolderSpp->$column)) {
                                        $uploadedFolders++;
                                    }
                                }
                            }
                        @endphp

                        @if ($uploadedFolders == 0)
                            <span class="badge badge-secondary">Belum Upload</span>
                        @elseif($uploadedFolders == 10)
                            <span class="badge badge-success">10/10 Folder</span>
                        @else
                            <span class="badge badge-warning">{{ $uploadedFolders }}/10</span>
                        @endif
                    </td>
                    <td>
                        @if ($d->dataOpsional)
                            {{ $d->dataOpsional->jumlah_extension ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($d->dataOpsional)
                            @if (isset($d->dataOpsional->status_wartel))
                                @if ($d->dataOpsional->status_wartel == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Database PONPES</p>
        <p>&copy; {{ date('Y') }} Database PONPES - All Rights Reserved</p>
    </div>
</body>

</html>

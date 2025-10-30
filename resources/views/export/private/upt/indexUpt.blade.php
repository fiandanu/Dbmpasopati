<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            padding: 6px 10px;
            border: 1px solid #ddd;
            font-size: 8px;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .section-header {
            background-color: #6f42c1;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }

        .label-cell {
            white-space: nowrap;
            width: 30%;
            font-weight: 600;
            background-color: #f5f5f5;
        }

        .extension-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .extension-table thead {
            background-color: #6f42c1;
            color: white;
        }

        .extension-table th {
            padding: 8px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .extension-table td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 8px;
            text-align: center;
            word-wrap: break-word;
        }

        .extension-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
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
        <p>Detail Data UPT VPAS</p>
    </div>

    <table>
        <tr>
            <td colspan="2" class="section-header">Data Opsional</td>
        </tr>
        <tr>
            <td class="label-cell">PIC UPT</td>
            <td>{{ $user->dataOpsional->pic_upt ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">No. Telpon</td>
            <td>{{ $user->dataOpsional->no_telpon ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Alamat</td>
            <td>{{ $user->dataOpsional->alamat ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Kanwil</td>
            <td>{{ $user->kanwil->kanwil }}</td>
        </tr>
        <tr>
            <td class="label-cell">Jumlah WBP</td>
            <td>{{ $user->dataOpsional->jumlah_wbp ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Jumlah Line Reguler Terpasang</td>
            <td>{{ $user->dataOpsional->jumlah_line ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Provider Internet</td>
            <td>{{ $user->dataOpsional->provider_internet ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Kecepatan Internet (mbps)</td>
            <td>{{ $user->dataOpsional->kecepatan_internet ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Tarif Wartel</td>
            <td>{{ $user->dataOpsional->tarif_wartel ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Status Wartel</td>
            <td>
                @php
                    $status = $user->dataOpsional->status_wartel ?? '';
                    if (empty($status)) {
                        echo '';
                    } else {
                        $status = strtolower(trim($status));
                        if ($status === 'aktif' || $status === '1' || $status === 'active') {
                            echo 'Aktif';
                        } elseif ($status === 'tidak aktif' || $status === 'nonaktif' || $status === '0' || $status === 'inactive') {
                            echo 'Tidak Aktif';
                        } else {
                            echo ucfirst($status);
                        }
                    }
                @endphp
            </td>
        </tr>

        <tr>
            <td colspan="2" class="section-header">IMC PAS</td>
        </tr>
        <tr>
            <td class="label-cell">Akses Topup Pulsa</td>
            <td>{{ $user->dataOpsional->akses_topup_pulsa ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Password Topup</td>
            <td>{{ $user->dataOpsional->password_topup ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Akses Download Rekaman</td>
            <td>{{ $user->dataOpsional->akses_download_rekaman ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Password Download</td>
            <td>{{ $user->dataOpsional->password_download ?? '' }}</td>
        </tr>

        <tr>
            <td colspan="2" class="section-header">Akses VPN</td>
        </tr>
        <tr>
            <td class="label-cell">Internet Protocol</td>
            <td>{{ $user->dataOpsional->internet_protocol ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">VPN User</td>
            <td>{{ $user->dataOpsional->vpn_user ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">VPN Password</td>
            <td>{{ $user->dataOpsional->vpn_password ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Jenis VPN</td>
            <td>{{ $user->dataOpsional->vpn->jenis_vpn ?? '' }}</td>
        </tr>

        <tr>
            <td colspan="2" class="section-header">Extension VPAS</td>
        </tr>
        <tr>
            <td class="label-cell">Jumlah Extension</td>
            <td>{{ $user->dataOpsional->jumlah_extension ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">PIN Tes</td>
            <td>{{ $user->dataOpsional->pin_tes ?? '' }}</td>
        </tr>
    </table>

    <!-- Tabel Extension Terpisah -->
    <div class="section-header">Daftar No Pemanggil, Email AirDroid dan Password</div>
    <table class="extension-table">
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 30%;">No Pemanggil</th>
                <th style="width: 30%;">Email AirDroid</th>
                <th style="width: 30%;">Password</th>
            </tr>
        </thead>
        <tbody>
            @php
                $extensions = explode("\n", $user->dataOpsional->no_pemanggil ?? '');
                $emails = explode("\n", $user->dataOpsional->email_airdroid ?? '');
                $passwords = explode("\n", $user->dataOpsional->password ?? '');
                $maxRows = max(count($extensions), count($emails), count($passwords));
            @endphp

            @for($i = 0; $i < $maxRows; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $extensions[$i] ?? '' }}</td>
                    <td>{{ $emails[$i] ?? '' }}</td>
                    <td>{{ $passwords[$i] ?? '' }}</td>
                </tr>
            @endfor

            @if($maxRows == 0)
                <tr>
                    <td colspan="4" style="text-align: center; font-style: italic;">Tidak ada data extension</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Database UPT</p>
        <p>&copy; {{ date('Y') }} Database UPT - All Rights Reserved</p>
    </div>
</body>

</html>

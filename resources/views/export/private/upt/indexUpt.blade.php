<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: left;
        }

        .section-header {
            font-weight: bold;
            background-color: #e4e4e4;
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
            width: 30%;
        }

        .extension-table {
            width: 100%;
            border-collapse: collapse;
        }

        .extension-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .extension-table td {
            text-align: center;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="title">{{ $title }}</div>
    <table>
        <tr>
            <td colspan="2" class="section-header">Data Opsional</td>
        </tr>
        <tr>
            <td class="nowrap">PIC UPT</td>
            <td>{{ $user->dataOpsional->pic_upt ?? '' }}</td>
        </tr>
        <tr>
            <td>No. Telpon</td>
            <td>{{ $user->dataOpsional->no_telpon ?? '' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>{{ $user->dataOpsional->alamat ?? '' }}</td>
        </tr>
        <tr>
            <td>Kanwil</td>
            <td>{{ $user->kanwil->kanwil }}</td>
        </tr>
        <tr>
            <td>Jumlah WBP</td>
            <td>{{ $user->dataOpsional->jumlah_wbp ?? '' }}</td>
        </tr>
        <tr>
            <td>Jumlah Line Reguler Terpasang</td>
            <td>{{ $user->dataOpsional->jumlah_line ?? '' }}</td>
        </tr>
        <tr>
            <td>Provider Internet</td>
            <td>{{ $user->dataOpsional->provider_internet ?? '' }}</td>
        </tr>
        <tr>
            <td>Kecepatan Internet (mbps)</td>
            <td>{{ $user->dataOpsional->kecepatan_internet ?? '' }}</td>
        </tr>
        <tr>
            <td>Tarif Wartel</td>
            <td>{{ $user->dataOpsional->tarif_wartel ?? '' }}</td>
        </tr>

        <tr>
            <td>Status Wartel</td>
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
            <td>Akses Topup Pulsa</td>
            <td>{{ $user->dataOpsional->akses_topup_pulsa ?? '' }}</td>
        </tr>
        <tr>
            <td>Password Topup</td>
            <td>{{ $user->dataOpsional->password_topup ?? '' }}</td>
        </tr>
        <tr>
            <td>Akses Download Rekaman</td>
            <td>{{ $user->dataOpsional->akses_download_rekaman ?? '' }}</td>
        </tr>
        <tr>
            <td>Password Download</td>
            <td>{{ $user->dataOpsional->password_download ?? '' }}</td>
        </tr>

        <tr>
            <td colspan="2" class="section-header">Akses VPN</td>
        </tr>
        <tr>
            <td>Internet Protocol</td>
            <td>{{ $user->dataOpsional->internet_protocol ?? '' }}</td>
        </tr>
        <tr>
            <td>VPN User</td>
            <td>{{ $user->dataOpsional->vpn_user ?? '' }}</td>
        </tr>
        <tr>
            <td>VPN Password</td>
            <td>{{ $user->dataOpsional->vpn_password ?? '' }}</td>
        </tr>
        <tr>
            <td>Jenis VPN</td>
            <td>{{ $user->dataOpsional->vpn->jenis_vpn ?? '' }}</td>
        </tr>

        <tr>
            <td colspan="2" class="section-header">Extension VPAS</td>
        </tr>
        <tr>
            <td>Jumlah Extension</td>
            <td>{{ $user->dataOpsional->jumlah_extension ?? '' }}</td>
        </tr>
        <tr>
            <td>PIN Tes</td>
            <td>{{ $user->dataOpsional->pin_tes ?? '' }}</td>
        </tr>
    </table>

    <!-- Tabel Extension Terpisah -->
    <div class="section-header"
        style="text-align: center; font-weight: bold; background-color: #e4e4e4; padding: 8px; margin-bottom: 10px;">
        Daftar Extension dan Password
    </div>
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
                    <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada data extension</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>

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
        }
        td, th {
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
    </style>
</head>
<body>
    <div class="title">{{ $title }}</div>
    <table>
        <tr><td colspan="2" class="section-header">Data Opsional</td></tr>
        <tr><td class="nowrap">PIC UPT</td><td>{{ $user->pic_upt }}</td></tr>
        <tr><td>No. Telpon</td><td>{{ $user->no_telpon }}</td></tr>
        <tr><td>Alamat</td><td>{{ $user->alamat }}</td></tr>
        <tr><td>Kanwil</td><td>{{ $user->kanwil }}</td></tr>
        <tr><td>Jumlah WBP</td><td>{{ $user->jumlah_wbp }}</td></tr>
        <tr><td>Jumlah Line Reguler Terpasang</td><td>{{ $user->jumlah_line_reguler }}</td></tr>
        <tr><td>Provider Internet</td><td>{{ $user->provider_internet }}</td></tr>
        <tr><td>Kecepatan Internet (mbps)</td><td>{{ $user->kecepatan_internet }}</td></tr>
        <tr><td>Tarif Wartel Reguler</td><td>{{ $user->tarif_wartel_reguler }}</td></tr>
        <tr><td>Status Wartel</td><td>{{ $user->status_wartel }}</td></tr>

        <tr><td colspan="2" class="section-header">IMC PAS</td></tr>
        <tr><td>Akses Topup Pulsa</td><td>{{ $user->akses_topup_pulsa }}</td></tr>
        <tr><td>Password Topup</td><td>{{ $user->password_topup }}</td></tr>
        <tr><td>Akses Download Rekaman</td><td>{{ $user->akses_download_rekaman }}</td></tr>
        <tr><td>Password Download</td><td>{{ $user->password_download }}</td></tr>

        <tr><td colspan="2" class="section-header">Akses VPN</td></tr>
        <tr><td>Internet Protocol</td><td>{{ $user->internet_protocol }}</td></tr>
        <tr><td>VPN User</td><td>{{ $user->vpn_user }}</td></tr>
        <tr><td>VPN Password</td><td>{{ $user->vpn_password }}</td></tr>
        <tr><td>Jenis VPN</td><td>{{ $user->jenis_vpn }}</td></tr>

        <tr><td colspan="2" class="section-header">Extension Reguler</td></tr>
        <tr><td>Jumlah Extension</td><td>{{ $user->jumlah_extension }}</td></tr>
        <tr><td>No Extension</td><td>{{ $user->no_extension }}</td></tr>
        <tr><td>Password Extension</td><td>{{ $user->extension_password }}</td></tr>
        <tr><td>PIN Tes</td><td>{{ $user->pin_tes }}</td></tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .card-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .card {
            display: table-cell;
            width: 48%;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            border-radius: 5px;
        }
        .card h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #333;
        }
        .card .subtitle {
            color: #666;
            font-size: 11px;
            margin-bottom: 10px;
        }
        .card .total {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .legend-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
            display: table;
            width: 100%;
        }
        .legend-item:last-child {
            border-bottom: none;
        }
        .legend-text {
            display: table-cell;
            width: 70%;
        }
        .legend-count {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }
        .total-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .total-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .total-card h4 {
            margin: 0 0 10px 0;
            color: #555;
        }
        .total-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ $generated_at }}</p>
    </div>

    <h2 style="margin-bottom: 15px;">Statistik Kategori</h2>

    <div class="card-container">
        <div class="card">
            <h3>PKS</h3>
            <p class="subtitle">Surat Perjanjian Kerja Sama</p>
            <div class="total">{{ $pksStats['total'] }} Data</div>
            <div class="legend-item">
                <span class="legend-text">Belum upload</span>
                <span class="legend-count">{{ $pksStats['belum_upload'] }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-text">Sebagian</span>
                <span class="legend-count">{{ $pksStats['sebagian'] }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-text">Lengkap</span>
                <span class="legend-count">{{ $pksStats['sudah_upload'] }}</span>
            </div>
        </div>

        <div class="card">
            <h3>SPP</h3>
            <p class="subtitle">Surat Perintah Pemasangan</p>
            <div class="total">{{ $sppStats['total'] }} Data</div>
            <div class="legend-item">
                <span class="legend-text">Belum upload</span>
                <span class="legend-count">{{ $sppStats['belum_upload'] }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-text">Sebagian</span>
                <span class="legend-count">{{ $sppStats['sebagian'] }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-text">Lengkap</span>
                <span class="legend-count">{{ $sppStats['sudah_upload'] }}</span>
            </div>
        </div>
    </div>

    <div class="card-container">
        <div class="card">
            <h3>VPAS</h3>
            <p class="subtitle">Layanan VPAS</p>
            <div class="total">{{ $VpasWartelStats['total'] }} Data</div>
            <div class="legend-item">
                <span class="legend-text">Wartel tidak aktif</span>
                <span class="legend-count">{{ $VpasWartelStats['tidak_aktif'] }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-text">Wartel aktif</span>
                <span class="legend-count">{{ $VpasWartelStats['aktif'] }}</span>
            </div>
        </div>

        <div class="card">
            <h3>REGULER</h3>
            <p class="subtitle">Layanan Reguler</p>
            <div class="total">{{ $RegulerWartelStats['total'] }} Data</div>
            <div class="legend-item">
                <span class="legend-text">Wartel tidak aktif</span>
                <span class="legend-count">{{ $RegulerWartelStats['tidak_aktif'] }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-text">Wartel aktif</span>
                <span class="legend-count">{{ $RegulerWartelStats['aktif'] }}</span>
            </div>
        </div>
    </div>

    <div class="total-section">
        <h2 style="margin-bottom: 15px;">Total Data</h2>
        
        <div class="total-card">
            <h4>Total Data UPT</h4>
            <div class="number">{{ number_format($totalUpt) }}</div>
        </div>

        <div class="total-card">
            <h4>VPAS Extension</h4>
            <div class="number">{{ number_format($totalExtensionVpas) }}</div>
        </div>

        <div class="total-card">
            <h4>Reguler Extension</h4>
            <div class="number">{{ number_format($totalExtensionReguler) }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Database UPT - Sistem Informasi Management</p>
    </div>
</body>
</html>
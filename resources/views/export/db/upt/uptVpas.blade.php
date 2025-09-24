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
        th, td { 
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
        .status-sebagian { 
            color: #ffc107;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        .vpas-badge {
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
                            $dataOpsional = (object) ($d['data_opsional_upt'] ?? null);
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
                        
                        // Determine CSS class based on status
                        if (str_contains(strtolower($status), 'belum')) {
                            $statusClass = 'status-belum';
                        } elseif (str_contains(strtolower($status), 'sudah')) {
                            $statusClass = 'status-sudah';
                        } else {
                            $statusClass = 'status-sebagian';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $d['namaupt'] }}</td>
                        <td>{{ $d['kanwil'] }}</td>
                        <td class="text-center">
                            <span class="vpas-badge">{{ ucfirst($d['tipe']) }}</span>
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($d['tanggal'])->format('d M Y') }}</td>
                        <td class="text-center {{ $statusClass }}">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        </div>
    @endif
</body>
</html>
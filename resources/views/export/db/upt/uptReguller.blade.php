<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-belum { color: red; }
        .status-sudah { color: green; }
        .status-sebagian { color: orange; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    @if (count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama UPT</th>
                    <th>Kanwil</th>
                    <th>Tipe</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status Update</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $d)
                    @php
                        $dataOpsional = (object) ($d['data_opsional_upt'] ?? null); // Handle relation as array
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
                            $statusClass = 'status-belum';
                        } elseif ($filledFields == $totalFields) {
                            $status = 'Sudah Update';
                            $statusClass = 'status-sudah';
                        } else {
                            $status = "Sebagian ({$percentage}%)";
                            $statusClass = 'status-sebagian';
                        }
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $d['namaupt'] }}</td>
                        <td>{{ $d['kanwil'] }}</td>
                        <td>{{ ucfirst($d['tipe']) }}</td>
                        <td>{{ \Carbon\Carbon::parse($d['tanggal'])->translatedFormat('d M Y') }}</td>
                        <td class="{{ $statusClass }}">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No data found.</p>
    @endif
</body>
</html>
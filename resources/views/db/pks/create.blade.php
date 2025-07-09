<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo PKS Detail</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .animated {
            animation-duration: 0.3s;
        }

        .fadeInDown {
            animation-name: fadeInDown;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -100%, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data PKS - Demo</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama UPT</th>
                                    <th>Kanwil</th>
                                    <th>Tanggal Upload File</th>
                                    <th>Upload File</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <span class="text-primary font-weight-bold">UPT Jakarta Pusat</span>
                                    </td>
                                    <td>Kanwil DKI Jakarta</td>
                                    <td>11-7-2024</td>
                                    <td><a href="#" class="btn btn-sm btn-info">Download</a></td>
                                    <td><span class="badge badge-success">Approved</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="showDetail('UPT Jakarta Pusat', 'Kanwil DKI Jakarta', '11-7-2024', 'dokumen_laporan.pdf', 'Approved', 'Laporan bulanan PKS periode Juli 2024')"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <span class="text-primary font-weight-bold">UPT Bandung</span>
                                    </td>
                                    <td>Kanwil Jawa Barat</td>
                                    <td>10-7-2024</td>
                                    <td><a href="#" class="btn btn-sm btn-info">Download</a></td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="showDetail('UPT Bandung', 'Kanwil Jawa Barat', '10-7-2024', 'laporan_pks.pdf', 'Pending', 'Laporan mingguan PKS periode Juli minggu ke-2')"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <span class="text-primary font-weight-bold">UPT Surabaya</span>
                                    </td>
                                    <td>Kanwil Jawa Timur</td>
                                    <td>09-7-2024</td>
                                    <td><a href="#" class="btn btn-sm btn-info">Download</a></td>
                                    <td><span class="badge badge-danger">Rejected</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="showDetail('UPT Surabaya', 'Kanwil Jawa Timur', '09-7-2024', 'data_pks.xlsx', 'Rejected', 'Data PKS bulanan dengan format Excel')"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <span class="text-primary font-weight-bold">UPT Yogyakarta</span>
                                    </td>
                                    <td>Kanwil D.I. Yogyakarta</td>
                                    <td>08-7-2024</td>
                                    <td><a href="#" class="btn btn-sm btn-info">Download</a></td>
                                    <td><span class="badge badge-success">Approved</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="showDetail('UPT Yogyakarta', 'Kanwil D.I. Yogyakarta', '08-7-2024', 'rekap_pks.pdf', 'Approved', 'Rekapitulasi data PKS triwulan II 2024')"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <span class="text-primary font-weight-bold">UPT Semarang</span>
                                    </td>
                                    <td>Kanwil Jawa Tengah</td>
                                    <td>07-7-2024</td>
                                    <td><a href="#" class="btn btn-sm btn-info">Download</a></td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="showDetail('UPT Semarang', 'Kanwil Jawa Tengah', '07-7-2024', 'data_bulanan.xlsx', 'Pending', 'Data PKS bulanan periode Juni 2024')"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetail(namaUpt, kanwil, tanggalUpload, namaFile, status, keterangan) {
            // Menentukan warna badge berdasarkan status
            let statusColor = '';
            let statusIcon = '';

            switch (status.toLowerCase()) {
                case 'approved':
                    statusColor = '#28a745';
                    statusIcon = 'success';
                    break;
                case 'pending':
                    statusColor = '#ffc107';
                    statusIcon = 'warning';
                    break;
                case 'rejected':
                    statusColor = '#dc3545';
                    statusIcon = 'error';
                    break;
                default:
                    statusColor = '#6c757d';
                    statusIcon = 'info';
            }

            Swal.fire({
                title: '<strong>' + namaUpt + '</strong>',
                icon: statusIcon,
                html: `
                    <div class="text-left">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama UPT:</strong></td>
                                <td>${namaUpt}</td>
                            </tr>
                            <tr>
                                <td><strong>Kanwil:</strong></td>
                                <td>${kanwil}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Upload:</strong></td>
                                <td>${tanggalUpload}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama File:</strong></td>
                                <td>${namaFile}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge" style="background-color: ${statusColor}; color: white;">${status}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Keterangan:</strong></td>
                                <td>${keterangan}</td>
                            </tr>
                        </table>
                    </div>
                `,
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                cancelButtonColor: '#aaa',  // Biar beda warna juga
                allowOutsideClick: true,
                allowEscapeKey: true,
                focusConfirm: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                width: '600px',
                padding: '2rem',
                customClass: {
                    popup: 'animated fadeInDown'
                }
            });
        }
    </script>
</body>

</html>
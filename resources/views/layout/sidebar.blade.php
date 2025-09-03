<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dbm | Pasopati</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet"
        href="{{ asset('lte/https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
    {{-- Bootstrap 4 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    {{-- Custom Css --}}

    @vite('resources/css/app.css')
    {{-- Tailwind Css --}}

    <style>
        :root {
            --Netral-10: #FFFFFF;
            --Netral-9: #FAFAFA;
            --Netral-8: #EBEBEB;
            --Netral-7: #DEDEDE;
            --Netral-6: #C7C7C7;
            --Netral-5: #ABABAB;
            --Netral-4: #949494;
            --Netral-3: #757575;
            --Netral-2: #616161;
            --Netral-1: #3D3D3D;
            --Netral-0: #262626;

            --Primary-01: #C7F5D5;
            --Primary-02: #93EBAF;
            --Primary-03: #5BE186;
            --Primary-04: #27D35D;
            --Primary-05: #1D9D45;
            --Primary-06: #177D37;
            --Primary-07: #125F2A;
            --Primary-08: #0C411D;
            --Primary-09: #061E0D;
            --Primary-10: #031108;

            --Secondary-00: #F1F6FE;
            --Secondary-01: #E7F0FD;
            --Secondary-02: #C5DAFC;
            --Secondary-03: #99BFFA;
            --Secondary-04: #639DF7;
            --Secondary-05: #3882F5;
            --Secondary-06: #186CE1;
            --Secondary-07: #155DC2;
            --Secondary-08: #124FA6;
            --Secondary-09: #0D3C7C;
            --Secondary-10: #08244A;

            --yellow-00: #FFFDF0;
            --yellow-01: #FFFAD6;
            --yellow-02: #FFF7B8;
            --yellow-03: #FFF28F;
            --yellow-04: #FFEE70;
            --yellow-05: #FFE942;
            --yellow-06: #FFE314;
            --yellow-07: #D6BD00;
            --yellow-08: #AD9900;
            --yellow-09: #756800;
            --yellow-10: #3D3600;

            --danger-00: #FFF0F1;
            --danger-01: #FFD6D9;
            --danger-02: #FFB8BF;
            --danger-03: #FF8F9A;
            --danger-04: #FF707E;
            --danger-05: #FF4255;
            --danger-06: #FF142B;
            --danger-07: #D60015;
            --danger-08: #AD0011;
            --danger-09: #75000B;
            --danger-10: #3D0006;
        }

        .Tipereguller {
            background-color: var(--Secondary-01);
            width: 74px;
            height: 32px;
            padding: 12px 6px;
            border-radius: 50px;
            border: 1px solid var(--Secondary-03);
            opacity: 1;
            font-family: Arial;
            font-size: 12px;
            color: var(--Secondary-07);
            text-align: center;
            display: inline-flex;
            justify-content: center;
            align-items: center
        }

        .Tipevpas {
            background-color: #C7F5D5;
            width: 74px;
            height: 32px;
            padding: 12px 6px;
            border-radius: 50px;
            border: 1px solid #5BE186;
            opacity: 1;
            font-family: Arial;
            font-size: 12px;
            color: #125F2A;
            text-align: center;
            display: inline-flex;
            justify-content: center;
            align-items: center
        }



        .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #f8fafc;
        }

        .modal-header {
            background-color: #e2e8f0;
            border-bottom: 1px solid #cbd5e0;
            padding: 1rem 1.5rem;
        }

        .modal-title {
            color: #2d3748;
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
            background-color: #ffffff;
            max-height: 500px;
            /* Set maximum height for modal-body */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        .section-title {
            color: #2b6cb0;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .form-label {
            color: #2d3748;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            background-color: #f8fafc;
            color: #2d3748;
        }

        .form-control:focus {
            border-color: #2b6cb0;
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.2);
            background-color: #ffffff;
        }

        .form-control[readonly] {
            background-color: #edf2f7;
            color: #718096;
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%234b5e7a' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E");
            background-size: 12px;
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
        }

        textarea.form-control {
            resize: vertical;
        }

        .text-muted {
            color: #718096;
        }

        .modal-footer {
            background-color: #e2e8f0;
            border-top: 1px solid #cbd5e0;
            padding: 1rem 1.5rem;
        }

        .btn-primary {
            background-color: #2b6cb0;
            border-color: #2b6cb0;
            border-radius: 6px;
        }

        .btn-primary:hover {
            background-color: #2c5282;
            border-color: #2c5282;
        }

        .btn-secondary {
            background-color: #718096;
            border-color: #718096;
            border-radius: 6px;
        }

        .btn-secondary:hover {
            background-color: #5a667a;
            border-color: #5a667a;
        }

        .border-bottom {
            border-color: #cbd5e0;
        }

        .btn-close-custom {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: #333;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            outline: none;
        }

        .btn-close-custom:hover {
            background: #f1f3f5;
            color: #000;
            transform: scale(1.1);
        }

        .btn-close-custom:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .btn-close-custom .bi-x {
            font-size: 1.8rem;
            line-height: 1;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider">
                        </div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class=" nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                        role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Alexander Pierce</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class=" btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        {{-- Data Base --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-database"></i>
                                <p>
                                    DataBase
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('database.DbUpt') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Database UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('database.DataBasePonpes') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Database Ponpes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Data Base --}}

                        {{-- Monitoring Server --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-shield-alt"></i>
                                <p>
                                    Monitoring Server
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('GrafikServer') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Grafik</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('MonitoringUpt') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Monitoring UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('MonitoringPonpes') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Monitoring Ponpes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Monitoring Server --}}

                        {{-- Monitoring Customer --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-eye"></i>
                                <p>
                                    Monitoring Customer
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('GrafikClient') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Grafik</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('KomplainUpt') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Komplain UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('KomplainPonpes') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Komplain Ponpes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('PencatatanKartu') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pencatatan kartu</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Monitoring Customer --}}

                        {{-- Tutorial --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-lightbulb"></i>
                                <p>
                                    Tutorial
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('TutorialUpt') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('TutorialPonpes') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ponpes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tutorial_server') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Server</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tutorial_mikrotik') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Mirkrotik</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Tutorial --}}

                        {{-- User --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-user"></i>
                                <p>
                                    User
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('upt.UserPage') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data UPT</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ponpes.UserPage') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Ponpes</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('provider.DataProvider') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Provider/Vpn</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('kendala.DataKendala') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Kendala/PIC</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- User --}}
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- /.content-wrapper -->
        @yield('content')
        <footer class="main-footer">
            <strong>Copyright &copy; Pasopati Nusantara.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Admin</b>
            </div>
        </footer>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark"> <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('lte/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('lte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('lte/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('lte/dist/js/pages/dashboard.js') }}"></script>
    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
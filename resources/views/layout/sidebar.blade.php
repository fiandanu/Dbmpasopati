<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dbm | Pasopati</title>
    <x-headlink></x-headlink>

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

        .modal-title {
            color: #2d3748;
            font-weight: 600;
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


        .text-muted {
            color: #718096;
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

        .btn-close-custom .ion-icon {
            font-size: 1.8rem;
            line-height: 1;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
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
                                    <a href="{{ route('ListDataMclientCatatanVpas') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pencatatan kartu Vpas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ListDataMclientCatatanVtren') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pencatatan kartu Vtren</p>
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
                                    <a href="{{ route('tutor_ponpes_reguller.TutorialPonpes') }}" class="nav-link">
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
                                    <a href="{{ route('User.UserPage') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data UPT</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('UserPonpes.UserPage') }}" class="nav-link">
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

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark"> <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <x-script></x-script>

</body>

</html>

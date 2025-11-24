<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dbm | Pasopati</title>
    <x-headlink></x-headlink>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-white-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar flex flex-col h-screen overflow-hidden">
                <!-- Sidebar user panel -->
                <div
                    class="d-flex justify-content-center mt-2 py-2 user-panel d-flex align-items-center flex-shrink-0 mb-3">
                    <div class="flex justify-center">
                        <img src="{{ asset('img/logo_pasopati.webp') }}" alt="Logo Pasopati" class="logo-responsive">
                    </div>
                    <div class="d-flex justify-content-center">
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav
                    class="flex-1 overflow-y-auto overflow-x-hidden pb-24 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent hover:scrollbar-thumb-gray-500">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Data Base -->
                        <li
                            class="nav-item {{ Route::is('database.*') || Route::is('upt.ListDataReguller') || Route::is('vpas.ListDataVpas') || Route::is('spp.ListDataSpp') || Route::is('dbpks.ListDataPks') || Route::is('ponpes.ListDataPonpes') || Route::is('DbPonpes.ListDataVtrend') || Route::is('sppPonpes.ListDataSpp') || Route::is('DbPonpes.pks.ListDataPks') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <span class="material-symbols-outlined">
                                    database
                                </span>
                                <p>DataBase<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('database.DbUpt') }}"
                                        class="nav-link {{ Route::is('database.DbUpt') || Route::is('upt.ListDataReguller') || Route::is('vpas.ListDataVpas') || Route::is('spp.ListDataSpp') || Route::is('dbpks.ListDataPks') ? 'active' : '' }}">
                                        <p>Database UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('database.DataBasePonpes') }}"
                                        class="nav-link {{ Route::is('database.DataBasePonpes') || Route::is('ponpes.ListDataPonpes') || Route::is('DbPonpes.ListDataVtrend') || Route::is('sppPonpes.ListDataSpp') || Route::is('DbPonpes.pks.ListDataPks') ? 'active' : '' }}">
                                        <p>Database Ponpes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <!-- Monitoring Server -->
                        <li
                            class="nav-item disabled-menu{{ Route::is('GrafikServer') || Route::is('MonitoringUpt') || Route::is('MonitoringPonpes') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <span class="material-symbols-outlined">
                                    shield
                                </span>
                                <p>Monitoring Server<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('GrafikServer') }}"
                                        class="nav-link {{ Route::is('GrafikServer') ? 'active' : '' }}">
                                        <p>Grafik</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('MonitoringUpt') }}"
                                        class="nav-link {{ Route::is('MonitoringUpt') ? 'active' : '' }}">
                                        <p>Monitoring UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('MonitoringPonpes') }}"
                                        class="nav-link {{ Route::is('MonitoringPonpes') ? 'active' : '' }}">
                                        <p>Monitoring Ponpes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Monitoring Customer -->
                        <li
                            class="nav-item {{ Route::is('GrafikPonpes') || Route::is('GrafikClient') || Route::is('MclientUptDashboard.KomplainUpt') || Route::is('MclientPonpesDashboard.KomplainPonpes') || Route::is('ListDataMclientCatatanVpas') || Route::is('ListDataMclientCatatanVtren') || Route::is('mcvpas.ListDataMclientVpas') || Route::is('mcreguler.ListDataMclientReguller') || Route::is('mclientkunjunganupt.ListDataMclientKunjungan') || Route::is('mclientpengirimanupt.ListDataMclientPengirimanUpt') || Route::is('mclientsettingalatupt.ListDataMclientSettingAlat') || Route::is('mcvtren.ListDataMclientVtren') || Route::is('mcponpesreguler.ListDataMclientPonpesReguller') || Route::is('mckunjunganponpes.ListDataMclientPonpesKunjungan') || Route::is('mclientponpessetting.ListDataMclientPonpesSetting') || Route::is('mclientpengirimanponpes.ListDataMclientPonpesPengiriman') || Route::is('mccatatanvpas.ListDataMclientCatatanVpas') || Route::is('mccatatanvtren.ListDataMclientCatatanVtren') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link ">
                                <span class="material-symbols-outlined">
                                    visibility
                                </span>
                                <p>Monitoring Customer<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('GrafikClient') }}"
                                        class="nav-link {{ Route::is('GrafikClient') ? 'active' : '' }}">
                                        <p>Grafik Monitoring UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('GrafikPonpes') }}"
                                        class="nav-link {{ Route::is('GrafikPonpes') ? 'active' : '' }}">
                                        <p>Grafik Monitoring Ponpes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('MclientUptDashboard.KomplainUpt') }}"
                                        class="nav-link {{ Route::is('MclientUptDashboard.KomplainUpt') || Route::is('mcvpas.ListDataMclientVpas') || Route::is('mcreguler.ListDataMclientReguller') || Route::is('mclientkunjunganupt.ListDataMclientKunjungan') || Route::is('mclientpengirimanupt.ListDataMclientPengirimanUpt') || Route::is('mclientsettingalatupt.ListDataMclientSettingAlat') ? 'active' : '' }}">
                                        <p>Komplain UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('MclientPonpesDashboard.KomplainPonpes') }}"
                                        class="nav-link {{ Route::is('MclientPonpesDashboard.KomplainPonpes') || Route::is('mcvtren.ListDataMclientVtren') || Route::is('mcponpesreguler.ListDataMclientPonpesReguller') || Route::is('mckunjunganponpes.ListDataMclientPonpesKunjungan') || Route::is('mclientponpessetting.ListDataMclientPonpesSetting') || Route::is('mclientpengirimanponpes.ListDataMclientPonpesPengiriman') ? 'active' : '' }}">
                                        <p>Komplain Ponpes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('mccatatanvpas.ListDataMclientCatatanVpas') }}"
                                        class="nav-link {{ Route::is('mccatatanvpas.ListDataMclientCatatanVpas') ? 'active' : '' }}">
                                        <p>Pencatatan kartu Vpas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('mccatatanvtren.ListDataMclientCatatanVtren') }}"
                                        class="nav-link {{ Route::is('mccatatanvtren.ListDataMclientCatatanVtren') ? 'active' : '' }}">
                                        <p>Pencatatan kartu Vtren</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Tutorial -->
                        <li
                            class="nav-item {{ Route::is('TutorialUpt') || Route::is('tutor_ponpes_reguller.TutorialPonpes') || Route::is('tutorial_server') || Route::is('tutorial_mikrotik') || Route::is('tutor_vpas.ListDataSpp') || Route::is('tutor_upt.ListDataSpp') || Route::is('tutorial_ponpes_vtren.ListDataSpp') || Route::is('tutorial_ponpes_reguller') || Route::is('server_page.ListDataSpp') || Route::is('mikrotik_page.ListDataSpp') || Route::is('tutor_ponpes_vtren.ListDataSpp') || Route::is('tutor_ponpes_reguller.ListDataSpp') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <span class="material-symbols-outlined">
                                    lightbulb_2
                                </span>
                                <p>Tutorial<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('TutorialUpt') }}"
                                        class="nav-link {{ Route::is('TutorialUpt') || Route::is('tutor_vpas.ListDataSpp') || Route::is('tutor_upt.ListDataSpp') ? 'active' : '' }}">
                                        <p>UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tutor_ponpes_reguller.TutorialPonpes') }}"
                                        class="nav-link {{ Route::is('tutor_ponpes_reguller.TutorialPonpes') || Route::is('tutorial_ponpes_vtren') || Route::is('tutorial_ponpes_reguller') ? 'active' : '' }}">
                                        <p>Ponpes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tutorial_server') }}"
                                        class="nav-link {{ Route::is('tutorial_server') ? 'active' : '' }}">
                                        <p>Server</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tutorial_mikrotik') }}"
                                        class="nav-link {{ Route::is('tutorial_mikrotik') ? 'active' : '' }}">
                                        <p>Mikrotik</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- User -->
                        <li
                            class="nav-item {{ Route::is('UserRole.user-role.index') || Route::is('User.UserPage') || Route::is('UserPonpes.ponpes.index') || Route::is('provider.DataProvider') || Route::is('kendala.DataKendala') || Route::is('kanwil.DataKanwil') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <span class="material-symbols-outlined">
                                    person
                                </span>
                                <p>Data Manajemen<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('User.UserPage') }}"
                                        class="nav-link {{ Route::is('User.UserPage') ? 'active' : '' }}">
                                        <p>Data UPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('UserPonpes.ponpes.index') }}"
                                        class="nav-link {{ Route::is('UserPonpes.ponpes.index') ? 'active' : '' }}">
                                        <p>Data Ponpes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('provider.DataProvider') }}"
                                        class="nav-link {{ Route::is('provider.DataProvider') ? 'active' : '' }}">
                                        <p>Data Provider/Vpn</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('kendala.DataKendala') }}"
                                        class="nav-link {{ Route::is('kendala.DataKendala') ? 'active' : '' }}">
                                        <p>Data Kendala/PIC</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('kanwil.DataKanwil') }}"
                                        class="nav-link {{ Route::is('kanwil.DataKanwil') || Route::is('namawilayah.DataNamaWilayah') ? 'active' : '' }}">
                                        <p>Data Kanwil/Nama Wilayah</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                                        <a href="{{ route('UserRole.user-role.index') }}"
                                            class="nav-link {{ Route::is('UserRole.user-role.index') || Route::is('namawilayah.DataNamaWilayah') ? 'active' : '' }}">
                                            <p>Kelola User</p>
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </li>
                    </ul>
                    {{-- PROFILE BUTTON - Fixed Bottom --}}
                    <div class="logout-wrapper">
                        <div class="profile-card">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <h3>{{ Auth::user()->username }}</h3>
                                <p class="text-kategori">{{ Auth::user()->nama ?? 'Guest' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="profile-action">
                                @csrf
                                <button type="submit" class="icon-logout">
                                    <span class="material-symbols-outlined">
                                        logout
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
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

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('public/assets/user/img/logotsu.png') }}" alt="AdminLTE Logo" class="brand-image"
            style="opacity: .8">
        <span class="brand-text font-weight-light" style="font-size: 15px;font-weight: bold;">PMB Universitas Tiga
            Serangkai</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('admin/file/FILE_PHOTOPROFILE/' . photo_profile()) }}"
                    class="img-circle elevation-2"
                    style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #adb5bd;" alt="User Image">
            </div>
            <div class="info text-sm">
                <a href="javascript:void(0)" class="d-block">{{ session('session')->nama }}</a>
                {{-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> --}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-header">Main Navigation</li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @php
                    $dtbeasiswa = checkmenu('Data Pendaftaran', 'Data Beasiswa');
                    $dtnonbeasiswa = checkmenu('Data Pendaftaran', 'Data Non Beasiswa');
                    $dtberkasbeasiswa = checkmenu('Berkas Beasiswa', 'Data Berkas Beasiswa');
                    $pendaftaran = checkmenu('Pembayaran', 'Pembayaran Pendaftaran');
                    $ukt = checkmenu('Pembayaran', 'Pembayaran UKT');
                    $dttest = checkmenu('Test Online', 'Data Test Online');
                    $dtfinal = checkmenu('Final PMB', 'Data Final PMB');
                    $generatenim = checkmenu('Generate NIM', 'Generate NIM');
                @endphp
                @if ($dtbeasiswa + $dtnonbeasiswa > 0)
                    <li class="nav-item"> {{-- menu-open --}}
                        <a href="#" class="nav-link"> {{-- active --}}
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Data Pendaftaran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($dtbeasiswa > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.databeasiswa.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Beasiswa
                                    </a>
                                </li>
                            @endif
                            @if ($dtnonbeasiswa > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.datanonbeasiswa.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Non Beasiswa
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($dtberkasbeasiswa > 0)
                    <li class="nav-item">
                        <a href="{{ route('admin.berkaspmb.show') }}" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>Data Berkas PMB</p>
                        </a>
                    </li>
                @endif
                @if ($pendaftaran + $ukt > 0)
                    <li class="nav-item"> {{-- menu-open --}}
                        <a href="#" class="nav-link"> {{-- active --}}
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>Data Pembayaran PMB
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($pendaftaran > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.pembayaranpmb.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Pendaftaran
                                    </a>
                                </li>
                            @endif
                            @if ($ukt > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.pembayaranukt.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        UKT
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($dttest > 0)
                    <li class="nav-item" style="display: none;">
                        <a href="{{ route('admin.testpmb.show') }}" class="nav-link">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>Data Test PMB</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item" style="display: none;">
                    <a href="{{ route('admin.emailpmb.show') }}" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Send Email</p>
                    </a>
                </li>
                @if($generatenim>0)
                <li class="nav-item">
                    <a href="{{ route('admin.nim.index') }}"
                        class="nav-link {{ request()->routeIs('admin.nim.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Generate NIM</p>
                    </a>
                </li>
                @endif
                @if ($dtfinal > 0)
                    <li class="nav-item">
                        <a href="{{ route('admin.finalpmb.show') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Final PMB</p>
                        </a>
                    </li>
                @endif
                @php
                    $asssoal = checkmenu('Assessment','Master Soal');
                    $asstest = checkmenu('Assessment','Master Test');
                    $asshasil = checkmenu('Assessment','Hasil Test');
                    $assmonitoring = checkmenu('Assessment','Monitoring Test');
                @endphp
                @if($asssoal+$asstest+$asshasil+$assmonitoring>0)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-list-ul"></i>
                        <p>
                            Assessment<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @if($asssoal+$asstest>0)
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>
                                    Master Data Assessment
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                @if($asstest>0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.mastertest.show') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Master Test</p>
                                    </a>
                                </li>
                                @endif
                                @if($asssoal>0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.mastersoal.show') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Master Soal</p>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                    @endif
                    @if($asshasil+$assmonitoring>0)
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-list-alt nav-icon"></i>
                                <p>
                                    Hasil & Monitoring
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                @if($asshasil>0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.testassesment.show') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Hasil Test</p>
                                    </a>
                                </li>
                                @endif
                                @if($assmonitoring>0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.monitoringassesment.show') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Monitoring Test</p>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                    @endif
                </li>
                @endif
                @php
                    $fakultas = checkmenu('Master Data', 'Master Fakultas');
                    $jurusan = checkmenu('Master Data', 'Master Jurusan');
                    $jenisP = checkmenu('Master Data', 'Master Jalur Pendaftaran');
                    $jenjang = checkmenu('Master Data', 'Master Jenjang Pendidikan');
                    $jurusanS = checkmenu('Master Data', 'Master Jurusan Sekolah');
                    $content = checkmenu('Master Data', 'Master Content');
                    $batch = checkmenu('Master Data', 'Master Batch Pendaftaran');
                    $berkas = checkmenu('Master Data', 'Master Berkas');
                    $ukt = checkmenu('Master Data', 'Master Tarif UKT');
                    $beasiswa = checkmenu('Master Data', 'Master Beasiswa');
                    $tingkatkejuaraan = checkmenu('Master Data', 'Master Tingkat Kejuaraan');
                    $provinsi = checkmenu('Master Data', 'Master Provinsi');
                    $kabupaten = checkmenu('Master Data', 'Master Kabupaten Kota');
                    $kecamatan = checkmenu('Master Data', 'Master kecamatan');
                    $kelurahan = checkmenu('Master Data', 'Master Kelurahan');
                    $jenisberkas = checkmenu('Master Data', 'Master Jenis Berkas');
                    $rekomendator = checkmenu('Master Data', 'Master Rekomendator');
                @endphp
                @if ($fakultas+$jurusan+$jenisP+$jenjang+$jurusanS+$content+$batch+$berkas+$ukt+$beasiswa+$tingkatkejuaraan+$provinsi+$kabupaten+$kecamatan+$kelurahan+$jenisberkas+$rekomendator>0)
                    <li class="nav-item"> {{-- menu-open --}}
                        <a href="#" class="nav-link"> {{-- active --}}
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Master Data PMB
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($batch > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.BatchPendaftaran.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Batch Pendaftaran
                                    </a>
                                </li>
                            @endif
                            @if ($jenisP > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.JenisPendaftaran.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Jalur Pendaftaran
                                    </a>
                                </li>
                            @endif
                            @if ($ukt > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.TarifUKT.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Tarif UKT
                                    </a>
                                </li>
                            @endif
                            @if ($beasiswa > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Beasiswa.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Beasiswa
                                    </a>
                                </li>
                            @endif
                            @if ($tingkatkejuaraan > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.TingkatKejuaraan.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Tingkat Kejuaraan
                                    </a>
                                </li>
                            @endif
                            @if ($provinsi > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Provinsi.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Provinsi
                                    </a>
                                </li>
                            @endif
                            @if ($kabupaten > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Kabupaten.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Kabupaten/Kota
                                    </a>
                                </li>
                            @endif
                            @if ($kecamatan > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Kecamatan.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Kecamatan
                                    </a>
                                </li>
                            @endif
                            @if ($kelurahan > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Kelurahan.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Kelurahan
                                    </a>
                                </li>
                            @endif
                            @if ($rekomendator > 0)
                            <li class="nav-item">
                                <a href="{{ route('admin.Rekomendator.show') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    Master Rekomendator
                                </a>
                            </li>
                            @endif
                            @if ($jenisberkas > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.JenisBerkas.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Jenis Berkas
                                    </a>
                                </li>
                            @endif
                            @if ($berkas > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Berkas.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Berkas
                                    </a>
                                </li>
                            @endif
                            @if ($fakultas > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.fakultas.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Fakultas
                                    </a>
                                </li>
                            @endif
                            @if ($jurusan > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Jurusan.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Jurusan
                                    </a>
                                </li>
                            @endif
                            @if ($jenjang > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.Jenjang.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Jenjang Pendidikan
                                    </a>
                                </li>
                            @endif
                            @if ($jurusanS > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.JurusanSekolah.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Master Jurusan Sekolah
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item" style="display: none;">
                                <a href="{{ route('admin.Test.show') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    Master Soal Test
                                </a>
                            </li>
                            @if ($content > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.content.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Master Content</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @php
                    $changepassword = checkmenu('Tools', 'Change Password');
                    $listmenu = checkmenu('Tools', 'List Menu');
                    $groupuser = checkmenu('Tools', 'Group User');
                    $usermanagement = checkmenu('Tools', 'User Management');
                    $userreset = checkmenu('Tools', 'User Reset');
                    // dd($changepassword,$listmenu);
                @endphp
                @if ($changepassword > 0)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Tools
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($changepassword > 0)
                                <li class="nav-item">
                                    <a href="{{ route('admin.show.changepassword') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        Change Password
                                    </a>
                                </li>
                            @endif
                            @if ($listmenu + $groupuser > 0)
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p> Management Menu
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @if ($listmenu > 0)
                                            <li class="nav-item">
                                                <a href="{{ route('admin.menu.show') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>List Menu</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($groupuser > 0)
                                            <li class="nav-item">
                                                <a href="{{ route('admin.gruopuser.show') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Group User</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if ($usermanagement + $userreset > 0)
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p> Management User
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @if ($usermanagement > 0)
                                            <li class="nav-item">
                                                <a href="{{ route('admin.show.userManagement') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>User Management</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userreset > 0)
                                            <li class="nav-item">
                                                <a href="{{ route('admin.UserReset.show') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>User Reset</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<style>
    .user-panel .info a {
        white-space: normal !important;
        word-break: break-word;
        display: block;
        max-width: 150px;
    }
</style>
<!-- /.Main Sidebar Container -->

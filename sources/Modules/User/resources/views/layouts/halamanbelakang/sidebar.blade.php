<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('Dashboard') }}" class="brand-link">
        <img src="{{ asset('public/assets/user/img/logotsu.png') }}" alt="Universitas Tiga Serangkai Logo" class="brand-image"
            style="opacity: .8">
        <span class="brand-text font-weight-light" style="font-size: 17px;font-weight: bold;">Universitas Tiga Serangkai</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="{{ url('sources/storage/app/FILE_PHOTOPROFILE/aa.jpg') }}"
                    class="img-circle elevation-2"
                    style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #adb5bd;" alt="User Image"> --}}
                    <img src="{{ asset('public/assets/img/user.png') }}"
                    class="img-circle elevation-2"
                    style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #adb5bd;" alt="User Image">
            </div>
            <div class="info text-sm">
                <a href="javascript:void(0)" class="d-block">{{ session('user')->nama }}</a>
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
                    <a href="{{ route('Dashboard') }}" class="nav-link {{$menu=='Dashboard' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kartupeserta.download') }}" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-id-card text-info"></i>
                        <p>Kartu Peserta (PDF)</p>
                    </a>
                </li>

                <li class="nav-item" style="display: none;">
                    <a href="{{route('pendaftaran')}}" class="nav-link {{$menu=='Pendaftaran' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Pendaftaran</p>
                    </a>
                </li>
                <li class="nav-item" style="display: none;">
                    <a href="{{route('pembayaran')}}" class="nav-link {{$menu=='Pembayaran' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Pembayaran PMB</p>
                    </a>
                </li>
                <li class="nav-item" style="display: none;">
                    <a href="{{route('BksBeasiswa')}}" class="nav-link {{$menu=='Validasi Berkas' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>Berkas Khusus</p>
                    </a>
                </li>
                <li class="nav-item" style="display: none;">
                    <a href="{{route('test')}}" class="nav-link {{$menu=='Online Test' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-list-ol"></i>
                        <p>Online Test</p>
                    </a>
                </li>
                <li class="nav-item" style="display: none;">
                    <a href="{{route('pembayaranUKT')}}" class="nav-link {{$menu=='Pembayaran UKT' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Pembayaran UKT</p>
                    </a>
                </li>
                <li class="nav-item" style="display: none;">
                    <a href="{{route('biodata')}}" class="nav-link {{$menu=='Biodata' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Biodata</p>
                    </a>
                </li>
                <li class="nav-item {{$menu=='Ganti Password' ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{$menu=='Ganti Password' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Tools
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('user.ChangePassword')}}" class="nav-link {{$menu=='Ganti Password' ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                Change Password
                            </a>
                        </li>
                    </ul>
                </li>
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

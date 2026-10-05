<style>
    .bell-shake {
        animation: ring 2s ease-in-out infinite;
        transform-origin: top center;
    }
    @keyframes ring {
        0% { transform: rotate(0); }
        10% { transform: rotate(15deg); }
        20% { transform: rotate(-10deg); }
        30% { transform: rotate(5deg); }
        40% { transform: rotate(-5deg); }
        50% { transform: rotate(0); }
        100% { transform: rotate(0); }
    }
    .dropdown-item-custom {
        padding: 10px 15px;
        transition: background-color 0.2s;
    }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link"><i class="fa fa-circle fa-sm text-success"></i> Online</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        @php
            // Memberikan nilai default 0 untuk menghindari error jika session kosong
            $pembayaranpendaftaran = session('notifapprovalpembayaranpendaftaran', 0);
            $berkaskhusus = session('notifapprovalberkaskhusus', 0);
            $test = session('notifapprovaltest', 0);
            $pembayaranukt = session('notifapprovalpembayaranukt', 0);
            $all = $pembayaranpendaftaran + $berkaskhusus + $test + $pembayaranukt;
        @endphp
        
        @if($all > 0)
            <li class="nav-item dropdown" style="margin-right: 10px;">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell bell-shake" style="font-size: 1.2rem;"></i>
                    <span class="badge badge-danger navbar-badge shadow-sm">{{$all}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0" style="min-width: 320px; border-radius: 0.5rem;">
                    <span class="dropdown-item dropdown-header bg-light font-weight-bold" style="border-radius: 0.5rem 0.5rem 0 0;">
                        <i class="fas fa-clipboard-list mr-1"></i> {{$all}} Notifikasi Menunggu
                    </span>
                    
                    @if($pembayaranpendaftaran > 0)
                        <div class="dropdown-divider m-0"></div>
                        <a href="{{route('admin.pembayaranpmb.show')}}" class="dropdown-item dropdown-item-custom">
                            <i class="fas fa-money-check-alt text-success mr-2"></i> Bayar Pendaftaran
                            <span class="float-right badge badge-success">{{$pembayaranpendaftaran}}</span>
                        </a>
                    @endif
                    
                    @if($berkaskhusus > 0)
                        <div class="dropdown-divider m-0"></div>
                        <a href="{{route('admin.berkaspmb.show')}}" class="dropdown-item dropdown-item-custom">
                            <i class="fas fa-file-alt text-info mr-2"></i> Berkas Khusus
                            <span class="float-right badge badge-info">{{$berkaskhusus}}</span>
                        </a>
                    @endif
                    
                    @if($test > 0)
                        <div class="dropdown-divider m-0"></div>
                        <a href="{{route('admin.testassesment.show')}}" class="dropdown-item dropdown-item-custom">
                            <i class="fas fa-tasks text-warning mr-2"></i> Test Assessment
                            <span class="float-right badge badge-warning">{{$test}}</span>
                        </a>
                    @endif
                    
                    @if($pembayaranukt > 0)
                        <div class="dropdown-divider m-0"></div>
                        <a href="{{route('admin.pembayaranukt.show')}}" class="dropdown-item dropdown-item-custom">
                            <i class="fas fa-wallet text-primary mr-2"></i> Pembayaran UKT
                            <span class="float-right badge badge-primary">{{$pembayaranukt}}</span>
                        </a>
                    @endif
                </div>
            </li>
        @endif

        <li class="dropdown user user-menu" style="margin-top: 8px;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-users-cog"></i>
            </a>
            <ul class="dropdown-menu shadow border-0">
                <li class="user-header bg-primary">
                    <img src="{{ url('/admin/file/FILE_PHOTOPROFILE/' . photo_profile()) }}" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid rgba(255,255,255,0.8);" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null;this.src='{{ asset('public/assets/img/user.png') }}';">
                    <p>
                        {{session('session')->nama}}
                    </p>
                </li>
                <li class="user-footer">
                    <form action="{{route('admin.logout')}}" method="POST" id="form-logout">
                        @csrf
                    </form>
                    <a href="{{route('admin.show.changeprofile')}}" class="btn btn-default btn-flat">Profile</a>
                    <button type="submit" class="btn btn-danger btn-flat float-right" form="form-logout">Sign out</button>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Zoom Page">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
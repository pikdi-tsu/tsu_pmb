<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('public/assets/user/img/logotsu.png') }}" type="image/png" />
    <title>TSU - {{$title}}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/dist/css/adminlte.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/dist/css/loading.css') }}">
    <style>
        /* Konten bisa di-scroll & tidak tertutup navbar/footer fixed (terutama di HP) */
        .rekom-page {
            min-height: 100vh;
            padding: calc(57px + 1.5rem) 0 calc(57px + 1.5rem);
            background: #e9ecef;
        }
        .rekom-page .card { max-width: 720px; margin: 0 auto; }
        .navbar-brand .brand-text { white-space: normal; font-size: 1.1rem; }
        @media (max-width: 575.98px) {
            .rekom-page { padding-left: .5rem; padding-right: .5rem; }
            .navbar-brand .brand-text { font-size: .95rem; }
            .rekom-page .btn-submit-rekom { width: 100%; margin: 1rem 0 0 !important; }
        }
    </style>
    @yield('link_href')
</head>

<body class="hold-transition layout-top-nav layout-footer-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light bg-teal">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <img src="{{ asset('public/assets/user/img/logotsu.png') }}" alt="AdminLTE Logo" class="brand-image"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light"><b>{{$title}} Universitas Tiga Serangkai</b></span>
                </a>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Main content -->
        <div class="rekom-page">
            @yield('content')
        </div>
        @include('user::login/loading')
        <!-- /.content -->

        <!-- Main Footer -->
        <footer class="main-footer text-sm text-center">
            <!-- To the right -->
            <div class="float-right">
            </div>
            <strong>Copyright &copy; {{date('Y')}} Universitas Tiga Serangkai.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('public/assets/admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('public/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('public/assets/admin/dist/js/main.js') }}"></script>
    <script src="{{ asset('public/assets/admin/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/dist/js/sweetalert.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        @if (Session::has('alert'))
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCloseButton: true,
                timer: {{ session('alert')['status'] === 'success' ? 5000 : 12000 }},
                timerProgressBar: true,
            }).fire({
                icon: @json(session('alert')['status']),
                title: @json(session('alert')['title']),
                text: @json(session('alert')['message']),
            });
        @elseif ($errors->any())
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, showCloseButton: true, timer: 8000, timerProgressBar: true })
                .fire({ icon: 'error', title: 'Data belum lengkap', text: 'Periksa kembali isian yang ditandai merah.' });
        @endif

        function notifalert(title,text,type) {
            Swal.fire({
                title: title,
                text: text,
                icon: type,
                timer: 1500,
                showConfirmButton: false
            });
        }

        $("#password,#a_1,#a_2").keypress(function(event){
            var ew = event.which;

            if(48 <= ew && ew <= 57)
                return true;
            if(65 <= ew && ew <= 90)
                return true;
            if(97 <= ew && ew <= 122)
                return true;
            return false;
        });
    </script>
    @yield('script')
</body>

</html>

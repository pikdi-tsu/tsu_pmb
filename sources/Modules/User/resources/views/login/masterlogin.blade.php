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
    @yield('link_href')
</head>

<body class="hold-transition layout-top-nav layout-footer-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light bg-teal">
            <div class="container">
                <a href="{{ route('indexing') }}" class="navbar-brand">
                    <img src="{{ asset('public/assets/user/img/logotsu.png') }}" alt="AdminLTE Logo" class="brand-image"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light"><b>PENDAFTARAN PMB TSU {{ date('Y') }}/{{ date('Y') + 1 }}</b></span>
                </a>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Main content -->
        <div class="content login-page">
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
            Swal.fire('{{ session('alert')['title'] }}', '{{ session('alert')['message'] }}',
                '{{ session('alert')['status'] }}')
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

        $("#a_1,#a_2").keypress(function(event){
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

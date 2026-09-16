
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{ asset('public/assets/user/img/logotsu.png') }}" type="image/png"/>
    <title>Admin PMB - {{$title}}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/fontawesome-free-7.1.0-web/css/all.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/plugins/summernote/summernote-bs4.min.css')}}">
    {{-- loading --}}
    <link rel="stylesheet" href="{{ asset('public/assets/admin/dist/css/loading.css') }}">
    <script src="{{ asset('public/assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    @yield('link_href')
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed">
    <div class="wrapper">
        <!-- Preloader -->

        @include('admin::template/admin/sidebar')
        @include('admin::template/admin/navbar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        @include('admin::template/admin/loading')
        @include('admin::template/admin/footer')

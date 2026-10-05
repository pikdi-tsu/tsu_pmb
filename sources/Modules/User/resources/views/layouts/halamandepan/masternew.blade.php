<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="{{ asset('public/assets/user/img/logotsu.png') }}" type="image/png" />
    <title>{{ $title }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('public/assets/user/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('public/assets/user/css/main.css') }}" rel="stylesheet">
    <style>
        /* Alert/toast selalu di atas #preloader (z-index 999999) */
        .swal-above-preloader { z-index: 1000000 !important; }
    </style>
</head>

<body class="index-page">

    @include('user::layouts/halamandepan/navbar')
    <main class="main">
        @yield('content')
    </main>

    <script>
        @if (Session::has('alert'))
            // sweetalert.js dimuat di bagian bawah halaman, tunggu sampai siap
            // Tampilkan setelah preloader hilang (window load), atau paling lambat 4 detik
            // agar alert tidak tertutup preloader & timer tidak habis sebelum terlihat
            (function () {
                var shown = false;
                function showSessionAlert() {
                    if (shown || typeof Swal === 'undefined') return;
                    shown = true;
                    @if (!empty(session('alert')['toast']))
                        Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: {{ session('alert')['status'] === 'success' ? 8000 : 'undefined' }},
                            timerProgressBar: true,
                            customClass: { container: 'swal-above-preloader' },
                            didOpen: function (toast) {
                                toast.addEventListener('mouseenter', Swal.stopTimer);
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                                toast.addEventListener('touchstart', Swal.stopTimer, { passive: true });
                            },
                        }).fire({
                            icon: @json(session('alert')['status']),
                            title: @json(session('alert')['title']),
                            text: @json(session('alert')['message']),
                        });
                    @else
                        Swal.fire({
                            title: @json(session('alert')['title']),
                            text: @json(session('alert')['message']),
                            icon: @json(session('alert')['status']),
                            customClass: { container: 'swal-above-preloader' },
                        });
                    @endif
                }
                // Hentikan hitung mundur saat tab tidak aktif
                document.addEventListener('visibilitychange', function () {
                    if (typeof Swal === 'undefined' || !Swal.isVisible()) return;
                    document.hidden ? Swal.stopTimer() : Swal.resumeTimer();
                });
                window.addEventListener('load', showSessionAlert);
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(showSessionAlert, 4000);
                });
            })();
        @endif
        // Swal.fire('halo', 'test alert',
        //         'success')

        function sweetAlert(alert, desc, text) {
            const Alert = Swal.mixin({
                showConfirmButton: true,
                timer: 3000
            });

            Alert.fire({
                type: alert,
                title: desc,
                text: text,
            });
        }
    </script>
    @yield('script')
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('public/assets/user/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/glightbox/js/glightbox.min.js') }}"></script>
    {{-- alert --}}
    <script src="{{ asset('public/assets/admin/dist/js/sweetalert.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('public/assets/user/js/main.js') }}"></script>
</body>


</html>

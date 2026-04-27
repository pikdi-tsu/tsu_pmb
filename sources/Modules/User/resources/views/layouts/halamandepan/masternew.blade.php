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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Vendor CSS Files -->
    <link href="{{ asset('public/assets/user/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/user/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('public/assets/user/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

    @include('user::layouts/halamandepan/navbar')
    <main class="main">
        @yield('content')
    </main>

    <script>
        @if (Session::has('alert'))
            Swal.fire('{{ session('alert')['title'] }}', '{{ session('alert')['message'] }}',
                '{{ session('alert')['status'] }}')
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
        new Swiper('.init-swiper', {
            loop: true,
            speed: 600,
            autoplay: {
                delay: 5000,
            },

            // MATIKAN centeredSlides agar kartu dimulai dari kiri container,
            // sehingga pas 3 biji berjajar rapi tanpa terpotong di pinggir.
            centeredSlides: false,

            // Pengaturan Responsif (KUNCI UTAMA)
            breakpoints: {
                // Saat di HP (lebar < 640px): Tampil 1 kartu
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                // Saat di Tablet (lebar < 992px): Tampil 2 kartu
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30
                },
                // Saat di Laptop/PC (lebar > 1200px): Tampil PAS 3 KARTU
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 30 /* Jarak antar kartu */
                }
            },

            // Navigasi & Pagination tetap sama
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true
            },
            navigation: {
                nextEl: '.custom-next',
                prevEl: '.custom-prev',
            }
        });
    </script>
    @yield('script')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
    <script src="{{ asset('public/assets/user/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('public/assets/user/vendor/glightbox/js/glightbox.min.js') }}"></script>
    {{-- alert --}}
    <script src="{{ asset('public/assets/admin/dist/js/sweetalert.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('public/assets/user/js/main.js') }}"></script>
</body>


</html>

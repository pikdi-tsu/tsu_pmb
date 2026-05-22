@extends('user::layouts/halamandepan/masternew')
@section('title', $title)

@section('link_href')
@endsection
@section('content')
<style>
    video {
        filter: brightness(0.5);
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>

    <div class="position-relative vh-100 overflow-hidden">

        <!-- Video Background -->
        <video autoplay muted loop playsinline
            class="position-absolute top-50 start-50 translate-middle min-vw-100 min-vh-100 object-fit-cover">
            <source src="{{ asset('public/assets/user/img/education/tsu.mp4') }}" type="video/mp4">
        </video>

        <!-- Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>

        <!-- Content -->
        <div class="container position-relative text-white h-100 d-flex align-items-center">
            <div>
                <h2 class="text-white">Selamat datang di Portal Penerimaan Mahasiswa Baru</h2>
                <h2 class="text-white">Tiga Serangkai University</h2>
                <h4 class="mb-3 text-white">Tahun Akademik {{ $thnakademik }}</h4>
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-bold text-white">Daftar Sekarang</a>
            </div>
        </div>

    </div>

    <div class="social-widget-container">

        <div class="social-list-extended">
            <a href="https://www.instagram.com/tsuniversity.official?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="
                target="_blank" class="social-item instagram-bg">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.tiktok.com/@tsuniversity.official?is_from_webapp=1&sender_device=pc" target="_blank"
                class="social-item tiktok-bg">
                <i class="fab fa-tiktok"></i>
            </a>
            <a href="https://www.youtube.com/@TSU_Official_25" target="_blank" class="social-item youtube-bg">
                <i class="fab fa-youtube"></i>
            </a>
        </div>

        <div class="expand-arrow">
            <i class="fas fa-chevron-up"></i>
        </div>

        <a href="https://wa.me/62895705354767" target="_blank" class="main-whatsapp-btn">
            <i class="fab fa-whatsapp"></i>
        </a>

    </div>
@endsection
@section('script')

@endsection

@extends('user::layouts/halamandepan/masternew')
@section('title', $title)

@section('link_href')
@endsection
@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

        <div class="hero-container">
            <video autoplay="" muted="" loop="" playsinline="" class="video-background">
                <source src="{{ asset('public/assets/user/img/education/video-2.mp4') }}" type="video/mp4">
            </video>
            <div class="overlay"></div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7" data-aos="zoom-out" data-aos-delay="100">
                        <div class="hero-content">
                            <h2>Seleksi Penerimaan Mahasiswa Baru</h2>
                            <h4>Tahun Ajaran 2025/2026</h4>
                            <p> <a href="#">Info Pendaftaran Mahasiswa Baru Gelombang 2 Th. 2025</a> </p>
                            <p> <a href="#">Info Pendaftaran Mahasiswa Baru Gelombang 3 Th. 2025</a> </p>
                            <div>
                                <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-bold">Daftar
                                    Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5" data-aos="zoom-out" data-aos-delay="200" style="display: none;">
                        <div class="stats-card">
                            <div class="stats-header">
                                <h3>Why Choose Us</h3>
                                <div class="decoration-line"></div>
                            </div>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-trophy-fill"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>98%</h4>
                                        <p>Graduate Employment</p>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-globe"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>45+</h4>
                                        <p>International Partners</p>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>15:1</h4>
                                        <p>Student-Faculty Ratio</p>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>120+</h4>
                                        <p>Degree Programs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="event-ticker">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-md-6 col-xl-4 col-12 ticker-item">
                        <span class="date">NOV 15</span>
                        <span class="title">Open House Day</span>
                        <a href="#" class="btn-register">Register</a>
                    </div>
                    <div class="col-md-6 col-12 col-xl-4  ticker-item">
                        <span class="date">DEC 5</span>
                        <span class="title">Application Workshop</span>
                        <a href="#" class="btn-register">Register</a>
                    </div>
                    <div class="col-md-6 col-12 col-xl-4 ticker-item">
                        <span class="date">JAN 10</span>
                        <span class="title">International Student Orientation</span>
                        <a href="#" class="btn-register">Register</a>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

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

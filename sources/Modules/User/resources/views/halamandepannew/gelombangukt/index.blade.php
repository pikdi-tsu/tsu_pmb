@extends('user::layouts/halamandepan/master')
@section('title', $title)

@section('link_href')
@endsection

@section('content')
<style>
    /* --- Warna Utama --- */
    :root {
        --teal-color: #1192a8;
        --border-color: #e5e7eb;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
    }

    /* --- Hero Section (Area Gambar) --- */
    .hero-section {
        position: relative;
        /* BACKGROUND GAMBAR SUDAH DIGANTI SESUAI PERMINTAAN */
        background-image:linear-gradient(rgba(18, 108, 132, 0.8), rgba(29, 36, 43, 0.9)), url('public/assets/img/belajar.jpg');
        background-size: cover;
        background-position: center;
        padding: 160px 0 140px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        color: #ffffff;
    }

    .hero-content h1 {
        font-family: var(--heading-font);
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #fff;
    }

    .hero-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
    }

    /* --- Page Container --- */
    .ps-page-container {
        background-color: #f5faff;
        padding-bottom: 80px;
    }

    /* --- Area Putih / Card Utama (OVERLAP EFFECT) --- */
    .ps-card-wrapper {
        background: #ffffff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-top: -80px; /* Efek melayang */
        position: relative;
        z-index: 10;
    }

    .ps-header h3 {
        font-family: var(--heading-font);
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 5px;
        font-size: 1.4rem;
    }

    .ps-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 25px;
    }

    .cta-banner {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('public/assets/img/kampustsu.png'); /* ganti dengan gambar kamu */

        background-size: cover;
        background-position: center;

        height: 400px;
        border-radius: 20px;
        padding: 40px;
    }
</style>

<section class="hero-section">
    <div class="container hero-content">
        <h1>Gelombang & UKT</h1>
        <p>Temukan informasi lengkap mengenai gelombang dan ukt di Universitas Tiga Serangkai.</p>
    </div>
</section>

<main id="main" class="ps-page-container">
    <div class="container">
        <div class="ps-card-wrapper">
            <div class="ps-header">
                <h3>Informasi Gelombang & UKT</h3>
                <p>Informasi Gelombang & UKT Universitas Tiga Serangkai</p>
            </div>

            <div class="border-top pt-2">
                <div class="card shadow-sm border-0" style="width: 100%;">
                    <div class="card-body">
                        <img src="{{ asset('public/assets/img/ukttsu.png') }}"
                            class="card-img-top"
                            alt="gambar">
                    </div>
                </div>
            </div>

            <div class="container border-top pt-2">
                <div class="cta-banner text-center d-flex align-items-center justify-content-center">
                    <div>
                        <h2 class="fw-bold mb-3 text-white">
                            Jadilah Bagian dari Universitas Tiga Serangkai!
                            Raih Masa Depan Gemilang Bersama Kami!
                        </h2>

                        <a href="{{ route('register') }}" class="btn btn-warning px-6 py-3 rounded-pill fw-semibold text-white">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

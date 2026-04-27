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
        background-image:linear-gradient(rgba(18, 108, 132, 0.8), rgba(29, 36, 43, 0.9)), url('../public/assets/img/fotowisuda.jpg');
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
        padding-bottom: 20px;
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

    .detail-header {
        background: linear-gradient(135deg, #1aa6b7, #0e7c86);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .section-title {
        font-weight: 700;
        color: #0e7c86;
    }

    .card {
        border-radius: 12px;
    }

    .list-group-item {
        padding-left: 0;
    }

    .cta-banner {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../public/assets/img/kampustsu.png'); /* ganti dengan gambar kamu */

        background-size: cover;
        background-position: center;

        height: 400px;
        border-radius: 20px;
        padding: 40px;
    }

</style>
<section class="hero-section">
    <div class="container hero-content">
        {{-- <h1>Beasiswa Prestasi</h1> --}}
        {{-- <p>Temukan informasi beasiswa di Universitas Tiga Serangkai.</p> --}}
    </div>
</section>
<main id="main" class="ps-page-container">
    <div class="container">

        <div class="ps-card-wrapper">
            <div class="ps-header">
                <h3>Informasi {{ $jenispendaftaran->jenis_pendaftaran}}</h3>
            </div>

            <div class="border-top pt-4">

                <p style="font-size: 12pt">
                    {{ $jenispendaftaran->deskripsi }}
                </p>

                @if ($jenispendaftaran->KodeJenis=='BSA2'||$jenispendaftaran->KodeJenis=='BSA3')
                <div class="row mt-5">
                    <!-- LEFT -->
                    <div class="col-md-7">
                        <h4 class="fw-bold" style="color: #11667B">Persyaratan Utama</h4>

                        <div class="row g-3 mt-2">
                            @foreach ($masterbeasiswa3 as $item)
                                    <div class="col-md-4">
                                        <div class="card h-100 text-center shadow-sm border-0">
                                            <div class="card-body">
                                                <div class="fs-3 mb-2">🏆</div>
                                                <p class="small mb-0">
                                                    {{ $item->juara_ke }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-md-5">
                        <h4 class="fw-bold" style="color: #11667B">Persyaratan Dokumen</h4>

                        <div class="card shadow-sm border-0 mt-3">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @foreach ($masterberkas as $item)
                                        <li class="list-group-item px-0">
                                            📄 {{ $item->deskripsi }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                 <div class="row mt-5">
                    <h4 class="fw-bold" style="color: #11667B">Persyaratan Dokumen</h4>
                    <div class="card shadow-sm border-0">
                        <div class="card-body">

                            <ul class="list-group list-group-flush">
                                @foreach ($masterberkas as $item)
                                    <li class="list-group-item border-0">
                                        📄 {{ $item->deskripsi }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-5">
                    <h4 class="fw-bold" style="color: #11667B">Fasilitas Beasiswa</h4>

                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatables" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Kategori Juara</th>
                                            <th>Fasilitas Beasiswa</th>
                                            <th>Durasi Beasiswa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($masterbeasiswa as $item)
                                        <tr>
                                            <td>{{$item->juara_ke}}</td>
                                            <td>{{$item->persen_potongan}}% Bebas UKT</td>
                                            <td>Semester 1 – {{$item->durasi_s1}}*</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p style="font-size: 10pt">
                                (*) dengan review penerimaan beasiswa setiap tahun oleh Kepala Program Studi masing-masing
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <h4 class="fw-bold" style="color: #11667B">Kontrak Beasiswa</h4>
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <ol class="list-group list-group-numbered">
                                @foreach($kontrakbeasiswa as $item)
                                    <li class="list-group-item border-0">
                                        {{ $item->namakontrak }}
                                    </li>
                                 @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-3">
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
    {{-- @include('user::layouts.halamandepan.cta-bantuan') --}}
</main>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#datatabbles').DataTable();
    });
</script>
@endsection

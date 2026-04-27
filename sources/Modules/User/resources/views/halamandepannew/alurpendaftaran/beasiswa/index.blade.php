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
        background-image:linear-gradient(rgba(18, 108, 132, 0.8), rgba(29, 36, 43, 0.9)), url('public/assets/img/fotowisuda.jpg');
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

    .custom-header {
        background: linear-gradient(135deg, #1aa6b7, #0e7c86);
        border-bottom: none;
    }

    .custom-card {
        background-color: #f8f9fa;
    }

    .btn-warning {
        background-color: #f2994a;
        border: none;
    }

    .btn-info {
        background-color: #1aa6b7;
        border: none;
    }

</style>
<section class="hero-section">
    <div class="container hero-content">
        <h1>Beasiswa</h1>
        <p>Temukan informasi beasiswa di Universitas Tiga Serangkai.</p>
    </div>
</section>
<main id="main" class="ps-page-container">
    <div class="container">

        <div class="ps-card-wrapper">
            <div class="ps-header">
                <h3>Informasi Beasiswa</h3>
                <p>Informasi Beasiswa Universitas Tiga Serangkai</p>
            </div>

            <div class="row g-4 border-top pt-4">
                <!-- CARD UTAMA -->
                @foreach ($jenispendaftaran as $key => $item)
                    @php
                        if ($item->biaya_pendaftaran == '1') {
                            $biayaformulir = 'BERBAYAR';
                        } else {
                            $biayaformulir = 'GRATIS';
                        }

                    @endphp
                    <div class="col-md-4">
                        <div class="card shadow border-0 rounded-4 overflow-hidden h-100">

                            <div class="text-center text-white p-4 custom-header">
                                <h5 class="fw-bold mb-1">{{ $item->jenis_pendaftaran }}</h5>
                                <div class="small">REGULER {{ $waktukuliah->waktu }} GASAL 2026</div>
                                <div class="mt-2 small">
                                    <i class="bi bi-clock"></i>
                                    {{ $batch->tglmulai_format . ' - ' . $batch->tglselesai_format}}
                                    {{-- 9 Februari 2026 - 30 April 2026 --}}
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <div class="row text-muted small">
                                    <div class="col-6 mb-3">
                                        <div>Periode Pendaftaran</div>
                                        <div class="fw-semibold text-dark">{{ $batch->tahun_akademik }}</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div>Gelombang</div>
                                        <div class="fw-semibold text-dark">{{ $batch->nama_batch }}</div>
                                    </div>

                                    <div class="col-6">
                                        <div>Sistem Kuliah</div>
                                        <div class="fw-semibold text-dark">{{ $waktukuliah->waktu }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div>Formulir</div>
                                        <div class="fw-semibold text-dark">{{ $biayaformulir }}</div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <a href="{{ route('detailbeasiswa', ['params' => encrypt($item->id)]) }}" class="btn btn-warning w-50 rounded-3">Lihat Detail</a>
                                    <a href="{{ route('register') }}" class="btn btn-info w-50 rounded-3">Daftar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


                <!-- CARD LAIN -->
                {{-- <div class="col-md-4">
                    <div class="card shadow border-0 rounded-4 overflow-hidden h-100">

                        <div class="text-center text-white p-4 custom-header">
                            <h5 class="fw-bold mb-1">BSA PRESTASI NON AKADEMIK</h5>
                            <div class="small">REGULER PAGI GASAL 2026</div>
                            <div class="mt-2 small">
                                <i class="bi bi-clock"></i>
                                9 Februari 2026 - 30 April 2026
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row text-muted small">
                                <div class="col-6 mb-3">
                                    <div>Periode Pendaftaran</div>
                                    <div class="fw-semibold text-dark">2026 Ganjil</div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div>Gelombang</div>
                                    <div class="fw-semibold text-dark">Gelombang 1</div>
                                </div>

                                <div class="col-6">
                                    <div>Sistem Kuliah</div>
                                    <div class="fw-semibold text-dark">Pagi</div>
                                </div>
                                <div class="col-6">
                                    <div>Formulir</div>
                                    <div class="fw-semibold text-dark">BERBAYAR</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <a href="#" class="btn btn-warning w-50 rounded-3">Lihat Detail</a>
                                <a href="#" class="btn btn-info w-50 rounded-3">Daftar</a>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-md-4">
                    <div class="card shadow border-0 rounded-4 overflow-hidden h-100">

                        <div class="text-center text-white p-4 custom-header">
                            <h5 class="fw-bold mb-1">BSA KELUARGA DENGAN KETERBATASAN EKONOMI</h5>
                            <div class="small">REGULER PAGI GASAL 2026</div>
                            <div class="mt-2 small">
                                <i class="bi bi-clock"></i>
                                9 Februari 2026 - 30 April 2026
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row text-muted small">
                                <div class="col-6 mb-3">
                                    <div>Periode Pendaftaran</div>
                                    <div class="fw-semibold text-dark">2026 Ganjil</div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div>Gelombang</div>
                                    <div class="fw-semibold text-dark">Gelombang 1</div>
                                </div>

                                <div class="col-6">
                                    <div>Sistem Kuliah</div>
                                    <div class="fw-semibold text-dark">Pagi</div>
                                </div>
                                <div class="col-6">
                                    <div>Formulir</div>
                                    <div class="fw-semibold text-dark">BERBAYAR</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <a href="#" class="btn btn-warning w-50 rounded-3">Lihat Detail</a>
                                <a href="#" class="btn btn-info w-50 rounded-3">Daftar</a>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-md-4">
                    <div class="card shadow border-0 rounded-4 overflow-hidden h-100">

                        <div class="text-center text-white p-4 custom-header">
                            <h5 class="fw-bold mb-1">BSA MAHASISWA INTERNASIONAL</h5>
                            <div class="small">REGULER PAGI GASAL 2026</div>
                            <div class="mt-2 small">
                                <i class="bi bi-clock"></i>
                                9 Februari 2026 - 30 April 2026
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row text-muted small">
                                <div class="col-6 mb-3">
                                    <div>Periode Pendaftaran</div>
                                    <div class="fw-semibold text-dark">2026 Ganjil</div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div>Gelombang</div>
                                    <div class="fw-semibold text-dark">Gelombang 1</div>
                                </div>

                                <div class="col-6">
                                    <div>Sistem Kuliah</div>
                                    <div class="fw-semibold text-dark">Pagi</div>
                                </div>
                                <div class="col-6">
                                    <div>Formulir</div>
                                    <div class="fw-semibold text-dark">BERBAYAR</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <a href="#" class="btn btn-warning w-50 rounded-3">Lihat Detail</a>
                                <a href="#" class="btn btn-info w-50 rounded-3">Daftar</a>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    @include('user::layouts.halamandepan.cta-bantuan')
</main>
@endsection

@section('script')
@endsection

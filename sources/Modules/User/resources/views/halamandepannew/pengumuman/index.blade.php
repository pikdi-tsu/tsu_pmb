@extends('user::layouts/halamandepan/master')
@section('title', 'Pengumuman - Universitas Tiga Serangkai')

@section('link_href')
@endsection
@section('content')

    <main id="main">
        
        <div class="page-header">
            <div class="container position-relative">
                <h2>Pengumuman</h2>
                <p>Dapatkan Informasi Terbaru Mengenai Pendaftaran</p>
            </div>
        </div>

        <section class="pengumuman-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-15">
                        <div class="card pengumuman-card">
                            
                            <a href="{{ route('detail_pengumuman') }}" class="pengumuman-item">
                                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Pengumuman 1" class="pengumuman-img">
                                <div class="pengumuman-content">
                                    <h3 class="pengumuman-title">Informasi Pendaftaran Mahasiswa Baru Semester Genap 2025</h3>
                                    <p class="pengumuman-desc">Informasi Pendaftaran Mahasiswa Baru Semester Genap 2025</p>
                                    <div class="pengumuman-meta">
                                        <i class="bi bi-clock"></i> 18 Januari 2026, 01:05:15
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="pengumuman-item">
                                <div class="pengumuman-content">
                                    <h3 class="pengumuman-title">Info Pendaftaran Mahasiswa Baru Gelombang 2 Th. 2025</h3>
                                    <p class="pengumuman-desc">WAKTU PENDAFTARAN : Gelombang 2&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;1 Mei 2025 s/d 30 Juni 2025</p>
                                    <div class="pengumuman-meta">
                                        <i class="bi bi-clock"></i> 28 Mei 2025, 14:01:33
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="pengumuman-item">
                                <img src="https://images.unsplash.com/photo-1588196749597-9ff046892e08?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Pengumuman 3" class="pengumuman-img">
                                <div class="pengumuman-content">
                                    <h3 class="pengumuman-title">Info Pendaftaran Mahasiswa Baru Gelombang 1 Th. 2025</h3>
                                    <p class="pengumuman-desc">WAKTU PENDAFTARAN : Gelombang 1&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;1 Januari s/d 30 April 2025</p>
                                    <div class="pengumuman-meta">
                                        <i class="bi bi-clock"></i> 13 Januari 2025, 10:14:46
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="pengumuman-item">
                                <div class="pengumuman-content">
                                    <h3 class="pengumuman-title">Info Pendaftaran Mahasiswa Baru Gelombang Dini Th. 2025</h3>
                                    <p class="pengumuman-desc">WAKTU PENDAFTARAN : Gelombang Dini&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;s/d 31 Desember 2024 Pendaftaran ONLINE : setiap saat</p>
                                    <div class="pengumuman-meta">
                                        <i class="bi bi-clock"></i> 9 November 2024, 10:57:00
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
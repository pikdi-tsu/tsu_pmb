@extends('user::layouts/halamandepan/master')
@section('title', 'Jalur Pendaftaran - Universitas Tiga Serangkai')

@section('link_href')
@endsection

@section('content')
<style>
    /* Custom Colors menyesuaikan referensi gambar */
    .text-teal { color: #009ca6; }
    .bg-teal { background-color: #009ca6; color: white; }
    .btn-teal { background-color: #009ca6; color: white; border: none; }
    .btn-teal:hover { background-color: #007d85; color: white; }
    .text-orange { color: #f39c12; }

    .card-filter {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .card-jalur {
        border-radius: 8px;
        border: 1px solid #eaeaea;
        transition: 0.3s;
    }
    .card-jalur:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #009ca6;
    }
    .banner-bantuan {
        background: linear-gradient(to right, rgba(243, 156, 18, 0.9), rgba(243, 156, 18, 0.7)), url('assets/img/gedung-kampus.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 15px;
        color: white;
    }
</style>

<div class="container mt-5 mb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i> Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jalur Pendaftaran</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h3 class="fw-bold">Jalur Pendaftaran</h3>
        <p class="text-secondary">Temukan jalur pendaftaran sesuai dengan pilihan program studi yang diminati.</p>
    </div>

    <div class="card card-filter p-3 mb-4">
        <div class="card-body">
            <form action="{{ url('/jalur-pendaftaran') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" name="jenjang">
                            <option value="">-- Pilih Jenjang --</option>
                            <option value="S1">S1</option>
                            <option value="D3">D3</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="prodi">
                            <option value="">-- Pilih Program Studi --</option>
                            </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="sistem_kuliah">
                            <option value="">-- Pilih Sistem Kuliah --</option>
                            <option value="Pagi">Pagi</option>
                            <option value="Malam">Malam</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-teal w-100 fw-bold">Cari Jalur Pendaftaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="accordion mb-4 shadow-sm" id="accordionTataCara">
        <div class="accordion-item border-0" style="border-radius: 10px; overflow: hidden;">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed fw-bold text-dark bg-white border" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Tata Cara Pendaftaran Mahasiswa Baru
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionTataCara">
                <div class="accordion-body border border-top-0 bg-light">
                    <ol>
                        <li>Membuat akun pendaftaran.</li>
                        <li>Memilih jalur pendaftaran.</li>
                        <li>Membayar biaya pendaftaran.</li>
                        <li>Mengisi form biodata dan mengunggah berkas.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="jalur-list">
        @forelse($batches as $batch)
            <div class="card card-jalur mb-3">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                    <div class="mb-3 mb-md-0">
                        <h5 class="fw-bold mb-2 text-uppercase">
                            PENDAFTARAN MAHASISWA BARU {{ $batch->tahun_akademik }} - {{ $batch->nama_batch }}
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            Semua Jalur (Reguler & Beasiswa)
                        </span>
                    </div>

                    <div class="text-md-end">
                        <p class="mb-1 text-secondary">
                            <i class="far fa-calendar-alt text-teal me-2"></i>
                            {{ \Carbon\Carbon::parse($batch->tglmulai)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($batch->tglselesai)->format('d M Y') }}
                        </p>
                        <p class="mb-3 text-secondary">
                            <i class="fas fa-tags text-teal me-2"></i> Biaya Daftar
                            <strong class="text-orange">Rp. 300.000</strong>
                        </p>

                        {{-- Cek apakah tanggal sekarang masih masuk masa pendaftaran --}}
                        @if(date('Y-m-d') >= $batch->tglmulai && date('Y-m-d') <= $batch->tglselesai)
                            <a href="{{ route('LoginPMB') }}" class="btn btn-teal px-4 py-2 fw-bold">Daftar Sekarang</a>
                        @else
                            <button class="btn btn-secondary px-4 py-2 fw-bold" disabled>Pendaftaran Ditutup</button>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center">
                Belum ada jadwal gelombang pendaftaran yang aktif saat ini.
            </div>
        @endforelse
    </div>


</div>
@endsection

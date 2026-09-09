@extends('user::layouts/halamanbelakang/header')
@section('title', $title)
@section('link_href')

<style>
    .stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ccc;
        color: white;
        font-weight: bold;
        z-index: 1;
    }

    .step.active .circle {
        background-color: dodgerblue;
    }

    .step.completed .circle {
        background-color: green;
    }

    /* Garis antar step */
    .line {
        flex: 1;
        height: 3px;
        background-color: #ccc;
        margin: 0;
        position: relative;
        top: -25px;
        /* posisikan di tengah lingkaran */
    }

    .step p {
        margin-top: 10px;
        font-size: 14px;
        text-align: center;
        min-height: 32px;
        /* supaya semua step punya tinggi teks yang sama */
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .step:first-child:before {
        content: none;
    }

    .step.completed+.line {
        background-color: green;
    }

    .step.active+.line {
        background-color: dodgerblue;
    }

    .step.validating .circle {
        background-color: #ffc107;
        /* kuning */
        color: white;
    }

    .step.validating+.line {
        background-color: #ffc107;
    }

    /* Step gagal (merah) */
    .step.rejected .circle {
        background-color: #dc3545;
        /* merah bootstrap */
        color: white;
    }

    /* Garis ikut merah kalau step gagal */
    .step.rejected+.line {
        background-color: #dc3545;
    }

    @media (max-width: 768px) {
        .step p {
            font-size: 12px;
        }

        .circle {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
    }
</style>

@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Selamat Datang di PMB Universitas Tiga Serangkai</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ route('Dashboard') }}">Dashboard</a></li>
                    {{-- <li class="breadcrumb-item active">Starter Page</li> --}}
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-md-12">
                @if($batch)
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Alur PMB Universitas Tiga Serangkai</h5>
                    </div>
                    <div class="card-body">
                        {{-- <div class="step {{ $step >= 1 ? 'completed' : '' }} {{ $step == 1 ? 'active' : '' }}"> --}}
                        {{-- complete:hijau, active:biru, validating:kuning, rejected:merah, ditaruh di step --}}
                        <div class="stepper">
                            <div class="step {{$akun->verifikasi_email==1 ? 'completed' : ''}}">
                                <div class="circle">1</div>
                                <p>Registrasi<i class="fas fa-check-circle text-green"></i></p>
                            </div>
                            <div class="line"></div>
                            <div class="step {{$akun->verifikasi_email==1 ? 'completed' : ''}}">
                                <div class="circle">2</div>
                                <p>Verifikasi<i class="fas fa-check-circle text-green"></i></p>
                            </div>
                            <div class="line"></div>
                            @php
                            $step3 = 'active';
                            $link3 = route('pendaftaran');
                            if($daftar!=null){
                            if($daftar->current_step==1){
                            $step3 = 'validating';
                            $link3 = route('pendaftaran');
                            }elseif($daftar->current_step>1){
                            $step3 = 'completed';
                            $link3 = 'javascript:void(0)';
                            }
                            }
                            @endphp
                            <div class="step {{$step3}}">
                                <div class="circle" >3</div>
                                @if($step3=='active'||$step3=='validating')
                                <a href="{{$link3}}">
                                    <p>Pendaftaran</p>
                                </a>
                                @else
                                <p>Pendaftaran<i class="fas fa-check-circle text-green"></i></p>
                                @endif
                            </div>
                            <div class="line"></div>
                            @php
                            $step4 = '';
                            $link4 = 'javascript:void(0)';
                            if($daftar!=null){
                            if($daftar->current_step==2){
                            $step4 = 'active';
                            $link4 = route('pembayaran');
                            }elseif($daftar->current_step==3){
                            $step4 = 'validating';
                            $link4 = route('pembayaran');
                            }elseif($daftar->current_step>3){
                            $step4 = 'completed';
                            $link4 = 'javascript:void(0)';
                            }
                            }
                            @endphp
                            <div class="step {{$step4}}">
                                <div class="circle">4</div>
                                @if($step4==''||$step4=='completed')
                                <p>Bayar Pendaftaran
                                    @if($step4=='completed')
                                    <i class="fas fa-check-circle text-green"></i>
                                    @endif
                                </p>
                                @else
                                <a href="{{$link4}}">
                                    <p>Bayar Pendaftaran</p>
                                </a>
                                @endif

                            </div>
                            <div class="line"></div>
                            @php
                            $step5 = '';
                            $link5 = 'javascript:void(0)';
                            if($daftar!=null){
                            if($daftar->current_step==4){
                            $step5 = 'active';
                            $link5 = route('BksBeasiswa');
                            }elseif($daftar->current_step==5&&$daftar->current_step!=$daftar->stop_step){
                            $step5 = 'validating';
                            $link5 = route('BksBeasiswa');
                            }elseif($daftar->current_step>5){
                            $step5 = 'completed';
                            $link5 = 'javascript:void(0)';
                            }elseif($daftar->current_step==5&&$daftar->current_step==$daftar->stop_step){
                            $step5 = 'rejected';
                            // $link5 = 'javascript:void(0)';
                            $link5 = route('BksBeasiswa');
                            }
                            }
                            @endphp
                            <div class="step {{$step5}}">
                                <div class="circle">5</div>
                                @if($step5==''||$step5=='completed')
                                <p>
                                    Berkas Khusus
                                    @if($step5=='completed')
                                    <i class="fas fa-check-circle text-green"></i>
                                    @endif
                                </p>
                                @else
                                <a href="{{$link5}}">
                                    <p>Berkas Khusus</p>
                                </a>
                                @endif
                            </div>
                            <div class="line"></div>
                            @php
                            $step6 = '';
                            $link6 = 'javascript:void(0)';
                            if($daftar!=null){
                            if($daftar->current_step==6){
                            $step6 = 'active';
                            $link6 = route('assessment.index');
                            }elseif($daftar->current_step==7&&$daftar->current_step!=$daftar->stop_step){
                            $step6 = 'validating';
                            $link6 = route('assessment.index');
                            }elseif($daftar->current_step>7){
                            $step6 = 'completed';
                            $link6 = 'javascript:void(0)';
                            }elseif($daftar->current_step==7&&$daftar->current_step==$daftar->stop_step){
                            $step6 = 'rejected';
                            $link6 = 'javascript:void(0)';
                            }
                            }
                            @endphp
                            <div class="step {{$step6}}">
                                <div class="circle">6</div>
                                @if($step6==''||$step6=='completed')
                                <p>
                                    Test Assesment
                                    @if($step6=='completed')
                                    <i class="fas fa-check-circle text-green"></i>
                                    @endif
                                </p>
                                @else
                                <a href="{{$link6}}">
                                    <p>Test Assesment</p>
                                </a>
                                @endif
                            </div>
                            <div class="line"></div>
                            @php
                            $step7 = '';
                            $link7 = 'javascript:void(0)';
                            if($daftar!=null){
                            if($daftar->current_step==8){
                            $step7 = 'active';
                            $link7 = route('pembayaranUKT');
                            }elseif($daftar->current_step==9){
                            $step7 = 'validating';
                            $link7 = route('pembayaranUKT');
                            }elseif($daftar->current_step>9){
                            $step7 = 'completed';
                            $link7 = 'javascript:void(0)';
                            }
                            }
                            @endphp
                            <div class="step {{$step7}}">
                                <div class="circle">7</div>
                                @if($step7==''||$step7=='completed')
                                <p>
                                    Bayar UKT
                                    @if($step7=='completed')
                                    <i class="fas fa-check-circle text-green"></i>
                                    @endif
                                </p>
                                @else
                                <a href="{{$link7}}">
                                    <p>Bayar UKT</p>
                                </a>
                                @endif
                            </div>
                            <div class="line"></div>
                            @php
                            $step8 = '';
                            $link8 = 'javascript:void(0)';
                            if($daftar!=null){
                            if($daftar->current_step==10){
                            $step8 = 'active';
                            $link8 = route('biodata');
                            }elseif($daftar->current_step>10){
                            $step8 = 'completed';
                            $link8 = 'javascript:void(0)';
                            }
                            }
                            @endphp
                            <div class="step {{$step8}}">
                                <div class="circle">8</div>
                                @if($step8==''||$step8=='completed')
                                <p>
                                    Biodata
                                    @if($step8=='completed')
                                    <i class="fas fa-check-circle text-green"></i>
                                    @endif
                                </p>
                                @else
                                <a href="{{$link8}}">
                                    <p>Biodata</p>
                                </a>
                                @endif

                            </div>
                            <div class="line"></div>
                            @php
                            $step9 = '';
                            $link9 = 'javascript:void(0)';
                            if($daftar!=null){
                            if($daftar->current_step==11){
                            $step9 = 'completed';
                            $link9 = route('HasilPMB');
                            }
                            }
                            @endphp
                            <div class="step {{$step9}}">
                                <div class="circle">9</div>
                                @if($step9=='')
                                <p>Hasil Akhir</p>
                                @else
                                <a href="{{$link9}}">
                                    <p>Hasil Akhir<i class="fas fa-check-circle text-green"></i></p>
                                </a>
                                @endif

                            </div>
                        </div>
                        <span class="text-bold">Perhatian</span><br>
                        <span class="text-bold"><code>1. Perhatikan setiap step pendaftaran anda</code></span><br>
                        <span class="text-bold"><code>2. Untuk biaya pendaftaran dan biaya UKT Tergantung Jalur pendaftarannya </code></span><br>
                        <span class="text-bold"><code>3. Berkas khusus hanya untuk jalur Beasiswa, Transfer, dan Pindahan </code></span><br>
                        <span class="text-bold"><code>4. Jika nomer step berwarna <span class="badge bg-success">hijau</span> maka step sudah selesai</code></span><br>
                        <span class="text-bold"><code>5. Jika nomor step berwarna <span class="badge bg-primary">biru</span> berarti anda sampai step tersebut</code></span><br>
                        <span class="text-bold"><code>6. Jika nomer step berwarna <span class="badge bg-warning">kuning</span> maka tunggu validasi dari admin dulu baru bisa lanjut ke tahap selanjutnya atau belum konfirmasi pendaftaran </code></span><br>
                        <span class="text-bold"><code>7. Jika nomer step berwarna <span class="badge bg-danger">merah</span> maka anda dinyatakan tidak lolos pada step tersebut </code></span><br>
                        <span class="text-bold"><code>8. Jika nomer step berwarna <span class="badge bg-secondary">abu-abu</span> maka step tersebut belum dibuka </code></span><br>
                        <span class="text-bold"><code>9. Jika Terjadi masalah, silahkan hubungi nomor <a href="https://wa.me/62895705354767?text=Saya tanya terkait pendaftaran" target="_blank">
                                    <i class="bi bi-whatsapp"></i> 0895705354767
                                </a> via Whatsapp </code></span><br>
                        <span class="text-bold"><code>10. Jika ingin melanjutkan ke tahap selanjutnya, click step yang berwarna <span class="badge bg-primary">biru</span></code></span><br><br>

                        <span class="text-bold">Good Luck 😊</span>
                    </div>
                </div>
                @else
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Informasi Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <code class="text-bold">Batch Pendaftaran Belum Dibuka Kembali. Silahkan Tunggu Batch Berikutnya.</code>
                    </div>
                </div>
                @endif
                @if($daftar)
                @if($daftar->bayar_pendaftaran == '1' || $daftar->current_step > 3)
                <div class="card card-success card-outline">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0 text-success font-weight-bold">
                            <i class="fas fa-id-card mr-2"></i>Kartu Peserta Ujian PMB
                        </h5>
                        <a href="{{ route('kartupeserta.download') }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="fas fa-download mr-1"></i> Download Kartu Peserta (PDF)
                        </a>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Selamat! Pembayaran biaya pendaftaran PMB Anda telah terkonfirmasi.</strong></p>
                        <p class="text-muted mb-2">Silakan unduh dan cetak <strong>Kartu Tanda Peserta Ujian Seleksi PMB</strong> Anda. Kartu ini merupakan bukti resmi dan wajib dibawa / ditunjukkan saat mengikuti seleksi.</p>
                        <a href="{{ route('kartupeserta.download') }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-print mr-1"></i> Cetak / Unduh Sekarang
                        </a>
                    </div>
                </div>
                @endif
                @if($daftar->keterangan)
                <div class="card card-warning">
                    <div class="card-header">
                        <h5 class="m-0">Pengumuman</h5>
                    </div>
                    <div class="card-body">
                        <code class="text-bold">{{$daftar->keterangan}}</code>
                    </div>
                </div>
                @endif
                @endif
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection
@section('script')
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $('#loading').show()
    });
</script>
@endsection

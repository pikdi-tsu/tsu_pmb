@extends('user::login/master_email')

@section('content')
<div class="text-center">
    <img src="https://i.imgur.com/zeCXY2b.png" alt="Universitas Tiga Serangkai" style="width: 100px;">
    <br>
    <label>
        Universitas Tiga Serangkai
    </label>
</div>

<div>
    <p>
        Hai {{ $nama }},
        <br>
        Selamat datang di Rekomendator PMB Universitas Tiga Serangkai
    </p>
    <p>
        Berikut adalah Kode Rekomendator Anda.
        <b>{{ $kode }}</b>
        <br>
        Terimakasih
    </p>
</div>

{{-- <div class="text-center">
    <a href="{{ route('verifikasi_akun', [Crypt::encrypt($akun->akun_id)]) }}" class="btn btn-success"><b>Aktivasi Akun</b></a>
</div> --}}
@endsection

<style>
  .logo img{
    height: 100px !important;
    width: auto !important;
    max-height: none !important;
    margin-right: 10px;
}
</style>
<header id="header" class="header d-flex align-items-center fixed-top" style="margin-bottom: 20px">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="{{ route('indexing') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('public/assets/user/img/logotsuputih.png') }}" alt="">
            {{-- <span>
                <h6>Seleksi Penerimaan Mahasiswa Baru</h6>
                <h4 class="sitename">Universitas Tiga Serangkai</h4>
            </span> --}}
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('indexing') }}">Beranda</a></li>
                {{-- <li><a href="{{ route('jalur_pendaftaran') }}">Jalur Pendaftaran</a></li> --}}
                <li class="dropdown"><a href="#"><span>Informasi</span> <i
                            class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ route('program_studi') }}">Program Studi</a></li>
                        <li><a href="{{ route('gelombangukt') }}">Gelombang & UKT</a></li>
                        <li class="dropdown">
                            <a href="#"><span>Alur Pendaftaran</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="{{ route('alurpendaftaranbeasiswa') }}">Beasiswa</a></li>
                                <li><a href="{{ route('pendaftaranreguler') }}">Reguler</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('kontakkami') }}">Kontak Kami</a></li>
                        <li><a href="{{ route('downloadbrowsur') }}">Download</a></li>
                        {{-- <li><a href="{{ route('pengumuman') }}">Pengumuman</a></li> --}}
                        {{-- <li><a href="{{ route('informasi_pendaftaran') }}">Informasi Pendaftaran</a></li> --}}
                        <li><a href="{{ route('daftarrekomendator.index') }}">Daftar Rekomendator</a></li>
                    </ul>
                </li>
                <li>
                    {{-- <a href="{{route('LoginPMB')}}" class="btn btn-outline-success px-2 text-white" style="display:inline-block;">Masuk | Daftar</a> --}}
                    <a href="{{route('LoginPMB')}}">Masuk | Daftar</a>
                </li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

    </div>
</header>

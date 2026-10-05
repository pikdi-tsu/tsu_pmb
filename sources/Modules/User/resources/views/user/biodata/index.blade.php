@extends('user::layouts/halamanbelakang/header')
@section('title', $title)
@section('link_href')
    <style>
        .step-header {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            /* kasih jarak ke form */
        }

        .circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgb(0, 128, 0);
            color: white;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .step-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
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
                        <li class="breadcrumb-item active">{{ $menu }}</li>
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
                    <div class="card card-warning" id="infoCard">
                        <div class="card-header">
                            <h3 class="card-title text-bold">Form Pengisian Biodata</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <p><code>* Perhatikan saat pengisian Biodata</code></p>
                            <p>1. <code>*</code> Wajib diisi</p>
                            <p>2. Halaman 1 terdiri dari data diri</p>
                            <p>3. Halaman 2 terdiri dari data keluarga</p>
                            <p>4. Halaman 3 terdiri dari data sekolah</p>
                            <p>5. Halaman 4 untuk berkas pendaftaran</p>
                            <p><code>* Data harus diisi dengan benar</code></p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    {{-- @for ($i = 0; $i < 10; $i++) --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{ $menu }}</h5>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('biodata.Save') }}" id="form-biodata" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="kodedaftar" id="kodedaftar"
                                    value="{{ $datadaftar->KodePendaftaran }}">
                                <div id="page-1">
                                    <div class="step-header">
                                        <div class="circle">1</div>
                                        <label class="step-title">Data Diri</label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label for="nik"><code>*</code> NIK</label>
                                            <input type="number" min="0" class="form-control pageku-1"
                                                name="nik" id="nik" value="{{ $bio->nik }}"
                                                placeholder="Masukan NIK">
                                            <label for="nokk"><code>*</code> No. KK</label>
                                            <input type="number" min="0" class="form-control pageku-1"
                                                name="nokk" id="nokk" placeholder="Masukan No KK">
                                            <label for="nama"><code>*</code> Nama</label>
                                            <input type="text" class="form-control pageku-1" name="nama"
                                                id="nama" value="{{ $bio->nama }}"
                                                placeholder="Masukan Nama Lengkap">
                                            <label for="jenkel"><code>*</code> Jenis Kelamin</label>
                                            <select class="form-control select2 pageku-1" id="jenkel" name="jenkel">
                                                <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                            <label for="tempat_lahir"><code>*</code> Tempat Lahir</label>
                                            <input type="text" class="form-control pageku-1" name="tempat_lahir"
                                                id="tempat_lahir" placeholder="Masukan Tempat Lahir">
                                            <label for="tgl_lahir"><code>*</code> Tanggal Lahir</label>
                                            <input type="date" class="form-control pageku-1" name="tgl_lahir"
                                                id="tgl_lahir">
                                        </div>
                                        <div class="col-md-4">
                                            <!-- <label for="tinggi_badan"><code>*</code> Tinggi Badan</label>
                                            <input type="number" min="0" class="form-control pageku-1" name="tinggi_badan" id="tinggi_badan" placeholder="Masukan Tinggi Badan">
                                            <label for="berat_badan"><code>*</code> Berat Badan</label>
                                            <input type="number" min="0" class="form-control pageku-1" name="berat_badan" id="berat_badan" placeholder="Masukan Berat Badan"> -->
                                            <label for="agama"><code>*</code> Agama</label>
                                            <select class="form-control select2 pageku-1" id="agama" name="agama">
                                                <option value="" selected disabled>-- Pilih Agama --</option>
                                                <option value="ISLAM">Islam</option>
                                                <option value="KRISTEN">Kristen</option>
                                                <option value="KATOLIK">Katolik</option>
                                                <option value="BUDHA">Budha</option>
                                                <option value="HINDU">Hindu</option>
                                                <option value="KONGHUCU">KONGHUCU</option>
                                            </select>
                                            <label for="nohp"><code>*</code> No HP</label>
                                            <input type="number" min="0" class="form-control pageku-1"
                                                name="nohp" id="nohp" value="{{ $bio->nohp }}"
                                                placeholder="Masukan No HP">
                                            <label for="email"><code>*</code> Email</label>
                                            <input type="email" class="form-control pageku-1" name="email"
                                                id="email" value="{{ session('user')->email }}"
                                                placeholder="Masukan Email" readonly>
                                            <label for="ukuran_jas"><code>*</code> Ukuran Jas Almamater</label>
                                            <input type="text" class="form-control pageku-1" name="ukuran_jas"
                                                id="ukuran_jas" placeholder="Masukan Ukuran Jas Almamater">
                                            <span class="text-bold d-block text-center mt-1"><code>!! Ambil 1 size dari
                                                    size biasa !!</code></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="provinsi"><code>*</code> Provinsi</label>
                                            <select class="form-control select2 pageku-1" id="provinsi" name="provinsi">
                                                <option value="" selected disabled>-- Pilih Provinsi --</option>
                                                @foreach ($provinsi as $p)
                                                    <option value="{{ $p->idprov }}"
                                                        {{ $p->idprov == $bio->provinsi ? 'selected' : '' }}>
                                                        {{ $p->nama_provinsi }}</option>
                                                @endforeach
                                            </select>
                                            <label for="kabupatenkota"><code>*</code> Kabupaten/Kota</label>
                                            <select class="form-control select2 pageku-1" id="kabupatenkota"
                                                name="kabupatenkota">
                                                <option value="" selected disabled>-- Pilih Kabupaten/Kota --
                                                </option>
                                                @foreach ($kabupaten as $kb)
                                                    <option value="{{ $kb->idkab }}"
                                                        {{ $kb->idprov == $bio->provinsi && $kb->idkab == $bio->kabupaten ? 'selected' : '' }}>
                                                        {{ $kb->nama_kabupaten }}</option>
                                                @endforeach
                                            </select>
                                            <label for="kecamatan"><code>*</code> Kecamatan</label>
                                            <select class="form-control select2 pageku-1" id="kecamatan"
                                                name="kecamatan">
                                                <option value="" selected disabled>-- Pilih Kecamatan --</option>
                                                @foreach ($kecamatan as $kc)
                                                    <option value="{{ $kc->idkec }}">{{ $kc->nama_kecamatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="kelurahan"><code>*</code> Desa/Kelurahan</label>
                                            <select class="form-control select2 pageku-1" id="kelurahan"
                                                name="kelurahan">
                                                <option value="" selected disabled>-- Pilih Desa/Kelurahan --
                                                </option>
                                            </select>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="rt"><code>*</code> RT</label>
                                                    <input type="number" min="0" class="form-control pageku-1"
                                                        name="rt" id="rt" placeholder="RT">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="rw"><code>*</code> RW</label>
                                                    <input type="number" min="0" class="form-control pageku-1"
                                                        name="rw" id="rw" placeholder="RW">
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="kodepos"><code>*</code> Kode Pos</label>
                                                    <input type="number" min="0" class="form-control pageku-1"
                                                        name="kodepos" id="kodepos" placeholder="Masukan Kode Pos">
                                                </div>
                                            </div>
                                            <label for="alamat_lengkap"><code>*</code> Alamat Lengkap</label>
                                            <textarea class="form-control pageku-1" name="alamat_lengkap" id="alamat_lengkap" rows="2"
                                                placeholder="Masukan Alamat Lengkap"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="page-2" style="display: none;">
                                    <div class="step-header">
                                        <div class="circle">2</div>
                                        <label class="step-title">Data Keluarga</label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <span class="badge bg-warning text-bold" style="font-size: 18px;">Data
                                                Ayah</span><br>
                                            <label for="nama_ayah"><code>*</code> Nama Ayah</label>
                                            <input type="text" class="form-control pageku-2" name="nama_ayah"
                                                id="nama_ayah" placeholder="Nama Lengkap Ayah">
                                            <label for="tempat_lahir_ayah"><code>*</code> Tempat Lahir</label>
                                            <input type="text" class="form-control pageku-2" name="tempat_lahir_ayah"
                                                id="tempat_lahir_ayah" placeholder="Tempat Lahir Ayah">
                                            <label for="tgl_lahir_ayah"><code>*</code> Tanggal Lahir</label>
                                            <input type="date" class="form-control pageku-2" name="tgl_lahir_ayah"
                                                id="tgl_lahir_ayah">
                                            <label for="statushidup_ayah"><code>*</code> Status</label>
                                            <select class="form-control select2 pageku-2" id="statushidup_ayah"
                                                name="statushidup_ayah">
                                                <option value="" selected disabled>-- Pilih Status --</option>
                                                <option value="Hidup">Hidup</option>
                                                <option value="Meninggal">Meninggal</option>
                                            </select>
                                            <label for="status_ayah"><code>*</code> Status Kekerabatan</label>
                                            <select class="form-control select2 pageku-2" id="status_ayah"
                                                name="status_ayah">
                                                <option value="" selected disabled>-- Pilih Status Kekerabatan --
                                                </option>
                                                <option value="Kandung">Kandung</option>
                                                <option value="Tiri">Tiri</option>
                                            </select>
                                            <label for="nohp_ayah"><code>*</code> No HP</label>
                                            <input type="number" min="0" class="form-control pageku-2"
                                                name="nohp_ayah" id="nohp_ayah" placeholder="No HP Ayah">
                                            <label for="pekerjaan_ayah"><code>*</code> Pekerjaan</label>
                                            <input type="text" class="form-control pageku-2" name="pekerjaan_ayah"
                                                id="pekerjaan_ayah" placeholder="Pekerjaan Ayah">
                                            <label for="penghasilan_ayah"><code>*</code> Penghasilan</label>
                                            <input type="number" min="0" class="form-control pageku-2"
                                                name="penghasilan_ayah" id="penghasilan_ayah" placeholder="Penghasilan">
                                            <label for="alamat_ayah"><code>*</code> Alamat</label>
                                            <textarea class="form-control pageku-2" name="alamat_ayah" id="alamat_ayah" rows="2"
                                                placeholder="Masukan Alamat Lengkap Ayah"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="badge bg-warning text-bold" style="font-size: 18px;">Data
                                                Ibu</span><br>
                                            <label for="nama_ibu"><code>*</code> Nama Ibu</label>
                                            <input type="text" class="form-control pageku-2" name="nama_ibu"
                                                id="nama_ibu" placeholder="Nama Lengkap Ibu">
                                            <label for="tempat_lahir_ibu"><code>*</code> Tempat Lahir</label>
                                            <input type="text" class="form-control pageku-2" name="tempat_lahir_ibu"
                                                id="tempat_lahir_ibu" placeholder="Tempat Lahir Ibu">
                                            <label for="tgl_lahir_ibu"><code>*</code> Tanggal Lahir</label>
                                            <input type="date" class="form-control pageku-2" name="tgl_lahir_ibu"
                                                id="tgl_lahir_ibu">
                                            <label for="statushidup_ibu"><code>*</code> Status</label>
                                            <select class="form-control select2 pageku-2" id="statushidup_ibu"
                                                name="statushidup_ibu">
                                                <option value="" selected disabled>-- Pilih Status --</option>
                                                <option value="Hidup">Hidup</option>
                                                <option value="Meninggal">Meninggal</option>
                                            </select>
                                            <label for="status_ibu"><code>*</code> Status Kekerabatan</label>
                                            <select class="form-control select2 pageku-2" id="status_ibu"
                                                name="status_ibu">
                                                <option value="" selected disabled>-- Pilih Status Kekerabatan --
                                                </option>
                                                <option value="Kandung">Kandung</option>
                                                <option value="Tiri">Tiri</option>
                                            </select>
                                            <label for="nohp_ibu"><code>*</code> No HP</label>
                                            <input type="number" min="0" class="form-control" name="nohp_ibu"
                                                id="nohp_ibu" placeholder="No HP Ibu">
                                            <label for="pekerjaan_ibu"><code>*</code> Pekerjaan</label>
                                            <input type="text" class="form-control pageku-2" name="pekerjaan_ibu"
                                                id="pekerjaan_ibu" placeholder="Pekerjaan Ibu">
                                            <label for="penghasilan_Ibu"><code>*</code> Penghasilan</label>
                                            <input type="number" min="0" class="form-control pageku-2"
                                                name="penghasilan_Ibu" id="penghasilan_ibu" placeholder="Penghasilan">
                                            <label for="alamat_ibu"><code>*</code> Alamat</label>
                                            <textarea class="form-control pageku-2" name="alamat_ibu" id="alamat_ibu" rows="2"
                                                placeholder="Masukan Alamat Lengkap Ibu"></textarea>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <span class="badge bg-warning text-bold" style="font-size: 18px;">Data
                                                Saudara</span><br>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="jumlah_saudara"><code>*</code> Jumlah Saudara</label>
                                                    <input type="number" min="0" class="form-control pageku-2"
                                                        name="jumlah_saudara" id="jumlah_saudara"
                                                        placeholder="Jumlah Saudara">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Nama Saudara</label>
                                                    <input type="text" class="form-control" name="nama_saudara[]"
                                                        id="nama_saudara_0" placeholder="Nama Saudara">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Pekerjaan Saudara</label>
                                                    <input type="text" class="form-control" name="pekerjaan_saudara[]"
                                                        id="pekerjaan_saudara_0" placeholder="Pekerjaan Saudara">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Status</label>
                                                    <select class="form-control select2" name="statushidup_saudara[]"
                                                        id="statushidup_saudara_0">
                                                        <option value="" selected disabled>-- Status Saudara --
                                                        </option>
                                                        <option value="Hidup">Hidup</option>
                                                        <option value="Meninggal">Meninggal</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Status Kekerabatan</label>
                                                    <select class="form-control select2"
                                                        name="statuskekerabatan_saudara[]"
                                                        id="statuskekerabatan_saudara_0">
                                                        <option value="" selected disabled>-- Status Kekerabatan --
                                                        </option>
                                                        <option value="Kakak Kandung">Kakak Kandung</option>
                                                        <option value="Kakak Tiri">Kakak Tiri</option>
                                                        <option value="Adik Kandung">Adik Kandung</option>
                                                        <option value="Adik Tiri">Adik Tiri</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1" style="margin-top:35px;">
                                                    <button type="button" id="add-saudara"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                            <div id="field-saudara">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="page-3" style="display: none;">
                                    <div class="step-header">
                                        <div class="circle">3</div>
                                        <label class="step-title">Data Sekolah</label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label for="nama_sekolah"><code>*</code> Nama Sekolah</label>
                                            <input type="text" name="nama_sekolah" id="nama_sekolah pageku-3"
                                                class="form-control" placeholder="Nama Sekolah">
                                            <label for="jenis_sekolah"><code>*</code> Jenis Sekolah</label>
                                            <select class="form-control select2 pageku-3" id="jenis_sekolah"
                                                name="jenis_sekolah">
                                                <option value="" selected disabled>-- Pilih Jenis Sekolah --</option>
                                                <option value="SMA">SMA</option>
                                                <option value="SMK">SMK</option>
                                                <option value="MA">MA</option>
                                            </select>
                                            <label for="provinsi_sekolah"><code>*</code> Provinsi Sekolah</label>
                                            <select class="form-control select2 pageku-3" id="provinsi_sekolah"
                                                name="provinsi_sekolah">
                                                <option value="" selected disabled>-- Pilih Provinsi Sekolah --
                                                </option>
                                                @foreach ($provinsi as $p)
                                                    <option value="{{ $p->idprov }}">{{ $p->nama_provinsi }}</option>
                                                @endforeach
                                            </select>
                                            <label for="kabupatenkota_sekolah"><code>*</code> Kabupaten/Kota
                                                Sekolah</label>
                                            <select class="form-control select2 pageku-3" id="kabupatenkota_sekolah"
                                                name="kabupatenkota_sekolah">
                                                <option value="" selected disabled>-- Pilih Kabupaten/Kota Sekolah --
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="npsn"><code>*</code> NPSN</label>
                                            <input type="number" min="0" name="npsn" id="npsn"
                                                class="form-control pageku-3" placeholder="NPSN">
                                            <label for="nisn"><code>*</code> NISN</label>
                                            <input type="number" min="0" name="nisn" id="nisn"
                                                class="form-control pageku-3" placeholder="NPSN">
                                            <label for="tahun_lulus"><code>*</code> Tahun Lulus</label>
                                            <select class="form-control select2 pageku-3" id="tahun_lulus"
                                                name="tahun_lulus">
                                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                                @php
                                                    $currentYear = date('Y');
                                                @endphp
                                                @for ($i = 0; $i < 10; $i++)
                                                    @php
                                                        $tahun = $currentYear - $i;
                                                    @endphp
                                                    <option value="{{ $tahun }}"
                                                        {{ $tahun == $datadaftar->tahun_lulus ? 'selected' : '' }}>
                                                        {{ $tahun }}</option>
                                                @endfor
                                            </select>
                                            <label for="nilai_akhir"><code>*</code> Nilai Akhir</label>
                                            <input type="number" min="0" name="nilai_akhir" id="nilai_akhir"
                                                class="form-control pageku-3" placeholder="Nilai Akhir">
                                        </div>

                                    </div>
                                </div>
                                <div id="page-4" style="display: none;">
                                    <div class="step-header">
                                        <div class="circle">4</div>
                                        <label class="step-title">Berkas Pendaftaran</label>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label><code>*</code> Perhatian</label><br>
                                        <code>1. Format file harus berbentuk PDF</code><br>
                                        <code>2. Ukuran Maksimal per berkas adalah 2 MB</code><br><br>

                                        <label><strong>Berikut Berkas yang harus di Upload :</strong></label><br>
                                        <div class="row mt-2">
                                            @foreach ($berkas->berkas as $row => $i)
                                                @php
                                                    $warna = $i->keterangan == 'Wajib' ? 'warning' : 'secondary';
                                                    $required = $i->keterangan == 'Wajib' ? 'required' : '';
                                                @endphp

                                                <div class="col-md-6 mb-3">
                                                    <label>
                                                        {{ $row + 1 }}. {{ $i->nama_berkas }}
                                                        <span class="badge bg-success">{{ $i->formatfile }}</span>
                                                        <span
                                                            class="badge bg-{{ $warna }}">{{ $i->keterangan }}</span>
                                                    </label>
                                                    <div class="custom-file">
                                                        {{-- 1. HIDDEN INPUT INI WAJIB ADA: Untuk dibaca oleh Controller --}}
                                                        <input type="hidden" name="format_wajib[{{ $i->KodeBerkas }}]"
                                                            value="{{ $i->formatfile }}">

                                                        <input type="file" class="custom-file-input input-berkas"
                                                            id="berkas_{{ $i->KodeBerkas }}"
                                                            name="berkas_pendaftaran[{{ $i->KodeBerkas }}]"
                                                            {{-- 2. ACCEPT INI HARUS DINAMIS: Untuk dibaca oleh JavaScript & File Explorer --}}
                                                            accept="{{ str_replace('.', '', strtolower($i->formatfile)) == 'jpg' ? '.jpg,.jpeg' : '.pdf' }}"
                                                            {{ $required }} data-nama="{{ $i->nama_berkas }}"
                                                            data-wajib="{{ $i->keterangan == 'Wajib' ? 'ya' : 'tidak' }}">

                                                        <label class="custom-file-label"
                                                            for="berkas_{{ $i->KodeBerkas }}">Choose file</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3">
                                            <label>Konfirmasi Password Baru</label>
                                            <label>Konfirmasi Password Baru</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Konfirmasi Password Baru</label>
                                            <label>Konfirmasi Password Baru</label>
                                        </div> --}}

                                </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center" style="margin-top: 10px;">
                            <button type="button" id="btn-prev" class="btn btn-secondary btn-sm mr-2"
                                style="display: none;">Prev</button>
                            <button type="button" id="btn-next" class="btn btn-secondary btn-sm">Next</button>
                        </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('Dashboard') }}" class="btn btn-secondary btn-sm mr-2">Kembali</a>
                        <button id="save-biodata" class="btn btn-sm btn-success float-right">Simpan</button>
                    </div>
                </div>
                {{-- @endfor --}}
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
            $('.select2').select2()

            setTimeout(function() {
                $('#infoCard').CardWidget('collapse');
            }, 10000);

            let no = 1;
            let maks = 4;

            loadEvent()

            function loadEvent() {
                nextEvent()
                prevEvent()
                changeKabupaten()
                changeKecamatan()
                changeKelurahan()
                add_saudara()
                changeKabupatenSekolah()
                save_biodata()
            }

            function nextEvent() {
                $('#btn-next').click(function(e) {
                    e.preventDefault();
                    no++;
                    let noprev = no - 1;
                    $('#page-' + no).show()
                    $('#page-' + noprev).hide()
                    if (no == maks) {
                        $(this).hide()
                        $('#btn-simpan').show()
                    } else {
                        $(this).show()
                        if (no > 1) {
                            $('#btn-prev').show()
                        } else {
                            $('#btn-prev').hide()
                        }
                    }
                });
            }

            function prevEvent() {
                $('#btn-prev').click(function(e) {
                    e.preventDefault();
                    console.log('bb')
                    no--;
                    let noprev = no + 1;
                    $('#page-' + no).show()
                    $('#page-' + noprev).hide()
                    if (no == 1) {
                        $(this).hide()
                        $('#btn-next').show()
                    } else {
                        $(this).show()
                        if (no > 1) {
                            $('#btn-next').show()
                        } else {
                            $('#btn-next').hide()
                        }

                    }
                });
            }

            function changeKabupaten() {
                $('#provinsi').change(function(e) {
                    e.preventDefault();
                    let provinsi = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: '{!! url('Biodata/ChangeKabupaten') !!}' + '/' + provinsi,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#kabupatenkota').empty().html(
                                '<option value="" selected disabled>-- Pilih Kabupaten/Kota --</option>'
                            )
                            $('#kecamatan').empty().html(
                                '<option value="" selected disabled>-- Pilih Kecamatan --</option>'
                            )
                            $('#kelurahan').empty().html(
                                '<option value="" selected disabled>-- Pilih Desa/Kelurahan --</option>'
                            )
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Kabupaten Tidak Ada !',
                                    'warning')
                            } else {
                                let dis1 = '';
                                for (i = 0; i < data.kabupaten.length; i++) {
                                    dis1 += '<option value="' + data.kabupaten[i].idkab + '">' +
                                        data.kabupaten[i].nama_kabupaten + '</option>'
                                }
                                $('#kabupatenkota').append(dis1);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error, Silahkan Hubungi Admin !',
                                icon: 'error'
                            }).then((result) => {
                                console.log(0)
                            });
                            return;
                        }
                    });
                });
            }

            function changeKecamatan() {
                $('#kabupatenkota').change(function(e) {
                    e.preventDefault();
                    let provinsi = $('#provinsi').val()
                    let kabupaten = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: '{!! url('Biodata/ChangeKecamatan') !!}' + '/' + provinsi + '/' + kabupaten,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#kecamatan').empty().html(
                                '<option value="" selected disabled>-- Pilih Kecamatan --</option>'
                            )
                            $('#kelurahan').empty().html(
                                '<option value="" selected disabled>-- Pilih Desa/Kelurahan --</option>'
                            )
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Kecamatan Tidak Ada !',
                                    'warning')
                            } else {
                                let dis1 = '';
                                for (i = 0; i < data.kecamatan.length; i++) {
                                    dis1 += '<option value="' + data.kecamatan[i].idkec + '">' +
                                        data.kecamatan[i].nama_kecamatan + '</option>'
                                }
                                $('#kecamatan').append(dis1);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error, Silahkan Hubungi Admin !',
                                icon: 'error'
                            }).then((result) => {
                                console.log(0)
                            });
                            return;
                        }
                    });
                });
            }

            function changeKelurahan() {
                $('#kecamatan').change(function(e) {
                    e.preventDefault();
                    let provinsi = $('#provinsi').val()
                    let kabupaten = $('#kabupatenkota').val()
                    let kecamatan = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: '{!! url('Biodata/ChangeKelurahan') !!}' + '/' + provinsi + '/' + kabupaten + '/' +
                            kecamatan,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#kelurahan').empty().html(
                                '<option value="" selected disabled>-- Pilih Desa/Kelurahan --</option>'
                            )
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Kelurahan Tidak Ada !',
                                    'warning')
                            } else {
                                let dis1 = '';
                                for (i = 0; i < data.kelurahan.length; i++) {
                                    dis1 += '<option value="' + data.kelurahan[i].idkel + '">' +
                                        data.kelurahan[i].nama_kelurahan + '</option>'
                                }
                                $('#kelurahan').append(dis1);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error, Silahkan Hubungi Admin !',
                                icon: 'error'
                            }).then((result) => {
                                console.log(0)
                            });
                            return;
                        }
                    });
                });
            }

            function add_saudara() {
                current = 1;
                $('#add-saudara').click(function(e) {
                    e.preventDefault();
                    current++;
                    let html = `<div class="row saudara-` + current +
                        `">
                                    <div class="col-md-4">
                                        <label>Nama Saudara</label>
                                        <input type="text" class="form-control" name="nama_saudara[]" id="nama_saudara_` +
                        current +
                        `" placeholder="Nama Saudara">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pekerjaan Saudara</label>
                                        <input type="text" class="form-control" name="pekerjaan_saudara[]" id="pekerjaan_saudara_` +
                        current +
                        `" placeholder="Pekerjaan Saudara">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Status</label>
                                        <select class="form-control select2" name="statushidup_saudara[]" id="statushidup_saudara_` +
                        current +
                        `">
                                            <option value="" selected disabled>-- Status Saudara --</option>
                                            <option value="Hidup">Hidup</option>
                                            <option value="Meninggal">Meninggal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Status Kekerabatan</label>
                                        <select class="form-control select2" name="statuskekerabatan_saudara[]" id="statuskekerabatan_saudara_` +
                        current + `">
                                            <option value="" selected disabled>-- Status Kekerabatan --</option>
                                            <option value="Kakak Kandung">Kakak Kandung</option>
                                            <option value="Kakak Tiri">Kakak Tiri</option>
                                            <option value="Adik Kandung">Adik Kandung</option>
                                            <option value="Adik Tiri">Adik Tiri</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1" style="margin-top:35px;">
                                        <button type="button" data-id="` + current + `" class="btn btn-danger btn-sm remove-saudara"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>`
                    $('#field-saudara').append(html);
                    $('#field-saudara .select2').select2();
                    removesaudara()
                });
            }

            function removesaudara() {
                $('.remove-saudara').click(function(e) {
                    let idku = $(this).data('id')
                    $('.saudara-' + idku).remove();
                });
            }

            function changeKabupatenSekolah() {
                $('#provinsi_sekolah').change(function(e) {
                    e.preventDefault();
                    let provinsi = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: '{!! url('Biodata/ChangeKabupaten') !!}' + '/' + provinsi,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#kabupatenkota_sekolah').empty().html(
                                '<option value="" selected disabled>-- Pilih Kabupaten/Kota Sekolah --</option>'
                            )
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Kabupaten Sekolah Tidak Ada !',
                                    'warning')
                            } else {
                                let dis1 = '';
                                for (i = 0; i < data.kabupaten.length; i++) {
                                    dis1 += '<option value="' + data.kabupaten[i].idkab + '">' +
                                        data.kabupaten[i].nama_kabupaten + '</option>'
                                }
                                $('#kabupatenkota_sekolah').append(dis1);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error, Silahkan Hubungi Admin !',
                                icon: 'error'
                            }).then((result) => {
                                console.log(0)
                            });
                            return;
                        }
                    });
                });
            }

            function save_biodata() {
                $('#save-biodata').click(function(e) {
                    e.preventDefault();
                    let validasi_bio = validasi_biodata()
                    let btn = $(this);
                    if (validasi_bio != 'ok') {
                        notifalert('Information', validasi_bio, 'warning')
                    } else {
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Data Biodata Anda Sudah Yakin Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.value) {
                                // Tampilkan loading di sini!
                                $('#loading').show();

                                btn.prop('disabled', true);
                                $('#form-biodata').submit();
                            } else {
                                return false;
                            }
                        });
                    }
                });
            }

            function validasi_biodata() {
                let page1 = $('.pageku-1')
                let jmlpage1 = 0;
                for (i = 1; i < page1.length; i++) {
                    if (page1[i].value == '' || page1[i].value == null) {
                        jmlpage1++
                    }
                }

                let page2 = $('.pageku-2')
                let jmlpage2 = 0;
                for (i = 1; i < page2.length; i++) {
                    if (page2[i].value == '' || page2[i].value == null) {
                        jmlpage2++
                    }
                }

                let page3 = $('.pageku-3')
                let jmlpage3 = 0;
                for (i = 1; i < page3.length; i++) {
                    if (page3[i].value == '' || page3[i].value == null) {
                        jmlpage3++
                    }
                }

                let notif_berkas = '';
                let inputsBerkas = $('.input-berkas');

                for (let i = 0; i < inputsBerkas.length; i++) {
                    let input = inputsBerkas[i];
                    let file = input.files[0];
                    let namaBerkas = $(input).data('nama');
                    let isWajib = $(input).data('wajib');

                    // Cek jika berkas wajib tapi kosong
                    if (isWajib === 'ya' && !file) {
                        notif_berkas = 'Berkas Wajib (' + namaBerkas + ') belum diupload!';
                        break; // Hentikan loop jika ketemu error
                    }

                    if (file) {
                        let fileType = file.type;
                        let fileSize = file.size; // dalam byte

                        // Ambil atribut 'accept' yang sudah kita setel di HTML (misal: ".pdf" atau ".jpg,.jpeg")
                        let acceptFormat = $(input).attr('accept');

                        // Validasi cerdas menyesuaikan format yang diminta
                        if (acceptFormat.includes('pdf') && fileType != 'application/pdf') {
                            notif_berkas = 'Format berkas ' + namaBerkas + ' harus PDF!';
                            break;
                        } else if (acceptFormat.includes('jpg') && fileType != 'image/jpeg' && fileType !=
                            'image/jpg') {
                            notif_berkas = 'Format berkas ' + namaBerkas + ' harus JPG/JPEG!';
                            break;
                        }

                        // Validasi ukuran file (maks 2 MB)
                        if (fileSize > 2 * 1024 * 1024) {
                            notif_berkas = 'Ukuran berkas ' + namaBerkas + ' maksimal 2 MB!';
                            break;
                        }
                    }
                }

                // Dibagian akhir function validasi_biodata() penentuan notif-nya:
                let notif = '';

                if (jmlpage1 > 0) {
                    notif = 'Data Diri Belum Lengkap ! Lengkapi Data Diri Pada Halaman 1 !';
                } else if (jmlpage2 > 0) {
                    notif = 'Data keluarga Belum Lengkap ! Lengkapi Data Keluarga Pada Halaman 2 !';
                } else if (jmlpage3 > 0) {
                    notif = 'Data Sekolah Belum Lengkap ! Lengkapi Data Sekolah Pada Halaman 3 !';
                } else if (notif_berkas !== '') {
                    notif = notif_berkas; // Masukkan pesan error dari loop berkas tadi
                } else {
                    notif = 'ok';
                }

                return notif;
            }

        });
    </script>
@endsection

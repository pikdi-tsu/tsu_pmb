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
                    <h1>{{ $menu }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('Dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
                    {{-- @for ($i = 0; $i < 10; $i++) --}}
                    <div class="card card-success">
                        <div class="card-header">
                            <h5 class="card-title">Pengumuman Diterima</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-bold">Selamat Anda Diterima di Universitas Tiga Serangkai pada :</p>
                            <p class="text-bold">Fakultas : {{ $datadaftar->jurusan_acc->fakultas->namafakultas }}</p>
                            <p class="text-bold">Jurusan :
                                {{ $datadaftar->jurusan_acc->jenjang->jenjang }}-{{ $datadaftar->jurusan_acc->jurusan }}</p>
                        </div>
                    </div>
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h5 class="card-title">Data Pendaftaran</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tabel-detail" class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No Registrasi</th>
                                        <th>: {{ $datadaftar->KodePendaftaran }}</th>
                                        <th>Nama Calon Mahasiswa</th>
                                        <th>: {{ $datadaftar->biodata->nama }}</th>
                                    </tr>
                                    <tr>
                                        <th>Batch Daftar</th>
                                        <th>: {{ $datadaftar->batch->nama_batch }} {{ $datadaftar->batch->tahun_akademik }}
                                        </th>
                                        <th>Tahun Lulus</th>
                                        <th>: {{ $datadaftar->tahun_lulus }}</th>
                                    </tr>
                                    <tr>
                                        <th>Jalur Daftar</th>
                                        <th>: {{ $datadaftar->jalur->jenis_pendaftaran }}</th>
                                        <th>Jurusan Sekolah</th>
                                        <th>:
                                            {{ $datadaftar->jurusansekolah->sekolah }}/{{ $datadaftar->jurusansekolah->jurusan_sekolah }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Program Studi Pilihan 1</th>
                                        <th>:
                                            {{ $datadaftar->prodi1->jenjang->jenjang }}-{{ $datadaftar->prodi1->jurusan }}
                                        </th>
                                        <th>UKT Program Studi 1</th>
                                        <th>: {{ rupiah($ukt1->biaya_ukt) }}</th>
                                    </tr>

                                    <tr>
                                        <th>Program Studi Pilihan 2</th>
                                        <th>:
                                            {{ $datadaftar->prodi2 ? $datadaftar->prodi2->jenjang->jenjang . '-' . $datadaftar->prodi2->jurusan : '-' }}
                                        </th>
                                        <th>UKT Program Studi 2</th>
                                        <th>: {{ $ukt2 ? rupiah($ukt2->biaya_ukt) : '-' }}</th>
                                    </tr>

                                    <tr>
                                        <th>Program Studi Pilihan 3</th>
                                        <th>:
                                            {{ $datadaftar->prodi3 ? $datadaftar->prodi3->jenjang->jenjang . '-' . $datadaftar->prodi3->jurusan : '-' }}
                                        </th>
                                        <th>UKT Program Studi 3</th>
                                        <th>: {{ $ukt3 ? rupiah($ukt3->biaya_ukt) : '-' }}</th>
                                    </tr>

                                    @php
                                        $tglkonfirm = explode(' ', $datadaftar->tgl_konfirm);
                                        $tgldaftar = explode(' ', $datadaftar->tgl_daftar);
                                    @endphp
                                    <tr>
                                        <th>Konfirmasi Daftar</th>
                                        <th>: {{ tglIndo($tglkonfirm[0]) }}</th>
                                        <th>Biaya Pendaftaran</th>
                                        <th>:
                                            {{ $datadaftar->jalur->biaya_pendaftaran == '1' ? rupiah($datadaftar->jalur->jml_biaya_pendaftaran) : 'Gratis' }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Daftar</th>
                                        <th>: {{ tglIndo($tgldaftar[0]) }}</th>
                                        <th>Waktu Kuliah</th>
                                        <th>: {{ $datadaftar->waktukuliah->waktu }}</th>
                                    </tr>
                                    <tr>
                                        <th>Status UKT</th>
                                        <th>: {{ $datadaftar->jalur->status_ukt == '0' ? 'Gratis' : 'Bayar' }}</th>
                                        <th>
                                            <code>*Khusus Beasiswa</code>
                                            <br>
                                            Kategori Beasiswa
                                        </th>
                                        <th>:
                                            @if ($datadaftar->jenisbeasiswa == null)
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                {{ $datadaftar->jenisbeasiswa->jenis_beasiswa }}
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <code>*Khusus Beasiswa</code>
                                            <br>
                                            Tingkat Kejuaraan
                                        </th>
                                        @php
                                            $tingkat =
                                                $datadaftar->jenisbeasiswa == null
                                                    ? '-'
                                                    : $datadaftar->jenisbeasiswa->idtingkat;
                                        @endphp
                                        <th>:
                                            @if ($tingkat == null || $tingkat == '-')
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                {{ $datadaftar->jenisbeasiswa->tingkat->tingkat_kejuaraan }}
                                            @endif
                                        </th>
                                        <th>
                                            <code>*Khusus Beasiswa</code>
                                            <br>
                                            Keterangan
                                        </th>
                                        <th>:
                                            @if ($datadaftar->jenisbeasiswa == null)
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                {{ $datadaftar->jenisbeasiswa->juara_ke }}
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <code>*Khusus Beasiswa</code>
                                            <br>
                                            Durasi Beasiswa D3
                                        </th>
                                        <th>:
                                            @if ($datadaftar->jenisbeasiswa == null)
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                {{ $datadaftar->jenisbeasiswa->durasi_d3 . ' Semester' }}
                                            @endif
                                        </th>
                                        <th>
                                            <code>*Khusus Beasiswa</code>
                                            <br>
                                            Durasi Beasiswa S1
                                        </th>
                                        <th>:
                                            @if ($datadaftar->jenisbeasiswa == null)
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                {{ $datadaftar->jenisbeasiswa->durasi_s1 . ' Semester' }}
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <!--<code>*Khusus Beasiswa</code>-->
                                            <!--<br>-->
                                            <!--Berkas Khusus Beasiswa-->
                                        </th>

                                        <th>
                                            <!--:-->
                                            <!--@if ($berkas_khusus == null)-->
                                            <!--    <span class="badge bg-warning">Non Beasiswa</span>-->
                                            <!--@else-->
                                            <!--    <a href="{{ $berkas_khusus }}" target="_blank"><span-->
                                            <!--            class="badge bg-success">{{ $datadaftar->berkas_khusus }}</span></a>-->
                                            <!--@endif-->
                                        </th>
                                        <th>Rekomendator</th>
                                        <th colspan="3">: {{ $rekomendator }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h5 class="card-title">Data Pembayaran</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tabel-detail" class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nomer Registrasi</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datadaftar->bayar as $key => $p)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $datadaftar->biodata->nama }}</td>
                                            <td>{{ $p->id_referensi }}</td>
                                            <td>{{ $p->kategori }}</td>
                                            <td>{{ rupiah($p->jumlah) }}</td>
                                            <td><span class="badge bg-success">{{ $p->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="card-title"></i> Assessment
                            </h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            {{-- Kita bungkus dengan list-group agar terlihat rapi dan elegan --}}
                            <ul class="list-group list-group-flush">

                                {{-- Header List (Opsional, untuk memperjelas konteks) --}}
                                <li class="list-group-item bg-light">
                                    <span class="text-bold text-muted">Subtes yang telah diselesaikan:</span>
                                </li>

                                @if (count($subtes_selesai) > 0)
                                    @foreach ($subtes_selesai as $key => $subtes)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                {{-- Nomor & Nama Test --}}
                                                <span class="text-bold text-dark d-block">
                                                    {{ $key + 1 }}. {{ $subtes->nama_test }}
                                                </span>
                                            </div>

                                            {{-- Badge Status --}}
                                            <div>
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item text-center p-4">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2 text-warning"></i>
                                            <p class="mb-0">Belum ada subtes yang dikerjakan atau diselesaikan.</p>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h5 class="card-title">Biodata</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="page-1">
                                <div class="step-header">
                                    <div class="circle">1</div>
                                    <label class="step-title">Data Diri</label>
                                </div>

                                <div class="row mt-3">
                                    <table id="tabel-detail-diri" class="table" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <th style="width: 15%;">NIK</th>
                                                <td style="width: 35%;">: {{ $datadaftar->biodata->nik }}</td>
                                                <th style="width: 15%;">Provinsi</th>
                                                <td style="width: 35%;">: {{ $provinsi->nama_provinsi }}</td>
                                            </tr>
                                            <tr>
                                                <th>No KK</th>
                                                <td>: {{ $datadaftar->biodata->nokk }}</td>
                                                <th>Kabupaten/Kota</th>
                                                <td>: {{ $kabupaten->nama_kabupaten }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>: {{ $datadaftar->biodata->nama }}</td>
                                                <th>Kecamatan</th>
                                                <td>: {{ $kecamatan->nama_kecamatan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td>: {{ $datadaftar->biodata->jenkel }}</td>
                                                <th>Desa/Kelurahan</th>
                                                <td>: {{ $kelurahan->nama_kelurahan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tempat Lahir</th>
                                                <td>: {{ $datadaftar->biodata->tempat_lahir }}</td>
                                                <th>RT / RW / Kode Pos</th>
                                                <td>: {{ $datadaftar->biodata->rt }} / {{ $datadaftar->biodata->rw }} /
                                                    {{ $datadaftar->biodata->kodepos }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Lahir</th>
                                                <td>: {{ tglIndo($datadaftar->biodata->tgl_lahir) }}</td>
                                                <th>Alamat Lengkap</th>
                                                <td>: {{ $datadaftar->biodata->alamat_lengkap }}</td>
                                            </tr>
                                            <tr>
                                                <th>Agama</th>
                                                <td>: {{ $datadaftar->biodata->agama }}</td>
                                                <th>No HP</th>
                                                <td>: {{ $datadaftar->biodata->nohp }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>: {{ session('user')->email }}</td>
                                                <th>Ukuran Jas Almamater</th>
                                                <td>: {{ $datadaftar->biodata->ukuran_jas }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="page-2" style="display: none;">
                                <div class="step-header">
                                    <div class="circle">2</div>
                                    <label class="step-title">Data Keluarga</label>
                                </div>
                                <div class="row mt-3">
                                    <table id="tabel-detail" class="table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="text-bold">
                                                    <span class="badge bg-warning text-bold" style="font-size: 18px;">Data
                                                        Ayah</span>
                                                </th>
                                                <th colspan="2" class="text-bold">
                                                    <span class="badge bg-warning text-bold" style="font-size: 18px;">Data
                                                        Ibu</span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Nama Ayah</th>
                                                <th>: {{ $datadaftar->biodata->nama_ayah }}</th>
                                                <th>Nama Ibu</th>
                                                <th>: {{ $datadaftar->biodata->nama_ibu }}</th>
                                            </tr>
                                            <tr>
                                                <th>Tempat Lahir Ayah</th>
                                                <th>: {{ $datadaftar->biodata->tempat_lahir_ayah }}</th>
                                                <th>Tempat Lahir Ibu</th>
                                                <th>: {{ $datadaftar->biodata->tempat_lahir_ibu }}</th>
                                            </tr>
                                            <tr>
                                                <th>Tgl Lahir Ayah</th>
                                                <th>: {{ tglIndo($datadaftar->biodata->tgl_lahir_ayah) }}</th>
                                                <th>Tgl Lahir Ibu</th>
                                                <th>: {{ tglIndo($datadaftar->biodata->tgl_lahir_ibu) }}</th>
                                            </tr>
                                            <tr>
                                                <th>Status Ayah</th>
                                                <th>: {{ $datadaftar->biodata->statushidup_ayah }}</th>
                                                <th>Status Ibu</th>
                                                <th>: {{ $datadaftar->biodata->statushidup_ibu }}</th>
                                            </tr>
                                            <tr>
                                                <th>Status Kekerabatan Ayah</th>
                                                <th>: {{ $datadaftar->biodata->status_ayah }}</th>
                                                <th>Status Kekerabatan Ibu</th>
                                                <th>: {{ $datadaftar->biodata->status_ibu }}</th>
                                            </tr>
                                            <tr>
                                                <th>No HP Ayah</th>
                                                <th>: {{ $datadaftar->biodata->nohp_ayah }}</th>
                                                <th>No HP Ibu</th>
                                                <th>: {{ $datadaftar->biodata->nohp_ibu }}</th>
                                            </tr>
                                            <tr>
                                                <th>Pekerjaan Ayah</th>
                                                <th>: {{ $datadaftar->biodata->pekerjaan_ayah }}</th>
                                                <th>pekerjaan Ibu</th>
                                                <th>: {{ $datadaftar->biodata->pekerjaan_ibu }}</th>
                                            </tr>
                                            <tr>
                                                <th>Penghasilan Ayah</th>
                                                <th>: {{ rupiah($datadaftar->biodata->penghasilan_ayah) }}</th>
                                                <th>Penghasilan Ibu</th>
                                                <th>: {{ rupiah($datadaftar->biodata->penghasilan_ibu) }}</th>
                                            </tr>
                                            <tr>
                                                <th>Alamat Ayah</th>
                                                <th>: {{ $datadaftar->biodata->alamat_ayah }}</th>
                                                <th>Alamat Ibu</th>
                                                <th>: {{ $datadaftar->biodata->alamat_ibu }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <br>
                                    <table id="tabel-detail" class="table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="8">
                                                    <span class="badge bg-warning text-bold" style="font-size: 18px;">Data
                                                        Saudara</span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="8">Jumlah Saudara :
                                                    {{ $datadaftar->biodata->jumlah_saudara }}</th>
                                            </tr>
                                            @if ($datadaftar->biodata->jumlah_saudara > 0)
                                                <tr style="background-color: aqua;">
                                                    <th>Nama Saudara</th>
                                                    <th>Pekerjaan Saudara</th>
                                                    <th>Status</th>
                                                    <th>Status Kekerabatan</th>
                                                </tr>
                                                @foreach ($datadaftar->biodata->saudara as $v)
                                                    <tr>
                                                        <th>{{ $v->nama }}</th>
                                                        <th>{{ $v->pekerjaan }}</th>
                                                        <th>{{ $v->status_hidup }}</th>
                                                        <th>{{ $v->status_kekerabatan }}</th>
                                                    </tr>
                                                @endforeach
                                            @endif

                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div id="page-3" style="display: none;">
                                <div class="step-header">
                                    <div class="circle">3</div>
                                    <label class="step-title">Data Sekolah</label>
                                </div>
                                <div class="row mt-3">
                                    <table id="tabel-detail" class="table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Nama Sekolah</th>
                                                <th>: {{ $datadaftar->biodata->nama_sekolah }}</th>
                                                <th>NPSN</th>
                                                <th>: {{ $datadaftar->biodata->npsn }}</th>
                                            </tr>
                                            <tr>
                                                <th>Jenis Sekolah</th>
                                                <th>: {{ $datadaftar->biodata->jenis_sekolah }}</th>
                                                <th>NISN</th>
                                                <th>: {{ $datadaftar->biodata->nisn }}</th>
                                            </tr>
                                            <tr>
                                                <th>Provinsi Sekolah</th>
                                                <th>: {{ $provinsi_sekolah->nama_provinsi }}</th>
                                                <th>Tahun Lulus</th>
                                                <th>: {{ $datadaftar->tahun_lulus }}</th>
                                            </tr>
                                            <tr>
                                                <th>Kabupaten/Kota Sekolah</th>
                                                <th>: {{ $kabupaten_sekolah->nama_kabupaten }}</th>
                                                <th>Nilai Akhir</th>
                                                <th>: {{ $datadaftar->biodata->nilai_akhir }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div id="page-4" style="display: none;">
                                <div class="step-header">
                                    <div class="circle">4</div>
                                    <label class="step-title">Berkas Pendaftaran</label>
                                </div>
                                <div class="row mt-3">
                                    <table id="tabel-detail" class="table table-bordered table-striped"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="4" style="background-color: cadetblue; color: white;"
                                                    class="text-center">Daftar Berkas Pendaftaran</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th>Nama Berkas yang Diminta</th>
                                                <th class="text-center" width="15%">Status Wajib</th>
                                                <th class="text-center" width="25%">File Pendaftar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailberkas_umum as $row => $b)
                                                @php
                                                    // CARA PALING AMPUH: Cari id_berkas yang sama persis dengan $b->id
                                                    $fileUploaded = $berkasPendaftar->firstWhere('id_berkas', $b->id);

                                                    $warna = $b->keterangan == 'Wajib' ? 'danger' : 'secondary';
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $row + 1 }}</td>
                                                    <td>
                                                        {{ $b->nama_berkas }} <br>
                                                        <small class="text-muted">Format: {{ $b->formatfile }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-{{ $warna }}">{{ $b->keterangan }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($fileUploaded)
                                                            @php
                                                                $parameter = \App\Models\Parameter::where(
                                                                    'id',
                                                                    1,
                                                                )->first();
                                                                $pathFile = url(
                                                                    'admin/file/' .
                                                                        strtoupper($parameter->file_umum) .
                                                                        '/' .
                                                                        $fileUploaded->nama_berkas,
                                                                );
                                                            @endphp
                                                            <a href="{{ $pathFile }}" target="_blank"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fa fa-eye"></i> Lihat Berkas
                                                            </a>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Belum Upload</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-center" style="margin-top: 10px;">
                                <button type="button" id="btn-prev" class="btn btn-secondary btn-sm mr-2"
                                    style="display: none;">Prev</button>
                                <button type="button" id="btn-next" class="btn btn-secondary btn-sm">Next</button>
                            </div>
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

            $('.select2').select2()

            let no = 1;
            let maks = 4;

            loadEvent()

            function loadEvent() {
                nextEvent()
                prevEvent()
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

            // $('#loading').show()

        });
    </script>
@endsection

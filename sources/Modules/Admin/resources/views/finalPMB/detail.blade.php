@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')

    <style>
        .step-header {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
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

        .test-nav-btn {
            width: 100px;
        }
    </style>

@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $menu }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.finalpmb.show') }}">Final PMB</a></li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h5 class="card-title">Pengumuman Diterima</h5>
                        </div>
                        <div class="card-body">
                            @if ($datadaftar->jurusan_acc)
                            <p class="text-bold">Diterima di Universitas Tiga Serangkai pada :</p>
                            <p class="text-bold">Fakultas : {{ $datadaftar->jurusan_acc->fakultas->namafakultas }}</p>
                            <p class="text-bold">Jurusan :
                                {{ $datadaftar->jurusan_acc->jenjang->jenjang }}-{{ $datadaftar->jurusan_acc->jurusan }}</p>
                            @else
                                <p class="text-bold">Belum Ada Pengumuman Diterima</p>
                            @endif
                            <a href="{{ route('admin.finalpmb.show') }}"
                                class="btn btn-secondary btn-sm float-right">Kembali</a>
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
                                        <th>: {{ $datadaftar->batch->nama_batch }}
                                            {{ $datadaftar->batch->tahun_akademik }}</th>
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
                                            {{ $datadaftar->prodi1 ? $datadaftar->prodi1->jenjang->jenjang . '-' . $datadaftar->prodi1->jurusan : '-' }}
                                        </th>
                                        <th>UKT Program Studi 1</th>
                                        <th>: {{ $ukt1 ? rupiah($ukt1->biaya_ukt) : '-' }}</th>
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
                                            <code>*Khusus Beasiswa</code><br>Kategori Beasiswa
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
                                            <code>*Khusus Beasiswa</code><br>Tingkat Kejuaraan
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
                                            <code>*Khusus Beasiswa</code><br>Keterangan
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
                                            <code>*Khusus Beasiswa</code><br>Durasi Beasiswa D3
                                        </th>
                                        <th>:
                                            @if ($datadaftar->jenisbeasiswa == null)
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                {{ $datadaftar->jenisbeasiswa->durasi_d3 . ' Semester' }}
                                            @endif
                                        </th>
                                        <th>
                                            <code>*Khusus Beasiswa</code><br>Durasi Beasiswa S1
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
                                            <code>*Khusus Beasiswa</code><br>Berkas Khusus Beasiswa
                                        </th>
                                        <th>:
                                            @if ($berkas_khusus == null)
                                                <span class="badge bg-warning">Non Beasiswa</span>
                                            @else
                                                <a href="{{ $berkas_khusus }}" target="_blank"><span
                                                        class="badge bg-success">{{ $datadaftar->berkas_khusus }}</span></a>
                                            @endif
                                        </th>
                                        <th>Rekomendator</th>
                                        <th>: {{ $rekomendator }}</th>
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

                    <div class="card card-primary collapsed-card" id="card-assessment">
                        <div class="card-header">
                            <h5 class="card-title">Hasil Assesment</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="test-navigation" style="display: none;"
                                class="justify-content-between align-items-center mb-3">
                                <button type="button" id="btn-prev-test" class="btn btn-secondary btn-sm test-nav-btn"><i
                                        class="fas fa-chevron-left"></i> Previous</button>
                                <h5 id="test-indicator" class="font-weight-bold m-0 text-primary">Assesment 1 dari 3</h5>
                                <button type="button" id="btn-next-test" class="btn btn-primary btn-sm test-nav-btn">Next
                                    <i class="fas fa-chevron-right"></i></button>
                            </div>

                            <div id="assessment-container">
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i><br>Memuat data
                                    assessment...
                                </div>
                            </div>
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
                            @if (empty($datadaftar->biodata))
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i> Pendaftar belum mencapai atau belum
                                    menyelesaikan tahap pengisian Biodata.
                                </div>
                            @else
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
                                                    <td style="width: 35%;">: {{ $datadaftar->biodata->nik ?? '-' }}</td>
                                                    <th style="width: 15%;">Provinsi</th>
                                                    <td style="width: 35%;">:
                                                        {{ $provinsi == null ? '-' : $provinsi->nama_provinsi }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No KK</th>
                                                    <td>: {{ $datadaftar->biodata->nokk ?? '-' }}</td>
                                                    <th>Kabupaten/Kota</th>
                                                    <td>: {{ $kabupaten == null ? '-' : $kabupaten->nama_kabupaten }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nama</th>
                                                    <td>: {{ $datadaftar->biodata->nama ?? '-' }}</td>
                                                    <th>Kecamatan</th>
                                                    <td>: {{ $kecamatan == null ? '-' : $kecamatan->nama_kecamatan }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Kelamin</th>
                                                    <td>: {{ $datadaftar->biodata->jenkel ?? '-' }}</td>
                                                    <th>Desa/Kelurahan</th>
                                                    <td>: {{ $kelurahan == null ? '-' : $kelurahan->nama_kelurahan }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tempat Lahir</th>
                                                    <td>: {{ $datadaftar->biodata->tempat_lahir ?? '-' }}</td>
                                                    <th>RT / RW / Kode Pos</th>
                                                    <td>: {{ $datadaftar->biodata->rt ?? '-' }} /
                                                        {{ $datadaftar->biodata->rw ?? '-' }} /
                                                        {{ $datadaftar->biodata->kodepos ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Lahir</th>
                                                    <td>:
                                                        {{ $datadaftar->biodata->tgl_lahir ? tglIndo($datadaftar->biodata->tgl_lahir) : '-' }}
                                                    </td>
                                                    <th>Alamat Lengkap</th>
                                                    <td>: {{ $datadaftar->biodata->alamat_lengkap ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Agama</th>
                                                    <td>: {{ $datadaftar->biodata->agama ?? '-' }}</td>
                                                    <th>No HP</th>
                                                    <td>: {{ $datadaftar->biodata->nohp ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>: {{ session('user')->email ?? '-' }}</td>
                                                    <th>Ukuran Jas Almamater</th>
                                                    <td>: {{ $datadaftar->biodata->ukuran_jas ?? '-' }}</td>
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
                                                    <th colspan="2" class="text-bold"><span
                                                            class="badge bg-warning text-bold"
                                                            style="font-size: 18px;">Data Ayah</span></th>
                                                    <th colspan="2" class="text-bold"><span
                                                            class="badge bg-warning text-bold"
                                                            style="font-size: 18px;">Data Ibu</span></th>
                                                </tr>
                                                <tr>
                                                    <th>Nama Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->nama_ayah ?? '-' }}</th>
                                                    <th>Nama Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->nama_ibu ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Tempat Lahir Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->tempat_lahir_ayah ?? '-' }}</th>
                                                    <th>Tempat Lahir Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->tempat_lahir_ibu ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Tgl Lahir Ayah</th>
                                                    <th>:
                                                        {{ $datadaftar->biodata->tgl_lahir_ayah ? tglIndo($datadaftar->biodata->tgl_lahir_ayah) : '-' }}
                                                    </th>
                                                    <th>Tgl Lahir Ibu</th>
                                                    <th>:
                                                        {{ $datadaftar->biodata->tgl_lahir_ibu ? tglIndo($datadaftar->biodata->tgl_lahir_ibu) : '-' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Status Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->statushidup_ayah ?? '-' }}</th>
                                                    <th>Status Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->statushidup_ibu ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Status Kekerabatan Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->status_ayah ?? '-' }}</th>
                                                    <th>Status Kekerabatan Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->status_ibu ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No HP Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->nohp_ayah ?? '-' }}</th>
                                                    <th>No HP Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->nohp_ibu ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Pekerjaan Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->pekerjaan_ayah ?? '-' }}</th>
                                                    <th>pekerjaan Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->pekerjaan_ibu ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Penghasilan Ayah</th>
                                                    <th>:
                                                        {{ $datadaftar->biodata->penghasilan_ayah ? rupiah($datadaftar->biodata->penghasilan_ayah) : '-' }}
                                                    </th>
                                                    <th>Penghasilan Ibu</th>
                                                    <th>:
                                                        {{ $datadaftar->biodata->penghasilan_ibu ? rupiah($datadaftar->biodata->penghasilan_ibu) : '-' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Alamat Ayah</th>
                                                    <th>: {{ $datadaftar->biodata->alamat_ayah ?? '-' }}</th>
                                                    <th>Alamat Ibu</th>
                                                    <th>: {{ $datadaftar->biodata->alamat_ibu ?? '-' }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <br>
                                        <table id="tabel-detail" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th colspan="8"><span class="badge bg-warning text-bold"
                                                            style="font-size: 18px;">Data Saudara</span></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="8">Jumlah Saudara :
                                                        {{ $datadaftar->biodata->jumlah_saudara ?? 0 }}</th>
                                                </tr>
                                                @if (!empty($datadaftar->biodata->jumlah_saudara) && $datadaftar->biodata->jumlah_saudara > 0)
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
                                        <br>
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
                                                    <th>: {{ $datadaftar->biodata->nama_sekolah ?? '-' }}</th>
                                                    <th>NPSN</th>
                                                    <th>: {{ $datadaftar->biodata->npsn ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Sekolah</th>
                                                    <th>: {{ $datadaftar->biodata->jenis_sekolah ?? '-' }}</th>
                                                    <th>NISN</th>
                                                    <th>: {{ $datadaftar->biodata->nisn ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Provinsi Sekolah</th>
                                                    <th>:
                                                        {{ $provinsi_sekolah == null ? '-' : $provinsi_sekolah->nama_provinsi }}
                                                    </th>
                                                    <th>Tahun Lulus</th>
                                                    <th>: {{ $datadaftar->tahun_lulus ?? '-' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Kabupaten/Kota Sekolah</th>
                                                    <th>:
                                                        {{ $kabupaten_sekolah == null ? '-' : $kabupaten_sekolah->nama_kabupaten }}
                                                    </th>
                                                    <th>Nilai Akhir</th>
                                                    <th>: {{ $datadaftar->biodata->nilai_akhir ?? '-' }}</th>
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
                                                        $fileUploaded = $berkasPendaftar
                                                            ? $berkasPendaftar->firstWhere('id_berkas', $b->id)
                                                            : null;
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
                                                                <span class="badge bg-warning text-dark">Belum
                                                                    Upload</span>
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').select2();

            let no = 1;
            let maks = 4;

            let assessmentAttempts = [];
            let currentTestIndex = 0;
            let assessmentLoaded = false;
            let kodeDaftar = '{{ encrypt($datadaftar->KodePendaftaran) }}';

            LoadEvent();

            function LoadEvent() {
                nextEvent();
                prevEvent();
                handleTestNavigation();

                $('#card-assessment').on('expanded.lte.cardwidget', function() {
                    if (!assessmentLoaded) {
                        loadAssessmentData();
                    }
                });

                loadAssessmentData();
            }

            function loadAssessmentData() {
                $.ajax({
                    type: "GET",
                    url: '{!! url('admin/TestAssesment/DetailTestOnlinePMB') !!}/' + encodeURIComponent(kodeDaftar),
                    dataType: "JSON",
                    success: function(data) {
                        assessmentLoaded = true;
                        if (data.hasil == 1 && data.attempts && data.attempts.length > 0) {
                            assessmentAttempts = data.attempts;
                            $('#test-navigation').addClass('d-flex').show();
                            renderTestContent(currentTestIndex);
                        } else {
                            $('#assessment-container').html(
                                '<div class="alert alert-warning text-center m-3">Peserta belum menyelesaikan satupun Assessment.</div>'
                                );
                        }
                    },
                    error: function() {
                        $('#assessment-container').html(
                            '<div class="alert alert-danger text-center m-3">Terjadi kesalahan saat memuat data Assessment.</div>'
                            );
                    }
                });
            }

            function handleTestNavigation() {
                $('#btn-next-test').click(function(e) {
                    e.preventDefault();
                    if (currentTestIndex < assessmentAttempts.length - 1) {
                        currentTestIndex++;
                        renderTestContent(currentTestIndex);
                    }
                });

                $('#btn-prev-test').click(function(e) {
                    e.preventDefault();
                    if (currentTestIndex > 0) {
                        currentTestIndex--;
                        renderTestContent(currentTestIndex);
                    }
                });
            }

            function renderTestContent(index) {
                let attempt = assessmentAttempts[index];
                let totalTests = assessmentAttempts.length;

                $('#test-indicator').text(`Assesment ${index + 1} dari ${totalTests}`);
                $('#btn-prev-test').prop('disabled', index === 0);
                $('#btn-next-test').prop('disabled', index === totalTests - 1);

                let statusIndo = 'Belum Mulai';
                if (attempt.status === 'finished') statusIndo = 'Selesai';
                else if (attempt.status === 'on_progress' || attempt.status === 'on progress') statusIndo =
                    'Sedang Dikerjakan';

                let summaryHtml = '';
                let isHIP = (attempt.tipe_engine === 'single_choice' || attempt.tipe_engine === 'likert');

                if (attempt.tipe_engine === 'multiple_choice') {
                    let benar = 0,
                        salah = 0;
                    if (attempt.answers) {
                        $.each(attempt.answers, function(i, a) {
                            if (a.is_benar == 1) benar++;
                            else salah++;
                        });
                    }
                    summaryHtml =
                        `<div class="alert alert-info py-2 mb-3"><strong>Ringkasan Assessment TPA:</strong> Benar: <span class="badge bg-success">${benar}</span> | Salah: <span class="badge bg-danger">${salah}</span> | Total Soal: ${attempt.answers ? attempt.answers.length : 0}</div>`;
                } else if (isHIP) {
                    let ya = 0,
                        tidak = 0;
                    if (attempt.answers) {
                        $.each(attempt.answers, function(i, a) {
                            let jwb1 = (a.jawaban_1 || '').toString().toLowerCase().trim();
                            let optLbl = (a.option_label || '').toString().toLowerCase().trim();
                            if (a.is_benar == 1 || a.is_benar === '1' || jwb1 === '1' || jwb1 === 'ya' ||
                                optLbl === '1' || optLbl === 'ya') {
                                ya++;
                            } else if (jwb1 !== '' && !isNaN(jwb1) && parseInt(jwb1) > 0) {
                                ya++;
                            } else {
                                tidak++;
                            }
                        });
                    }
                    summaryHtml =
                        `<div class="alert alert-info py-2 mb-3"><strong>Ringkasan Minat Bakat:</strong> Mendapat Nilai: <span class="badge bg-success">${ya}</span> | Nilai 0: <span class="badge bg-secondary">${tidak}</span> | Total Soal: ${attempt.answers ? attempt.answers.length : 0}</div>`;
                }

                let html = `
                    <div class="card card-info card-outline mb-0">
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered mb-0">
                                <tr>
                                    <th width="20%">Nama Assessment</th>
                                    <td width="30%"><strong>${attempt.tipe_test_nama}</strong></td>
                                    <th width="20%">Waktu Mulai</th>
                                    <td width="30%">${formatTanggalWaktuIndo(attempt.mulai_at)}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge ${attempt.status === 'finished' ? 'bg-success' : 'bg-warning'}">${statusIndo}</span></td>
                                    <th>Waktu Selesai</th>
                                    <td>${formatTanggalWaktuIndo(attempt.selesai_at)}</td>
                                </tr>
                            </table>

                            <div class="p-3">${summaryHtml}</div>

                            <h6 class="font-weight-bold ml-3 mt-2">Daftar Jawaban Peserta:</h6>
                            <div class="table-responsive p-2 mb-3" style="max-height: 350px; overflow-y: auto;">
                                <table class="table table-striped table-bordered text-sm text-left">
                `;

                if (isHIP) {
                    html += `
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="45%">Soal</th>
                                <th width="35%">Jawaban Aktual</th>
                                <th width="15%" class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                    `;
                } else {
                    html += `
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="40%">Soal</th>
                                <th width="55%">Jawaban Aktual</th>
                            </tr>
                        </thead>
                        <tbody>
                    `;
                }

                if (attempt.answers && attempt.answers.length > 0) {
                    $.each(attempt.answers, function(i, ans) {
                        if (isHIP) {
                            let nilai = 0;
                            let jwb1 = (ans.jawaban_1 || '').toString().toLowerCase().trim();
                            let optLbl = (ans.option_label || '').toString().toLowerCase().trim();

                            if (ans.is_benar == 1 || ans.is_benar === '1') {
                                nilai = 1;
                            } else if (jwb1 === '1' || jwb1 === 'ya' || jwb1 === 'benar') {
                                nilai = 1;
                            } else if (optLbl === '1' || optLbl === 'ya' || optLbl === 'benar') {
                                nilai = 1;
                            } else if (jwb1 !== '' && !isNaN(jwb1)) {
                                nilai = parseInt(jwb1);
                            }

                            let jwbAktual = ans.option_label || ans.jawaban_1 || '-';

                            html += `
                                <tr>
                                    <td class="text-center">${i + 1}</td>
                                    <td>${ans.pertanyaan}</td>
                                    <td>${jwbAktual}</td>
                                    <td class="text-center font-weight-bold text-primary">${nilai}</td>
                                </tr>
                            `;
                        } else {
                            html += `
                                <tr>
                                    <td class="text-center">${i + 1}</td>
                                    <td>${ans.pertanyaan}</td>
                                    <td>${formatJawaban(attempt.tipe_engine, ans)}</td>
                                </tr>
                            `;
                        }
                    });
                } else {
                    let colSpan = isHIP ? 4 : 3;
                    html +=
                        `<tr><td colspan="${colSpan}" class="text-center">Belum ada jawaban tersimpan.</td></tr>`;
                }
                html += `</tbody></table></div>`;

                if (attempt.tipe_engine === 'disc') {
                    let ans = attempt.answers || [];
                    let l1 = {
                        D: 0,
                        I: 0,
                        S: 0,
                        C: 0,
                        star: 0
                    };
                    let l2 = {
                        D: 0,
                        I: 0,
                        S: 0,
                        C: 0,
                        star: 0
                    };

                    ans.forEach(a => {
                        let m = a.most_disc ? a.most_disc.toUpperCase() : '';
                        let k = a.least_disc ? a.least_disc.toUpperCase() : '';
                        if (m === 'D') l1.D++;
                        else if (m === 'I') l1.I++;
                        else if (m === 'S') l1.S++;
                        else if (m === 'C') l1.C++;
                        else if (m === '*') l1.star++;
                        if (k === 'D') l2.D++;
                        else if (k === 'I') l2.I++;
                        else if (k === 'S') l2.S++;
                        else if (k === 'C') l2.C++;
                        else if (k === '*') l2.star++;
                    });

                    let tot1 = l1.D + l1.I + l1.S + l1.C + l1.star;
                    let tot2 = l2.D + l2.I + l2.S + l2.C + l2.star;
                    let l3 = {
                        D: l1.D - l2.D,
                        I: l1.I - l2.I,
                        S: l1.S - l2.S,
                        C: l1.C - l2.C
                    };

                    let urlPdf = '{!! url('admin/TestAssesment/PrintDISC') !!}/' + attempt.id;
                    html += `
                        <div class="p-3 border-top bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                                <h5 class="font-weight-bold text-info mb-0">REKAPITULASI & GRAFIK DISC</h5>
                                <a href="${urlPdf}" target="_blank" class="btn btn-warning btn-sm text-dark font-weight-bold "><i class="fas fa-print"></i> Cetak PDF DISC</a>
                            </div>
                    `;

                    html += `
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center table-sm font-weight-bold" style="border: 2px solid #000; font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th style="border-bottom: 2px solid #000;">No</th><th style="background:#ffeb3b; border-bottom: 2px solid #000;">P</th><th style="background:#6ee7b7; border-bottom: 2px solid #000;">K</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">P</th><th style="background:#d8b4e2; border-right: 2px solid #000; border-bottom: 2px solid #000;">K</th>
                                        <th style="border-bottom: 2px solid #000;">No</th><th style="background:#ffeb3b; border-bottom: 2px solid #000;">P</th><th style="background:#6ee7b7; border-bottom: 2px solid #000;">K</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">P</th><th style="background:#d8b4e2; border-right: 2px solid #000; border-bottom: 2px solid #000;">K</th>
                                        <th style="border-bottom: 2px solid #000;">No</th><th style="background:#ffeb3b; border-bottom: 2px solid #000;">P</th><th style="background:#6ee7b7; border-bottom: 2px solid #000;">K</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">P</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">K</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    for (let i = 0; i < 8; i++) {
                        let col1 = ans[i] || {};
                        let col2 = ans[i + 8] || {};
                        let col3 = ans[i + 16] || {};

                        html += `<tr>`;
                        html +=
                            `<td style="border-left: 2px solid #000;">${i+1}</td>
                                 <td style="background:#fff9c4;">${col1.most_urutan || ''}</td>
                                 <td style="background:#a7f3d0;">${col1.least_urutan || ''}</td>
                                 <td style="background:#f3e8ff;">${col1.most_disc || ''}</td>
                                 <td style="background:#f3e8ff; border-right: 2px solid #000;">${col1.least_disc || ''}</td>`;
                        html +=
                            `<td>${i+9}</td>
                                 <td style="background:#fff9c4;">${col2.most_urutan || ''}</td>
                                 <td style="background:#a7f3d0;">${col2.least_urutan || ''}</td>
                                 <td style="background:#f3e8ff;">${col2.most_disc || ''}</td>
                                 <td style="background:#f3e8ff; border-right: 2px solid #000;">${col2.least_disc || ''}</td>`;
                        html +=
                            `<td>${i+17}</td>
                                 <td style="background:#fff9c4;">${col3.most_urutan || ''}</td>
                                 <td style="background:#a7f3d0;">${col3.least_urutan || ''}</td>
                                 <td style="background:#f3e8ff;">${col3.most_disc || ''}</td>
                                 <td style="background:#f3e8ff; border-right: 2px solid #000;">${col3.least_disc || ''}</td>`;
                        html += `</tr>`;
                    }
                    html += `</tbody></table></div>`;

                    html += `
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6">
                                <table class="table table-bordered text-center table-sm font-weight-bold" style="border: 2px solid #000;">
                                    <thead>
                                        <tr class="bg-white">
                                            <th style="border-bottom: 2px solid #000;">Line</th><th style="border-bottom: 2px solid #000;">D</th><th style="border-bottom: 2px solid #000;">I</th><th style="border-bottom: 2px solid #000;">S</th><th style="border-bottom: 2px solid #000;">C</th><th style="border-bottom: 2px solid #000;">*</th><th style="border-bottom: 2px solid #000;">tot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>1</td><td>${l1.D}</td><td>${l1.I}</td><td>${l1.S}</td><td>${l1.C}</td><td>${l1.star}</td><td class="text-danger">${tot1}</td></tr>
                                        <tr><td>2</td><td>${l2.D}</td><td>${l2.I}</td><td>${l2.S}</td><td>${l2.C}</td><td>${l2.star}</td><td class="text-danger">${tot2}</td></tr>
                                        <tr style="background:#b0bec5;"><td>3</td><td>${l3.D}</td><td>${l3.I}</td><td>${l3.S}</td><td>${l3.C}</td><td></td><td></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;

                    html += `
                        <div class="row mb-3">
                            <div class="col-md-4 text-center">
                                <h6 class="font-weight-bold border border-dark p-1 mb-0">GRAPH 1 MOST<br><small>Mask Public Self</small></h6>
                                <div style="border:1px solid #000; padding:10px; background:#fff;">
                                    <canvas id="discChart1" height="200"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <h6 class="font-weight-bold border border-dark p-1 mb-0">GRAPH 2 LEAST<br><small>Core Private Self</small></h6>
                                <div style="border:1px solid #000; padding:10px; background:#fff;">
                                    <canvas id="discChart2" height="200"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <h6 class="font-weight-bold border border-dark p-1 mb-0">GRAPH 3 CHANGE<br><small>Mirror Perceived Self</small></h6>
                                <div style="border:1px solid #000; padding:10px; background:#fff;">
                                    <canvas id="discChart3" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    `;

                    let res = attempt.hasil_disc;
                    if (res) {
                        let safeTitle = (val) => {
                            if (!val) return '-';
                            if (typeof val === 'string') return val;
                            if (typeof val === 'object') {
                                if (val.nama) return val.nama;
                                if (val.karakter) return val.karakter;
                                return JSON.stringify(val);
                            }
                            return val;
                        };

                        let safeList = (val) => {
                            if (!val) return '';
                            let arr = Array.isArray(val) ? val : (typeof val === 'object' ? Object.values(val) :
                                [val]);
                            return arr.map(s => `<li>${typeof s === 'object' ? JSON.stringify(s) : s}</li>`)
                                .join('');
                        };

                        html += `
                            <div class="row border-top pt-3">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold text-decoration-underline">Kepribadian Saat di Publik</h6>
                                    <div class="text-primary font-weight-bold mb-2">${safeTitle(res.karakter_publik)}</div>
                                    <ul class="pl-3 text-sm">${safeList(res.sifat_publik)}</ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold text-decoration-underline">Kepribadian Asli</h6>
                                    <div class="text-primary font-weight-bold mb-2">${safeTitle(res.karakter_asli)}</div>
                                    <ul class="pl-3 text-sm">${safeList(res.sifat_asli)}</ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold text-decoration-underline">Kepribadian Saat Tertekan</h6>
                                    <div class="text-primary font-weight-bold mb-2">${safeTitle(res.karakter_tekanan)}</div>
                                    <ul class="pl-3 text-sm">${safeList(res.sifat_tekanan)}</ul>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <h6 class="font-weight-bold">Deskripsi Kepribadian:</h6>
                                    <p class="text-sm text-justify border p-2 bg-white">${typeof res.deskripsi === 'object' ? JSON.stringify(res.deskripsi) : (res.deskripsi || '-')}</p>
                                    <h6 class="font-weight-bold mt-2">Job Match:</h6>
                                    <p class="text-sm text-justify border p-2 bg-white">${typeof res.job_match === 'object' ? JSON.stringify(res.job_match) : (res.job_match || '-')}</p>
                                </div>
                            </div>
                        `;
                    }
                    html += `</div>`;

                    setTimeout(() => {
                        renderDiscChart('discChart1', l1);
                        renderDiscChart('discChart2', l2);
                        renderDiscChart('discChart3', l3, true);
                    }, 500);
                }

                html += `</div></div>`;
                $('#assessment-container').html(html);
            }

            function formatJawaban(engine, ans) {
                if (engine === 'disc') {
                    return `<span class="text-success font-weight-bold">P:</span> ${ans.most_label || '-'} <br>
                            <span class="text-danger font-weight-bold">K:</span> ${ans.least_label || '-'}`;
                } else if (engine === 'multiple_choice') {
                    let text = ans.option_label || '-';
                    if (ans.is_benar == 1) text +=
                    ` <i class="fas fa-check-circle text-success" title="Benar"></i>`;
                    else if (ans.is_benar == 0 && ans.option_label) text +=
                        ` <i class="fas fa-times-circle text-danger" title="Salah"></i>`;
                    return text;
                } else if (engine === 'single_choice' || engine === 'likert' || engine === 'dual_scale') {
                    return ans.option_label || '-';
                }
                return ans.jawaban_1 || ans.option_label || '-';
            }

            function renderDiscChart(canvasId, dataSkor, isLine3 = false) {
                let canvas = document.getElementById(canvasId);
                if (!canvas) return;

                if (window[canvasId] instanceof Chart) {
                    window[canvasId].destroy();
                }

                let yMin = isLine3 ? -24 : 0;
                let yMax = 24;

                window[canvasId] = new Chart(canvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['D', 'I', 'S', 'C'],
                        datasets: [{
                            label: 'Skor Raw',
                            data: [dataSkor.D, dataSkor.I, dataSkor.S, dataSkor.C],
                            borderColor: '#3b82f6',
                            backgroundColor: '#3b82f6',
                            borderWidth: 2,
                            pointRadius: 5,
                            fill: false,
                            tension: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                min: yMin,
                                max: yMax
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
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
        });
    </script>
@endsection

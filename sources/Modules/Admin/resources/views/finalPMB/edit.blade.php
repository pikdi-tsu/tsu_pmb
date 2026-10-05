@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')

<style>
    .step-header {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px; /* kasih jarak ke form */
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
                    <h1>{{$menu}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.finalpmb.show') }}">Final PMB</a></li>
                        <li class="breadcrumb-item active">{{$menu}}</li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{$menu}}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.finalpmb.update')}}" id="form-biodata" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="kodedaftar" id="kodedaftar" value="{{$datadaftar->KodePendaftaran}}">
                                <div id="page-1">
                                    <div class="step-header">
                                        <div class="circle">1</div>
                                        <label class="step-title">Data Diri</label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label for="nik"><code>*</code> NIK</label>
                                            <input type="number" min="0" class="form-control pageku-1" name="nik" id="nik" value="{{$datadaftar->biodata->nik}}" placeholder="Masukan NIK">
                                            <label for="nokk"><code>*</code> No. KK</label>
                                            <input type="number" min="0" class="form-control pageku-1" value="{{$datadaftar->biodata->nokk}}" name="nokk" id="nokk" placeholder="Masukan No KK">
                                            <label for="nama"><code>*</code> Nama</label>
                                            <input type="text" class="form-control pageku-1" name="nama" id="nama" value="{{$datadaftar->biodata->nama}}" placeholder="Masukan Nama Lengkap">
                                            <label for="jenkel"><code>*</code> Jenis Kelamin</label>
                                            <select class="form-control select2 pageku-1" id="jenkel" name="jenkel">
                                                <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki" {{$datadaftar->biodata->jenkel=='Laki-laki' ? 'selected' : ''}}>Laki-laki</option>
                                                <option value="Perempuan" {{$datadaftar->biodata->jenkel=='Perempuan' ? 'selected' : ''}}>Perempuan</option>
                                            </select>
                                            <label for="tempat_lahir"><code>*</code> Tempat Lahir</label>
                                            <input type="text" class="form-control pageku-1" name="tempat_lahir" id="tempat_lahir" placeholder="Masukan Tempat Lahir" value="{{$datadaftar->biodata->tempat_lahir}}">
                                            <label for="tgl_lahir"><code>*</code> Tanggal Lahir</label>
                                            <input type="date" class="form-control pageku-1" name="tgl_lahir" id="tgl_lahir" value="{{$datadaftar->biodata->tgl_lahir}}">
                                        </div>
                                        <div class="col-md-4">
                                            <!-- <label for="tinggi_badan"><code>*</code> Tinggi Badan</label>
                                            <input type="number" min="0" class="form-control pageku-1" name="tinggi_badan" id="tinggi_badan" placeholder="Masukan Tinggi Badan" value="{{$datadaftar->biodata->tinggi_badan}}">
                                            <label for="berat_badan"><code>*</code> Berat Badan</label>
                                            <input type="number" min="0" class="form-control pageku-1" name="berat_badan" id="berat_badan" placeholder="Masukan Berat Badan" value="{{$datadaftar->biodata->berat_badan}}"> -->
                                            <label for="agama"><code>*</code> Agama</label>
                                            <select class="form-control select2 pageku-1" id="agama" name="agama">
                                                <option value="" selected disabled>-- Pilih Agama --</option>
                                                <option value="ISLAM" {{$datadaftar->biodata->agama=='ISLAM' ? 'selected' : ''}}>Islam</option>
                                                <option value="KRISTEN" {{$datadaftar->biodata->agama=='KRISTEN' ? 'selected' : ''}}>Kristen</option>
                                                <option value="KATOLIK" {{$datadaftar->biodata->agama=='KATOLIK' ? 'selected' : ''}}>Katolik</option>
                                                <option value="BUDHA" {{$datadaftar->biodata->agama=='BUDHA' ? 'selected' : ''}}>Budha</option>
                                                <option value="HINDU" {{$datadaftar->biodata->agama=='HINDU' ? 'selected' : ''}}>Hindu</option>
                                                <option value="KONGHUCU" {{$datadaftar->biodata->agama=='KONGHUCU' ? 'selected' : ''}}>KONGHUCU</option>
                                            </select>
                                            <label for="nohp"><code>*</code> No HP</label>
                                            <input type="number" min="0" class="form-control pageku-1" name="nohp" id="nohp"  value="{{$datadaftar->biodata->nohp}}" placeholder="Masukan No HP">
                                            <label for="email"><code>*</code> Email</label>
                                            <input type="email" class="form-control pageku-1" name="email" id="email" value="{{$datadaftar->biodata->akunbio->email}}" placeholder="Masukan Email" readonly>
                                            <label for="ukuran_jas"><code>*</code> Ukuran Jas Almamater</label>
                                            <input type="text" class="form-control pageku-1" name="ukuran_jas" id="ukuran_jas" placeholder="Masukan Ukuran Jas Almamater" value="{{$datadaftar->biodata->ukuran_jas}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="provinsi"><code>*</code> Provinsi</label>
                                            <select class="form-control select2 pageku-1" id="provinsi" name="provinsi">
                                                <option value="" selected disabled>-- Pilih Provinsi --</option>
                                                @foreach ($provinsi as $p)
                                                <option value="{{$p->idprov}}" {{$p->idprov==$datadaftar->biodata->provinsi ? 'selected' : ''}}>{{$p->nama_provinsi}}</option>
                                                @endforeach
                                            </select>
                                            <label for="kabupatenkota"><code>*</code> Kabupaten/Kota</label>
                                            <select class="form-control select2 pageku-1" id="kabupatenkota" name="kabupatenkota">
                                                <option value="" selected disabled>-- Pilih Kabupaten/Kota --</option>
                                                {{-- @foreach ($kabupaten as $kb)
                                                <option value="{{$kb->idkab}}" {{($kb->idprov==$datadaftar->biodata->provinsi&&$kb->idkab==$datadaftar->biodata->kabupaten) ? 'selected' : ''}}>{{$kb->nama_kabupaten}}</option>
                                                @endforeach --}}
                                            </select>
                                            <label for="kecamatan"><code>*</code> Kecamatan</label>
                                            <select class="form-control select2 pageku-1" id="kecamatan" name="kecamatan">
                                                <option value="" selected disabled>-- Pilih Kecamatan --</option>
                                            </select>
                                            <label for="kelurahan"><code>*</code> Desa/Kelurahan</label>
                                            <select class="form-control select2 pageku-1" id="kelurahan" name="kelurahan">
                                                <option value="" selected disabled>-- Pilih Desa/Kelurahan --</option>
                                            </select>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="rt"><code>*</code> RT</label>
                                                    <input type="number" min="0" class="form-control pageku-1" name="rt" id="rt" placeholder="RT" value="{{$datadaftar->biodata->rt}}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="rw"><code>*</code> RW</label>
                                                    <input type="number" min="0" class="form-control pageku-1" name="rw" id="rw" placeholder="RW" value="{{$datadaftar->biodata->rw}}">
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="kodepos"><code>*</code> Kode Pos</label>
                                                    <input type="number" min="0" class="form-control pageku-1" name="kodepos" id="kodepos" placeholder="Masukan Kode Pos" value="{{$datadaftar->biodata->kodepos}}">
                                                </div>
                                            </div>
                                            <label for="alamat_lengkap"><code>*</code> Alamat Lengkap</label>
                                            <textarea class="form-control pageku-1" name="alamat_lengkap" id="alamat_lengkap" rows="2" placeholder="Masukan Alamat Lengkap">{{$datadaftar->biodata->alamat_lengkap}}</textarea>
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
                                            <span class="badge bg-warning text-bold" style="font-size: 18px;">Data Ayah</span><br>
                                            <label for="nama_ayah"><code>*</code> Nama Ayah</label>
                                            <input type="text" class="form-control pageku-2" name="nama_ayah" id="nama_ayah" placeholder="Nama Lengkap Ayah" value="{{$datadaftar->biodata->nama_ayah}}">
                                            <label for="tempat_lahir_ayah"><code>*</code> Tempat Lahir</label>
                                            <input type="text" class="form-control pageku-2" name="tempat_lahir_ayah" id="tempat_lahir_ayah" placeholder="Tempat Lahir Ayah" value="{{$datadaftar->biodata->tempat_lahir_ayah}}">
                                            <label for="tgl_lahir_ayah"><code>*</code> Tanggal Lahir</label>
                                            <input type="date" class="form-control pageku-2" name="tgl_lahir_ayah" id="tgl_lahir_ayah" value="{{$datadaftar->biodata->tgl_lahir_ayah}}">
                                            <label for="statushidup_ayah"><code>*</code> Status</label>
                                            <select class="form-control select2 pageku-2" id="statushidup_ayah" name="statushidup_ayah">
                                                <option value="" selected disabled>-- Pilih Status --</option>
                                                <option value="Hidup" {{$datadaftar->biodata->statushidup_ayah=='Hidup' ? 'selected' : ''}}>Hidup</option>
                                                <option value="Meninggal" {{$datadaftar->biodata->statushidup_ayah=='Meninggal' ? 'selected' : ''}}>Meninggal</option>
                                            </select>
                                            <label for="status_ayah"><code>*</code> Status Kekerabatan</label>
                                            <select class="form-control select2 pageku-2" id="status_ayah" name="status_ayah">
                                                <option value="" selected disabled>-- Pilih Status Kekerabatan --</option>
                                                <option value="Kandung" {{$datadaftar->biodata->status_ayah=='Kandung' ? 'selected' : ''}}>Kandung</option>
                                                <option value="Tiri" {{$datadaftar->biodata->status_ayah=='Tiri' ? 'selected' : ''}}>Tiri</option>
                                            </select>
                                            <label for="nohp_ayah"><code>*</code> No HP</label>
                                            <input type="number" min="0" class="form-control pageku-2" name="nohp_ayah" id="nohp_ayah" placeholder="No HP Ayah" value="{{$datadaftar->biodata->nohp_ayah}}">
                                            <label for="pekerjaan_ayah"><code>*</code> Pekerjaan</label>
                                            <input type="text" class="form-control pageku-2" name="pekerjaan_ayah" id="pekerjaan_ayah" placeholder="Pekerjaan Ayah" value="{{$datadaftar->biodata->pekerjaan_ayah}}">
                                            <label for="penghasilan_ayah"><code>*</code> Penghasilan</label>
                                            <input type="number" min="0" class="form-control pageku-2" name="penghasilan_ayah" id="penghasilan_ayah" placeholder="Penghasilan" value="{{$datadaftar->biodata->penghasilan_ayah}}">
                                            <label for="alamat_ayah"><code>*</code> Alamat</label>
                                            <textarea class="form-control pageku-2" name="alamat_ayah" id="alamat_ayah" rows="2" placeholder="Masukan Alamat Lengkap Ayah">{{$datadaftar->biodata->alamat_ayah}}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="badge bg-warning text-bold" style="font-size: 18px;">Data Ibu</span><br>
                                            <label for="nama_ibu"><code>*</code> Nama Ibu</label>
                                            <input type="text" class="form-control pageku-2" name="nama_ibu" id="nama_ibu" placeholder="Nama Lengkap Ibu" value="{{$datadaftar->biodata->nama_ibu}}">
                                            <label for="tempat_lahir_ibu"><code>*</code> Tempat Lahir</label>
                                            <input type="text" class="form-control pageku-2" name="tempat_lahir_ibu" id="tempat_lahir_ibu" placeholder="Tempat Lahir Ibu" value="{{$datadaftar->biodata->tempat_lahir_ibu}}">
                                            <label for="tgl_lahir_ibu"><code>*</code> Tanggal Lahir</label>
                                            <input type="date" class="form-control pageku-2" name="tgl_lahir_ibu" id="tgl_lahir_ibu" value="{{$datadaftar->biodata->tgl_lahir_ibu}}">
                                            <label for="statushidup_ibu"><code>*</code> Status</label>
                                            <select class="form-control select2 pageku-2" id="statushidup_ibu" name="statushidup_ibu">
                                                <option value="" selected disabled>-- Pilih Status --</option>
                                                <option value="Hidup" {{$datadaftar->biodata->statushidup_ibu=='Hidup' ? 'selected' : ''}}>Hidup</option>
                                                <option value="Meninggal" {{$datadaftar->biodata->statushidup_ibu=='Meninggal' ? 'selected' : ''}}>Meninggal</option>
                                            </select>
                                            <label for="status_ibu"><code>*</code> Status Kekerabatan</label>
                                            <select class="form-control select2 pageku-2" id="status_ibu" name="status_ibu">
                                                <option value="" selected disabled>-- Pilih Status Kekerabatan --</option>
                                                <option value="Kandung" {{$datadaftar->biodata->status_ibu=='Kandung' ? 'selected' : ''}}>Kandung</option>
                                                <option value="Tiri" {{$datadaftar->biodata->status_ibu=='Tiri' ? 'selected' : ''}}>Tiri</option>
                                            </select>
                                            <label for="nohp_ibu"><code>*</code> No HP</label>
                                            <input type="number" min="0" class="form-control" name="nohp_ibu" id="nohp_ibu" placeholder="No HP Ibu" value="{{$datadaftar->biodata->nohp_ibu}}">
                                            <label for="pekerjaan_ibu"><code>*</code> Pekerjaan</label>
                                            <input type="text" class="form-control pageku-2" name="pekerjaan_ibu" id="pekerjaan_ibu" placeholder="Pekerjaan Ibu" value="{{$datadaftar->biodata->pekerjaan_ibu}}">
                                            <label for="penghasilan_Ibu"><code>*</code> Penghasilan</label>
                                            <input type="number" min="0" class="form-control pageku-2" name="penghasilan_Ibu" id="penghasilan_ibu" placeholder="Penghasilan" value="{{$datadaftar->biodata->penghasilan_ibu}}">
                                            <label for="alamat_ibu"><code>*</code> Alamat</label>
                                            <textarea class="form-control pageku-2" name="alamat_ibu" id="alamat_ibu" rows="2" placeholder="Masukan Alamat Lengkap Ibu">{{$datadaftar->biodata->alamat_ibu}}</textarea>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <span class="badge bg-warning text-bold" style="font-size: 18px;">Data Saudara</span><br>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="jumlah_saudara"><code>*</code> Jumlah Saudara</label>
                                                    <input type="number" min="0" class="form-control pageku-2" name="jumlah_saudara" id="jumlah_saudara" placeholder="Jumlah Saudara" value="{{$datadaftar->biodata->jumlah_saudara==null ? 0 : $datadaftar->biodata->jumlah_saudara}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                @php
                                                    $nama = null;
                                                    $pekerjaan = null;
                                                    $status = null;
                                                    $kekerabatan = null;
                                                    $jml = $datadaftar->biodata->jumlah_saudara==null ? 0 : $datadaftar->biodata->jumlah_saudara;
                                                    if($jml>0){
                                                       $nama = $datadaftar->biodata->saudara[0]->nama;
                                                       $pekerjaan = $datadaftar->biodata->saudara[0]->pekerjaan;
                                                       $status = $datadaftar->biodata->saudara[0]->status_hidup;
                                                       $kekerabatan = $datadaftar->biodata->saudara[0]->status_kekerabatan;
                                                    }
                                                @endphp
                                                <div class="col-md-4">
                                                    <label>Nama Saudara</label>
                                                    <input type="text" class="form-control" name="nama_saudara[]" id="nama_saudara_0" placeholder="Nama Saudara" value="{{$nama}}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Pekerjaan Saudara</label>
                                                    <input type="text" class="form-control" name="pekerjaan_saudara[]" id="pekerjaan_saudara_0" placeholder="Pekerjaan Saudara" value="{{$pekerjaan}}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Status</label>
                                                    <select class="form-control select2" name="statushidup_saudara[]" id="statushidup_saudara_0">
                                                        <option value="" selected disabled>-- Status Saudara --</option>
                                                        <option value="Hidup" {{$status=='Hidup' ? 'selected' : ''}}>Hidup</option>
                                                        <option value="Meninggal" {{$status=='Meninggal' ? 'selected' : ''}}>Meninggal</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Status Kekerabatan</label>
                                                    <select class="form-control select2" name="statuskekerabatan_saudara[]" id="statuskekerabatan_saudara_0">
                                                        <option value="" selected disabled>-- Status Kekerabatan --</option>
                                                        <option value="Kakak Kandung" {{$kekerabatan=='Kakak Kandung' ? 'selected' : ''}}>Kakak Kandung</option>
                                                        <option value="Kakak Tiri" {{$kekerabatan=='Kakak Tiri' ? 'selected' : ''}}>Kakak Tiri</option>
                                                        <option value="Adik Kandung" {{$kekerabatan=='Adik Kandung' ? 'selected' : ''}}>Adik Kandung</option>
                                                        <option value="Adik Tiri" {{$kekerabatan=='Adik Tiri' ? 'selected' : ''}}>Adik Tiri</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1" style="margin-top:35px;">
                                                    <button type="button" id="add-saudara" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                            <div id="field-saudara">
                                                @if($jml>1)
                                                    @for($i = 1; $i < count($datadaftar->biodata->saudara); $i++)
                                                        <div class="row saudara-{{$i}}">
                                                            <div class="col-md-4">
                                                                <label>Nama Saudara</label>
                                                                <input type="text" class="form-control" name="nama_saudara[]" id="nama_saudara_{{$i}}" placeholder="Nama Saudara" value="{{$datadaftar->biodata->saudara[$i]->nama}}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Pekerjaan Saudara</label>
                                                                <input type="text" class="form-control" name="pekerjaan_saudara[]" id="pekerjaan_saudara_{{$i}}" placeholder="Pekerjaan Saudara" value="{{$datadaftar->biodata->saudara[$i]->pekerjaan}}">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Status</label>
                                                                <select class="form-control select2" name="statushidup_saudara[]" id="statushidup_saudara_{{$i}}">
                                                                    <option value="" selected disabled>-- Status Saudara --</option>
                                                                    <option value="Hidup" {{$datadaftar->biodata->saudara[$i]->status_hidup=='Hidup' ? 'selected' : ''}}>Hidup</option>
                                                                    <option value="Meninggal" {{$datadaftar->biodata->saudara[$i]->status_hidup=='Meninggal' ? 'selected' : ''}}>Meninggal</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Status Kekerabatan</label>
                                                                <select class="form-control select2" name="statuskekerabatan_saudara[]" id="statuskekerabatan_saudara_{{$i}}">
                                                                    <option value="" selected disabled>-- Status Kekerabatan --</option>
                                                                    <option value="Kakak Kandung" {{$datadaftar->biodata->saudara[$i]->status_kekerabatan=='Kakak Kandung' ? 'selected' : ''}}>Kakak Kandung</option>
                                                                    <option value="Kakak Tiri" {{$datadaftar->biodata->saudara[$i]->status_kekerabatan=='Kakak Tiri' ? 'selected' : ''}}>Kakak Tiri</option>
                                                                    <option value="Adik Kandung" {{$datadaftar->biodata->saudara[$i]->status_kekerabatan=='Adik Kandung' ? 'selected' : ''}}>Adik Kandung</option>
                                                                    <option value="Adik Tiri" {{$datadaftar->biodata->saudara[$i]->status_kekerabatan=='Adik Tiri' ? 'selected' : ''}}>Adik Tiri</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1" style="margin-top:35px;">
                                                                <button type="button" data-id="{{$i}}" class="btn btn-danger btn-sm remove-saudara"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @endif
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
                                            <input type="text" name="nama_sekolah" id="nama_sekolah pageku-3" class="form-control" placeholder="Nama Sekolah" value="{{$datadaftar->biodata->nama_sekolah}}">
                                            <label for="jenis_sekolah"><code>*</code> Jenis Sekolah</label>
                                            <select class="form-control select2 pageku-3" id="jenis_sekolah" name="jenis_sekolah">
                                                <option value="" selected disabled>-- Pilih Jenis Sekolah --</option>
                                                <option value="SMA" {{$datadaftar->biodata->jenis_sekolah=='SMA' ? 'selected' : ''}}>SMA</option>
                                                <option value="SMK" {{$datadaftar->biodata->jenis_sekolah=='SMK' ? 'selected' : ''}}>SMK</option>
                                                <option value="MA" {{$datadaftar->biodata->jenis_sekolah=='MA' ? 'selected' : ''}}>MA</option>
                                            </select>
                                            <label for="provinsi_sekolah"><code>*</code> Provinsi Sekolah</label>
                                            <select class="form-control select2 pageku-3" id="provinsi_sekolah" name="provinsi_sekolah">
                                                <option value="" selected disabled>-- Pilih Provinsi Sekolah --</option>
                                                @foreach ($provinsi as $p)
                                                <option value="{{$p->idprov}}" {{$p->idprov==$datadaftar->biodata->provinsi_sekolah ? 'selected' : ''}}>{{$p->nama_provinsi}}</option>
                                                @endforeach
                                            </select>
                                            <label for="kabupatenkota_sekolah"><code>*</code> Kabupaten/Kota Sekolah</label>
                                            <select class="form-control select2 pageku-3" id="kabupatenkota_sekolah" name="kabupatenkota_sekolah">
                                                <option value="" selected disabled>-- Pilih Kabupaten/Kota Sekolah --</option>
                                                @foreach ($kabupaten as $kb)
                                                <option value="{{$kb->idkab}}" {{($kb->idprov==$datadaftar->biodata->provinsi_sekolah&&$kb->idkab==$datadaftar->biodata->kabupaten_sekolah) ? 'selected' : ''}}>{{$kb->nama_kabupaten}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="npsn"><code>*</code> NPSN</label>
                                            <input type="number" min="0" name="npsn" id="npsn" class="form-control pageku-3" value="{{$datadaftar->biodata->npsn}}" placeholder="NPSN">
                                            <label for="nisn"><code>*</code> NISN</label>
                                            <input type="number" min="0" name="nisn" id="nisn" class="form-control pageku-3" value="{{$datadaftar->biodata->nisn}}" placeholder="NPSN">
                                            <label for="tahun_lulus"><code>*</code> Tahun Lulus</label>
                                            <select class="form-control select2 pageku-3" id="tahun_lulus" name="tahun_lulus">
                                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                                @php
                                                    $currentYear = date('Y');
                                                @endphp
                                                @for ($i = 0; $i < 10; $i++)
                                                    @php
                                                        $tahun = $currentYear - $i;
                                                    @endphp
                                                    <option value="{{ $tahun }}" {{$tahun==$datadaftar->tahun_lulus ? 'selected' : ''}}>{{ $tahun }}</option>
                                                @endfor
                                            </select>
                                            <label for="nilai_akhir"><code>*</code> Nilai Akhir</label>
                                            <input type="number" min="0" name="nilai_akhir" id="nilai_akhir" class="form-control pageku-3" value="{{$datadaftar->biodata->nilai_akhir}}" placeholder="Nilai Akhir">
                                        </div>

                                    </div>
                                </div>
                                <div id="page-4" style="display: none;">
    <div class="step-header">
        <div class="circle">4</div>
        <label class="step-title">Berkas Pendaftaran</label>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Perhatian:</strong>
                <ul>
                    <li>Upload file baru HANYA jika ingin mengganti file yang sudah ada.</li>
                    <li>Ukuran maksimal per berkas adalah 2 MB.</li>
                </ul>
            </div>
            
            <table class="table table-bordered table-striped mt-3">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="35%">Nama Berkas</th>
                        <th width="20%">Status Berkas</th>
                        <th width="40%">Upload File Baru (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($berkas->berkas as $row => $i)
                        @php
                            $warna = $i->keterangan == 'Wajib' ? 'danger' : 'secondary';
                            // Cek apakah file sudah pernah diupload (Pastikan $berkasPendaftar dikirim dari fungsi Edit di Controller)
                            $fileUploaded = $berkasPendaftar->firstWhere('id_berkas', $i->id);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $row + 1 }}</td>
                            <td>
                                {{ $i->nama_berkas }} <br>
                                <span class="badge bg-{{ $warna }}">{{ $i->keterangan }}</span>
                                <small class="text-muted">Format: {{ $i->formatfile }}</small>
                            </td>
                            <td class="text-center">
                                @if($fileUploaded)
                                    @php
                                        $parameter = \App\Models\Parameter::where('id', 1)->first();
                                        $pathFile = url('admin/file/' . strtoupper($parameter->file_umum) . '/' . $fileUploaded->nama_berkas);
                                    @endphp
                                    <a href="{{ $pathFile }}" target="_blank" class="badge bg-success" style="text-decoration: none;">
                                        <i class="fa fa-check"></i> Sudah Upload (Lihat)
                                    </a>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="fa fa-times"></i> Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                <input type="file" name="berkas_baru[{{ $i->id }}]" class="form-control pageku-4" accept="{{ $i->formatfile == '.pdf' ? 'application/pdf' : 'image/jpeg,image/png' }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
                                <div class="col-md-12 d-flex justify-content-center" style="margin-top: 10px;">
                                    <button type="button" id="btn-prev" class="btn btn-secondary btn-sm mr-2" style="display: none;">Prev</button>
                                    <button type="button" id="btn-next" class="btn btn-secondary btn-sm">Next</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.finalpmb.show') }}" class="btn btn-secondary btn-sm mr-2">Kembali</a>
                            <button id="save-biodata" class="btn btn-sm btn-success float-right">Simpan</button>
                        </div>
                    </div>
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

            let provId = "{{ $datadaftar->biodata->provinsi }}";
            let kabId  = "{{ $datadaftar->biodata->kabupaten }}";
            let kecId  = "{{ $datadaftar->biodata->kecamatan }}";
            let kelId  = "{{ $datadaftar->biodata->kelurahan }}";

            LoadEvent()

            function LoadEvent()
            {
                nextEvent()
                prevEvent()
                add_saudara()
                removesaudara()
                changeKabupatenSekolah()
                save_biodata()
            }

            function nextEvent()
            {
                $('#btn-next').click(function (e) {
                    e.preventDefault();
                    no++;
                    let noprev = no-1;
                    $('#page-'+no).show()
                    $('#page-'+noprev).hide()
                    if(no==maks){
                        $(this).hide()
                        $('#btn-simpan').show()
                    }else{
                        $(this).show()
                        if(no>1){
                           $('#btn-prev').show()
                        }else{
                            $('#btn-prev').hide()
                        }
                    }
                });
            }

            function prevEvent()
            {
                $('#btn-prev').click(function (e) {
                    e.preventDefault();
                    console.log('bb')
                    no--;
                    let noprev = no+1;
                    $('#page-'+no).show()
                    $('#page-'+noprev).hide()
                    if(no==1){
                        $(this).hide()
                        $('#btn-next').show()
                    }else{
                        $(this).show()
                       if(no>1){
                           $('#btn-next').show()
                        }else{
                            $('#btn-next').hide()
                        }

                    }
                });
            }

            function add_saudara()
            {
                current = {{count($datadaftar->biodata->saudara)==0 ? 1 : count($datadaftar->biodata->saudara)}};
                $('#add-saudara').click(function (e) {
                    e.preventDefault();
                    current++;
                    let html = `<div class="row saudara-`+current+`">
                                    <div class="col-md-4">
                                        <label>Nama Saudara</label>
                                        <input type="text" class="form-control" name="nama_saudara[]" id="nama_saudara_`+current+`" placeholder="Nama Saudara">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pekerjaan Saudara</label>
                                        <input type="text" class="form-control" name="pekerjaan_saudara[]" id="pekerjaan_saudara_`+current+`" placeholder="Pekerjaan Saudara">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Status</label>
                                        <select class="form-control select2" name="statushidup_saudara[]" id="statushidup_saudara_`+current+`">
                                            <option value="" selected disabled>-- Status Saudara --</option>
                                            <option value="Hidup">Hidup</option>
                                            <option value="Meninggal">Meninggal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Status Kekerabatan</label>
                                        <select class="form-control select2" name="statuskekerabatan_saudara[]" id="statuskekerabatan_saudara_`+current+`">
                                            <option value="" selected disabled>-- Status Kekerabatan --</option>
                                            <option value="Kakak Kandung">Kakak Kandung</option>
                                            <option value="Kakak Tiri">Kakak Tiri</option>
                                            <option value="Adik Kandung">Adik Kandung</option>
                                            <option value="Adik Tiri">Adik Tiri</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1" style="margin-top:35px;">
                                        <button type="button" data-id="`+current+`" class="btn btn-danger btn-sm remove-saudara"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>`
                    $('#field-saudara').append(html);
                    $('#field-saudara .select2').select2();
                    removesaudara()
                });
            }

            function removesaudara()
            {
                $('.remove-saudara').click(function(e) {
                    let idku = $(this).data('id')
                    $('.saudara-'+idku).remove();
                });
            }

            $('#provinsi').on('change', function () {
                loadKabupaten($(this).val());
            });

            $('#kabupatenkota').on('change', function () {
                loadKecamatan($('#provinsi').val(), $(this).val());
            });

            $('#kecamatan').on('change', function () {
                loadKelurahan($('#provinsi').val(), $('#kabupatenkota').val(), $(this).val());
            });

            // Jalankan prefill jika ada data lama
            if (provId) {
                let selectKec = kecId;
                let selectKel = kelId;
                loadKabupaten(provId, kabId, function () {
                    if (kabId) {
                        loadKecamatan(provId, kabId, selectKec, function () {
                            if (kecId) {
                                loadKelurahan(provId, kabId, kecId,selectKel, function () {
                                    if (kelId) {
                                        $('#kelurahan').val(kelId).trigger('change');
                                    }
                                });
                            }
                        });
                    }
                });
            }

            function changeKabupatenSekolah()
            {
                $('#provinsi_sekolah').change(function (e) {
                    e.preventDefault();
                    let provinsi = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/FinalPMB/ChangeKabupaten') !!}'+'/'+provinsi,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#kabupatenkota_sekolah').empty().html('<option value="" selected disabled>-- Pilih Kabupaten/Kota Sekolah --</option>')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information','Data Kabupaten Sekolah Tidak Ada !','warning')
                            }else {
                                let dis1 = '';
                                for (i = 0; i < data.kabupaten.length; i++) {
                                    dis1 += '<option value="' + data.kabupaten[i].idkab + '">'+ data.kabupaten[i].nama_kabupaten + '</option>'
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

            function save_biodata()
            {
                $('#save-biodata').click(function (e) {
                    e.preventDefault();
                    let validasi_bio = validasi_biodata()
                    let btn = $(this);
                    if(validasi_bio!='ok'){
                        notifalert('Information',validasi_bio,'warning')
                    }else{
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Data Biodata Anda Sudah Yakin Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                btn.prop('disabled',true)
                                $('#form-biodata').submit();
                            }else{
                                return false;
                            }
                        });
                    }
                });
            }

            function validasi_biodata()
            {
                let page1 = $('.pageku-1')
                let jmlpage1 = 0;
                for (i=1; i<page1.length; i++) {
                    if(page1[i].value==''||page1[i].value==null){
                        jmlpage1++
                    }
                }

                let page2 = $('.pageku-2')
                let jmlpage2 = 0;
                for (i=1; i<page2.length; i++) {
                    if(page2[i].value==''||page2[i].value==null){
                        jmlpage2++
                    }
                }

                let page3 = $('.pageku-3')
                let jmlpage3 = 0;
                for (i=1; i<page3.length; i++) {
                    if(page3[i].value==''||page3[i].value==null){
                        jmlpage3++
                    }
                }

                let fileInputs = $('.pageku-4');
                let jmlpage4 = 0;

                for (let j = 0; j < fileInputs.length; j++) {
                    let fileField = fileInputs[j];
                    if (fileField.files.length > 0) {
                        let uploadedFile = fileField.files[0];
                        let fileSize = uploadedFile.size; // dalam byte

                        // Validasi ukuran file (maks 2 MB)
                        if (fileSize > 2 * 1024 * 1024) { 
                            jmlpage4 = 3;
                            break; // Stop looping jika ada 1 saja yang kebesaran
                        }
                    }
                }

                let notif = '';

                if(jmlpage1 > 0){
                    notif = 'Data Diri Belum Lengkap ! Lengkapi Data Diri Pada Halaman 1 !';
                }else if(jmlpage2 > 0){
                    notif = 'Data keluarga Belum Lengkap ! Lengkapi Data Keluarga Pada Halaman 2 !';
                }else if(jmlpage3 > 0){
                    notif = 'Data Sekolah Belum Lengkap ! Lengkapi Data Sekolah Pada Halaman 3 !';
                }else if(jmlpage4 == 3){
                    notif = 'Ada Berkas yang ukurannya melebihi 2 MB! Cek kembali halaman 4.';
                }else{
                    notif = 'ok'
                }

                return notif;
            }


        });

        function loadKabupaten(provinsi, selectedKab = null, callback = null) {
            $.ajax({
                type: "GET",
                url: '{!! url('admin/FinalPMB/ChangeKabupaten') !!}' + '/' + provinsi,
                dataType: "JSON",
                beforeSend: function() {
                    $('#loading').show()
                    $('#kabupatenkota').html('<option value="">-- Pilih Kabupaten/Kota --</option>')
                    $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>')
                    $('#kelurahan').html('<option value="">-- Pilih Desa/Kelurahan --</option>')
                },
                success: function(data) {
                    $('#loading').hide()
                    if (data.hasil == 0) {
                        notifalert('Information','Data Kabupaten Tidak Ada !','warning')
                    } else {
                        $.each(data.kabupaten, function(i, item) {
                            let selected = (item.idkab == selectedKab) ? 'selected' : '';
                            $('#kabupatenkota').append('<option value="'+item.idkab+'" '+selected+'>'+item.nama_kabupaten+'</option>');
                        });
                    }
                    if (callback) callback();
                },
                error: function() {
                    $('#loading').hide()
                    Swal.fire('Gagal','Error, Silahkan Hubungi Admin !','error');
                }
            });
        }

        function loadKecamatan(provinsi, kabupaten, selectedKec = null, callback = null) {
            $.ajax({
                type: "GET",
                url: '{!! url('admin/FinalPMB/ChangeKecamatan') !!}'+'/'+provinsi+'/'+kabupaten,
                dataType: "JSON",
                beforeSend: function() {
                    $('#loading').show()
                    $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>')
                    $('#kelurahan').html('<option value="">-- Pilih Desa/Kelurahan --</option>')
                },
                success: function(data) {
                    $('#loading').hide()
                    if (data.hasil == 0) {
                        notifalert('Information','Data Kecamatan Tidak Ada !','warning')
                    } else {
                        $.each(data.kecamatan, function(i, item) {
                            let selected = (item.idkec == selectedKec) ? 'selected' : '';
                            $('#kecamatan').append('<option value="'+item.idkec+'" '+selected+'>'+item.nama_kecamatan+'</option>');
                        });
                    }
                    if (callback) callback();
                },
                error: function() {
                    $('#loading').hide()
                    Swal.fire('Gagal','Error, Silahkan Hubungi Admin !','error');
                }
            });
        }

        function loadKelurahan(provinsi, kabupaten, kecamatan, selectedKel = null, callback = null) {
            $.ajax({
                type: "GET",
                url: '{!! url('admin/FinalPMB/ChangeKelurahan') !!}'+'/'+provinsi+'/'+kabupaten+'/'+kecamatan,
                dataType: "JSON",
                beforeSend: function() {
                    $('#loading').show()
                    $('#kelurahan').html('<option value="">-- Pilih Desa/Kelurahan --</option>')
                },
                success: function(data) {
                    $('#loading').hide()
                    if (data.hasil == 0) {
                        notifalert('Information','Data Kelurahan Tidak Ada !','warning')
                    } else {
                        $.each(data.kelurahan, function(i, item) {
                            let selected = (item.idkel == selectedKel) ? 'selected' : '';
                            $('#kelurahan').append('<option value="'+item.idkel+'" '+selected+'>'+item.nama_kelurahan+'</option>');
                        });
                    }
                    if (callback) callback();
                },
                error: function() {
                    $('#loading').hide()
                    Swal.fire('Gagal','Error, Silahkan Hubungi Admin !','error');
                }
            });
        }
    </script>
@endsection


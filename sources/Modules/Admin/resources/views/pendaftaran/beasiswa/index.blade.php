@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')

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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0">{{$menu}}</h5>
                            <a href="{{ route('admin.databeasiswa.exportexcel') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel mr-1"></i>Export Excel
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No Registrasi</th>
                                        <th>Batch Daftar</th>
                                        <th>Jalur Daftar</th>
                                        <th>Kategori Beasiswa</th>
                                        <th>Prodi Pilihan 1</th>
                                        <th>Prodi Pilihan 2</th>
                                        <th>Prodi Pilihan 3</th>
                                        <th>Jadwal Kelas</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-modal">Detail Pendaftaran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabel-detail" class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center" style="background-color: rgb(0, 255, 42);">Data Pendaftaran Calon Mahasiswa Baru</th>
                            </tr>
                            <tr>
                                <th>No Registrasi</th>
                                <th id="o-noregist" class="o-detaildaftar"></th>
                                <th>Nama Calon Mahasiswa</th>
                                <th id="o-nama" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Batch Daftar</th>
                                <th id="o-batch" class="o-detaildaftar"></th>
                                <th>Tahun Lulus</th>
                                <th id="o-tahunlulus" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Jalur Daftar</th>
                                <th id="o-jalur" class="o-detaildaftar"></th>
                                <th>Jurusan Sekolah</th>
                                <th id="o-jurusansekolah" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 1</th>
                                <th id="o-prodi1" class="o-detaildaftar"></th>
                                <th>UKT Program Studi 1</th>
                                <th id="o-uktprodi1" class="o-detaildaftar"></th>
                            </tr>

                            <tr>
                                <th>Program Studi Pilihan 2</th>
                                <th id="o-prodi2" class="o-detaildaftar"></th>
                                <th>UKT Program Studi 2</th>
                                <th id="o-uktprodi2" class="o-detaildaftar"></th>
                            </tr>

                            <tr>
                                <th>Program Studi Pilihan 3</th>
                                <th id="o-prodi3" class="o-detaildaftar"></th>
                                <th>UKT Program Studi 3</th>
                                <th id="o-uktprodi3" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Konfirmasi Daftar</th>
                                <th id="o-konfirmdaftar" class="o-detaildaftar"></th>
                                <th>Biaya Pendaftaran</th>
                                <th id="o-biayadaftar" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Tanggal Daftar</th>
                                <th id="o-tgldaftar" class="o-detaildaftar"></th>
                                <th>Waktu Kuliah</th>
                                <th id="o-waktukuliah" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Status UKT</th>
                                <th id="o-statusukt" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Kategori Beasiswa
                                </th>
                                <th id="o-beasiswa" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Tingkat Kejuaraan
                                </th>
                                <th id="o-juarabea" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Keterangan
                                </th>
                                <th id="o-keteranganbea" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Durasi Beasiswa D3
                                </th>
                                <th id="o-durasid3" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Durasi Beasiswa S1
                                </th>
                                <th id="o-durasis1" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Rekomendator</th>
                                <th id="o-rekomendator" class="o-detaildaftar" colspan="3"></th>
                            </tr>
                            <tr>
                                <th colspan="4" style="background-color: rgb(251, 255, 0);"><code>*Berkas yang diperlukan</code></th>
                            </tr>
                        </thead>
                        <tbody id="detail-berkas" class="o-detaildaftar">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-edit-rekomendator">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Rekomendator</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h6 class="text-muted mb-2">Rekomendator Saat Ini:</h6>
                        <h4 id="teks_rekomendator_saatini" class="text-bold text-primary">-</h4>

                        <button type="button" id="btn-tampil-form-edit" class="btn btn-sm btn-outline-warning mt-3">
                            <i class="fas fa-edit"></i> Ubah / Tambah Rekomendator
                        </button>
                    </div>

                    <div id="wadah-form-rekomendator" style="display: none; border-top: 1px solid #eee; padding-top: 15px;">
                        <form id="form-edit-rekomendator">
                            <input type="hidden" id="edit_id_daftar" name="id_daftar">
                            <div class="form-group">
                                <label>Cari & Pilih Rekomendator Baru</label>
                                <select class="form-control" id="select_rekomendator" name="kode_rekomendator" style="width: 100%;">
                                    <option value="" selected disabled>-- Ketik Nama Rekomendator --</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-save-rekomendator" class="btn btn-success" style="display: none;"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal edit jurusan --}}
    <div class="modal fade" id="modal-edit-jurusan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-titlejurusan"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-editjurusan" method="POST" action="#">
                        <input type="hidden" id="kodependaftaranmahasiswa" name="kodependaftaranmahasiswa">
                        <div class="form-group mb-3">
                            <label for="prodi1">Program Studi Pilihan 1</label>
                            <select class="form-control select2" id="prodi1" name="prodi1">
                                <option value="" selected disabled>-- Pilih Program Studi 1 --</option>
                                @foreach ($getfakultas as $item)
                                    <optgroup label="{{ $item->namafakultas }}">
                                        @foreach ($item->jurusan as $jurusan)
                                            <option value="{{ $jurusan->KodeJurusan }}">
                                                {{ $jurusan->jenjang->jenjang .' '. $jurusan->jurusan }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="prodi2">Program Studi Pilihan 2</label>
                            <select class="form-control select2" id="prodi2" name="prodi2">
                                <option value="" selected disabled>-- Pilih Program Studi 2 --</option>
                                @foreach ($getfakultas as $item)
                                    <optgroup label="{{ $item->namafakultas }}">
                                        @foreach ($item->jurusan as $jurusan)
                                            <option value="{{ $jurusan->KodeJurusan }}">
                                                {{ $jurusan->jenjang->jenjang .' '. $jurusan->jurusan }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="prodi3">Program Studi Pilihan 3</label>
                            <select class="form-control select2" id="prodi3" name="prodi3">
                                <option value="" selected disabled>-- Pilih Program Studi 3 --</option>
                                @foreach ($getfakultas as $item)
                                    <optgroup label="{{ $item->namafakultas }}">
                                        @foreach ($item->jurusan as $jurusan)
                                            <option value="{{ $jurusan->KodeJurusan }}">
                                                {{ $jurusan->jenjang->jenjang .' '. $jurusan->jurusan }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jadwalkelas">Jadwal Kelas</label>
                            <select class="form-control select2" id="jadwalkelas" name="jadwalkelas">
                                <option value="" selected disabled>-- Pilih Jadwal Kelas --</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-saveupdatejurusan" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
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

            loadEvent()

            function loadEvent()
            {
                tabelBeasiswa()
            }

            $('.select2').select2({
                dropdownParent: $('#modal-edit-jurusan'),
                width: '100%'
            });

            function tabelBeasiswa()
            {
                let otable = $('#example2').DataTable({
                    destroy: true,
                    processing: true,
                    paging: false,
                    scrollX: true,
                    scrollY: '500px',
                    scrollCollapse: true,
                    serverSide: true,
                    searchDelay: 500,
                    responsive: false,
                    order: [],
                    ajax: {
                        url: '{!! route('admin.databeasiswa.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'noreg'
                        },
                        {
                            data: 'batch'
                        },
                        {
                            data: 'jalur'
                        },
                        {
                            data: 'beasiswa'
                        },
                        {
                            data: 'prodi1'
                        },
                        {
                            data: 'prodi2'
                        },
                        {
                            data: 'prodi3'
                        },
                        {
                            data: 'jadwalkelas'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    language: {
                        processing: '<i class="fa fa-spinner fa-lg fa-spin"></i>'
                    },
                    drawCallback: function(settings) {
                        ShowDetail()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function ShowDetail()
            {
                $('.btn_detail').click(function (e) {
                    e.preventDefault();
                    let param = $(this).data('id')
                    // $('#modal-detail').modal('show')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/DataPendaftaran/Beasiswa/DetailBeasiswa') !!}'+'/'+param,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('.o-detaildaftar').empty()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Pendaftaran Tidak Ditemukan','error')
                            }else{
                                $('#o-noregist').html(data.daftar.KodePendaftaran)
                                $('#o-nama').html(data.daftar.biodata.nama)
                                $('#o-batch').html(data.daftar.batch.nama_batch+' '+data.daftar.batch.tahun_akademik)
                                $('#o-tahunlulus').html(data.daftar.tahun_lulus)
                                $('#o-jalur').html(data.daftar.jalur.KodeJenis+'-'+data.daftar.jalur.jenis_pendaftaran)
                                $('#o-jurusansekolah').html(data.daftar.jurusansekolah.sekolah+'/'+data.daftar.jurusansekolah.jurusan_sekolah)
                                $('#o-prodi1').html(data.daftar.prodi1.jenjang.jenjang+'-'+data.daftar.prodi1.jurusan)
                                $('#o-prodi2').html(data.daftar.prodi2.jenjang.jenjang+'-'+data.daftar.prodi2.jurusan)
                                $('#o-prodi3').html(data.daftar.prodi3 ? data.daftar.prodi3.jenjang.jenjang+'-'+data.daftar.prodi3.jurusan : '-');
                                // let ukt1 = data.ukt1.biaya_ukt.replace(/\D/g, '')
                                let ukt1 = new Intl.NumberFormat('id-ID').format(data.ukt1.biaya_ukt);
                                $('#o-uktprodi1').html('Rp '+ukt1)
                                let ukt2 = new Intl.NumberFormat('id-ID').format(data.ukt2.biaya_ukt);
                                $('#o-uktprodi2').html('Rp '+ukt2)
                                let ukt3 = data.ukt3 ? new Intl.NumberFormat('id-ID').format(data.ukt3.biaya_ukt) : '0';
                                $('#o-uktprodi3').html('Rp '+ukt3);
                                let konfirmdaftar = data.daftar.konfirm_pendaftaran=='0' ? 'Belum Konfirmasi' : 'Sudah Konfirmasi';
                                $('#o-konfirmdaftar').html(konfirmdaftar)
                                let biayadaftar = data.daftar.jalur.biaya_pendaftaran=='1' ? 'Rp '+new Intl.NumberFormat('id-ID').format(data.daftar.jalur.jml_biaya_pendaftaran) : 'Gratis';
                                $('#o-biayadaftar').html(biayadaftar)
                                $('#o-tgldaftar').html(data.daftar.tgl_daftar)
                                $('#o-waktukuliah').html(data.daftar.waktukuliah.waktu)
                                $('#o-rekomendator').html(data.rekomendator);
                                let statusUkt = data.daftar.jalur.status_ukt=='0' ? 'Gratis' : 'Bayar';
                                $('#o-statusukt').html(statusUkt)
                                let beasiswa = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.jenis_beasiswa
                                $('#o-beasiswa').html(beasiswa)
                                let tingkat = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.idtingkat;
                                $('#o-juarabea').html(tingkat==null||tingkat=='-'?'-':data.daftar.jenisbeasiswa.tingkat.tingkat_kejuaraan)
                                let ketbea = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.juara_ke
                                $('#o-keteranganbea').html(ketbea)
                                let durasid3 = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.durasi_d3+' Semester'
                                $('#o-durasid3').html(durasid3)
                                let durasis1 = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.durasi_s1+' Semester'
                                $('#o-durasis1').html(durasis1)

                                let berkas = ''

                                berkas += '<tr style="background-color: rgb(0, 238, 255);">'+
                                            '<th colspan="4" class="text-center">'+data.daftar.jalur.berkasumum.jenis_berkas+'</th>'+
                                          '</tr>'
                                berkas += '<tr style="background-color: rgb(0, 238, 255);">'+
                                            '<th>No</td>'+
                                            '<th>Nama berkas</th>'+
                                            '<th>Keterangan</th>'+
                                            '<th>Format File</th>'+
                                          '</tr>'

                                for(i=0;i<data.daftar.jalur.berkasumum.berkas.length;i++){
                                    let no = i+1;
                                    berkas += '<tr>'+
                                                '<td>'+no+'</td>'+
                                                '<td>'+data.daftar.jalur.berkasumum.berkas[i].nama_berkas+'</td>'+
                                                '<td>'+data.daftar.jalur.berkasumum.berkas[i].keterangan+'</td>'+
                                                '<td>'+data.daftar.jalur.berkasumum.berkas[i].formatfile+'</td>'+
                                              '</tr>'
                                }

                                let berkaskhusus = data.daftar.jalur.berkas_khusus ? data.daftar.jalur.berkaskhusus.jenis_berkas : 'Khusus';
                                berkas += '<tr style="background-color: rgb(0, 238, 255);">'+
                                            '<th colspan="4" class="text-center">'+berkaskhusus+'</th>'+
                                          '</tr>'
                                if(data.daftar.jalur.berkas_khusus){
                                    berkas += '<tr style="background-color: rgb(0, 238, 255);">'+
                                                '<th>No</td>'+
                                                '<th>Nama berkas</th>'+
                                                '<th>Keterangan</th>'+
                                                '<th>Format File</th>'+
                                              '</tr>'
                                    for(i=0;i<data.daftar.jalur.berkaskhusus.berkas.length;i++){
                                        let noo = i+1;
                                        berkas += '<tr>'+
                                                    '<td>'+noo+'</td>'+
                                                    '<td>'+data.daftar.jalur.berkaskhusus.berkas[i].nama_berkas+'</td>'+
                                                    '<td>'+data.daftar.jalur.berkaskhusus.berkas[i].keterangan+'</td>'+
                                                    '<td>'+data.daftar.jalur.berkaskhusus.berkas[i].formatfile+'</td>'+
                                                '</tr>'
                                    }

                                }else{
                                    berkas += '<tr>'+
                                            '<th colspan="4" class="text-center">Tidak Ada Berkas Khusus</th>'+
                                          '</tr>'
                                }

                                $('#detail-berkas').append(berkas)

                                $('#modal-detail').modal('show')
                            }
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal Show Data Pendaftaran !',
                                text: 'Internal Server Error',
                                icon: 'error'
                            }).then((result) => {
                                window.isEditing = false;
                            });
                            return;
                        }
                    });
                    return false;
                });
            }

        });

        $('#example2').on('click', '.btn_edit_jurusan', function(e) {
            e.preventDefault();
            let param = $(this).data('id');
            // $('#modal-edit-jurusan').modal('show');

            $.ajax({
                    type: "GET",
                    url: '{!! url('admin/DataPendaftaran/Beasiswa/EditJurusan') !!}' + '/' + param,
                    dataType: "JSON",
                    beforeSend: function(response) {
                        $('#loading').show();
                    },
                    success: function(data) {
                    console.log('data :>> ', data.jalur);
                        $('#loading').hide();
                        if(data.hasil == 0) {
                            notifalert('Information', 'Data Pendaftaran Tidak Ditemukan', 'error');
                        } else {
                            $('#kodependaftaranmahasiswa').val(param);
                            $('.modal-titlejurusan').html('Data Jurusan ' + data.KodePendaftaran);
                            $('#prodi1').val(data.pilihan1).trigger('change');
                            $('#prodi2').val(data.pilihan2).trigger('change');
                            $('#prodi3').val(data.pilihan3).trigger('change');
                            $('#modal-edit-jurusan').modal('show');

                                // reset option
                            $('#jadwalkelas').html(
                                '<option value="" disabled>-- Pilih Jadwal Kelas --</option>'
                            );

                            // tambah option dinamis
                            if(data.jalur.kelaspagi == 1){
                                $('#jadwalkelas').append(
                                    `<option value="PAGI">Kelas Pagi</option>`
                                );
                            }

                            if(data.jalur.kelassore == 1){
                                $('#jadwalkelas').append(
                                    `<option value="SORE">Kelas Sore</option>`
                                );
                            }

                            // set selected jika ada data sebelumnya
                            if(data.kelaspagi == 1){
                                $('#jadwalkelas').val('PAGI');
                            }

                            if(data.kelassore == 1){
                                $('#jadwalkelas').val('SORE');
                            }

                            // refresh select2
                            $('#jadwalkelas').trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        console.error("AJAX ERROR:", xhr.responseText);
                        Swal.fire({
                            title: 'Gagal Show Data',
                            text: 'Terjadi masalah saat mengambil data dari server. Silakan coba lagi atau hubungi admin.',
                            icon: 'error'
                        });
                    }
                });
        });

        $('#btn-saveupdatejurusan').click(function(e) {
            e.preventDefault();
            let kodependaftaran = $('#kodependaftaranmahasiswa').val();
            let prodi1 = $('#prodi1').val();
            let prodi2 = $('#prodi2').val();
            let prodi3 = $('#prodi3').val();
            let jadwalkelas = $('#jadwalkelas').val();

            $.ajax({
                type: "POST",
                url: '{!! route('admin.databeasiswa.updatejurusan') !!}',
                data: {
                    kodependaftaran: kodependaftaran,
                    prodi1: prodi1,
                    prodi2: prodi2,
                    prodi3: prodi3,
                    jadwalkelas: jadwalkelas
                },
                dataType: "JSON",
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#loading').hide();
                    if (response.status == 'success') {
                        $('#modal-edit-jurusan').modal('hide');
                        notifalert('Berhasil', response.message, 'success');
                        $('#example2').DataTable().ajax.reload(null, false);
                    } else {
                        notifalert('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    $('#loading').hide();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        });

        $('#example2').on('click', '.btn_edit_rekomendator', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            // MENGGUNAKAN .attr() AGAR DATA SELALU TERBACA AKURAT DARI DOM
            let rek_saatini = $(this).attr('data-rek');

            $('#edit_id_daftar').val(id);

            // Tampilkan Teks Rekomendator Saat Ini
            if(rek_saatini && rek_saatini !== '-' && rek_saatini !== '') {
                $('#teks_rekomendator_saatini').text(rek_saatini);
            } else {
                $('#teks_rekomendator_saatini').text('-');
            }

            // Sembunyikan form dropdown pencarian & tombol simpan
            $('#wadah-form-rekomendator').hide();
            $('#btn-save-rekomendator').hide();
            // Munculkan tombol pemancing edit
            $('#btn-tampil-form-edit').show();

            // Kosongkan Select2
            $('#select_rekomendator').empty().append('<option value="" selected disabled>-- Ketik Nama Rekomendator --</option>');

            $('#modal-edit-rekomendator').modal('show');
        });

        // 2. EVENT KETIKA TOMBOL "UBAH / TAMBAH" DI DALAM MODAL DIKLIK
        $('#btn-tampil-form-edit').click(function(e){
            e.preventDefault();
            $(this).hide(); // Sembunyikan tombol "Ubah"
            $('#wadah-form-rekomendator').slideDown('fast'); // Tampilkan form Select2
            $('#btn-save-rekomendator').fadeIn('fast'); // Tampilkan tombol "Simpan"
        });

        // SETUP SELECT2 UNTUK PENCARIAN NAMA REKOMENDATOR SAJA
        $('#select_rekomendator').select2({
            dropdownParent: $('#modal-edit-rekomendator'),
            theme: 'bootstrap4',
            placeholder: '-- Ketik Nama Rekomendator --',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{!! route('admin.databeasiswa.carirekomendator') !!}', // Pastikan route ini dapat diakses admin juga
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.kode_rekomendator+' - '+item.nama_rekomendator,
                                id: item.kode_rekomendator
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // EVENT SIMPAN PERUBAHAN REKOMENDATOR
        $('#btn-save-rekomendator').click(function(e) {
            e.preventDefault();
            let id_daftar = $('#edit_id_daftar').val();
            let kode_rek = $('#select_rekomendator').val();

            if (!kode_rek) {
                notifalert('Information', 'Pilih Rekomendator terlebih dahulu!', 'warning');
                return;
            }

            $.ajax({
                type: "POST",
                url: '{!! route('admin.databeasiswa.updaterekomendator') !!}',
                data: {
                    id_daftar: id_daftar,
                    kode_rekomendator: kode_rek
                },
                dataType: "JSON",
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#loading').hide();
                    if (response.status == 'success') {
                        $('#modal-edit-rekomendator').modal('hide');
                        notifalert('Berhasil', response.message, 'success');
                        $('#example2').DataTable().ajax.reload();
                    } else {
                        notifalert('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    $('#loading').hide();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        });

        $('body').on('click', '.btn_hapus', function() {
            let idku = $(this).attr('data-id');

            Swal.fire({
                title: 'Konfirmasi Hapus!',
                text: 'Apakah Anda Yakin Menghapus Mahasiswa ?',
                icon: 'question',
                showConfirmButton: true,
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{!! route('admin.databeasiswa.hapusdata') !!}",
                        dataType: "JSON",
                        data: {
                            id: idku,
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Sedang Proses',
                                html: 'Mohon Tunggu Sebentar',
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                showCancelButton: false,
                                showConfirmButton: false,
                                backdrop: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                icon: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                location.reload();
                                Swal.close();
                            });
                            return;
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            let res = xhr.responseJSON;
                            Swal.fire({
                                title: res?.title ?? 'Error',
                                text: res?.message ?? error,
                                icon: status
                            });
                            return;
                        }
                    });
                    return false;
                }
            })
        });

    </script>
@endsection

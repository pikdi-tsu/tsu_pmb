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
                        <div class="card-header">
                            <h5 class="m-0">
                                {{$menu}}
                                <button type="button" id="btn-inputtest" style="display: none;" class="btn btn-sm btn-success float-right">Input Tanggal Test</button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <code>* Setelah hasil Test keluar, admin bisa menentukan calon mahasiswa yang lolos test atau tidak pada kolom hasil</code><br>
                            <code>* Klik</code> <i title="Lolos Test" class="fa fa-check-square fa-lg text-green"></i> <code>Untuk mahasiswa yang lolos test</code><br>
                            <code>* Klik</code> <i title="Tidak Lolos Test" class="fa fa-window-close fa-lg text-red"></i> <code>Untuk mahasiswa yang tidak lolos Test</code>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>No Registrasi</th>
                                            <th>Batch Daftar</th>
                                            <th>Jalur Daftar</th>
                                            <th>Kategori Beasiswa</th>
                                            <th>Tgl Test</th>
                                            <th>Nilai Test</th>
                                            <th>Jurusan Diterima</th>
                                            <th>Validator</th>
                                            <th>Status</th>
                                            <th>Hasil</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
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
                    <h4 class="modal-title" id="judul-modal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabel-detail" class="table" style="width: 100%;display:none;">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center" style="background-color: rgb(0, 204, 255);">Data Pendaftaran Calon Mahasiswa Baru</th>
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
                                <th>Jalur Daftar</th>
                                <th id="o-jalur" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 1</th>
                                <th id="o-prodi1" class="o-detaildaftar"></th>
                                <th>Program Studi Pilihan 2</th>
                                <th id="o-prodi2" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 3</th>
                                <th id="o-prodi3" class="o-detaildaftar"></th>
                                <th>Nilai Test</th>
                                <th id="o-nilaitest" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Jurusan Diterima</th>
                                <th id="o-jurusanditerima" class="o-detaildaftar"></th>
                                <th>Tanggal Test</th>
                                <th id="o-tgltest" class="o-detaildaftar"></th>
                            </tr>
                        </thead>
                        <tbody id="detail-bayar">
                            <tr>
                                <th colspan="4" class="text-center" style="background-color: rgb(0, 204, 255);">Detail Soal & Jawaban Peserta</th>
                            </tr>
                            <tr>
                                <th>Soal</th>
                                <th>Pilihan Ganda</th>
                                <th>Skor</th>
                                <th>Jawaban Peserta</th>
                            </tr>
                            @foreach ($soal as $row => $s)
                                <tr>
                                    <td>
                                        {{$row+1}}. {{$s->soal_test}}
                                        <input type="hidden" id="jawabanbenar-{{$s->kode_soal}}" value="{{$s->jawaban[0]->jawaban_benar}}">
                                    </td>
                                    <td>
                                        @foreach($s->jawaban as $key => $j)
                                            {{$j->pilihan}}. {{$j->jawaban}}
                                            @if($j->score>0)
                                                <i class="fas fa-check-circle text-green"></i>
                                            @endif
                                            <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($s->jawaban as $key => $j)
                                            @if($j->score>0)
                                                <span class="badge bg-success">{{$j->score}}</span>
                                            @else
                                                <span class="badge bg-danger">{{$j->score}}</span>
                                            @endif
                                            <br>
                                        @endforeach
                                    </td>
                                    <td id="jawabanpeserta-{{$s->kode_soal}}" class="jawabanpeserta"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form id="form-diterima" style="display: none;" method="POST" action="#">
                        @csrf
                        <input type="hidden" id="kodedaftar" name="kodedaftar" value="">
                        <input type="hidden" id="status_diterima" name="status_diterima" value="">
                        <label for="jurusan_diterima">Jurusan Diterima</label>
                        <select class="form-control select2" id="jurusan_diterima" name="jurusan_diterima">
                            <option value="" selected disabled>-- Pilih Jurusan --</option>

                        </select>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btn-closemodal" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-lolos" style="display: none;" class="btn btn-success"><i class="fas fa-paper-plane"></i> Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

            $('.select2').select2()

            loadEvent()

            function loadEvent()
            {
                tabelTestPMB()
                submitlolostest()
            }


            function tabelTestPMB()
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
                        url: '{!! route('admin.testpmb.tabel') !!}',
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
                            data: 'tgl_test'
                        },
                        {
                            data: 'nilai'
                        },
                        {
                            data: 'diterima'
                        },
                        {
                            data: 'validator'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'hasil'
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
                        detailhasil_test()
                        lolostest()
                        tidaklolostest()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function detailhasil_test()
            {
                $('.btn_detail').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/TestOnlinePMB/DetailTestOnlinePMB') !!}'+'/'+params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('.o-detaildaftar').empty()
                            $('.jawabanpeserta').empty()
                            $('#judul-modal').empty()
                            $('#tabel-detail').hide()
                            $('#form-diterima').hide()
                            $('#btn-lolos').hide()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Test Tidak Ditemukan','error')
                            }else{
                                $('#judul-modal').html('Detail Test Online')
                                $('#o-noregist').html(': '+data.daftar.KodePendaftaran);
                                $('#o-nama').html(': '+data.daftar.biodata.nama);
                                $('#o-batch').html(': '+data.daftar.batch.nama_batch+' '+data.daftar.batch.tahun_akademik);
                                $('#o-jalur').html(': '+data.daftar.jalur.jenis_pendaftaran);
                                $('#o-prodi1').html(': ' + (data.daftar.prodi1 && data.daftar.prodi1.jenjang ? data.daftar.prodi1.jenjang.jenjang + '-' + data.daftar.prodi1.jurusan : (data.daftar.prodi1 ? data.daftar.prodi1.jurusan : '-')));
                                $('#o-prodi2').html(': ' + (data.daftar.prodi2 && data.daftar.prodi2.jenjang ? data.daftar.prodi2.jenjang.jenjang + '-' + data.daftar.prodi2.jurusan : (data.daftar.prodi2 ? data.daftar.prodi2.jurusan : '-')));
                                $('#o-prodi3').html(': ' + (data.daftar.prodi3 && data.daftar.prodi3.jenjang ? data.daftar.prodi3.jenjang.jenjang + '-' + data.daftar.prodi3.jurusan : (data.daftar.prodi3 ? data.daftar.prodi3.jurusan : '-')));
                                $('#o-tgltest').html(': '+dateIndo(data.daftar.tgl_test));
                                $('#o-nilaitest').html(': '+data.daftar.nilai_test);
                                let diterima = data.daftar.jurusan_diterima ? data.daftar.jurusan_acc.jenjang.jenjang+'-'+data.daftar.jurusan_acc.jurusan : '-'
                                $('#o-jurusanditerima').html(diterima)
                                // $('#detail-bayar').append(html);
                                let jawabanpeserta = data.daftar.jawaban_peserta
                                for (i=0;i<jawabanpeserta.length;i++) {
                                    let jwbbenar = $('#jawabanbenar-'+jawabanpeserta[i].kodesoal).val()
                                    let koreksi = '';
                                    if(jwbbenar==jawabanpeserta[i].jawaban_peserta){
                                        koreksi = '<i class="fas fa-check-circle text-green"></i>'
                                    }else{
                                        koreksi = '<span class="badge bg-danger">X</span>'
                                    }
                                    $('#jawabanpeserta-'+jawabanpeserta[i].kodesoal).html(jawabanpeserta[i].jawaban_peserta+' '+koreksi)
                                }
                                $('#modal-detail').modal('show')
                                $('#tabel-detail').slideDown('slow');
                            }
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal Show Data Pembayaran !',
                                text: 'Hubungi Tim IT',
                                icon: 'error'
                            }).then((result) => {
                                // window.isEditing = false;
                            });
                            return;
                        }
                    });
                    return false;
                });
            }

            function lolostest()
            {
                $('.lolos').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/TestOnlinePMB/ShowJurusanDiterima') !!}'+'/'+params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('.o-detaildaftar').empty()
                            $('.jawabanpeserta').empty()
                            $('#judul-modal').empty()
                            $('#tabel-detail').hide()
                            $('#form-diterima').hide()
                            $('#btn-lolos').hide()
                            $('#kodedaftar').val(null)
                            $('#status_diterima').val(null)
                            $('#jurusan_diterima').empty()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Pendaftar Tidak ditemukan','error')
                            }else{
                                $('#judul-modal').html('Hasil Test Online')
                                $('#btn-lolos').show()
                                $('#kodedaftar').val(params)
                                $('#status_diterima').val('1')

                                let html = ''
                                    html += '<option value="" selected disabled>-- Pilih Jurusan --</option>'

                                for (i = 0;i<data.jurusan.length;i++) {
                                    html += '<option value="'+data.jurusan[i].KodeJurusan+'">'+data.jurusan[i].jenjang.jenjang+' - '+data.jurusan[i].jurusan+'</option>'
                                }
                                $('#jurusan_diterima').append(html)
                                $('#form-diterima').slideDown('slow');
                                $('#modal-detail').modal('show')
                            }
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal Show Data !',
                                text: 'Hubungi Tim IT',
                                icon: 'error'
                            }).then((result) => {
                                // window.isEditing = false;
                                console.log('error')
                            });
                            return;
                        }
                    });
                    return false;
                });
            }

            function submitlolostest()
            {
                $('#btn-lolos').click(function (e) {
                    e.preventDefault();
                    let dataku = $('#form-diterima').serialize()
                    if($('#jurusan_diterima').val()==''||$('#jurusan_diterima').val()==null){
                        notifalert('Information','Jurusan Diterima tidak boleh kosong !','warning')
                    }else{
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Anda yakin Peserta ini lolos test ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.testpmb.save')}}",
                                    data: dataku,
                                    dataType: "JSON",
                                    beforeSend: function(response) {
                                        $('#btn-lolos').html('<i class="fas fa-hourglass"></i> Please Wait')
                                        $('#btn-lolos').prop('disabled', true)
                                        $('#loading').show()
                                    },
                                    success: function(data) {
                                        $('#loading').hide()
                                        Swal.fire({
                                            title: data.title,
                                            text: data.message,
                                            icon: data.status
                                        }).then((result) => {
                                            $('#btn-lolos').html('<i class="fas fa-paper-plane"></i> Simpan')
                                            $('#btn-lolos').prop('disabled',false)
                                            if(data.status=='success'){
                                                $('#btn-closemodal').trigger('click')
                                                $('#example2').DataTable().ajax.reload();
                                            }
                                        });
                                        return;
                                    },
                                    error: function(xhr, status, error) {
                                        $('#loading').hide()
                                        Swal.fire({
                                            title: 'Unsuccessfully Saved Data',
                                            text: 'Check Your Data',
                                            icon: 'error'
                                        }).then((result) => {
                                            $('#btn-lolos').html('<i class="fas fa-paper-plane"></i> Simpan')
                                            $('#btn-lolos').prop('disabled',false)
                                            console.log('error')
                                        });
                                        return;
                                    }
                                });
                                return false;
                            }else{
                                return false;
                            }
                        });
                    }
                });
            }

            function tidaklolostest()
            {
                $('.tidaklolos').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    let formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('kodedaftar', params);
                    formData.append('status_diterima', '-1');
                    formData.append('jurusan_diterima', '');
                    Swal.fire({
                        title: "Information",
                        text: "Apakah Anda yakin Peserta Ini Tidak Lolos Test ?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if(result.value){
                            $.ajax({
                                type: "POST",
                                url: "{{route('admin.testpmb.save')}}",
                                processData: false,
                                contentType: false,
                                data: formData,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: data.title,
                                        text: data.message,
                                        icon: data.status
                                    }).then((result) => {
                                        if(data.status=='success'){
                                            $('#example2').DataTable().ajax.reload();
                                        }
                                    });
                                    return;
                                },
                                error: function(xhr, status, error) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Unsuccessfully Saved Data',
                                        text: 'Check Your Data',
                                        icon: 'error'
                                    }).then((result) => {
                                        console.log('error')
                                    });
                                    return;
                                }
                            });
                        }else{
                            return false;
                        }
                    });
                });
            }

            $('#modal-detail').on('hidden.bs.modal', function () {
                $('.o-detaildaftar').empty()
                $('.jawabanpeserta').empty()
                $('#judul-modal').empty()
                $('#tabel-detail').hide()
                $('#form-diterima').hide()
                $('#btn-lolos').hide()
                $('#kodedaftar').val(null)
                $('#status_diterima').val(null)
                $('#jurusan_diterima').empty()
            });

            function dateIndo(datetime) {
                if (!datetime) return '';

                let bulanIndo = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];

                let d = new Date(datetime);

                if (isNaN(d)) return '';

                let hari = d.getDate();
                let bulan = bulanIndo[d.getMonth()];
                let tahun = d.getFullYear();

                let jam = ("0" + d.getHours()).slice(-2);
                let menit = ("0" + d.getMinutes()).slice(-2);

                return `${hari} ${bulan} ${tahun} ${jam}:${menit}`;
            }

        });
    </script>
@endsection

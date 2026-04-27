@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $menu }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Master Assessment</li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">
                                {{ $menu }}
                                <button class="btn btn-primary btn-sm float-right" id="btn-tambah"><i class="fas fa-plus"></i> Tambah</button>
                                <button id="btn-batal" class="btn btn-warning btn-sm float-right" style="display: none;"><i class="fas fa-undo"></i>Batal</button>
                                <button id="submit-batch" class="btn btn-success float-right btn-sm" style="display: none;margin-right:5px;"> <i class="fas fa-paper-plane"></i> Submit</button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" id="slide-test" style="display: none;"> <!-- Ubah lebar form di sini -->
                                <form id="form-test" method="POST" action="#">
                                    @csrf
                                    <input type="hidden" id="IdTest" name="IdTest" value="">
                                    <!-- Nama Jenis -->
                                    <table class="table">
                                        <tr>
                                            <th width="15%">Nama Test<code>*</code></th>
                                            <th width="35%">
                                                <input type="text" id="nama_test" name="nama_test" placeholder="Nama Test" class="form-control" title="Isian Maksimal 100 karakter">
                                            </th>
                                            <th width="15%">Tipe Engine<code>*</code></th>
                                            <th width="35%">
                                                <select class="form-control select2" id="engine_test" name="engine_test">
                                                    <option value="" selected disabled>-- Pilih Tipe Engine --</option>
                                                    @foreach($engine as $key => $p)
                                                        <option value="{{ $p->tipe_engine }}">{{$p->tipe_engine}} ({{ $p->keterangan }})</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Kode Test<code>*</code></th>
                                            <th>
                                                <input type="text" id="kodetest" name="kodetest" placeholder="Kode Test" class="form-control" title="Isian Maksimal 20 karakter">
                                            </th>
                                            <th>Durasi Test (Menit)<code>*</code></th>
                                            <th>
                                                <input type="number"  class="form-control" name="durasi_test" id="durasi_test" min="1">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Urutan<code>*</code></th>
                                            <th>
                                                <input type="number"  class="form-control" name="urutan_test" id="urutan_test" min="1">
                                            </th>
                                            <th>Status Aktif</th>
                                            <th>
                                                <select class="form-control select2" id="status" name="status">
                                                    <option value="" selected disabled>-- Pilih Status --</option>
                                                    <option value="1">Aktif</option>
                                                    <option value="0">Non Aktif</option>
                                                </select>
                                            </th>
                                        </tr>
                                    </table>
                                    <!-- Buttons -->
                                    {{-- <div class="form-group">
                                        <button type="button" id="submit-batch" class="btn btn-success float-right btn-sm"> <i class="fas fa-paper-plane"></i> Submit</button>
                                    </div> --}}
                                </form>
                                <hr>
                            </div>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Test</th>
                                            <th>Kode Test</th>
                                            <th>Urutan</th>
                                            <th>Durasi Test</th>
                                            <th>Status Aktif</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

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
                add()
                batal()
                tabel()
                save()
            }

            function tabel()
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
                        url: '{!! route('admin.mastertest.tabel') !!}',
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
                            data: 'kode'
                        },
                        {
                            data: 'urutan'
                        },
                        {
                            data: 'durasi'
                        },
                        {
                            data: 'aktif'
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
                        edit()
                        aktifNonaktif()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function add()
            {
                $('#btn-tambah').click(function (e) {
                    e.preventDefault();
                    $(this).hide()
                    $('#btn-batal').show()
                    $('#submit-batch').show()
                    $('#slide-test').slideDown('slow');
                });
            }

            function batal()
            {
                $('#btn-batal').click(function (e) {
                    e.preventDefault();
                    $('#slide-test').slideUp('slow');
                    $(this).hide()
                    $('#submit-batch').hide()
                    $('#btn-tambah').show()
                    $('#form-test')[0].reset();
                    $('#form-test').find('select').each(function() {
                        $(this).val($(this).data('default') ?? '').trigger('change');
                    });
                });
            }

            function validasi_tipetest()
            {
                let namatest = $('#nama_test').val()
                let kodetest = $('#kodetest').val()
                let urutan = $('#urutan_test').val()
                let tipe = $('#engine_test').val()
                let durasi = $('#durasi_test').val()
                // let status = $('#status').val()

                let notif = ''

                if(namatest==''||namatest==null){
                    notif = 'Nama Test Tidak Boleh Kosong'
                }else if(kodetest==''||kodetest==null){
                    notif = 'Nama Test Tidak Boleh Kosong'
                }else if(urutan==0||urutan==null||urutan==''){
                    notif = 'Urutan Soal Harus Lebih dari 0 atau tidak boleh kosong'
                }else if(tipe==''||tipe==null){
                    notif = 'Tipe Engine Tidak Boleh Kosong'
                }else if(durasi==0||durasi==null||durasi==''){
                    notif = 'Durasi Test Harus lebih dari 0 Menit atau tidak boleh kosong'
                }else{
                    notif = 'ok'
                }
                return notif;
            }

            function save()
            {
                $('#submit-batch').click(function (e) {
                    e.preventDefault();
                    let validasiku = validasi_tipetest()
                    if(validasiku!='ok'){
                        notifalert('Perhatian',validasiku,'warning')
                    }else{
                        let dataku = $('#form-test').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Data Type Test Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.mastertest.save')}}",
                                    data: dataku,
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
                                                $('#btn-batal').trigger('click');
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
                                            // $('#example2').DataTable().ajax.reload();
                                        });
                                        return;
                                    }
                                });
                            }else{
                                return false;
                            }
                        });
                    }
                });
            }

            function edit()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/Assessment/MasterAssessment/Test/edit-test') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-batal').trigger('click')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Type Test Tidak Ditemukan','error')
                            }else{
                                $('#IdTest').val(data.type.id)
                                $('#nama_test').val(data.type.nama_test)
                                $('#engine_test').val(data.type.tipe_engine).trigger('change')
                                $('#kodetest').val(data.type.kode_test)
                                $('#durasi_test').val(data.type.durasi_menit)
                                $('#urutan_test').val(data.type.urutan)
                                $('#status').val(data.type.isactive).trigger('change')
                                $('#btn-tambah').trigger('click')
                            }
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Silahkan Hubungi PIKDI',
                                icon: 'error'
                            }).then((result) => {

                            });
                            return;
                        }
                    });
                    return false;
                });
            }

            function aktifNonaktif()
            {
                $('.btn_aktif').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    let status = $(this).data('aktif')
                    Swal.fire({
                        title: 'Information',
                        text: status=='0' ? 'Non Aktifkan Type Test ?' : 'Aktifkan Type Test ?',
                        icon: 'question',
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/Assessment/MasterAssessment/Test/status-test') !!}'+'/'+params+'/'+status,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    if(data.status=='success'){
                                        $('#example2').DataTable().ajax.reload();
                                    }
                                    notifalert(data.title,data.message,data.status)
                                },
                                error: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Silahkan Hubungi PIKDI',
                                        icon: 'error'
                                    }).then((result) => {

                                    });
                                    return;
                                }
                            });
                            return false;
                        }else{
                            return false;
                        }
                    })

                });
            }

        });
    </script>
@endsection

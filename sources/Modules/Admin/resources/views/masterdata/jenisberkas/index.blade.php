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
                    <h1>{{ $menu }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Master Data</li>
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
                            <h5 class="m-0">{{ $menu }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6"> <!-- Ubah lebar form di sini -->
                                    <form id="form-fakultas" method="POST" action="#">
                                        @csrf
                                        <input type="hidden" id="IdJenisBerkas" name="IdJenisBerkas" value="">
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="jenis">Jenis Berkas</label>
                                            <input type="text" id="jenisberkas" name="jenisberkas" placeholder="Jenis Berkas" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="kategori">Kategori Berkas</label>
                                            <select class="form-control select2" id="kategori" name="kategori">
                                                <option value="" selected disabled>-- Pilih Kategori Berkas --</option>
                                                <option value="0">Umum</option>
                                                <option value="1">Khusus</option>
                                            </select>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="tahun">Keterangan</label>
                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Keterangan Berkas"></textarea>
                                        </div>
                                        <!-- Buttons -->
                                        <div class="form-group">
                                            <button type="button" id="submit-batch" class="btn btn-success float-right" style="margin-left:10px;"> <i class="fas fa-paper-plane"></i> Submit</button>
                                            <button id="btn-reset" class="btn btn-warning float-right">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive" style="margin-top: 20px;">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Berkas</th>
                                            <th>Kategori Berkas</th>
                                            <th>Jumlah Berkas</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Action</th>
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

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-modal">Detail Jenis Berkas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabel-detail" class="table table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Jenis Berkas : </th>
                                <th colspan="5" id="o-jenisberkas"></th>
                            </tr>
                            <tr>
                                <th>Keterangan : </th>
                                <th colspan="5" id="o-ketberkas"></th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-center" style="background-color: rgb(114, 182, 46);">Berikut adalah detail jumlah berkas</th>
                            </tr>
                            <tr>
                                <th>No</th>
                                {{-- <th>Kode Berkas</th> --}}
                                <th>Nama Berkas</th>
                                <th>Deskripsi</th>
                                <th>Keterangan</th>
                                <th>Format File</th>
                            </tr>
                        </thead>
                        <tbody id="o-isidetail">

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

            // Tunda tabel hingga layout siap
            // setTimeout(() => {
                loadEvent()
            // }, 1000);

            function loadEvent()
            {
                tabelJenisBerkas()
                submitJenisBerkas()
                btn_reset()
            }

            function tabelJenisBerkas()
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
                        url: '{!! route('admin.JenisBerkas.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jenis'
                        },
                        {
                            data: 'kategori'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'keterangan'
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
                        EditJenisBerkas()
                        DeleteJenisBerkas()
                        DetailJenisBerkas()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function submitJenisBerkas()
            {
                $('#submit-batch').click(function (e) {
                    e.preventDefault();
                    let validation = validationBerkas()
                    if(validation != 'success'){
                        notifalert('Information',validation,'warning')
                    }else{
                        let dataku = $('#form-fakultas').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Jenis Berkas dan Keterangan Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.JenisBerkas.Store')}}",
                                    data: dataku,
                                    dataType: "JSON",
                                    beforeSend: function(response) {
                                        $('#submit-batch').html('<i class="fas fa-hourglass"></i> Please Wait')
                                        $('#submit-batch').prop('disabled', true)
                                        $('#loading').show()
                                    },
                                    success: function(data) {
                                        $('#loading').hide()
                                        Swal.fire({
                                            title: data.title,
                                            text: data.message,
                                            icon: data.status
                                        }).then((result) => {
                                            $('#idku').val(null)
                                            $('#submit-batch').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-batch').prop('disabled',false)
                                            $('#form-fakultas').trigger('reset');
                                            $("#example2").DataTable().ajax.reload();
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
                                            $('#submit-batch').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-batch').prop('disabled',false)
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

            function validationBerkas()
            {
                let jenis    = $('#jenisberkas').val()
                let notifku = ''
                if (jenis == null || jenis == '') {
                    notifku = 'Jenis berkas tidak boleh kosong'
                }else{
                    notifku = 'success';
                }
                return notifku;
            }

            function EditJenisBerkas()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/JenisBerkas/EditJenisBerkas') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-reset').trigger('click')
                            // $('#form-fakultas').trigger('reset');
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Jenis Berkas Tidak Ditemukan', 'error')
                            }else{
                                $('#IdJenisBerkas').val(data.IdJenisBerkas)
                                $('#kategori').val(data.jenisberkas.kategori).trigger('change')
                                $('#jenisberkas').val(data.jenisberkas.jenis_berkas)
                                $('#keterangan').val(data.jenisberkas.keterangan)
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteJenisBerkas()
            {
                $('.btn_delete').click(function(e) {
                    // e.preventDefault()
                    let params = $(this).data('id')
                    let status = $(this).data('status')

                    Swal.fire({
                        title: 'Information',
                        text: 'Are you sure to delete this item ?',
                        icon: 'question',
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/MasterData/JenisBerkas/Status') !!}' + '/' + params + '/' + status,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Information',
                                        text: data.message,
                                        icon: data.type
                                    }).then(() => {
                                        $("#example2").DataTable().ajax.reload();
                                    })
                                },
                                error: function(data) {
                                    $('#loading').hide()
                                    console.log(0)
                                }
                            });
                            return false;
                        }else{
                            return false;
                        }
                    })
                })
            }

            function DetailJenisBerkas()
            {
                $('.detail_berkas').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    // $('#modal-detail').modal('show')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/JenisBerkas/EditJenisBerkas') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#o-jenisberkas').html('')
                            $('#o-ketberkas').html('')
                            $('#o-isidetail').html('')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Jenis Berkas Tidak Ditemukan', 'error')
                            }else{
                                $('#o-jenisberkas').html(data.jenisberkas.jenis_berkas)
                                $('#o-ketberkas').html(data.jenisberkas.keterangan)
                                let jml = data.jenisberkas.berkas
                                let html = ''
                                if(jml.length==0){
                                    html += '<tr>'+
                                                '<td colspan="6" class="text-center">Berkas Belum Ada</td>'+
                                            '</tr>'
                                }else{
                                    for(i=0;i<jml.length;i++){
                                        let no = i+1;
                                        html += '<tr>'+
                                                    '<td>'+no+'</td>'+
                                                    // '<td>'+jml[i].KodeBerkas+'</td>'+
                                                    '<td>'+jml[i].nama_berkas+'</td>'+
                                                    '<td>'+jml[i].deskripsi+'</td>'+
                                                    '<td>'+jml[i].keterangan+'</td>'+
                                                    '<td>'+jml[i].formatfile+'</td>'+
                                                '</tr>'
                                    }
                                }
                                $('#o-isidetail').append(html);
                                $('#modal-detail').modal('show')
                            }
                        }
                    });
                    return false;
                });
            }

            function btn_reset()
            {
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdJenisBerkas').val(null)
                    $('#form-fakultas').trigger('reset');
                    $('#kategori').val('').trigger('change')
                    $("#example2").DataTable().ajax.reload();
                });
            }

        });
    </script>
@endsection

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
                                        <input type="hidden" id="IdBerkas" name="IdBerkas" value="">
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="jenis">Jenis Berkas</label>
                                            <select class="form-control select2" id="jenis" name="jenis" required>
                                                <option value="" selected disabled>-- Pilih Jenis Berkas --</option>
                                                @foreach ($jenis as $i)
                                                <option value="{{$i->id}}">{{$i->kategori==1 ? 'Berkas Khusus' : 'Berkas Umum'}} - {{$i->jenis_berkas}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="batch">Nama Berkas</label>
                                            <input type="text" id="nama" name="nama" placeholder="Nama Berkas" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="tahun">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi Berkas"></textarea>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="status">Keterangan</label>
                                            <select class="form-control select2" id="status" name="status" required>
                                                <option value="" selected disabled>-- Pilih Keterangan --</option>
                                                <option value="Wajib">Wajib</option>
                                                <option value="Tidak Wajib">Tidak Wajib</option>
                                            </select>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="formatfile">Format File</label>
                                            <select class="select2" id="formatfile" name="formatfile[]" multiple="multiple" data-placeholder="Pilih Format File" style="width: 100%;" required>
                                                <option value=".jpg">.jpg</option>
                                                <option value=".jpeg">.jpeg</option>
                                                <option value=".png">.png</option>
                                                <option value=".pdf">.pdf</option>
                                            </select>
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
                                            {{-- <th>Kode Berkas</th> --}}
                                            <th>Jenis Berkas</th>
                                            <th>Nama Berkas</th>
                                            <th>Deskripsi</th>
                                            <th>Keterangan</th>
                                            <th>Format File</th>
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
                tabelBerkas()
                submitBerkas()
                btn_reset()
            }

            function tabelBerkas()
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
                        url: '{!! route('admin.Berkas.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        // {
                        //     data: 'kode'
                        // },
                        {
                            data: 'jenis'
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'deskripsi'
                        },
                        {
                            data: 'keterangan'
                        },
                        {
                            data: 'format'
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
                        EditBerkas()
                        DeleteBerkas()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });

                // setTimeout(function () {
                //     otable.columns.adjust().draw(false);
                // }, 300); // ulangi sekali lagi untuk jaga-jaga
            }

            function submitBerkas()
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
                            text: "Apakah Berkas Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.Berkas.Store')}}",
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
                                            $('#IdBerkas').val(null)
                                            $('#submit-batch').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-batch').prop('disabled',false)
                                            $('#form-fakultas').trigger('reset');
                                            $('#jenis').val('').trigger('change')
                                            $('#formatfile').val('').trigger('change')
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
                            }else{
                                return false;
                            }
                        });
                    }
                });
            }

            function validationBerkas()
            {
                // let kode     = $('#kodeberkas').val()
                let jenis    = $('#jenis').val()
                let nama     = $('#nama').val()
                let deskripsi= $('#deskripsi').val()
                let status   = $('#status').val()
                let format  = $('#formatfile').val()

                let notifku = ''
                // if (kode == null || kode == '') {
                //     notifku = 'Kode berkas tidak boleh kosong'
                // } else
                if (jenis == null || jenis == '') {
                    notifku = 'Jenis berkas tidak boleh kosong'
                } else if (nama == null || nama == '') {
                    notifku = 'Nama berkas tidak boleh kosong'
                } else if (deskripsi == null || deskripsi == '') {
                    notifku = 'Deskripsi tidak boleh kosong'
                } else if (status == null || status=='') {
                    notifku = 'Keterangan Tidak Boleh Kosong'
                }else if(format==0){
                    notifku = 'Format File Tidak Boleh Kosong'
                }else{
                    notifku = 'success';
                }
                return notifku;
            }

            function EditBerkas()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/Berkas/EditBerkas') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#IdBatch').val(null)
                            $('#form-fakultas').trigger('reset');
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Berkas Tidak Ditemukan', 'error')
                            }else{
                                $('#IdBerkas').val(data.IdBerkas)
                                // $('#kodeberkas').val(data.berkas.KodeBerkas)
                                $('#jenis').val(data.berkas.IdJenis).trigger('change')
                                $('#nama').val(data.berkas.nama_berkas)
                                $('#deskripsi').val(data.berkas.deskripsi)
                                $('#status').val(data.berkas.keterangan).trigger('change')
                                let format = data.berkas.formatfile
                                if(format!=null){
                                    $('#formatfile').val(format.split(', ')).trigger('change')
                                }else{
                                    $('#formatfile').val('').trigger('change')
                                }
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteBerkas()
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
                                url: '{!! url('admin/MasterData/Berkas/Status') !!}' + '/' + params + '/' + status,
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

            function btn_reset()
            {
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdBerkas').val(null)
                    $('#form-fakultas').trigger('reset');
                    $("#example2").DataTable().ajax.reload();
                    $('#jenis').val('').trigger('change')
                    $('#formatfile').val('').trigger('change')
                });
            }

        });
    </script>
@endsection

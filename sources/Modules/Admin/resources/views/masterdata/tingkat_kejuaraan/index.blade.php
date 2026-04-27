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
                                        <input type="hidden" id="IdTingkat" name="IdTingkat" value="">

                                        <div class="form-group mb-3">
                                            <label for="batch">Tingkat Kejuaraan</label>
                                            <input type="text" id="tingkat" name="tingkat" placeholder="Nama Tingkat" class="form-control">
                                        </div>
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
                                            <th>Nama Tingkat Kejuaraan</th>
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
                tabelTingkat()
                submitTingkat()
                btn_reset()
            }

            function tabelTingkat()
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
                        url: '{!! route('admin.TingkatKejuaraan.Tabel') !!}',
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
                        EditTingkat()
                        DeleteTingkat()
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

            function submitTingkat()
            {
                $('#submit-batch').click(function (e) {
                    e.preventDefault();
                    let tingkat = $('#tingkat').val()
                    if(tingkat==null||tingkat==''){
                        notifalert('Information','Nama Tingkat Kejuaraan Tidak Boleh Kosong !','warning')
                    }else{
                        let dataku = $('#form-fakultas').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Tingkat Kejuaraan Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.TingkatKejuaraan.Store')}}",
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
                                            $('#submit-batch').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-batch').prop('disabled', false)
                                            $('#btn-reset').trigger('click')
                                        });
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

            function EditTingkat()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/TingkatKejuaraan/EditTingkat') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-reset').trigger('click')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Berkas Tidak Ditemukan', 'error')
                            }else{
                                $('#IdTingkat').val(data.tingkat.id)
                                $('#tingkat').val(data.tingkat.tingkat_kejuaraan)
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteTingkat()
            {
                $('.btn_delete').click(function(e) {
                    // e.preventDefault()
                    let params = $(this).data('id')
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
                                url: '{!! url('admin/MasterData/TingkatKejuaraan/Delete') !!}' + '/' + params,
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
                    $('#IdTingkat').val(null)
                    $('#tingkat').val(null)
                    $("#example2").DataTable().ajax.reload();
                });
            }

        });
    </script>
@endsection

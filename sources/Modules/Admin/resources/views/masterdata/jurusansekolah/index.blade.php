@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
<style>
.table-responsive {
    width: 100% !important;
    overflow-x: auto;
}
#example2 {
    width: 100% !important;
    table-layout: auto;
}
</style>
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
                                    <form id="form-fakultas" method="POST" action="{{route('admin.JurusanSekolah.Store')}}">
                                        @csrf
                                        <input type="hidden" id="IdSekolah" name="IdSekolah" value="">
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="sekolah">Sekolah</label>
                                            <input type="text" id="sekolah" name="sekolah" placeholder="Sekolah" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="jurusan">Jurusan Sekolah</label>
                                            <input type="text" id="jurusan" name="jurusan" placeholder="Jurusan Sekolah" class="form-control" required>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success float-right" style="margin-left:10px;"> <i class="fas fa-paper-plane"></i> Submit</button>
                                            <button id="btn-reset" class="btn btn-warning float-right">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive" style="margin-top: 20px;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Sekolah</th>
                                            <th>Jurusan Sekolah</th>
                                            <th>Aktif</th>
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

            let otable;
            // loadEvent()
            // Tunda tabel hingga layout siap
            setTimeout(() => {
                loadEvent()
            }, 1000);

            function loadEvent(){
                tabelSekolah()
                btn_reset()
            }

            function tabelSekolah(){
                otable = $('#example2').DataTable({
                    destroy: true,
                    processing: true,
                    paging: false,
                    scrollX: true,
                    scrollY: '500px',
                    scrollCollapse: true,
                    serverSide: true,
                    searchDelay: 500,
                    responsive: false,
                    order: [[1,'asc']],
                    ajax: {
                        url: '{!! route('admin.JurusanSekolah.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'sekolah'
                        },
                        {
                            data: 'jurusan'
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
                        EditSekolah()
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

            function EditSekolah(){
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/JurusanSekolah/EditSekolah') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#IdSekolah').val(null)
                            $('#sekolah').val(null)
                            $('#jurusan').val(null)
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Sekolah Tidak Ditemukan', 'error')
                            }else{
                                $('#IdSekolah').val(data.IdSekolah)
                                $('#sekolah').val(data.sekolah.sekolah)
                                $('#jurusan').val(data.sekolah.jurusan_sekolah)
                            }
                        }
                    });
                    return false;
                });
            }

            function btn_reset(){
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdSekolah').val(null)
                    $('#sekolah').val(null)
                    $('#jurusan').val(null)
                });
            }

        });
    </script>
@endsection

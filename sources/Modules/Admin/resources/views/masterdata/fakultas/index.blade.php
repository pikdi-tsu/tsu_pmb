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
                </div><div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Master Data</li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div></div></div></div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{ $menu }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6"> <form id="form-fakultas" method="POST" action="{{route('admin.fakultas.Store')}}">
                                        @csrf
                                        <input type="hidden" id="IdFakultas" name="IdFakultas" value="">
                                        <div class="form-group mb-3">
                                            <label for="kodefakultas">Kode Fakultas   <code>*Otomatis Generate</code></label>
                                            <input type="text" id="kdfakultas" name="kdfakultas" class="form-control" value="{{$kdfakultas}}" readonly required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="namafakultas">Nama Fakultas</label>
                                            <input type="text" id="namafakultas" name="namafakultas" placeholder="Nama Fakultas" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="singkatanfakultas">Singkatan Fakultas</label>
                                            <input type="text" id="singkatanfakultas" name="singkatanfakultas" placeholder="Singkatan Fakultas" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="format_nim">Format NIM Fakultas</label>
                                            <input type="text" id="format_nim" name="format_nim" placeholder="Contoh: A, B, dll (Max 5 Karakter)" class="form-control" maxlength="5">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success float-right" style="margin-left:10px;"> <i class="fas fa-paper-plane"></i> Submit</button>
                                            <button id="btn-reset" class="btn btn-warning float-right">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 20px;">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Fakultas</th>
                                            <th>Nama Fakultas</th>
                                            <th>Singkatan</th>
                                            <th>Format NIM</th> <th>Aktif</th>
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
                </div>
            </div></div>
    @endsection
@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            setTimeout(() => {
                loadEvent()
            }, 1000);

            function loadEvent(){
                tabelFakultas()
                btn_reset()
            }

            function tabelFakultas(){
                let otable = $('#example2').DataTable({
                    destroy: true,
                    processing: true,
                    paging: false,
                    scrollX: true,
                    scrollY: '500px',
                    scrollCollapse: true,
                    serverSide: true,
                    searchDelay: 500,
                    order: [],
                    ajax: {
                        url: '{!! route('admin.fakultas.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kode'
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'singkatan'
                        },
                        // TAMBAHAN: Kolom Format NIM
                        {
                            data: 'format_nim'
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
                        EditFakultas()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger: "hover"
                    });
                    $('[data-tooltip="tooltip"]').tooltip({
                        trigger: "hover"
                    });
                });

                // setTimeout(function () {
                //     otable.columns.adjust().draw(false);
                // }, 1000); // ulangi sekali lagi untuk jaga-jaga
            }

            function EditFakultas(){
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/Fakultas/EditFakultas') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#IdFakultas').val(null)
                            $('#kdfakultas').val(null)
                            $('#namafakultas').val(null)
                            $('#singkatanfakultas').val(null)
                            $('#format_nim').val(null) // Tambahan reset
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Fakultas Tidak Ditemukan', 'error')
                            }else{
                                $('#IdFakultas').val(data.IdFakultas)
                                $('#kdfakultas').val(data.fakultas.KodeFakultas)
                                $('#namafakultas').val(data.fakultas.namafakultas)
                                $('#singkatanfakultas').val(data.fakultas.singkatan)
                                // TAMBAHAN: Set nilai format NIM
                                $('#format_nim').val(data.fakultas.format_nim)
                            }
                        }
                    });
                    return false;
                });
            }

            function btn_reset(){
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdFakultas').val(null)
                    $('#kdfakultas').val('{{$kdfakultas}}')
                    $('#namafakultas').val(null)
                    $('#singkatanfakultas').val(null)
                    $('#format_nim').val(null) // Tambahan reset
                });
            }

        });
    </script>
@endsection
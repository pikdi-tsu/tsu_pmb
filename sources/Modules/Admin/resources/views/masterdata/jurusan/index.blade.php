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
                                <div class="col-md-6"> <form id="form-fakultas" method="POST" action="{{route('admin.Jurusan.Store')}}">
                                        @csrf
                                        <input type="hidden" id="IdJurusan" name="IdJurusan" value="">
                                        <div class="form-group mb-3">
                                            <label for="kdjurusan">Kode Jurusan <code>*Otomatis Generate</code></label>
                                            <input type="text" id="kdjurusan" name="kdjurusan" class="form-control" value="{{$kdjurusan}}" readonly required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="jenjang">Jenjang</label>
                                            <select class="form-control select2" id="jenjang" name="jenjang" required>
                                                <option value="-1" selected disabled>-- Pilih Jenjang --</option>
                                                @foreach ($jenjang as $i)
                                                    <option value="{{$i->id}}">{{$i->jenjang}} - {{$i->nama_jenjang}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="jurusansekolah">Asal Jurusan Sekolah</label>
                                            <select class="form-control select2" id="jurusansekolah" name="jurusansekolah" required>
                                                <option value="-1" selected disabled>-- Pilih Asal Jurusan Sekolah --</option>
                                                @foreach ($sekolah as $i)
                                                    <option value="{{$i->id}}">{{$i->sekolah}} - {{$i->jurusan_sekolah}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="fakultas">Fakultas</label>
                                            <select class="form-control select2" id="fakultas" name="fakultas" required>
                                                <option value="-1" selected disabled>-- Pilih Fakultas --</option>
                                                @foreach ($fakultas as $i)
                                                    <option value="{{$i->KodeFakultas}}">{{$i->singkatan}} - {{$i->namafakultas}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="namajurusan">Nama Jurusan</label>
                                            <input type="text" id="namajurusan" name="namajurusan" placeholder="Nama Jurusan" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="format_nim">Format NIM Prodi</label>
                                            <input type="text" id="format_nim" name="format_nim" placeholder="Contoh: 001, 002, dll (Max 5 Karakter)" class="form-control" maxlength="5">
                                        </div>

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
                                            <th>Kode Jurusan</th>
                                            <th>Nama Jurusan</th>
                                            <th>Fakultas</th>
                                            <th>Jenjang</th>
                                            <th>Jurusan Sekolah</th>
                                            <th>Format NIM</th> <th>Aktif</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

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

            $('.select2').select2()

            let otable;

            setTimeout(() => {
                loadEvent()
            }, 1000);

            function loadEvent(){
                tabelJurusan()
                btn_reset()
            }

            function tabelJurusan(){
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
                        url: '{!! route('admin.Jurusan.Tabel') !!}',
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
                            data: 'fakultas'
                        },
                        {
                            data: 'jenjang'
                        },
                        {
                            data: 'jurusansekolah'
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
                        EditJurusan()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function EditJurusan(){
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/Jurusan/EditJurusan') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#IdJurusan').val(null)
                            $('#kdjurusan').val(null)
                            $('#jenjang').val('-1').trigger('change')
                            $('#jurusansekolah').val('-1').trigger('change')
                            $('#fakultas').val('-1').trigger('change')
                            $('#namajurusan').val(null)
                            $('#format_nim').val(null) // Tambahan reset
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Jurusan Tidak Ditemukan', 'error')
                            }else{
                                $('#IdJurusan').val(data.IdJurusan)
                                $('#kdjurusan').val(data.jurusan.KodeJurusan)
                                $('#jenjang').val(data.jurusan.idjenjang).trigger('change')
                                $('#jurusansekolah').val(data.jurusan.idjurusansekolah).trigger('change')
                                $('#fakultas').val(data.jurusan.idfakultas).trigger('change')
                                $('#namajurusan').val(data.jurusan.jurusan)
                                // TAMBAHAN: Set nilai format NIM
                                $('#format_nim').val(data.jurusan.format_nim)
                            }
                        }
                    });
                    return false;
                });
            }

            function btn_reset(){
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdJurusan').val(null)
                    $('#kdjurusan').val('{{$kdjurusan}}')
                    $('#jenjang').val('-1').trigger('change')
                    $('#jurusansekolah').val('-1').trigger('change')
                    $('#fakultas').val('-1').trigger('change')
                    $('#namajurusan').val(null)
                    $('#format_nim').val(null) // Tambahan reset
                });
            }

        });
    </script>
@endsection

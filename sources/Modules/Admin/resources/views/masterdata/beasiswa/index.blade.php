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
                                    <form id="form-fakultas" method="POST" action="#">
                                        @csrf
                                        <input type="hidden" id="IdBeasiswa" name="IdBeasiswa" value="">
                                        <div class="form-group mb-3">
                                            <label for="jalur">Jalur Pendaftaran</label>
                                            <select class="form-control select2" id="jalur" name="jalur" required>
                                                <option value="" selected disabled>-- Pilih Jalur Beasiswa --</option>
                                                @foreach ($jalur as $i)
                                                    <option value="{{$i->id}}">{{$i->KodeJenis}} - {{$i->jenis_pendaftaran}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="kode">Jenis Beasiswa</label>
                                            <input type="text" id="jenis" name="jenis" placeholder="Jenis Beasiswa" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="tingkat">Tingkat</label>
                                            <select class="form-control select2" id="tingkat" name="tingkat" required>
                                                <option value="" selected disabled>-- Pilih Tingkat --</option>
                                                @foreach ($tingkat as $i)
                                                    <option value="{{$i->id}}">{{$i->tingkat_kejuaraan}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="namajenis">Juara</label>
                                            <input type="text" id="juara" name="juara" placeholder="Juara ke" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="biaya_daftar">Durasi Beasiswa D3 <code>*Dalam Semester</code></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        D3
                                                    </span>
                                                </div>
                                                <input type="number" value="0" min="0" max="6" class="form-control" id="biaya_d3" name="biaya_d3" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="biaya_daftar">Durasi Beasiswa S1 <code>*Dalam Semester</code></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        S1
                                                    </span>
                                                </div>
                                                <input type="number" value="0" min="0" max="8" class="form-control" id="biaya_s1" name="biaya_s1" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="biaya_daftar">Potongan <code>*Dalam Persen</code></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        %
                                                    </span>
                                                </div>
                                                <input type="number" value="0" min="0" max="100" class="form-control" id="potongan" name="potongan" required>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="form-group">
                                            <button type="button" id="submit-jalur" class="btn btn-success float-right" style="margin-left:10px;"> <i class="fas fa-paper-plane"></i> Submit</button>
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
                                            <th>Jalur Pendaftaran</th>
                                            <th>Jenis Beasiswa</th>
                                            <th>Tingkat Prestasi</th>
                                            <th>Juara Ke</th>
                                            <th>Durasi Beasiswa (S1)</th>
                                            <th>Durasi Beasiswa (D3)</th>
                                            <th>Potongan (%)</th>
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

            loadEvent()

            function loadEvent(){
                tabelBeasiswa()
                btn_reset()
                submitbeasiswa()
            }

            function tabelBeasiswa(){
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
                    order: [[1,'asc']],
                    ajax: {
                        url: '{!! route('admin.Beasiswa.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jalur'
                        },
                        {
                            data: 'beasiswa'
                        },
                        {
                            data: 'tingkat'
                        },
                        {
                            data: 'juara'
                        },
                        {
                            data: 'durasi_s1'
                        },
                        {
                            data: 'durasi_d3'
                        },
                        {
                            data: 'potongan'
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
                        EditBeasiswa()
                        DeleteBeasiswa()
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

            function validationBeasiswa()
            {
                let jalur       = $('#jalur').val()
                let jenis       = $('#jenis').val()
                let biaya3      = $('#biaya_d3').val()
                let biaya1      = $('#biaya_s1').val()
                let potongan    = $('#potongan').val()

                let notifku = ''
                if (jalur == null || jalur == '') {
                    notifku = 'Jalur Pendaftaran tidak boleh kosong'
                } else if (jenis == null || jenis == '') {
                    notifku = 'Jenis Beasiswa tidak boleh kosong'
                } else if (biaya3 == 0) {
                    notifku = 'Durasi Beasiswa D3 tidak boleh kosong'
                } else if (biaya1 == 0) {
                    notifku = 'Durasi Beasiswa S1 Tidak Boleh Kosong'
                } else if(potongan==0){
                    notifku = 'potongan Tidak Boleh Kosong'
                } else{
                    notifku = 'success';
                }
                return notifku;
            }

            function submitbeasiswa()
            {
                $('#submit-jalur').click(function (e) {
                    e.preventDefault();
                    let checkvalidation = validationBeasiswa();
                    if(checkvalidation != 'success'){
                        notifalert('Information',checkvalidation,'warning')
                    }else{
                        let dataku = $('#form-fakultas').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Beasiswa Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.Beasiswa.Store')}}",
                                    data: dataku,
                                    dataType: "JSON",
                                    beforeSend: function(response) {
                                        $('#submit-jalur').html('<i class="fas fa-hourglass"></i> Please Wait')
                                        $('#submit-jalur').prop('disabled', true)
                                        $('#loading').show()
                                    },
                                    success: function(data) {
                                        $('#loading').hide()
                                        Swal.fire({
                                            title: data.title,
                                            text: data.message,
                                            icon: data.status
                                        }).then((result) => {
                                            $('#IdJenis').val(null)
                                            $('#submit-jalur').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-jalur').prop('disabled',false)
                                            $('#btn-reset').trigger('click')
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
                                            $('#submit-jalur').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-jalur').prop('disabled',false)
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

            function EditBeasiswa(){
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/Beasiswa/EditBeasiswa') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-reset').trigger('click')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Beasiswa Tidak Ditemukan', 'error')
                            }else{
                                $('#IdBeasiswa').val(data.IdBeasiswa)
                                $('#jalur').val(data.bea.IdJalur).trigger('change')
                                $('#jenis').val(data.bea.jenis_beasiswa)
                                $('#tingkat').val(data.bea.idtingkat).trigger('change')
                                $('#juara').val(data.bea.juara_ke)
                                $('#biaya_d3').val(data.bea.durasi_d3)
                                $('#biaya_s1').val(data.bea.durasi_s1)
                                $('#potongan').val(data.bea.persen_potongan)
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteBeasiswa()
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
                                url: '{!! url('admin/MasterData/Beasiswa/Status') !!}' + '/' + params + '/' + status,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Information',
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

            function btn_reset(){
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdBeasiswa').val(null);
                    $('#form-fakultas').trigger('reset');
                    $('#jalur').val('').trigger('change')
                    $('#tingkat').val('').trigger('change')
                    $("#example2").DataTable().ajax.reload();
                });
            }

        });
    </script>
@endsection

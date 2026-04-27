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
                                        <input type="hidden" id="IdUKT" name="IdUKT" value="">
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="batch">Batch Pendaftaran</label>
                                            <select class="form-control select2" id="batch" name="batch" required>
                                                <option value="" selected disabled>-- Pilih Batch Pendaftaran --</option>
                                                @foreach ($batch as $i)
                                                <option value="{{$i->id}}">{{$i->kode_batch}} - {{$i->nama_batch}} - {{$i->tahun_akademik}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="jalur">Jalur Pendaftaran</label>
                                            <select class="form-control select2" id="jalur" name="jalur" required>
                                                <option value="" selected disabled>-- Pilih Jalur Pendaftaran --</option>
                                                @foreach ($jalur as $q)
                                                <option value="{{$q->id}}">{{$q->KodeJenis}} - {{$q->jenis_pendaftaran}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="prodi">Program Studi</label>
                                            <select class="form-control select2" id="prodi" name="prodi[]" multiple="multiple" data-placeholder="-- Pilih Program Studi --" style="width: 100%;" required>
                                                @foreach ($prodi as $p)
                                                <option value="{{$p->id}}">{{$p->fakultas->singkatan}} - {{$p->jenjang->jenjang}} - {{$p->jurusan}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="biaya_ukt">Tarif UKT</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        Rp
                                                    </span>
                                                </div>
                                                <input type="number" value="0" min="0" class="form-control" id="biaya_ukt" name="biaya_ukt" required>
                                            </div>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="tahun">Keterangan</label>
                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Keterangan"></textarea>
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
                                            <th>Batch Pendaftaran</th>
                                            <th>Jalur Pendaftaran</th>
                                            <th>Prodi/Jurusan</th>
                                            <th>Tarif UKT</th>
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
                tabelUKT()
                submitUKT()
                btn_reset()
                // formatRP()
            }

            function tabelUKT()
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
                        url: '{!! route('admin.TarifUKT.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'batch'
                        },
                        {
                            data: 'jalur'
                        },
                        {
                            data: 'prodi'
                        },
                        {
                            data: 'tarif'
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
                        EditUKT()
                        DeleteUKT()
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

            function submitUKT()
            {
                $('#submit-batch').click(function (e) {
                    e.preventDefault();
                    let validation = validationUKT()
                    if(validation != 'success'){
                        notifalert('Information',validation,'warning')
                    }else{
                        let dataku = $('#form-fakultas').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Tarif UKT Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.TarifUKT.Store')}}",
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
                                            $('#btn-reset').trigger('click')
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

            function validationUKT()
            {
                let batch  = $('#batch').val()
                let jalur  = $('#jalur').val()
                let prodi  = $('#prodi').val()
                let ket    = $('#keterangan').val()

                let notifku = ''
                if (batch == null || batch == '') {
                    notifku = 'Batch Pendaftaran tidak boleh kosong'
                } else if (jalur == null || jalur == '') {
                    notifku = 'Jalur Pendaftaran tidak boleh kosong'
                } else if (prodi==0) {
                    notifku = 'Prodi/Jurusan tidak boleh kosong'
                } else if (ket == null || ket=='') {
                    notifku = 'Keterangan Tidak Boleh Kosong'
                } else{
                    notifku = 'success';
                }
                return notifku;
            }

            function EditUKT()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/TarifUKT/EditUKT') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-reset').trigger('click')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Tarif UKT Tidak Ditemukan', 'error')
                            }else{
                                $('#IdUKT').val(data.IdUKT)
                                $('#batch').val(data.ukt.idbatch).trigger('change')
                                $('#jalur').val(data.ukt.idjalur).trigger('change')
                                $('#prodi').val(data.ukt.idjurusan).trigger('change')
                                $('#biaya_ukt').val(data.ukt.biaya_ukt)
                                $('#keterangan').val(data.ukt.keterangan)
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteUKT()
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
                                url: '{!! url('admin/MasterData/TarifUKT/Status') !!}' + '/' + params + '/' + status,
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

            function btn_reset()
            {
                $('#btn-reset').click(function (e) {
                    e.preventDefault();
                    $('#IdUKT').val(null)
                    $('#form-fakultas').trigger('reset');
                    $("#example2").DataTable().ajax.reload();
                    $('#batch').val('').trigger('change')
                    $('#jalur').val('').trigger('change')
                    $('#prodi').val('').trigger('change')
                });
            }

            function formatRP()
            {
                $('#biaya_ukt').on('keyup', function(){
                    $(this).val(formatRupiah($(this).val()));
                });

            }

            function formatRupiah(angka) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split   = number_string.split(','),
                    sisa    = split[0].length % 3,
                    rupiah  = split[0].substr(0, sisa),
                    ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            }

        });
    </script>
@endsection

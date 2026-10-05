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
                                        <input type="hidden" id="IdBatch" name="IdBatch" value="">
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="kodebatch">Kode Batch</label>
                                            <input type="text" id="kodebatch" name="kodebatch" placeholder="Kode Batch" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="batch">Nama Batch</label>
                                            <input type="text" id="batch" name="batch" placeholder="Nama Batch" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="tahun">Tahun Ajaran</label>
                                            <input type="text" id="tahun" name="tahun" placeholder="Input Tahun Ajaran" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="tglmulai">Tgl Mulai</label>
                                            <input type="date" id="tglmulai" name="tglmulai" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="tglselesai">Tgl Selesai</label>
                                            <input type="date" id="tglselesai" name="tglselesai" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="kuota">Kuota</label>
                                            <input type="number" value="0" id="kuota" name="kuota" placeholder="Kuota Pendaftar" class="form-control" required>
                                        </div>
                                        <!-- Nama Jenis -->
                                        <div class="form-group mb-3">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="" selected disabled>-- Pilih Status --</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Non Aktif</option>
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
                                            <th>Kode Batch</th>
                                            <th>Nama Batch</th>
                                            <th>Tahun Akademik</th>
                                            <th>Tgl Mulai</th>
                                            <th>Tgl Selesai</th>
                                            <th>Kuota</th>
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
                tabelBatch()
                submitBatch()
                btn_reset()
            }

            function tabelBatch()
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
                        url: '{!! route('admin.BatchPendaftaran.Tabel') !!}',
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
                            data: 'tahun'
                        },
                        {
                            data: 'mulai'
                        },
                        {
                            data: 'selesai'
                        },
                        {
                            data: 'kuota'
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
                        EditBatch()
                        DeleteBatch()
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

            function submitBatch()
            {
                $('#submit-batch').click(function (e) {
                    e.preventDefault();
                    let validation = validationBatch()
                    if(validation != 'success'){
                        notifalert('Information',validation,'warning')
                    }else{
                        let dataku = $('#form-fakultas').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Pertanyaan Pilihan Ganda Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.BatchPendaftaran.Store')}}",
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

            function validationBatch()
            {
                let kode    = $('#kodebatch').val()
                let nama    = $('#batch').val()
                let tahun   = $('#tahun').val()
                let mulai   = $('#tglmulai').val()
                let selesai = $('#tglselesai').val()
                let kuota   = $('#kuota').val()
                let status  = $('#status').val()

                let startDate = new Date(mulai);
                let endDate = new Date(selesai);
                let notifku = ''
                if (kode == null || kode == '') {
                    notifku = 'Kode batch tidak boleh kosong'
                } else if (nama == null || nama == '') {
                    notifku = 'Nama batch tidak boleh kosong'
                } else if (tahun == null || tahun == '') {
                    notifku = 'Tahun akademik tidak boleh kosong'
                } else if (mulai == null || mulai == '') {
                    notifku = 'Tanggal mulai tidak boleh kosong'
                } else if (selesai == null || selesai == '') {
                    notifku = 'Tanggal selesai tidak boleh kosong'
                } else if (kuota == 0) {
                    notifku = 'Kuota Harus Lebih Dari 0'
                } else if (status == null || status=='') {
                    notifku = 'Status Tidak Boleh Kosong'
                }else if(endDate < startDate){
                    notifku = 'Tanggal selesai tidak boleh sebelum tanggal mulai!'
                }else{
                    notifku = 'success';
                }
                return notifku;
            }

            function EditBatch(){
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/BatchPendaftaran/EditBatch') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#IdBatch').val(null)
                            $('#form-fakultas').trigger('reset');
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Batch Tidak Ditemukan', 'error')
                            }else{
                                $('#IdBatch').val(data.IdBatch)
                                $('#kodebatch').val(data.batch.kode_batch)
                                $('#batch').val(data.batch.nama_batch)
                                $('#tahun').val(data.batch.tahun_akademik)
                                $('#tglmulai').val(data.batch.tglmulai)
                                $('#tglselesai').val(data.batch.tglselesai)
                                $('#kuota').val(data.batch.kuota)
                                $('#status').val(data.batch.isactive).trigger('change')
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteBatch(){
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
                                url: '{!! url('admin/MasterData/BatchPendaftaran/Status') !!}' + '/' + params + '/' + status,
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
                                        // window.location.reload();
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
                    $('#IdBatch').val(null)
                    $('#form-fakultas').trigger('reset');
                    $("#example2").DataTable().ajax.reload();
                });
            }

        });
    </script>
@endsection

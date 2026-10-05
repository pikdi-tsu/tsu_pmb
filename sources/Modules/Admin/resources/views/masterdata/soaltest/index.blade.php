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
                            <h5 class="m-0">
                                {{ $menu }}
                                <a href="#" class="btn btn-success btn-sm float-right" id="show-upload">
                                    <i class="fa fa-upload"></i>
                                     Upload Soal
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6"> <!-- Ubah lebar form di sini -->
                                    <form class="form-horizontal" id="form-banksoal-pilgan" method="POST" action="#">
                                        @csrf
                                        <input type="hidden" id="idku" name="idku" value="">
                                        <!-- Nama Jenis -->
                                        <!-- Soal Pilgan -->
                                        <div class="form-group mb-3">
                                            <label class="h5 fw-semibold font-heading mb-0">Kode Soal</label>
                                            <input type="text" name="kodesoal" id="kodesoal" placeholder="Input Kode Soal" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="h5 fw-semibold font-heading mb-0">Pertanyaan</label>
                                            <input type="text" name="pertanyaan" id="pertanyaan" placeholder="Input Pertanyaan" class="form-control" required="required" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label class="h5 fw-semibold font-heading mb-0">Pilihan & Score</label>
                                            <div class="form-group row">
                                                <div class="col-sm-1">
                                                    <input type="text" name="abjad[]" style="text-transform:uppercase" class="form-control abjadku" maxlength="1" required>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pilihan[]" class="form-control" placeholder="Input Jawaban" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="number" name="score[]" class="form-control" placeholder="score" required>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-primary btn-md" id="add-pilgan"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="pilganku"></div>
                                        <div class="form-group">
                                            <label class="h5 fw-semibold font-heading mb-0">Kunci Jawaban</label>
                                            <input type="text" name="kuncijawaban" style="text-transform:uppercase;max-width: 7%;" id="kuncijawaban"class="form-control" maxlength="1" autocomplete="off" value="" required>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success float-right" style="margin-left:10px;" id="submit-pilgan">Submit</button>
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
                                            <th>Kode Soal</th>
                                            <th>Soal</th>
                                            <th>Jumlah Pilgan</th>
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

<div class="modal fade" id="modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="#" id="master_dataku" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal">Detail Pertanyaan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-detail-banksoal">
                        <div class="form-group">
                            <label class="control-label">Pertanyaan</label>
                            <input type="text" id="d-pertanyaanku" class="form-control " value="" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pilihan & Score</label>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <input type="text" name="d_abjadku[]" class="form-control uppercase" placeholder="A" maxlength="1" readonly>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="d_pilihanku[]" class="form-control" placeholder="A. nama pilihan" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" name="d_scoreku[]" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div id="d-pilganku"></div>
                        <div class="form-group">
                            <label class="control-label">Kunci Jawaban</label>
                            <div class="col-sm-2">
                                <input type="text" name="d_kuncijawabanku" id="d-kuncijawabanku" class="form-control uppercase" value="" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-upload">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="judul_modal">Upload Question Bank</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <a href="{{ $linkfile }}" class="btn btn-success rounded-pill py-9">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download Template Master Soal
                </a>
                <span></span>
                <form class="form-horizontal" id="form-banksoal-pilgan" method="POST" action="#" style="margin-top: 20px;">
                    @csrf
                    <div class="form-group">
                        <div class="input-group col-sm-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_soal" name="file_soal" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required="required">
                                <label class="custom-file-label" for="photoprofile">Pilih File Soal</label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-saveupload">Upload</button>
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

            loadEvent()

            function loadEvent(){
                tambahdata()
                submit_pilgan()
                tabelSoal()
                btn_reset()
                showupload()
                save_upload()
            }

            function tambahdata(){
                current = 1
                $('#add-pilgan').click(function(e) {
                    current++;
                    var character = String.fromCharCode(64 + current);
                    $('#pilganku').append(
                        `<div class="form-group pilgan-`+current+`">
                            <div class="form-group row">
                                <div class="col-sm-1">
                                    <input type="text" name="abjad[]" class="form-control uppercase abjadku" style="text-transform:uppercase" maxlength="1" required>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan[]" class="form-control" placeholder="Input Jawaban" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="score[]" class="form-control" placeholder="score" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" data-id="`+current+`" class="btn btn-danger btn-md btn_remove" title="Hapus Pilihan"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>`
                    );
                    removefield()
                });
            }

            function removefield(){
                $('.btn_remove').click(function(e) {
                    let idku = $(this).data('id')
                    $('.pilgan-'+idku).remove();
                });
            }

            function validation_pilgan(){
                let abjad = $("#form-banksoal-pilgan input[name='abjad[]']")
                    .map(function() {
                        if ($(this).val() == '') {
                            return;
                        } else {
                            return $(this).val().toUpperCase();
                        }
                    }).get();
                let values = $("#form-banksoal-pilgan input[name='pilihan[]']")
                    .map(function() {
                        if ($(this).val() == '') {
                            return;
                        } else {
                            return $(this).val();
                        }
                    }).get();
                let score = $("#form-banksoal-pilgan input[name='score[]']")
                    .map(function() {
                        if ($(this).val() == '') {
                            return;
                        } else {
                            return $(this).val();
                        }
                    }).get();
                let duplicate = checkduplicate(values);
                let duplicateabjad = checkduplicate(abjad);
                let type = $('#form-banksoal-pilgan #typeku').val();
                let pertanyaan = $('#form-banksoal-pilgan #pertanyaan').val();
                let kunci = $('#form-banksoal-pilgan #kuncijawaban').val().toUpperCase();
                let kodesoal = $('#form-banksoal-pilgan #kodesoal').val();
                console.log(kunci,abjad)
                let notifku = ''
                if (kodesoal == null || kodesoal == '') {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Kode Soal Tidak Boleh Kosong'
                } else if (pertanyaan == null || pertanyaan == '') {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Pertanyaan Tidak Boleh Kosong'
                } else if (abjad == null || abjad == [''] || abjad == "" || values == null || values ==
                    [''] || values == "" || score == null || score == [''] || score == "") {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Pilihan/Score Tidak Boleh Kosong'
                } else if (kunci == null || kunci == '') {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Kunci Jawaban Tidak Boleh Kosong'
                } else if (duplicateabjad.length >= 1) {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Pilihan Tidak Boleh Sama'
                } else if (duplicate.length >= 1) {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Nama Pilihan Tidak Boleh Sama'
                } else if (abjad.includes(kunci) == false) {
                    $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                    $('#form-banksoal-pilgan #submit-pilgan').prop('disabled', false)
                    notifku = 'Kunci Jawaban Tidak Sama Dengan Pilihan'
                }else{
                    notifku = 'success';
                }
                return notifku;
            }

            function checkduplicate(params){
                let findDuplicates = arr => arr.filter((item, index) => arr.indexOf(item) != index)
                return findDuplicates(params);
            }

            function submit_pilgan(){
                $('#submit-pilgan').click(function(e) {
                    let validasi = validation_pilgan()
                    if(validasi != 'success'){
                        alert(validasi)
                    }else{
                        let dataku = $('#form-banksoal-pilgan').serialize()
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
                                    url: "{{route('admin.Test.Store')}}",
                                    data: dataku,
                                    dataType: "JSON",
                                    beforeSend: function(response) {
                                        $('#submit-pilgan').html('<i class="fas fa-hourglass"></i> Please Wait')
                                        $('#submit-pilgan').prop('disabled', true)
                                        $('#loading').show()
                                    },
                                    success: function(data) {
                                        $('#loading').hide()
                                        Swal.fire({
                                            title: data.title,
                                            text: data.message,
                                            icon: (data.status != 'failed') ? 'success' :
                                                'error'
                                        }).then((result) => {
                                            $('#idku').val(null)
                                            $('#submit-pilgan').html('Submit')
                                            $('#submit-pilgan').prop('disabled',false)
                                            $('#form-banksoal-pilgan').trigger('reset');
                                            $("#pilganku").empty();
                                            $("#example2").DataTable().ajax.reload();
                                            current = 1;
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
                                            $('#form-banksoal-pilgan #submit-pilgan').html(' Submit')
                                            $('#form-banksoal-pilgan #submit-pilgan').prop('disabled',false)
                                            current = 1;
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
                })
            }

            function tabelSoal(){
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
                        url: '{!! route('admin.Test.Tabel') !!}',
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
                            data: 'soal'
                        },
                        {
                            data: 'jumlah'
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
                        EditSoal()
                        detail_soal()
                        btndelete_event()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function EditSoal(){
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/MasterData/SoalTest/EditSoal') !!}'+'/'+params+'/edit',
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#pilganku').empty();
                            $('#kodesoal').val(null)
                            $('#pertanyaan').val(null);
                            $('#idku').val(null);
                            $("input[name='abjad[]']").val(null);
                            $("input[name='pilihan[]']").val(null);
                            $("input[name='score[]']").val(null);
                            $('#kuncijawaban').val(null)
                            $('#loading').show()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            let hasil = data.hasil
                            if(hasil==1){
                                $('#idku').val(data.master.id_soal);
                                $('#kodesoal').val(data.master.kode_soal)
                                $('#pertanyaan').val(data.master.soal_test);
                                $('#kuncijawaban').val(data.master.jawaban[0].jawaban_benar);
                                let jawab = data.master.jawaban;
                                if (jawab[0].jawaban) {
                                    $("input[name='abjad[]']").val(jawab[0].pilihan);
                                    $("input[name='pilihan[]']").val(jawab[0].jawaban);
                                    $("input[name='score[]']").val(jawab[0].score);
                                }
                                for (let index = 1; index < jawab.length; index++) {
                                    current++
                                    $("input[name='pilihan[]']").first().html('test');
                                    $('#form-banksoal-pilgan #pilganku').append(
                                        `<div class="form-group pilgan-`+current+`">
                                            <div class="form-group row">
                                                <div class="col-sm-1">
                                                    <input type="text" name="abjad[]" class="form-control uppercase" value="`+jawab[index].pilihan+`">
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="pilihan[]" name="pilihan[]" class="form-control"  value="`+jawab[index].jawaban+`">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="number" name="score[]" class="form-control" value="`+jawab[index].score+`">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" data-id="`+current+`" class="btn btn-danger btn-md btn_remove" title="Hapus Pilihan"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        </div>`
                                    );

                                }
                                removefield()
                                $("#example2").DataTable().ajax.reload();
                            }else if(hasil==2){
                                Swal.fire({
                                    title: "Information",
                                    text: "Soal Sedang Digunakan !",
                                    icon: "warning",
                                    buttons: false,
                                    timer: 1500,
                                })
                                $("#example2").DataTable().ajax.reload();
                            }else{
                                Swal.fire({
                                    title: "Error",
                                    text: "Data Tidak Ditemukan ! Silahkan Periksa Kembali",
                                    icon: "error",
                                    buttons: false,
                                    timer: 1500,
                                })
                                $("#example2").DataTable().ajax.reload();
                            }
                        }
                    });
                    return false;
                });
            }

            function detail_soal(){
                $('.btn_detail').click(function(e){
                    let params = $(this).data('id')
                    $('#modal_form').modal('show')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/MasterData/SoalTest/EditSoal') !!}'+'/'+params+'/detail',
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#d-pilganku').html(null);
                            $('#d-pertanyaanku').val(null);
                            $("input[name='d_abjadku[]']").val(null);
                            $("input[name='d_pilihanku[]']").val(null);
                            $("input[name='d_scoreku[]']").val(null);
                            $('#d-kuncijawabanku').val(null)
                            $('#loading').show()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            let soal = data.master.soal_test
                                $('#d-pertanyaanku').val(soal);
                                $('#d-kuncijawabanku').val(data.master.jawaban[0].jawaban_benar);

                                let jawab = data.master.jawaban;

                                if (jawab[0].jawaban) {
                                    $("input[name='d_abjadku[]']").val(jawab[0].pilihan);
                                    $("input[name='d_pilihanku[]']").val(jawab[0].jawaban);
                                    $("input[name='d_scoreku[]']").val(jawab[0].score);
                                }
                                for (let index = 1; index < jawab.length; index++) {
                                    // $("#form-detail-banksoal input[name='pilihanku[]']").first().html('test');
                                    $('#d-pilganku').append(
                                        `<div class="form-group" pilgan-`+index+`">
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <input type="text" name="d_abjadku[]" class="form-control uppercase" value="`+jawab[index].pilihan+`" readonly>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" name="d_pilihanku[]" class="form-control"  value="`+jawab[index].jawaban+`" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="number" name="d_scoreku[]" class="form-control" value="`+jawab[index].score+`" readonly>
                                                </div>
                                            </div>
                                        </div>`
                                    );

                                }
                        }
                    });
                    return false;
                });
            }

            function btndelete_event(){
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
                                url: '{!! url('admin/MasterData/SoalTest/Status') !!}' + '/' + params + '/' + status,
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
                    $('#idku').val(null)
                    $('#kodesoal').val(null)
                    $('#pertanyaan').val(null)
                    $('#pilganku').empty();
                    $("input[name='abjad[]']").val(null);
                    $("input[name='pilihan[]']").val(null);
                    $("input[name='score[]']").val(null);
                    $('#kuncijawaban').val(null)
                    $("#example2").DataTable().ajax.reload();
                });
            }

            function showupload()
            {
                $('#show-upload').click(function (e) {
                    e.preventDefault();
                    $('#file_soal').val(null)
                    $('#modal-upload').modal('show')
                });
            }

            function save_upload()
            {
                $('#btn-saveupload').click(function(e){
                    $('#btn-saveupload').html('Please Wait')
                    $('#btn-saveupload').prop('disabled', true)

                    let fileku = $('#file_soal').prop('files')[0]
                    let form_data = new FormData();
                    form_data.append('fileku', fileku);

                    // POST DATA
                    $.ajax({
                        type: "POST",
                        url: '{!! route("admin.Test.UploadExcel") !!}',
                        processData: false,
                        contentType: false,
                        data: form_data,
                        dataType: "JSON",
                        success: function(data) {
                            Swal.fire({
                                title: 'Information',
                                text: data.message,
                                icon: data.status
                            }).then(() => {
                                $('#btn-saveupload').html('Upload')
                                $('#btn-saveupload').prop('disabled', false)
                                $("#example2").DataTable().ajax.reload();
                                if(data.status!='error'){
                                    $('#modal-upload').modal('hide')
                                }
                            })
                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Information',
                                text: 'Silahkan Periksa Isi File Excel Anda !',
                                icon: 'error'
                            }).then(() => {
                                $('#btn-saveupload').html('Upload')
                                $('#btn-saveupload').prop('disabled', false)
                                // $("#example1").DataTable().ajax.reload();
                            })
                            // $('#modal-uploadnilai').modal('hide')
                        }
                    });
                    return false;

                });
            }

            function alert(params){
                Swal.fire({
                    title: 'Information',
                    text: params,
                    icon: 'warning',
                    buttons: false,
                    timer: 1500
                });
                return;
            }

        });
    </script>
@endsection

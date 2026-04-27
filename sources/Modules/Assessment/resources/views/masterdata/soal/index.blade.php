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
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Master Assessment</li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">
                                {{ $menu }}
                                <button class="btn btn-primary btn-sm float-right" id="btn-tambah"><i class="fas fa-plus"></i> Tambah</button>
                                <button id="btn-batal" class="btn btn-warning btn-sm float-right" style="display: none;"><i class="fas fa-undo"></i>Batal</button>
                                <button id="submit-test" class="btn btn-success float-right btn-sm" style="display: none;margin-right:5px;"> <i class="fas fa-paper-plane"></i> Submit</button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" id="slide-test" style="display: none;"> <!-- Ubah lebar form di sini -->
                                <h5>Soal Test</h5>
                                <form id="form-soal" method="POST" action="#">
                                    @csrf
                                    <input type="hidden" id="IdSoal" name="IdSoal" value="">
                                    <div class="row mt-3">
                                        <!-- Pilih Test -->
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Type Test</label>
                                                <select name="test_type_id" id="test_type_id" class="form-control select2" required>
                                                    <option value="" selected disabled>-- Pilih Test --</option>
                                                    @foreach($testTypes as $test)
                                                        <option value="{{ $test->id }}"data-engine="{{ $test->tipe_engine }}">{{ $test->nama_test }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Pertanyaan -->
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label>Pertanyaan</label>
                                                <textarea name="pertanyaan" id="pertanyaan" class="form-control" rows="1" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <h5>
                                        Option Jawaban
                                        <a href="#" id="btn-add-option" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
                                    </h5>

                                    <div id="option-container"></div>


                                </form>
                                <hr>
                            </div>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Type Test</th>
                                            <th>Soal</th>
                                            <th>Option Jawaban</th>
                                            <th>Status Aktif</th>
                                            <th class="text-center">Action</th>
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
            let engineType = null;

            loadEvent()

            function loadEvent()
            {
                changeTest()
                addOption()
                tabel()
                add()
                batal()
                saveTest()
            }

            function tabel()
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
                        url: '{!! route('admin.mastersoal.tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'type'
                        },
                        {
                            data: 'soal'
                        },
                        {
                            data: 'pilihan'
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
                        edit()
                        aktifNonaktif()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }

            function changeTest()
            {
                $('#test_type_id').on('change', function () {
                    engineType = $(this).find(':selected').data('engine');
                    resetOptions();
                    generateOption();
                });
            }

            function addOption()
            {
                 $('#btn-add-option').on('click', function () {
                    let typetest = $('#test_type_id').val()
                    if(typetest==''||typetest==null){
                        notifalert('Perhatian','Isi Test Terlebih Dahulu','warning')
                    }else{
                        generateOption();
                    }
                });
            }

            function resetOptions()
            {
                $('#option-container').html('');
            }

            function generateOption()
            {
                let index = $('.option-item').length;
                let html = '';
                html += `<div class="option-item border p-2 mt-2">
                            <div class="row">`;
                // Label
                html += `<div class="col-md-8">
                            <input type="text" name="options[${index}][label]"
                                class="form-control" placeholder="Teks Jawaban" required>
                        </div>`;
                // MULTIPLE CHOICE
                if (engineType === 'multiple_choice') {
                    html += `<div class="col-md-2">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio"
                                        name="correct_option" value="${index}">
                                    <label class="form-check-label">
                                        Jawaban Benar
                                    </label>
                                </div>
                            </div>`;
                }
                // SINGLE CHOICE
                if (engineType === 'single_choice') {
                    html += `<div class="col-md-2">
                                <input type="number"
                                    name="options[${index}][nilai]"
                                    class="form-control"
                                    placeholder="Nilai" min="0">
                            </div>`;
                }
                // DISC
                if (engineType === 'disc') {
                    html += `<div class="col-md-2">
                                <select name="options[${index}][disc_tipe]"
                                        class="form-control">
                                    <option value="" selected disabled>-- Tipe DISC --</option>
                                    <option value="D">D</option>
                                    <option value="I">I</option>
                                    <option value="S">S</option>
                                    <option value="C">C</option>
                                </select>
                            </div>`;
                }
                html += `<div class="col-md-2">
                            <button type="button"
                                    class="btn btn-danger btn-sm btn-remove">
                                    Hapus
                            </button>
                        </div>`;
                html += `</div></div>`;
                $('#option-container').append(html);
                deleteOption()
            }

            function deleteOption()
            {
                $('.btn-remove').click(function (e) {
                    $(this).closest('.option-item').remove();
                });
            }

            function add()
            {
                $('#btn-tambah').click(function (e) {
                    e.preventDefault();
                    $(this).hide()
                    $('#btn-batal').show()
                    $('#submit-test').show()
                    // resetOptions()
                    $('#slide-test').slideDown('slow');
                });
            }

            function batal()
            {
                $('#btn-batal').click(function (e) {
                    e.preventDefault();
                    $('#slide-test').slideUp('slow');
                    resetOptions()
                    $(this).hide()
                    $('#submit-test').hide()
                    $('#btn-tambah').show()
                    $('#form-soal')[0].reset();
                    $('#IdSoal').val(null)
                    $('#form-soal').find('select').each(function() {
                        $(this).val($(this).data('default') ?? '').trigger('change');
                    });
                });
            }

            function validasi_test()
            {
                let totalOption = $('.option-item').length;
                let pertanyaan = $('#pertanyaan').val()

                let notif = ''

                let tipe = [];
                $('select[name*="[disc_tipe]"]').each(function () {
                    if ($(this).val() !== '') {
                        tipe.push($(this).val());
                    }
                });
                let required = ['D','I','S','C'];

                if (!engineType) {
                    notif = 'Pilih Test terlebih dahulu';
                }else if (pertanyaan === ''&&pertanyaan===null) {
                    notif = 'Multiple Choice minimal 4 option'
                }else if (engineType === 'multiple_choice' && totalOption < 4) {
                    notif = 'Multiple Choice minimal 4 option'
                }else if (engineType === 'single_choice' && totalOption < 2) {
                    notif = 'Single Choice minimal 2 option';
                }else if (engineType === 'disc' && totalOption !== 4) {
                    notif = ('DISC harus tepat 4 option (D, I, S, C)');
                }else if (engineType === 'multiple_choice' && !$('input[name="correct_option"]:checked').length) { // Validasi khusus multiple_choice → harus ada 1 jawaban benar
                    // if (!$('input[name="correct_option"]:checked').length) {
                    notif = 'Pilih 1 jawaban benar';
                    // }
                    console.log(1);
                }else if (engineType === 'disc'&&(tipe.length !== 4 || !required.every(r => tipe.includes(r)))) { // Validasi DISC → pastikan D I S C lengkap
                    // if (tipe.length !== 4 || !required.every(r => tipe.includes(r))) {
                        notif = 'DISC harus memiliki 4 tipe lengkap: D, I, S, C'
                    // }
                    console.log(2);
                }else{
                    notif = 'ok'
                }
                return notif;
            }

            function saveTest()
            {
                $('#submit-test').click(function (e) {
                    e.preventDefault();
                    let validasitest = validasi_test()
                    if(validasitest!='ok'){
                        notifalert('Perhatian',validasitest,'warning')
                    }else{
                        let dataku = $('#form-soal').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Soal Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.mastersoal.save')}}",
                                    data: dataku,
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
                                        }).then((result) => {
                                            if(data.status=='success'){
                                                $('#btn-batal').trigger('click');
                                                $('#example2').DataTable().ajax.reload();
                                            }
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
                                            // $('#example2').DataTable().ajax.reload();
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

            function edit()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/Assessment/MasterAssessment/Soal/edit-soal') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-batal').trigger('click')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            // resetOptions()
                            $('#IdSoal').val(data.id);
                            $('#test_type_id').val(data.tipe_test_id).trigger('change')
                            $('#pertanyaan').val(data.pertanyaan);
                            $('#urutan').val(data.urutan)
                            let engine = data.tipe.tipe_engine;
                            if(engine == 'multiple_choice'){
                                renderMultiple(data.option);
                            }
                            if(engine == 'single_choice'){
                                renderSingle(data.option);
                            }
                            if(engine == 'disc'){
                                renderDisc(data.option);
                            }
                            $('#btn-tambah').trigger('click')
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Silahkan Hubungi PIKDI',
                                icon: 'error'
                            }).then((result) => {

                            });
                            return;
                        }
                    });
                    return false;
                });
            }

            function renderMultiple(option)
            {
                let html = '';
                option.forEach((opt, index) => {
                    html += `<div class="option-item border p-2 mt-2">
                                <div class="row">`;
                    html += `<div class="col-md-8">
                            <input type="text" name="options[${index}][label]"
                                class="form-control" placeholder="Teks Jawaban" value="${opt.label}">
                        </div>
                        <div class="col-md-2">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio"
                                        name="correct_option" value="${index}" ${opt.is_benar == 1 ? 'checked' : ''}>
                                    <label class="form-check-label">
                                        Jawaban Benar
                                    </label>
                                </div>
                            </div>`;
                    html += `<div class="col-md-2">
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-remove">
                                        Hapus
                                </button>
                            </div>`;
                    html += `</div></div>`;
                });
                deleteOption()
                $('#option-container').html(html);
            }

            function renderSingle(option)
            {
                let html = '';
                option.forEach((opt, index) => {
                    html += `<div class="option-item border p-2 mt-2">
                                <div class="row">`;
                    html += `<div class="col-md-8">
                            <input type="text" name="options[${index}][label]"
                                class="form-control" placeholder="Teks Jawaban" value="${opt.label}">
                        </div>
                        <div class="col-md-2">
                                <input type="number"
                                    name="options[${index}][nilai]"
                                    class="form-control"
                                    placeholder="Nilai" min="0" value="${opt.nilai}">
                            </div>`;
                    html += `<div class="col-md-2">
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-remove">
                                        Hapus
                                </button>
                            </div>`;
                    html += `</div></div>`;
                });
                deleteOption()
                $('#option-container').html(html);
            }

            function renderDisc(option)
            {
                let html = '';
                option.forEach((opt, index) => {
                    html += `<div class="option-item border p-2 mt-2">
                                <div class="row">`;
                    html += `
                        <div class="col-md-8">
                            <input type="text" name="options[${index}][label]"
                                class="form-control" placeholder="Teks Jawaban" value="${opt.label}">
                        </div>
                        <div class="col-md-2">
                                <select name="options[${index}][disc_tipe]"
                                        class="form-control">
                                    <option value="" selected disabled>-- Tipe DISC --</option>
                                    <option value="D" ${opt.disc_tipe == 'D' ? 'selected' : ''}>D</option>
                                    <option value="I" ${opt.disc_tipe == 'I' ? 'selected' : ''}>I</option>
                                    <option value="S" ${opt.disc_tipe == 'S' ? 'selected' : ''}>S</option>
                                    <option value="C" ${opt.disc_tipe == 'C' ? 'selected' : ''}>C</option>
                                </select>
                            </div>`;
                    html += `<div class="col-md-2">
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-remove">
                                        Hapus
                                </button>
                            </div>`;
                    html += `</div></div>`;
                });
                deleteOption()
                $('#option-container').html(html);
            }

            function aktifNonaktif()
            {
                $('.btn_aktif').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    let status = $(this).data('aktif')
                    Swal.fire({
                        title: 'Information',
                        text: status=='0' ? 'Non Aktifkan Soal Test ?' : 'Aktifkan Soal Test ?',
                        icon: 'question',
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/Assessment/MasterAssessment/Soal/status-soal') !!}'+'/'+params+'/'+status,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    if(data.status=='success'){
                                        $('#example2').DataTable().ajax.reload();
                                    }
                                    notifalert(data.title,data.message,data.status)
                                },
                                error: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Silahkan Hubungi PIKDI',
                                        icon: 'error'
                                    }).then((result) => {

                                    });
                                    return;
                                }
                            });
                            return false;
                        }else{
                            return false;
                        }
                    })

                });
            }

        });
    </script>
@endsection

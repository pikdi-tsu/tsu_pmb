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
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Master Data</li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
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
                                <div class="col-md-6">
                                    <form id="form-fakultas" method="POST" action="#">
                                        @csrf
                                        <input type="hidden" id="IdJenis" name="IdJenis" value="">
                                        <div class="form-group mb-3">
                                            <label for="kode">Kode Jalur</label>
                                            <input type="text" id="kode" name="kode" placeholder="Kode Jalur Pendaftaran" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="namajenis">Nama Jalur Pendaftaran</label>
                                            <input type="text" id="namajalur" name="namajalur" placeholder="Jalur Pendaftaran" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="jenisjalur">Jenis Jalur Pendaftaran</label>
                                            <select class="form-control" id="jenisjalur" name="jenisjalur" required>
                                                <option value="" selected disabled>-- Pilih Jenis Jalur Pendaftaran --</option>
                                                <option value="1">Beasiswa</option>
                                                <option value="0">Non Beasiswa</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="biaya_daftar">Biaya Pendaftaran <code>*Ceklis bila ada biaya pendaftaran dan isi jumlah biaya (jika ada)</code></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <input type="checkbox" id="check_daftar" name="check_daftar" value="0">
                                                    </span>
                                                </div>
                                                {{-- <input type="number" value="0" min="0" class="form-control" id="biaya_daftar" name="biaya_daftar" readonly> --}}
                                                <input type="text" value="0" min="0" name="biaya_daftar" id="biaya_daftar" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="status">UKT</label>
                                            <select class="form-control" id="statusukt" name="statusukt" required>
                                                <option value="" selected disabled>-- Pilih Status UKT --</option>
                                                <option value="1">Ya</option>
                                                <option value="0">Tidak</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="berkasumum">Berkas Umum</label>
                                            <select class="form-control select2" id="berkasumum" name="berkasumum">
                                                <option value="" selected disabled>-- Pilih Jenis Berkas Umum --</option>
                                                @foreach ($master as $i)
                                                    @if($i->kategori==0)
                                                        <option value="{{$i->id}}">{{$i->jenis_berkas}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="status">Berkas Khusus <code>*Khusus Jalur Beasiswa, Transfer, dan Pindahan</code></label>
                                            <select class="form-control select2" id="berkaskhusus" name="berkaskhusus" required>
                                                <option value="" selected disabled>-- Pilih Jenis Berkas Khusus --</option>
                                                @foreach ($master as $i)
                                                    @if($i->kategori==1)
                                                        <option value="{{$i->id}}">{{$i->jenis_berkas}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="format_nim">Format NIM</label>
                                            <input type="text" id="format_nim" name="format_nim" placeholder="Contoh: 4 (Max 5 Karakter)" class="form-control" maxlength="5">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="tahun">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi Jalur"></textarea>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input class="custom-control-input" type="checkbox" id="checkboxpagi" name="kelaspagi" value="1">
                                                <label for="checkboxpagi" class="custom-control-label">Kelas Pagi</label>
                                            </div>
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input class="custom-control-input" type="checkbox" id="checkboxsore" name="kelassore" value="1">
                                                <label for="checkboxsore" class="custom-control-label">Kelas Malam</label>
                                            </div>
                                        </div>

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
                                            <th>Kode Jalur</th>
                                            <th>Nama Jalur</th>
                                            <th>Jenis</th>
                                            <th>Format NIM</th> <th>Biaya Pendaftaran</th>
                                            <th>Status UKT</th>
                                            <th>Berkas Umum</th>
                                            <th>Berkas Khusus</th>
                                            <th>Deskripsi</th>
                                            <th>Jadwal Kelas</th>
                                            <th>Aktif</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

            function loadEvent()
            {
                tabelJenis()
                btn_reset()
                checkDaftar()
                submitJalur()
                aktifkanJalur()
                // rupiah()
            }

            function tabelJenis()
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
                    // order: [[1,'asc']],
                    ajax: {
                        url: '{!! route('admin.JenisPendaftaran.Tabel') !!}',
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
                            data: 'status'
                        },
                        // TAMBAHAN KOLOM FORMAT NIM
                        {
                            data: 'format_nim'
                        },
                        {
                            data: 'biaya_daftar'
                        },
                        {
                            data: 'ukt'
                        },
                        {
                            data: 'berkasumum'
                        },
                        {
                            data: 'berkaskhusus'
                        },
                        {
                            data: 'deskripsi'
                        },
                        {
                            data: 'jadwalkelas'
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
                        EditJenis()
                        DeleteJalur()
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

            function validationJalur()
            {
                let kode         = $('#kode').val()
                let nama         = $('#namajalur').val()
                let jenisjalur   = $('#jenisjalur').val()
                let checkbiaya   = $('#check_daftar').val()
                let jmlbiaya     = $('#biaya_daftar').val()
                let ukt          = $('#statusukt').val()
                let berkasumum   = $('#berkasumum').val()
                let berkaskhusus = $('#berkaskhusus').val()
                let deskripsi    = $('#deskripsi').val()
                let kelaspagi    = $('#checkboxpagi').is(':checked') ? 1 : 0;
                let kelassore    = $('#checkboxsore').is(':checked') ? 1 : 0;

                let notifku = ''
                if (kode == null || kode == '') {
                    notifku = 'Kode Jalur Pendaftaran tidak boleh kosong'
                } else if (nama == null || nama == '') {
                    notifku = 'Nama Jalur Pendaftaran tidak boleh kosong'
                } else if (jenisjalur == null || jenisjalur == '') {
                    notifku = 'Jenis Jalur Pendaftaran tidak boleh kosong'
                } else if (checkbiaya == 1 && jmlbiaya == 0) {
                    notifku = 'Biaya Pendaftaran tidak boleh kosong'
                } else if (ukt == null || ukt=='') {
                    notifku = 'Status UKT Tidak Boleh Kosong'
                } else if(berkasumum==null || berkasumum==''){
                    notifku = 'Berkas Umum Tidak Boleh Kosong'
                } else if (deskripsi == null || deskripsi == '') {
                    notifku = 'Deskripsi tidak boleh kosong'
                } else if (kelaspagi == 0 && kelassore == 0) {
                    notifku = 'Silahkan Pilih Salah Satu Jadwal Kelas'
                } else{
                    notifku = 'success';
                }
                return notifku;
            }

            function submitJalur()
            {
                $('#submit-jalur').click(function (e) {
                    e.preventDefault();
                    let checkvalidation = validationJalur();
                    if(checkvalidation != 'success'){
                        notifalert('Information',checkvalidation,'warning')
                    } else{
                        let dataku = $('#form-fakultas').serialize()
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Jalur Pendaftaran Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('admin.JenisPendaftaran.Store')}}",
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
                                            // $('#IdJenis').val(null)
                                            $('#submit-jalur').html('<i class="fas fa-paper-plane"></i> Submit')
                                            $('#submit-jalur').prop('disabled',false)
                                            $('#btn-reset').trigger('click')
                                            // $('#form-fakultas').trigger('reset');
                                            // $('#jenis').val('').trigger('change')
                                            // $('#formatfile').val('').trigger('change')
                                            $('#example2').DataTable().ajax.reload();
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

            function EditJenis()
            {
                $('.btn_edit').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url:  '{!! url('admin/MasterData/JenisPendaftaran/EditJenis') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#btn-reset').trigger('click')
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Jenis Pendaftaran Tidak Ditemukan', 'error')
                            }else{
                                $('#IdJenis').val(data.IdJenis)
                                $('#kode').val(data.jenis.KodeJenis)
                                $('#namajalur').val(data.jenis.jenis_pendaftaran)
                                $('#jenisjalur').val(data.jenis.is_beasiswa).trigger('change')
                                // TAMBAHAN SET VALUE FORMAT NIM
                                $('#format_nim').val(data.jenis.format_nim)
                                if(data.jenis.biaya_pendaftaran==1){
                                    $('#check_daftar').val(data.jenis.biaya_pendaftaran)
                                    $('#check_daftar').prop('checked',true).trigger('change')
                                    // $('#biaya_daftar').val(data.jenis.jml_biaya_pendaftaran)
                                    $('#biaya_daftar').val(formatRupiah(data.jenis.jml_biaya_pendaftaran));
                                }
                                $('#statusukt').val(data.jenis.status_ukt).trigger('change')
                                $('#berkasumum').val(data.jenis.berkas_umum).trigger('change')
                                $('#berkaskhusus').val(data.jenis.berkas_khusus).trigger('change')
                                $('#deskripsi').val(data.jenis.deskripsi)
                                $('#checkboxpagi').prop('checked', data.jenis.kelaspagi == '1');
                                $('#checkboxsore').prop('checked', data.jenis.kelassore == '1');
                            }
                        }
                    });
                    return false;
                });
            }

            function DeleteJalur()
            {
                $('.btn_delete').click(function(e) {
                    // e.preventDefault()
                    let params = $(this).data('id')
                    let status = $(this).data('status')

                    Swal.fire({
                        title: 'Information',
                        text: 'Apakah Anda Yakin Menghapus Data Ini ?',
                        icon: 'question',
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/MasterData/JenisPendaftaran/Status') !!}' + '/' + params + '/' + status,
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

            function aktifkanJalur()
            {
                // $('.btn_aktifkan').click(function(e) {
                $(document).on('click', '.btn_aktifkan', function(e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    let status = $(this).data('status')

                    Swal.fire({
                        title: 'Information',
                        text: 'Apakah Anda Yakin Ingin Mengaktifkan ?',
                        icon: 'question',
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/MasterData/JenisPendaftaran/StatusAktif') !!}' + '/' + params + '/' + status,
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
                    $('#IdJenis').val(null);
                    $('#form-fakultas').trigger('reset');
                    $('#biaya_daftar').prop('readonly',true);
                    $('#biaya_daftar').prop('required', false);
                    $('#biaya_daftar').val(0);
                    $('#status').val('').trigger('change')
                    // $("#example2").DataTable().ajax.reload();
                    $('#berkasumum').val('').trigger('change')
                    $('#berkaskhusus').val('').trigger('change')
                    // $('#kode').val(null)
                    // $('#namajalur').val(null)
                });
            }

            function checkDaftar()
            {
                $('#check_daftar').on('change', function () {
                    let aa = $(this).is(':checked')
                    if(aa){
                        $('#biaya_daftar').prop('readonly',false)
                        $('#biaya_daftar').prop('required', true);
                        $(this).val(1)
                    }else{
                        $('#biaya_daftar').prop('readonly',true)
                        $('#biaya_daftar').prop('required', false);
                        $('#biaya_daftar').val(0);
                        $(this).val(0)
                    }
                });
            }

            // function rupiah()
            // {
            //     const inputBiaya = document.getElementById('biaya_daftar');

            //     inputBiaya.addEventListener('keyup', function(e) {
            //         let angka = this.value.replace(/[^,\d]/g, '').toString();

            //         let split = angka.split(',');
            //         let sisa = split[0].length % 3;

            //         let rupiah = split[0].substr(0, sisa);
            //         let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            //         if (ribuan) {
            //             let separator = sisa ? '.' : '';
            //             rupiah += separator + ribuan.join('.');
            //         }

            //         rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

            //         this.value = rupiah;
            //     });
            // }

            $('#biaya_daftar').on('keyup', function(){
                $(this).val(formatRupiah($(this).val()));
            });

            function formatRupiah(angka){
                let number_string = angka.toString().replace(/[^,\d]/g, '');

                let split = number_string.split(',');
                let sisa = split[0].length % 3;

                let rupiah = split[0].substr(0, sisa);

                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if(ribuan){
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

                return rupiah;
            }


        });
    </script>
@endsection

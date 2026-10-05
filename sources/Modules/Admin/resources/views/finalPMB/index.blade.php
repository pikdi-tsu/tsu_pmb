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
                    <h1>{{$menu}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$menu}}</li>
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
                            <h5 class="m-0">{{$menu}}</h5>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Batch Daftar</th>
                                        <th>Jalur Daftar</th>
                                        <th>Pindah Jalur Pendaftaran</th>
                                        <th>Program Studi</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    {{-- Modal edit jurusan --}}
    <div class="modal fade" id="modal-edit-jurusan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-titlejurusan"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-editjurusan" method="POST" action="#">
                        <input type="hidden" id="kodependaftaranmahasiswa" name="kodependaftaranmahasiswa">
                        <div class="form-group mb-3">
                            <label for="prodiditerima">Program Studi Diterima</label>
                            <select class="form-control select2" id="prodiditerima" name="prodiditerima">
                                <option value="" selected disabled>-- Pilih Program Studi --</option>
                                @foreach ($getfakultas as $item)
                                    <optgroup label="{{ $item->namafakultas }}">
                                        @foreach ($item->jurusan as $jurusan)
                                            <option value="{{ $jurusan->KodeJurusan }}">
                                                {{ $jurusan->jenjang->jenjang .' '. $jurusan->jurusan }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jadwalkelas">Jadwal Kelas</label>
                            <select class="form-control select2" id="jadwalkelas" name="jadwalkelas">
                                <option value="" selected disabled>-- Pilih Jadwal Kelas --</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-saveupdatejurusan" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
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

            LoadEvent()

            function LoadEvent()
            {
                tabelFinal()
            }

            $('.select2').select2({
                dropdownParent: $('#modal-edit-jurusan'),
                width: '100%'
            });

            function tabelFinal()
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
                    responsive: true,
                    order: [],
                    ajax: {
                        url: '{!! route('admin.finalpmb.tabel') !!}',
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
                            data: 'batch'
                        },
                        {
                            data: 'jalur'
                        },
                        {
                            data: 'pindahjalur'
                        },
                        {
                            data: 'programstudi'
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
                        // ShowDetail()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});

                });
            }
        });

        $('#example2').on('click', '.btn_edit_jurusan', function(e) {
            e.preventDefault();
            let param = $(this).data('id');
            // $('#modal-edit-jurusan').modal('show');

            $.ajax({
                type: "GET",
                url: '{!! url('admin/FinalPMB/EditJurusan') !!}' + '/' + param,
                dataType: "JSON",
                beforeSend: function(response) {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log('data :>> ', data);
                    $('#loading').hide();
                    if(data.hasil == 0) {
                        notifalert('Information', 'Data Pendaftaran Tidak Ditemukan', 'error');
                    } else {
                        $('#kodependaftaranmahasiswa').val(param);
                        $('.modal-titlejurusan').html('Data Jurusan ' + data.biodata.nama);
                        $('#prodiditerima').val(data.jurusan_diterima).trigger('change');
                        $('#modal-edit-jurusan').modal('show');

                        // reset option
                        $('#jadwalkelas').html(
                            '<option value="" disabled>-- Pilih Jadwal Kelas --</option>'
                        );

                        // tambah option dinamis
                        if(data.jalur.kelaspagi == 1){
                            $('#jadwalkelas').append(
                                `<option value="PAGI">Kelas Pagi</option>`
                            );
                        }

                        if(data.jalur.kelassore == 1){
                            $('#jadwalkelas').append(
                                `<option value="SORE">Kelas Sore</option>`
                            );
                        }

                        // set selected jika ada data sebelumnya
                        if(data.kelaspagi == 1){
                            $('#jadwalkelas').val('PAGI');
                        }

                        if(data.kelassore == 1){
                            $('#jadwalkelas').val('SORE');
                        }

                        // refresh select2
                        $('#jadwalkelas').trigger('change');
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    console.error("AJAX ERROR:", xhr.responseText);
                    Swal.fire({
                        title: 'Gagal Show Data',
                        text: 'Terjadi masalah saat mengambil data dari server. Silakan coba lagi atau hubungi admin.',
                        icon: 'error'
                    });
                }
            });
        });

        $('#btn-saveupdatejurusan').click(function(e) {
            e.preventDefault();
            let kodependaftaran = $('#kodependaftaranmahasiswa').val();
            let prodiditerima = $('#prodiditerima').val();
            let jadwalkelas = $('#jadwalkelas').val();

            $.ajax({
                type: "POST",
                url: '{!! route('admin.finalpmb.updatejurusan') !!}',
                data: {
                    kodependaftaran: kodependaftaran,
                    prodiditerima: prodiditerima,
                    jadwalkelas: jadwalkelas
                },
                dataType: "JSON",
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#loading').hide();
                    if (response.status == 'success') {
                        $('#modal-edit-jurusan').modal('hide');
                        notifalert('Berhasil', response.message, 'success');
                        $('#example2').DataTable().ajax.reload(null, false);
                    } else {
                        notifalert('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    $('#loading').hide();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        });
    </script>
@endsection

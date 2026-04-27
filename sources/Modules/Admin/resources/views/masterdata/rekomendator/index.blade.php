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
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
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
                                <div class="col-md-8">
                                    <form id="form-rekomendator" method="POST" action="#">
                                        @csrf
                                        <input type="hidden" id="IdRekomendator" name="IdRekomendator" value="">

                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group mb-3">
                                                    <label for="nama_rekomendator">Nama Rekomendator</label>
                                                    <input type="text" id="nama_rekomendator" name="nama_rekomendator"
                                                        placeholder="Nama Lengkap" class="form-control" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="kategori">Kategori</label>
                                                    <select class="form-control select2" id="kategori" name="kategori"
                                                        required>
                                                        <option value="" selected disabled>-- Pilih Kategori --
                                                        </option>
                                                        @foreach ($kategori as $kat)
                                                            <option value="{{ $kat->kode_kategori }}">
                                                                {{ $kat->kategori_rekomendator }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="pekerjaan">Pekerjaan</label>
                                                    <input type="text" id="pekerjaan" name="pekerjaan"
                                                        placeholder="Pekerjaan" class="form-control">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="alamat">Alamat</label>
                                                    <textarea id="alamat" name="alamat" rows="3" class="form-control" placeholder="Alamat Lengkap"></textarea>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="no_hp">No. Handphone</label>
                                                    <input type="text" id="no_hp" name="no_hp"
                                                        placeholder="08xxxxxxxxxx" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group mb-3">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" name="email" placeholder="Email"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="nama_bank">Nama Bank</label>
                                                    <input type="text" id="nama_bank" name="nama_bank"
                                                        placeholder="Contoh: BCA, BRI" class="form-control">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="no_rekening">No. Rekening</label>
                                                    <input type="text" id="no_rekening" name="no_rekening"
                                                        placeholder="Nomor Rekening" class="form-control">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="atasnama_rekening">Atas Nama Rekening</label>
                                                    <input type="text" id="atasnama_rekening" name="atasnama_rekening"
                                                        placeholder="Atas Nama" class="form-control">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="" selected disabled>-- Pilih Status --</option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Non Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="button" id="submit-rekomendator"
                                                class="btn btn-success float-right" style="margin-left:10px;"> <i
                                                    class="fas fa-paper-plane"></i> Submit</button>
                                            <button id="btn-reset" class="btn btn-warning float-right">Reset</button>
                                            <button id="btn-import" class="btn btn-info float-right"
                                                style="margin-right:10px;"><i class="fas fa-file-excel"></i> Import
                                                Excel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table id="tabel-rekomendator"
                                            class="table table-bordered table-hover"style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Rekomendator</th>
                                                    <th>Nama Rekomendator</th>
                                                    <th>Kategori</th>
                                                    <th>Pekerjaan</th>
                                                    <th>No. HP</th>
                                                    <th>No Rekening</th>
                                                    <th>Bank</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
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
        </div>
    </div>

    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Rekomendator</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="form-import" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-info" style="padding: 10px;">
                            <i class="fas fa-info-circle"></i> <strong>Pilih Template Excel:</strong><br>
                            <ol style="margin-bottom: 0; padding-left: 20px; margin-top: 5px;">
                                <li>
                                    <a href="{{ route('admin.Rekomendator.TemplateExcel') }}" class="font-weight-bold"
                                        style="color: #00ff15; text-decoration: underline;">Template Input Baru</a> <br>
                                    <small class="text-dark">(Digunakan untuk input data baru. Kode dibuat otomatis oleh
                                        sistem)</small>
                                </li>
                                <li style="margin-top: 8px;">
                                    <a href="{{ route('admin.Rekomendator.MigrationTemplateExcel') }}"
                                        class="font-weight-bold"
                                        style="color: #00ff15; text-decoration: underline;">Template Migrasi Data</a> <br>
                                    <small class="text-dark">(Gunakan kode afiliator lama dari sistem sebelumnya)</small>
                                </li>
                            </ol>
                        </div>
                        <div class="form-group">
                            <label>Pilih File Excel (.xlsx)</label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control"
                                accept=".xlsx, .xls, .csv" required>
                        </div>
                        <p class="text-muted"><small>*Pastikan baris pertama Excel berisi header kolom: kategori_id,
                                nama_rekomendator, alamat, pekerjaan, no_hp, email, nama_bank, no_rekening,
                                atasnama_rekening.</small></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="btn-proses-import">Upload & Proses</button>
                    </div>
                </form>
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

            // Beri sedikit jeda saat render awal agar layout rapi (sama seperti form lain)
            setTimeout(() => {
                loadEvent();
            }, 500);

            function loadEvent() {
                tabelRekomendator();
                submitRekomendator();
                btn_reset();
                importExcel();
            }

            function cekKategoriManual() {
                $('#kategori').change(function() {
                    // Ambil Teks dari opsi yang dipilih (contoh: "DOSEN" atau "UMUM")
                    let teksKategori = $(this).find('option:selected').text().trim().toUpperCase();

                    // Cek apakah form sedang dalam mode edit
                    let isEdit = $('#IdRekomendator').val() !== '';

                    if (teksKategori === 'DOSEN' || teksKategori === 'TENDIK') {
                        // Buka kunci input agar bisa diketik
                        $('#kode_rekomendator').prop('readonly', false).attr('placeholder',
                            'Ketik Kode Manual...');
                    } else {
                        // Kunci kembali inputnya
                        $('#kode_rekomendator').prop('readonly', true).attr('placeholder',
                        'Auto Generated');

                        // Kosongkan isi field HANYA JIKA sedang tambah data baru.
                        // (Jika sedang edit, kita biarkan kode aslinya tetap tampil)
                        if (!isEdit) {
                            $('#kode_rekomendator').val('');
                        }
                    }
                });
            }

            function kapitalisasiKode() {
                $('#kode_rekomendator').on('input', function() {
                    let isi = $(this).val();
                    $(this).val(isi.toUpperCase());
                });
            }

            function tabelRekomendator() {
                let otable = $('#tabel-rekomendator').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    ajax: {
                        url: '{!! route('admin.Rekomendator.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kode_rekomendator'
                        },
                        {
                            data: 'nama_rekomendator'
                        },
                        // PERUBAHAN DI SINI: Render selalu mengembalikan string kosong
                        {
                            data: 'nama_kategori',
                            // render: function(data, type, row) {
                            //     return ''; // Dikosongkan sesuai permintaan
                            // }
                        },
                        {
                            data: 'pekerjaan'
                        },
                        {
                            data: 'no_hp'
                        },
                        {
                            data: 'no_rekening'
                        },
                        {
                            data: 'nama_bank'
                        },
                        {
                            data: 'email'
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
                    drawCallback: function(settings) {
                        EditRekomendator();
                        StatusRekomendator();
                        DestroyRekomendator();
                        KirimEmailRekomendator();
                    }
                });
            }

            function submitRekomendator() {
                $('#submit-rekomendator').click(function(e) {
                    e.preventDefault();
                    let validation = validationRekomendator();
                    if (validation != 'success') {
                        Swal.fire('Peringatan', validation, 'warning');
                    } else {
                        let dataku = $('#form-rekomendator').serialize();
                        Swal.fire({
                            title: "Konfirmasi",
                            text: "Apakah data sudah benar?",
                            icon: "question",
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('admin.Rekomendator.Store') }}",
                                    data: dataku,
                                    dataType: "JSON",
                                    beforeSend: function() {
                                        // Tampilkan Loading
                                        $('#loading').show();
                                        $('#submit-rekomendator').html(
                                            '<i class="fas fa-hourglass"></i> Please Wait'
                                        ).prop('disabled', true);
                                    },
                                    success: function(data) {
                                        // Sembunyikan Loading
                                        $('#loading').hide();
                                        Swal.fire({
                                            title: data.title,
                                            text: data.message,
                                            icon: data.status
                                        }).then(() => {
                                            $('#submit-rekomendator').html(
                                                '<i class="fas fa-paper-plane"></i> Submit'
                                            ).prop('disabled', false);
                                            $('#form-rekomendator').trigger(
                                                'reset');
                                            $('#kategori').val('').trigger(
                                                'change');
                                            $('#IdRekomendator').val('');
                                            $("#tabel-rekomendator").DataTable()
                                                .ajax.reload();
                                        });
                                    },
                                    error: function() {
                                        // Sembunyikan Loading jika error
                                        $('#loading').hide();
                                        Swal.fire('Error', 'Gagal menyimpan data',
                                            'error');
                                        $('#submit-rekomendator').html(
                                            '<i class="fas fa-paper-plane"></i> Submit'
                                        ).prop('disabled', false);
                                    }
                                });
                            }
                        });
                    }
                });
            }

            function validationRekomendator() {
                let nama = $('#nama_rekomendator').val();
                let status = $('#status').val();
                let kategori = $('#kategori').val();

                if (nama == null || nama == '') return 'Nama rekomendator tidak boleh kosong';
                if (kategori == null || kategori == '') return 'Kategori tidak boleh kosong';
                if (status == null || status == '') return 'Status tidak boleh kosong';
                return 'success';
            }

            function EditRekomendator() {
                $('.btn_edit').click(function(e) {
                    e.preventDefault();
                    let params = $(this).data('id');
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/MasterData/Rekomendator/Edit') !!}/' + params,
                        dataType: "JSON",
                        beforeSend: function() {
                            // Tampilkan Loading saat mengambil data
                            $('#loading').show();
                        },
                        success: function(data) {
                            // Sembunyikan Loading
                            $('#loading').hide();
                            if (data.hasil == 0) {
                                Swal.fire('Informasi', 'Data tidak ditemukan', 'error');
                            } else {
                                $('#IdRekomendator').val(data.IdRekomendator);
                                $('#kode_rekomendator').val(data.data.kode_rekomendator);
                                $('#nama_rekomendator').val(data.data.nama_rekomendator);
                                $('#kategori').val(data.data.kategori).trigger('change');
                                $('#pekerjaan').val(data.data.pekerjaan);
                                $('#alamat').val(data.data.alamat);
                                $('#no_hp').val(data.data.no_hp);
                                $('#email').val(data.data.email);
                                $('#nama_bank').val(data.data.nama_bank);
                                $('#no_rekening').val(data.data.no_rekening);
                                $('#atasnama_rekening').val(data.data.atasnama_rekening);
                                $('#status').val(data.data.isactive).trigger('change');
                            }
                        },
                        error: function() {
                            $('#loading').hide();
                            Swal.fire('Error', 'Gagal mengambil data', 'error');
                        }
                    });
                });
            }

            function StatusRekomendator() {
                $('.btn_status').click(function(e) {
                    e.preventDefault();
                    let params = $(this).data('id');
                    let status = $(this).data('status');

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah anda yakin mengubah status data ini?',
                        icon: 'question',
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/MasterData/Rekomendator/Status') !!}/' + params + '/' + status,
                                dataType: "JSON",
                                beforeSend: function() {
                                    $('#loading').show(); // Tampilkan Loading
                                },
                                success: function(data) {
                                    $('#loading').hide(); // Sembunyikan Loading
                                    Swal.fire(data.title, data.message, data.type).then(
                                        () => {
                                            $("#tabel-rekomendator").DataTable()
                                                .ajax.reload();
                                        });
                                },
                                error: function() {
                                    $('#loading').hide();
                                    Swal.fire('Error', 'Gagal mengubah status',
                                        'error');
                                }
                            });
                        }
                    });
                });
            }

            function DestroyRekomendator() {
                $('.btn_destroy').click(function(e) {
                    e.preventDefault();
                    let params = $(this).data('id');

                    Swal.fire({
                        title: 'Hapus Permanen?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/MasterData/Rekomendator/Destroy') !!}/' + params,
                                dataType: "JSON",
                                beforeSend: function() {
                                    $('#loading').show(); // Tampilkan Loading
                                },
                                success: function(data) {
                                    $('#loading').hide(); // Sembunyikan Loading
                                    Swal.fire(data.title, data.message, data.status)
                                        .then(() => {
                                            $("#tabel-rekomendator").DataTable()
                                                .ajax.reload();
                                        });
                                },
                                error: function() {
                                    $('#loading').hide();
                                    Swal.fire('Error',
                                        'Terjadi kesalahan sistem saat menghapus data.',
                                        'error');
                                }
                            });
                        }
                    });
                });
            }

            function KirimEmailRekomendator() {
                $('.btn_email').click(function(e) {
                    e.preventDefault();
                    let params = $(this).data('id');

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Yakin Mengirim Email ?',
                        icon: 'question',
                        showCancelButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/MasterData/Rekomendator/KirimEmail') !!}/' + params,
                                dataType: "JSON",
                                beforeSend: function() {
                                    $('#loading').show(); // Tampilkan Loading
                                },
                                success: function(data) {
                                    $('#loading').hide(); // Sembunyikan Loading
                                    Swal.fire(data.title, data.message, data.status)
                                        .then(() => {
                                            $("#tabel-rekomendator").DataTable()
                                                .ajax.reload();
                                        });
                                },
                                error: function() {
                                    $('#loading').hide();
                                    Swal.fire('Error',
                                        'Terjadi kesalahan sistem saat mengirim email.',
                                        'error');
                                }
                            });
                        }
                    });
                });
            }

            function btn_reset() {
                $('#btn-reset').click(function(e) {
                    e.preventDefault();
                    $('#IdRekomendator').val('');
                    $('#form-rekomendator').trigger('reset');
                    $('#kategori').val('').trigger('change');
                });
            }

            function importExcel() {
                $('#btn-import').click(function(e) {
                    e.preventDefault();
                    $('#form-import').trigger('reset');
                    $('#modal-import').modal('show');
                });

                $('#form-import').on('submit', function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.Rekomendator.UploadExcel') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#loading').show(); // Tampilkan Loading overlay
                            $('#btn-proses-import').html(
                                '<i class="fas fa-spinner fa-spin"></i> Proses...').prop(
                                'disabled', true);
                        },
                        success: function(response) {
                            $('#loading').hide(); // Sembunyikan Loading
                            $('#modal-import').modal('hide');
                            $('#btn-proses-import').html('Upload & Proses').prop('disabled',
                                false);

                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                icon: response.status
                            }).then(() => {
                                $("#tabel-rekomendator").DataTable().ajax.reload();
                            });
                        },
                        error: function(xhr) {
                            $('#loading').hide(); // Sembunyikan Loading
                            $('#btn-proses-import').html('Upload & Proses').prop('disabled',
                                false);
                            Swal.fire('Error',
                                'Format file tidak sesuai atau terjadi kesalahan sistem.',
                                'error');
                        }
                    });
                });
            }

        });
    </script>
@endsection

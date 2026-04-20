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
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row" style="display: none;">
                                <div class="col-lg-2">
                                    <select class="form-control select2" id="kategori" name="kategori" required>
                                        <option value="" selected disabled>-- Pilih Jenis Berkas --</option>
                                        <option value="{{ encrypt('pendaftaran') }}">Berkas Khusus</option>
                                        <option value="{{ encrypt('ukt') }}">Berkas Umum</option>
                                    </select>
                                </div>
                                <!-- /.col-lg-6 -->
                                <div class="btn-group">
                                    <button type="button" id="btn-showpembayaran" class="btn btn-primary btn-sm"
                                        style="margin-top: 6px;margin-right: 10px;">Show Data</button>
                                    <button type="button" class="btn btn-success btn-sm"
                                        style="margin-top: 6px;display:none;">Export Excel</button>
                                    <!-- /input-group -->
                                </div>
                                <!-- /.col-lg-6 -->
                            </div>
                            <code>* Klik Nama Berkas untuk melihat berkas</code>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>No Registrasi</th>
                                            <th>Batch Daftar</th>
                                            <th>Jalur Daftar</th>
                                            <th>Kategori Beasiswa</th>

                                            <th>Keterangan</th>
                                            <th>Validator</th>
                                            <th>Pindah Jalur</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                <div class="modal fade" id="modal-berkas-user">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Daftar Berkas Pendaftar</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table id="tabel-berkas-detail" class="table table-bordered table-hover"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Berkas</th>
                                                                <th>Validator</th>
                                                                <th>Status</th>
                                                                <th>Keterangan</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
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
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <div class="modal fade" id="modal-approval-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Validasi Berkas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="judul-nama-berkas" class="text-primary mb-3"></h5>

                    <form id="form-approval-item">
                        <input type="hidden" name="kodedaftar_item" id="kodedaftar_item" value="">
                        <input type="hidden" name="idberkas_item" id="idberkas_item" value="">

                        <div class="form-group">
                            <label for="status_item">Status Validasi</label>
                            <select class="form-control" id="status_item" name="status_item" required>
                                <option value="" selected disabled>-- Pilih Status --</option>
                                <option value="1">Berkas Sesuai (OK)</option>
                                <option value="-1">Ditolak / Perlu Revisi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="keterangan_item">Catatan / Keterangan Revisi</label>
                            <textarea name="keterangan_item" id="keterangan_item" class="form-control" rows="3"
                                placeholder="Contoh: Dokumen buram, tolong scan ulang..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-save-approval-item" class="btn btn-success">Simpan Validasi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-approval">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-modal">Approval Berkas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <code>* Jika Masih Ada Revisi Berkas Silahkan Input Pada Keterangan, dan jangan ubah status
                        berkas</code><br>
                    <code>* Berkas yang Sudah Diverifikasi Tidak Dapat diubah lagi</code>
                    <form id="form-approval" action="#" method="POST">
                        <input type="hidden" name="iddaftar" id="iddaftar" value="">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"
                            placeholder="Tambahkan Keterangan (Jika Ada Revisi)"></textarea>
                        <label for="status">Status</label>
                        <select class="form-control select2" id="status" name="status">
                            <option value="0" selected disabled>-- Pilih Status Berkas --</option>
                            <option value="1">OK</option>
                            <option value="-1">Ditolak</option>
                        </select><br>
                        <div id="wadah_pindah_jalur" style="display: none;">
    <input type="checkbox" id="pindahjalur" name="pindahjalur" value="0">
    <label for="pindahjalur">Arahkan Ke jalur Reguler</label>
</div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btn-closemodalberkas" class="btn btn-default"
                        data-dismiss="modal">Close</button>
                    <button type="button" id="btn-saveapproval" class="btn btn-success">Simpan</button>
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

            function loadEvent() {
                tabelBerkasKhusus()
                SubmitApprovalBerkas()
                checkPindahJalur()
                watchStatus();
            }

            function tabelBerkasKhusus() {
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
                        url: '{!! route('admin.berkaspmb.tabel') !!}',
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
                            data: 'noreg'
                        },
                        {
                            data: 'batch'
                        },
                        {
                            data: 'jalur'
                        },
                        {
                            data: 'beasiswa'
                        },

                        {
                            data: 'keterangan'
                        },
                        {
                            data: 'validator'
                        },
                        {
                            data: 'pindahjalur'
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
                        approvalBerkas()
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
            }

            $('#example2').on('click', '.btn_lihat_berkas', function(e) {
                e.preventDefault();
                let id_pendaftaran = $(this).data('id');

                $('#modal-berkas-user').modal('show');
                loadTabelBerkasUser(id_pendaftaran);
            });

            function loadTabelBerkasUser(id) {
                let urlGetBerkas = '{{ route('admin.getberkasuser') }}?id=' + encodeURIComponent(id);

                $('#tabel-berkas-detail').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: urlGetBerkas,
                        type: 'GET'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_berkas'
                        },
                        {
                            data: 'validator',
                            className: 'text-center'
                        },
                        {
                            data: 'status',
                            className: 'text-center'
                        },
                        {
                            data: 'keterangan'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    language: {
                        processing: '<i class="fa fa-spinner fa-lg fa-spin"></i>'
                    }
                });
            }

            // Membuka Modal Validasi Per-Item
            $('#tabel-berkas-detail').on('click', '.btn-validasi-item', function(e) {
                e.preventDefault();

                // Ambil data
                let kodeDaftar = $(this).data('kodedaftar');
                let idBerkas = $(this).data('idberkas');
                let namaBerkas = $(this).data('namaberkas');

                // Isi form di modal
                $('#kodedaftar_item').val(kodeDaftar);
                $('#idberkas_item').val(idBerkas);
                $('#judul-nama-berkas').html('<i class="fa fa-file-alt"></i> ' + namaBerkas);
                $('#status_item').val('');
                $('#keterangan_item').val('');

                $('#modal-approval-item').modal('show');
            });

            // Submit Data Validasi ke Server
            $(document).on('click', '#btn-save-approval-item', function(e) {
                e.preventDefault();

                let status = $('#status_item').val();
                let kodedaftar = $('#kodedaftar_item').val();
                let idberkas = $('#idberkas_item').val();
                let keterangan = $('#keterangan_item').val();

                // Validasi input
                if (status == '' || status == null) {
                    Swal.fire('Peringatan', 'Silakan pilih Status Validasi terlebih dahulu!', 'warning');
                    return false;
                }

                let formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('kode_daftar', kodedaftar);
                formData.append('id_berkas', idberkas);
                formData.append('status', status);
                formData.append('keterangan', keterangan);

                Swal.fire({
                    title: "Konfirmasi",
                    text: "Simpan validasi untuk item berkas ini?",
                    icon: "question",
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.berkaspmb.saveitem') }}", // Sesuaikan dengan nama route Anda
                            processData: false,
                            contentType: false,
                            data: formData,
                            dataType: "JSON",
                            beforeSend: function() {
                                $('#loading').show();
                                $('#btn-save-approval-item').prop('disabled', true);
                            },
                            success: function(data) {
                                $('#loading').hide();
                                $('#btn-save-approval-item').prop('disabled', false);

                                Swal.fire({
                                    title: data.title,
                                    text: data.message,
                                    icon: data.status
                                }).then(() => {
                                    if (data.status === 'success') {
                                        // 1. Tutup modal validasi item
                                        $('#modal-approval-item').modal('hide');

                                        // 2. Reload tabel rincian berkas (Modal Detail)
                                        if ($.fn.DataTable.isDataTable(
                                                '#tabel-berkas-detail')) {
                                            $('#tabel-berkas-detail')
                                            .DataTable().ajax.reload(null,
                                                false);
                                        }

                                        // 3. Reload tabel utama (Halaman Belakang)
                                        if ($.fn.DataTable.isDataTable(
                                                '#example2')) {
                                            $('#example2').DataTable().ajax
                                                .reload(null, false);
                                        }

                                        // 4. Jaga agar body tetap bisa di-scroll (Trik double modal)
                                        $('body').addClass('modal-open');
                                    }
                                });
                            },
                            error: function(xhr) {
                                $('#loading').hide();
                                $('#btn-save-approval-item').prop('disabled', false);
                                Swal.fire('Error', 'Terjadi kesalahan sistem.',
                                'error');
                            }
                        });
                    }
                });
            });

            function approvalBerkas() {
    $('.btn_approval').click(function(e) {
        e.preventDefault();
        let params = $(this).data('id');
        $('#iddaftar').val(params);

        // --- TAMBAHAN RESET FORM ---
        $('#status').val('0').trigger('change'); // Kembalikan ke "-- Pilih Status --"
        $('#keterangan').val('');                // Kosongkan keterangan
        $('#pindahjalur').prop('checked', false).val(0); // Hilangkan centang
        $('#wadah_pindah_jalur').hide();         // Sembunyikan kembali checkbox
        // ---------------------------

        $('#modal-approval').modal('show');
    });
}

            function SubmitApprovalBerkas() {
                $('#btn-saveapproval').click(function(e) {
                    e.preventDefault();

                    let status = $('#status').val();

                    // Validasi jika status belum dipilih
                    if (status == '' || status == null || status == '0') {
                        Swal.fire('Peringatan', 'Silakan pilih Status Berkas terlebih dahulu!', 'warning');
                        return false;
                    }

                    let formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('kodedaftar', $('#iddaftar').val());
                    formData.append('keterangan', $('#keterangan').val());
                    formData.append('status', status);
                    formData.append('pindahjalur', $('#pindahjalur').val());

                    Swal.fire({
                        title: "Konfirmasi",
                        text: "Apakah Anda yakin ingin menyelesaikan validasi berkas untuk pendaftar ini?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('admin.berkaspmb.save') }}",
                                processData: false,
                                contentType: false,
                                data: formData,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show();
                                    $('#btn-saveapproval').prop('disabled', true);
                                },
                                success: function(data) {
                                    $('#loading').hide();
                                    $('#btn-saveapproval').prop('disabled', false);

                                    Swal.fire({
                                        title: data.title,
                                        text: data.message,
                                        icon: data.status
                                    }).then((result) => {
                                        if (data.status === 'success') {
                                            // 1. Reload tabel (ini bebas, entah tabel utama atau tabel dalam modal)
                                            $('#example2').DataTable().ajax
                                                .reload(null, false);

                                            $('#modal-approval').modal('hide');

                                            // 3. --- INI KUNCI UNTUK MENGEMBALIKAN SCROLL NYA ---
                                            $('body').addClass('modal-open');
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    $('#loading').hide();
                                    $('#btn-saveapproval').prop('disabled', false);
                                    Swal.fire('Error',
                                        'Terjadi kesalahan pada server. Silakan coba lagi.',
                                        'error');
                                }
                            });
                        }
                    });
                });
            }

            function watchStatus() {
    $('#status').on('change', function() {
        let nilaiStatus = $(this).val();

        // Jika pilih Ditolak (-1), munculkan pilihan pindah jalur
        if (nilaiStatus == '-1') {
            $('#wadah_pindah_jalur').slideDown();
        } else {
            // Jika pilih OK (1) atau reset (0), sembunyikan lagi
            $('#wadah_pindah_jalur').slideUp();

            // Hapus centangan dan kembalikan value ke 0 agar tidak ikut tersubmit
            $('#pindahjalur').prop('checked', false).val(0);
        }
    });
}

            $('#modal-approval-item').on('hidden.bs.modal', function() {

                // Secara otomatis mengembalikan class modal-open ke body
                // agar modal pertama di belakangnya bisa di-scroll kembali
                $('body').addClass('modal-open');

            });

            function checkPindahJalur() {
                $('#pindahjalur').on('change', function() {
                    let aa = $(this).is(':checked')
                    if (aa) {
                        $(this).val(1)
                    } else {
                        $(this).val(0)
                    }
                });
            }

            $('#modal-approval').on('hidden.bs.modal', function() {
                $('#iddaftar').val('')
                $('#keterangan').val('')
                $('#status').val(0).trigger('change');
                $('#pindahjalur').val(0)
                $('#pindahjalur').prop('checked', false)
            });
        });
    </script>
@endsection

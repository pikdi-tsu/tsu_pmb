@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-history text-info mr-2"></i>Log Aktivitas Admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Log Aktivitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Filter Card -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i>Filter Log</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Dari</label>
                                <input type="date" id="filter_tgl_dari" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Sampai</label>
                                <input type="date" id="filter_tgl_sampai" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modul</label>
                                <select id="filter_modul" class="form-control form-control-sm">
                                    <option value="">-- Semua Modul --</option>
                                    <option value="Pembayaran PMB">Pembayaran PMB</option>
                                    <option value="Pembayaran UKT">Pembayaran UKT</option>
                                    <option value="Berkas PMB">Berkas PMB</option>
                                    <option value="Test Online">Test Online</option>
                                    <option value="Generate NIM">Generate NIM</option>
                                    <option value="Data Pendaftar">Data Pendaftar</option>
                                    <option value="Group User">Group User</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NIK Admin</label>
                                <input type="text" id="filter_admin_nik" class="form-control form-control-sm" placeholder="Cari NIK admin...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="btn_filter" class="btn btn-info btn-sm">
                                <i class="fas fa-search mr-1"></i>Terapkan Filter
                            </button>
                            <button id="btn_reset_filter" class="btn btn-secondary btn-sm ml-2">
                                <i class="fas fa-redo mr-1"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Log -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i>Riwayat Aktivitas</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl_log" class="table table-bordered table-striped table-hover table-sm" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:40px">#</th>
                                    <th style="width:140px">Waktu</th>
                                    <th style="width:150px">Admin</th>
                                    <th style="width:160px">Modul / Aksi</th>
                                    <th style="width:130px">Kode Target</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var table = $('#tbl_log').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.logaktivitas.tabel") }}',
            data: function (d) {
                d.tgl_dari     = $('#filter_tgl_dari').val();
                d.tgl_sampai   = $('#filter_tgl_sampai').val();
                d.modul        = $('#filter_modul').val();
                d.admin_nik    = $('#filter_admin_nik').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',  orderable: false, searchable: false },
            { data: 'waktu' },
            { data: 'admin' },
            { data: 'modul_aksi' },
            { data: 'target' },
            { data: 'keterangan' },
        ],
        order: [[1, 'desc']],
        responsive: true,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Memuat data...',
            emptyTable: 'Belum ada log aktivitas',
            zeroRecords: 'Data tidak ditemukan',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            search: 'Cari:'
        }
    });

    // Terapkan filter
    $('#btn_filter').on('click', function () {
        table.ajax.reload();
    });

    // Reset filter
    $('#btn_reset_filter').on('click', function () {
        $('#filter_tgl_dari, #filter_tgl_sampai, #filter_admin_nik').val('');
        $('#filter_modul').val('');
        table.ajax.reload();
    });

    // Enter pada input filter
    $('#filter_tgl_dari, #filter_tgl_sampai, #filter_modul, #filter_admin_nik').on('keyup change', function (e) {
        if (e.key === 'Enter') { table.ajax.reload(); }
    });
});
</script>
@endsection

@extends('admin::template/admin/header')
@section('title', $title)

@section('link_href')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        /* === TSU Stat Cards Grid (4 Columns) === */
        .tsu-stat-grid-role {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 992px) {
            .tsu-stat-grid-role {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .tsu-stat-grid-role {
                grid-template-columns: 1fr;
            }
        }
        .tsu-stat-card {
            border-radius: 12px;
            padding: 1.15rem 1.25rem;
            box-shadow: 0 4px 14px rgba(9, 75, 84, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .tsu-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(9, 75, 84, 0.15);
        }
        .tsu-stat-card__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.65rem;
        }
        .tsu-stat-card__label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.95;
            margin: 0;
        }
        .tsu-stat-card__icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .tsu-stat-card__value {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: baseline;
            gap: 0.35rem;
        }
        .tsu-stat-card__unit {
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.85;
        }
        .tsu-stat-card__subtext {
            font-size: 0.75rem;
            font-weight: 500;
            opacity: 0.85;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tsu-stat-card--total {
            background: linear-gradient(135deg, #094b54 0%, #0c6170 100%);
            color: #ffffff;
        }
        .tsu-stat-card--permissions {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
        }
        .tsu-stat-card--core {
            background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
            color: #ffffff;
        }
        .tsu-stat-card--users {
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
            color: #ffffff;
        }

        .tsu-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 16px rgba(9, 75, 84, 0.06);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .tsu-card__header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .tsu-card__title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="{{ $menuIcon }} mr-2" style="color: #094b54;"></i> {{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">System Management</li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
                    <i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {!! session('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- 4 Stat Cards --}}
            <div class="tsu-stat-grid-role">
                <div class="tsu-stat-card tsu-stat-card--total">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Total Roles</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-user-shield"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['total_roles'] }} <span class="tsu-stat-card__unit">Role</span></div>
                        <div class="tsu-stat-card__subtext">Semua Role Terdaftar</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--permissions">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Total Permissions</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-key"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['total_permissions'] }} <span class="tsu-stat-card__unit">Izin</span></div>
                        <div class="tsu-stat-card__subtext">Hak Akses Tersedia</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--core">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Role Lokal PMB</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-cube"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['core_roles'] }} <span class="tsu-stat-card__unit">Role</span></div>
                        <div class="tsu-stat-card__subtext">Dikelola Mandiri di PMB</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--users">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Assigned Users</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-users"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['assigned_users'] }} <span class="tsu-stat-card__unit">User</span></div>
                        <div class="tsu-stat-card__subtext">Pengguna Memiliki Role</div>
                    </div>
                </div>
            </div>

            {{-- Main Table Card --}}
            <div class="tsu-card">
                <div class="tsu-card__header">
                    <div class="tsu-card__title">
                        <i class="fas fa-list text-muted mr-1"></i> Data Role & Akses Permissions
                    </div>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-light border shadow-sm" id="btn-reload" title="Refresh Data" style="border-radius: 8px;">
                            <i class="fas fa-sync-alt"></i>
                        </button>

                        <form action="{{ route('admin.system.roles.sync') }}" method="POST" style="display:inline;" class="form-sync">
                            @csrf
                            <button type="button" class="btn btn-sm btn-sync shadow-sm text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); border-radius: 8px;" title="Sinkronisasi Role Global dari Vault">
                                <i class="fas fa-cloud-download-alt mr-1"></i> Sync Roles Vault
                            </button>
                        </form>

                        <a href="{{ route('admin.system.roles.create') }}" class="btn btn-sm btn-create shadow-sm text-white" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
                            <i class="fas fa-plus mr-1"></i> Tambah Role Lokal
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="table-role">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%; text-align: center;">No</th>
                                    <th>Nama Role</th>
                                    <th>Total Permission</th>
                                    <th>Lingkup (Scope)</th>
                                    <th style="width: 15%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Role Container --}}
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content" id="modal-edit-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <div class="text-center p-5">
                    <div class="spinner-border" style="color: #094b54;" role="status"></div>
                    <p class="mt-2 text-muted" style="font-size: 0.85rem;">Sedang memuat data...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#table-role').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('admin.system.roles.json') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'name', name: 'name' },
                    { data: 'permissions_count', name: 'permissions_count', searchable: false },
                    { data: 'is_identity', name: 'is_identity' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[1, 'asc']],
                language: {
                    search: "Cari Role:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ role",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 role",
                    infoFiltered: "(disaring dari _MAX_ total role)",
                    zeroRecords: "Tidak ada data role yang cocok",
                    emptyTable: "Belum ada data role",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
            });

            $('#btn-reload').on('click', function() {
                let $btn = $(this);
                $btn.find('i').addClass('fa-spin');
                table.ajax.reload(function() {
                    $btn.find('i').removeClass('fa-spin');
                });
            });

            // Sync Roles Vault
            $('body').on('click', '.btn-sync', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Sinkronisasi Role?',
                    html: "Sistem akan mengambil data role terbaru dari <b>Homebase Vault</b>.<br><small class='text-muted'>Pastikan server Vault sedang aktif.</small>",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-sync fa-spin mr-1"></i> Ya, Sync Sekarang!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#094b54',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Menghubungkan...',
                            html: 'Mohon tunggu, sedang meminta data ke Homebase...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                        form.submit();
                    }
                });
            });

            // Modal Create
            $('body').on('click', '.btn-create', function(e) {
                e.preventDefault();
                $('#modal-edit').modal('show');
                $('#modal-edit-content').html(`
                    <div class="text-center p-5">
                        <div class="spinner-border" style="color: #094b54;" role="status"></div>
                        <p class="mt-2 text-muted" style="font-size: 0.85rem;">Memuat Form Role Baru...</p>
                    </div>
                `);

                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    success: function(res) {
                        $('#modal-edit-content').html(res);
                    },
                    error: function(xhr) {
                        $('#modal-edit-content').html(`<div class="text-center text-danger p-5">Gagal memuat form role.</div>`);
                    }
                });
            });

            // Modal Edit
            $('body').on('click', '.btn-edit', function(e) {
                e.preventDefault();
                var url = $(this).data('url') || $(this).attr('href');
                if (!url) return;

                $('#modal-edit').modal('show');
                $('#modal-edit-content').html(`
                    <div class="text-center p-5">
                        <div class="spinner-border" style="color: #094b54;" role="status"></div>
                        <p class="mt-2 text-muted" style="font-size: 0.85rem;">Mengambil Data Role...</p>
                    </div>
                `);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#modal-edit-content').html(res);
                    },
                    error: function(xhr) {
                        $('#modal-edit-content').html(`<div class="text-center text-danger p-5">Gagal mengambil data role.</div>`);
                    }
                });
            });

            // Delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var name = $(this).closest('tr').find('td:eq(1)').text().trim();

                Swal.fire({
                    title: 'Hapus Role Lokal?',
                    html: `Apakah Anda yakin ingin menghapus role: <b>${name}</b>?<br><small class='text-danger'>Data yang dihapus tidak dapat dikembalikan!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menghapus role...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection

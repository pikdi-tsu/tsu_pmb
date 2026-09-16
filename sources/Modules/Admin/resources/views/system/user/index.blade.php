@extends('admin::template/admin/header')
@section('title', $title)

@section('link_href')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        .tsu-stat-grid-user {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 992px) {
            .tsu-stat-grid-user {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .tsu-stat-grid-user {
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
        .tsu-stat-card--tendik {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
        }
        .tsu-stat-card--dosen {
            background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
            color: #ffffff;
        }
        .tsu-stat-card--admin {
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
            <div class="tsu-stat-grid-user">
                <div class="tsu-stat-card tsu-stat-card--total">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Total Pengguna</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-users"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['total'] }} <span class="tsu-stat-card__unit">Akun</span></div>
                        <div class="tsu-stat-card__subtext">Terdaftar di Modul PMB</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--tendik">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Tendik</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-user-tie"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['tendik'] }} <span class="tsu-stat-card__unit">Orang</span></div>
                        <div class="tsu-stat-card__subtext">Tenaga Kependidikan</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--dosen">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Dosen</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-chalkboard-teacher"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['dosen'] }} <span class="tsu-stat-card__unit">Orang</span></div>
                        <div class="tsu-stat-card__subtext">Tenaga Pendidik</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--admin">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Admin PMB</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-shield-alt"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['admin'] }} <span class="tsu-stat-card__unit">Akun</span></div>
                        <div class="tsu-stat-card__subtext">Pengelola Aplikasi</div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="tsu-card">
                <div class="tsu-card__header">
                    <div class="tsu-card__title">
                        <i class="fas fa-users-cog text-muted mr-1"></i> Daftar Akun Pengguna Modul
                    </div>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-light border shadow-sm" id="btn-reload" title="Refresh Data" style="border-radius: 8px;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <form action="{{ route('admin.system.users.sync') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="button" class="btn btn-sm text-white shadow-sm font-weight-bold btn-sync-user" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
                                <i class="fas fa-cloud-download-alt mr-1"></i> Sync User Vault
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="table-user">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%; text-align: center;">No</th>
                                    <th style="width: 6%; text-align: center;">Avatar</th>
                                    <th>Nama & NIP</th>
                                    <th>Email</th>
                                    <th>Role Ditugaskan</th>
                                    <th style="width: 10%; text-align: center;">Status</th>
                                    <th>Login Terakhir</th>
                                    <th style="width: 12%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Edit Role --}}
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="modal-edit-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <div class="text-center p-5">
                    <div class="spinner-border" style="color: #094b54;" role="status"></div>
                    <p class="mt-2 text-muted" style="font-size: 0.85rem;">Memuat data user...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#table-user').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('admin.system.users.json') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'avatar', name: 'avatar', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'roles', name: 'roles', orderable: false, searchable: false },
                    { data: 'isactive', name: 'isactive', className: 'text-center' },
                    { data: 'last_login_at', name: 'last_login_at', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[6, 'desc']],
                language: {
                    search: "Cari User:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ user",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 user",
                    infoFiltered: "(disaring dari _MAX_ total user)",
                    zeroRecords: "Tidak ada data user yang cocok",
                    emptyTable: "Belum ada data user internal",
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

            // Sync User Vault
            $('body').on('click', '.btn-sync-user', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Sinkronisasi User?',
                    html: "Sistem akan menyelaraskan akun pengguna dari <b>TSU Homebase Vault</b>.<br><small class='text-muted'>Proses ini mungkin memerlukan beberapa detik.</small>",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-sync fa-spin mr-1"></i> Ya, Sync!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#094b54',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Sinkronisasi...',
                            html: 'Memeriksa dan memperbarui data akun...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                        form.submit();
                    }
                });
            });

            // Edit Role Modal
            $('body').on('click', '.btn-edit-role', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = "{{ route('admin.system.users.edit', ':id') }}".replace(':id', id);

                $('#modal-edit').modal('show');
                $('#modal-edit-content').html(`
                    <div class="text-center p-5">
                        <div class="spinner-border" style="color: #094b54;" role="status"></div>
                        <p class="mt-2 text-muted" style="font-size: 0.85rem;">Memuat Data Role Pengguna...</p>
                    </div>
                `);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#modal-edit-content').html(res);
                    },
                    error: function(xhr) {
                        $('#modal-edit-content').html(`<div class="text-center text-danger p-5">Gagal mengambil data user.</div>`);
                    }
                });
            });

            // Delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var name = $(this).closest('tr').find('td:eq(2)').text().trim();

                Swal.fire({
                    title: 'Hapus User?',
                    html: `Apakah Anda yakin ingin mengeluarkan akun <b>${name}</b> dari modul PMB?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection

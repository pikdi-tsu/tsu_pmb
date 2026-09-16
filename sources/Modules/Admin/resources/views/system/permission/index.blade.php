@extends('admin::template/admin/header')
@section('title', $title)

@section('link_href')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        .tsu-stat-grid-perm {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 992px) {
            .tsu-stat-grid-perm {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .tsu-stat-grid-perm {
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
        .tsu-stat-card--pmb {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
        }
        .tsu-stat-card--system {
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
            <div class="tsu-stat-grid-perm">
                <div class="tsu-stat-card tsu-stat-card--total">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Total Permissions</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-key"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['total'] }} <span class="tsu-stat-card__unit">Izin</span></div>
                        <div class="tsu-stat-card__subtext">Semua Izin Terdaftar</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--pmb">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Modul PMB</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-user-graduate"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['pmb'] }} <span class="tsu-stat-card__unit">Izin</span></div>
                        <div class="tsu-stat-card__subtext">Prefix pmb:*</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--system">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Modul System</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-cogs"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['system'] }} <span class="tsu-stat-card__unit">Izin</span></div>
                        <div class="tsu-stat-card__subtext">Prefix system:*</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--users">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Modul Users</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-users"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['users'] }} <span class="tsu-stat-card__unit">Izin</span></div>
                        <div class="tsu-stat-card__subtext">Prefix users:*</div>
                    </div>
                </div>
            </div>

            {{-- 2 Columns: Table on Left, Form on Right --}}
            <div class="row">
                <div class="col-lg-7">
                    <div class="tsu-card">
                        <div class="tsu-card__header">
                            <div class="tsu-card__title">
                                <i class="fas fa-list text-muted mr-1"></i> Daftar Role Permissions
                            </div>
                            <button type="button" class="btn btn-sm btn-light border shadow-sm" id="btn-reload" title="Refresh Data" style="border-radius: 8px;">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped w-100" id="table-permission">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 8%; text-align: center;">No</th>
                                            <th>Nama Permission</th>
                                            <th style="width: 15%; text-align: center;">Guard</th>
                                            <th style="width: 15%; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="tsu-card">
                        <div class="tsu-card__header" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); color: #ffffff;">
                            <div class="tsu-card__title text-white" id="form-title">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Permission Baru
                            </div>
                        </div>
                        <form action="{{ route('admin.system.permissions.store') }}" method="POST" id="form-permission">
                            @csrf
                            <div class="card-body p-4">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Nama Permission <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-key" style="color: #094b54;"></i></span>
                                        </div>
                                        <input type="text" name="name" id="input-name" class="form-control font-weight-bold"
                                               placeholder="contoh: pmb:pendaftaran:view" required autocomplete="off">
                                    </div>
                                    @error('name')
                                        <small class="text-danger mt-1 d-block font-weight-bold">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="p-3 border rounded" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-info-circle text-primary mr-1"></i> Aturan Format Standar TSU:
                                    </h6>
                                    <p class="text-muted small mb-2">Gunakan format 3 segmen dipisahkan titik dua (<b>:</b>):</p>
                                    <code class="d-block p-2 rounded mb-2" style="background: #ffffff; border: 1px solid #cbd5e1; color: #094b54; font-size: 0.85rem;">
                                        modul:fitur:aksi
                                    </code>
                                    <div class="small text-muted">
                                        Contoh valid:<br>
                                        &bull; <code>pmb:pendaftaran:view</code><br>
                                        &bull; <code>pmb:pendaftaran:create</code><br>
                                        &bull; <code>system:user:view</code><br>
                                        &bull; <code>system:role:edit</code>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light px-4 py-3 border-top d-flex justify-content-end" style="border-color: #e2e8f0 !important;">
                                <button type="submit" class="btn btn-sm px-4 font-weight-bold text-white shadow-sm" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
                                    <i class="fas fa-save mr-1"></i> Simpan Permission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#table-permission').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('admin.system.permissions.json') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'name', name: 'name' },
                    { data: 'guard_name', name: 'guard_name', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[1, 'asc']],
                language: {
                    search: "Cari Permission:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ izin",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 izin",
                    infoFiltered: "(disaring dari _MAX_ total izin)",
                    zeroRecords: "Tidak ada data permission yang cocok",
                    emptyTable: "Belum ada data permission",
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

            // Delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var name = $(this).closest('tr').find('code').text().trim() || 'Permission ini';

                Swal.fire({
                    title: 'Hapus Permission?',
                    html: `Apakah Anda yakin ingin menghapus permission: <b>${name}</b>?<br><small class='text-danger'>Pastikan permission ini tidak sedang dipakai dalam kode aplikasi!</small>`,
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
                            text: 'Sedang menghapus permission...',
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

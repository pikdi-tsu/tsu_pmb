@extends('admin::template/admin/header')
@section('title', $title)

@section('link_href')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        .tsu-stat-grid-menu {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 992px) {
            .tsu-stat-grid-menu {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .tsu-stat-grid-menu {
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
        .tsu-stat-card--root {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
        }
        .tsu-stat-card--sub {
            background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
            color: #ffffff;
        }
        .tsu-stat-card--active {
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
        .tsu-row-root {
            background-color: #f8fafc !important;
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
            <div class="tsu-stat-grid-menu">
                <div class="tsu-stat-card tsu-stat-card--total">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Total Menu</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-bars"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['total'] }} <span class="tsu-stat-card__unit">Menu</span></div>
                        <div class="tsu-stat-card__subtext">Semua Item Sidebar</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--root">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Menu Utama (Root)</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-folder"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['root'] }} <span class="tsu-stat-card__unit">Menu</span></div>
                        <div class="tsu-stat-card__subtext">Kategori Level 1</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--sub">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Sub Menu</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-level-down-alt"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['sub'] }} <span class="tsu-stat-card__unit">Sub</span></div>
                        <div class="tsu-stat-card__subtext">Item Anak / Turunan</div>
                    </div>
                </div>

                <div class="tsu-stat-card tsu-stat-card--active">
                    <div class="tsu-stat-card__top">
                        <span class="tsu-stat-card__label">Status Aktif</span>
                        <div class="tsu-stat-card__icon-badge"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <div>
                        <div class="tsu-stat-card__value">{{ $stats['active'] }} <span class="tsu-stat-card__unit">Menu</span></div>
                        <div class="tsu-stat-card__subtext">Tampil di Sidebar</div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="tsu-card">
                <div class="tsu-card__header">
                    <div class="tsu-card__title">
                        <i class="fas fa-sitemap text-muted mr-1"></i> Struktur Navigasi Sidebar
                    </div>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-light border shadow-sm" id="btn-reload" title="Refresh Data" style="border-radius: 8px;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="btn btn-sm text-white shadow-sm font-weight-bold" data-toggle="modal" data-target="#modal-tambah-menu" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
                            <i class="fas fa-plus mr-1"></i> Tambah Menu
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="table-menu">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nama Menu (Hierarki)</th>
                                    <th>Icon</th>
                                    <th>Route</th>
                                    <th>Permission</th>
                                    <th style="width: 10%; text-align: center;">Status</th>
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

    {{-- Modal Tambah Menu --}}
    <div class="modal fade" id="modal-tambah-menu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); padding: 1.1rem 1.4rem;">
                    <h5 class="modal-title font-weight-bold" style="font-size: 1.05rem;">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Menu Sidebar Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.system.menus.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-sm text-dark">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Data Beasiswa" required style="border-radius: 8px;">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-sm text-dark">Icon Class (FontAwesome)</label>
                                    <input type="text" name="icon" class="form-control" placeholder="Contoh: fas fa-file-alt" value="fas fa-box" style="border-radius: 8px;">
                                    <small class="text-muted">Default: <code>fas fa-box</code></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-sm text-dark">Route Laravel</label>
                                    <input type="text" name="route" class="form-control" placeholder="admin.databeasiswa.show" style="border-radius: 8px;">
                                    <small class="text-muted">Isi <code>#</code> jika berupa dropdown induk.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-sm text-dark">Permission Kunci</label>
                                    <select name="permission_name" class="form-control select2" style="width: 100%;">
                                        <option value="">-- Public (Bebas Akses) --</option>
                                        @foreach($permissions as $perm)
                                            <option value="{{ $perm }}">{{ $perm }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-sm text-dark">Parent Menu (Induk)</label>
                                    <select name="parent_id" class="form-control select2" style="width: 100%;">
                                        <option value="">-- Jadikan Menu Utama (Root) --</option>
                                        @foreach($parents as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-sm text-dark">Urutan Tampil (Order)</label>
                                    <input type="number" name="order" class="form-control" value="0" style="border-radius: 8px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-sm text-dark">Status Visibilitas</label>
                                    <div class="custom-control custom-switch pt-2">
                                        <input type="checkbox" class="custom-control-input" id="create_isactive" name="isactive" value="1" checked>
                                        <label class="custom-control-label font-weight-bold text-sm text-success" for="create_isactive">Aktif (Tampil di Sidebar)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between p-3" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                        <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn text-white px-4 font-weight-bold" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Menu (AJAX Loaded) --}}
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" id="modal-edit-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <div class="text-center p-5">
                    <div class="spinner-border" style="color: #094b54;" role="status"></div>
                    <p class="mt-2 text-muted" style="font-size: 0.85rem;">Memuat data menu...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#table-menu').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ordering: false, // Mempertahankan sort urutan treeview
                ajax: "{{ route('admin.system.menus.json') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'icon', name: 'icon' },
                    { data: 'route', name: 'route' },
                    { data: 'permission', name: 'permission' },
                    { data: 'status', name: 'status', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                language: {
                    search: "Cari Menu:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ menu",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 menu",
                    infoFiltered: "(disaring dari _MAX_ total menu)",
                    zeroRecords: "Tidak ada data menu yang cocok",
                    emptyTable: "Belum ada data menu sidebar",
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

            // Edit Modal
            $('body').on('click', '.btn-edit-menu', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $('#modal-edit').modal('show');
                $('#modal-edit-content').html(`
                    <div class="text-center p-5">
                        <div class="spinner-border" style="color: #094b54;" role="status"></div>
                        <p class="mt-2 text-muted" style="font-size: 0.85rem;">Memuat Data Menu...</p>
                    </div>
                `);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#modal-edit-content').html(res);
                    },
                    error: function(xhr) {
                        $('#modal-edit-content').html(`<div class="text-center text-danger p-5">Gagal mengambil data menu.</div>`);
                    }
                });
            });

            // Delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Hapus Menu?',
                    text: 'Apakah Anda yakin ingin menghapus menu ini?',
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

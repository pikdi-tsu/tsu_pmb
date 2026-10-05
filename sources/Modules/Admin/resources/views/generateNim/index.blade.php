@extends('admin::template/admin/header')

@section('title', $title)

@section('link_href')
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $menu }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-filter"></i> Filter Data Pendaftaran</h3>
                    <a href="{{ route('admin.nim.exportexcel') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel mr-1"></i>Export Rekap NIM
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.nim.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tahun Akademik</label>
                                    <select name="tahun" class="form-control select2" onchange="this.form.submit()">
                                        <option value="">-- Pilih Tahun --</option>
                                        @foreach ($tahun_list as $thn)
                                            <option value="{{ $thn }}"
                                                {{ $tahun_selected == $thn ? 'selected' : '' }}>
                                                {{ $thn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Batch Pendaftaran</label>
                                    <select name="batch_id" class="form-control select2">
                                        <option value="">-- Pilih Batch --</option>
                                        @if (!empty($batch_list))
                                            @foreach ($batch_list as $batch)
                                                <option value="{{ $batch->id }}"
                                                    {{ $batch_selected == $batch->id ? 'selected' : '' }}>
                                                    {{ $batch->nama_batch }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tampilkan Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($batch_selected)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-users"></i> Data Mahasiswa Lolos (Tahap 11)</h3>

                        <div class="ml-auto d-flex">
                            <form action="{{ route('admin.nim.proses') }}" method="POST"
                                onsubmit="return confirm('Yakin ingin men-generate NIM untuk mahasiswa yang belum memiliki NIM di Batch ini?');">
                                @csrf
                                <form id="form-generate">
                                    <input type="hidden" name="batch_id" id="generate-batch-id"
                                        value="{{ $batch_selected }}">
                                    <button type="button" id="btn-generate" class="btn btn-success btn-sm">
                                        <i class="fas fa-cogs"></i> Generate NIM Sekarang
                                    </button>
                                </form>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="tabel-nim" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Pendaftaran</th>
                                    <th>Nama Batch</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Jurusan Diterima</th>
                                    <th>Jalur Daftar</th>
                                    <th>NIM</th>
                                    <th>Generate Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mahasiswa as $index => $mhs)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $mhs->KodePendaftaran }}</td>
                                        <td>{{ $mhs->batch->nama_batch ?? '-' }}</td>
                                        <td>{{ $mhs->biodata->nama ?? '-' }}</td>
                                        <td>{{ $mhs->jurusan_acc->jurusan ?? '-' }}</td>
                                        <td>{{ $mhs->jalur->jenis_pendaftaran ?? '-' }}</td>
                                        <td class="text-center">
                                            @if (!empty($mhs->biodata->nim))
                                                <span class="badge bg-warning"
                                                    style="font-size: 16px;">{{ $mhs->biodata->nim }}</span>
                                            @else
                                                <span class="badge bg-secondary">Belum ada NIM</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($mhs->biodata->validator_nik))
                                                <span class="badge bg-info">{{ $mhs->biodata->validator_nik }}</span>
                                                <br>
                                                <small class="text-muted font-weight-bold">
                                                    {{ namaku($mhs->biodata->validator_nik) ?? 'Admin' }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger font-weight-bold">
                                            Tidak ada data mahasiswa yang valid (Tahap 11) di batch ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            // Setup CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').select2();

            // Set default isEditing ke false (karena halaman ini tidak ada mode edit)
            window.isEditing = false;

            loadEvent();

            function loadEvent() {
                initTabelNim();
                prosesGenerateNim();
            }

            // 1. Penerapan di DataTables
            function initTabelNim() {
                if ($('#tabel-nim').length) {
                    $('#tabel-nim').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        // Menyamakan standar dengan menu lain
                        "language": {
                            "processing": '<i class="fa fa-spinner fa-lg fa-spin"></i>',
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                        }
                    });
                }
            }

            // 2. Penerapan di AJAX Generate
            function prosesGenerateNim() {
                $('#btn-generate').click(function(e) {
                    e.preventDefault();

                    let batch_id = $('#generate-batch-id').val();

                    if (!batch_id) {
                        Swal.fire('Peringatan!', 'Silakan pilih batch terlebih dahulu.', 'warning');
                        return false;
                    }

                    Swal.fire({
                        title: "Konfirmasi Generate",
                        text: "Yakin ingin men-generate NIM untuk mahasiswa yang belum memiliki NIM di Batch ini?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: "Ya, Generate!",
                        cancelButtonText: "Batal",
                        confirmButtonColor: "#28a745"
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "{!! route('admin.nim.proses') !!}",
                                data: {
                                    batch_id: batch_id
                                },
                                dataType: "JSON",
                                beforeSend: function() {
                                    // Panggil loading overlay global yang ada di master header-mu
                                    $('#loading').show();
                                },
                                success: function(data) {
                                    // Matikan loading hanya jika bukan mode edit (sesuai standarmu)
                                    if (!window.isEditing) {
                                        $('#loading').hide();
                                    }

                                    if (data.status == false) {
                                        Swal.fire('Informasi', data.message, 'warning');
                                    } else {
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: data.message,
                                            icon: 'success'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    if (!window.isEditing) {
                                        $('#loading').hide();
                                    }

                                    let errorMsg = 'Silakan Hubungi Tim IT !';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMsg = xhr.responseJSON.message;
                                    }

                                    Swal.fire({
                                        title: 'Gagal Memproses!',
                                        text: errorMsg,
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>
@endsection

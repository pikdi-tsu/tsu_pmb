@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')
<style>
    .pipeline-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
    }
    @media (max-width: 1250px) {
        .pipeline-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
    }
    @media (max-width: 576px) {
        .pipeline-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    .pipeline-card {
        border-radius: 12px;
        color: #fff;
        padding: 14px 14px 12px 14px;
        position: relative;
        overflow: hidden;
        min-height: 112px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: none;
    }
    .pipeline-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.16);
    }
    .pipeline-card .pipe-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .pipeline-card .pipe-step-badge {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        background: rgba(255,255,255,0.25);
        padding: 2px 8px;
        border-radius: 20px;
        display: inline-block;
    }
    .pipeline-card .pipe-arrow-indicator {
        font-size: 0.75rem;
        opacity: 0.65;
    }
    .pipeline-card .pipe-num {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .pipeline-card .pipe-title {
        font-size: 0.85rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        opacity: 0.98;
    }
    .pipeline-card .pipe-desc {
        font-size: 0.72rem;
        opacity: 0.85;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }
    .pipeline-card .pipe-watermark {
        position: absolute;
        right: 8px;
        bottom: 6px;
        font-size: 3.2rem;
        opacity: 0.16;
        pointer-events: none;
        transition: transform 0.3s ease;
    }
    .pipeline-card:hover .pipe-watermark {
        transform: scale(1.1) rotate(-5deg);
    }

    .chart-card   { border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); border: none; }
    .batch-row-aktif         { background: #f0fff4 !important; }
    .batch-row-akan_datang   { background: #fffde7 !important; }
    .batch-row-berakhir      { background: #fafafa !important; color: #aaa; }
    .progress-kuota { border-radius: 4px; height: 8px; }
</style>
@endsection

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-tachometer-alt text-primary mr-2"></i>Dashboard PMB</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            {{-- ===== WARNING BATCH HAMPIR BERAKHIR ===== --}}
            @if($batchHampirBerakhir->count() > 0)
                @foreach($batchHampirBerakhir as $bw)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian!</strong> Batch <strong>{{ $bw['nama'] }}</strong> akan berakhir dalam
                        <strong>{{ $bw['sisa_hari'] }} hari</strong> ({{ \Carbon\Carbon::parse($bw['tglselesai'])->format('d/m/Y') }}).
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endforeach
            @endif

            {{-- ===== PIPELINE FUNNEL CARDS ===== --}}
            <div class="card card-outline card-primary mb-4 shadow-sm" style="border-radius: 12px;">
                <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 font-weight-bold" style="font-size: 1.05rem;">
                        <i class="fas fa-stream text-primary mr-2"></i>Pipeline & Funnel Penerimaan Mahasiswa Baru
                    </h5>
                    <span class="badge badge-light px-3 py-2 text-primary font-weight-bold" style="font-size: 0.85rem; border: 1px solid #e2e8f0; border-radius: 20px;">
                        <i class="fas fa-check-circle text-success mr-1"></i> Konversi Akhir: 
                        {{ $total > 0 ? round($sudahNIM / $total * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="card-body pt-2 pb-3">
                    <div class="pipeline-grid">

                        <!-- Tahap 1 -->
                        <div class="pipeline-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <div class="pipe-top">
                                <span class="pipe-step-badge">Tahap 1</span>
                                <i class="fas fa-chevron-right pipe-arrow-indicator d-none d-xl-inline"></i>
                            </div>
                            <div class="pipe-num">{{ number_format($total) }}</div>
                            <div class="pipe-title">Total Pendaftar</div>
                            <div class="pipe-desc">Akun terdaftar</div>
                            <i class="fas fa-users pipe-watermark"></i>
                        </div>

                        <!-- Tahap 2 -->
                        <div class="pipeline-card" style="background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);">
                            <div class="pipe-top">
                                <span class="pipe-step-badge">Tahap 2</span>
                                <i class="fas fa-chevron-right pipe-arrow-indicator d-none d-xl-inline"></i>
                            </div>
                            <div class="pipe-num">{{ number_format($sudahBayarPMB) }}</div>
                            <div class="pipe-title">Bayar PMB Lunas</div>
                            <div class="pipe-desc">
                                {{ $total > 0 ? round($sudahBayarPMB / $total * 100) : 0 }}% dari pendaftar
                            </div>
                            <i class="fas fa-money-bill-wave pipe-watermark"></i>
                        </div>

                        <!-- Tahap 3 -->
                        <div class="pipeline-card" style="background: linear-gradient(135deg, #f7971e 0%, #e67e22 100%);">
                            <div class="pipe-top">
                                <span class="pipe-step-badge">Tahap 3</span>
                                <i class="fas fa-chevron-right pipe-arrow-indicator d-none d-xl-inline"></i>
                            </div>
                            <div class="pipe-num">{{ number_format($berkasApproved) }}</div>
                            <div class="pipe-title">Berkas Disetujui</div>
                            <div class="pipe-desc">
                                {{ $sudahBayarPMB > 0 ? round($berkasApproved / $sudahBayarPMB * 100) : 0 }}% terverifikasi
                            </div>
                            <i class="fas fa-file-check pipe-watermark"></i>
                        </div>

                        <!-- Tahap 4 -->
                        <div class="pipeline-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="pipe-top">
                                <span class="pipe-step-badge">Tahap 4</span>
                                <i class="fas fa-chevron-right pipe-arrow-indicator d-none d-xl-inline"></i>
                            </div>
                            <div class="pipe-num">{{ number_format($lulusTest) }}</div>
                            <div class="pipe-title">Lulus Seleksi</div>
                            <div class="pipe-desc">
                                {{ $sudahBayarPMB > 0 ? round($lulusTest / $sudahBayarPMB * 100) : 0 }}% lolos seleksi
                            </div>
                            <i class="fas fa-graduation-cap pipe-watermark"></i>
                        </div>

                        <!-- Tahap 5 -->
                        <div class="pipeline-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <div class="pipe-top">
                                <span class="pipe-step-badge">Tahap 5</span>
                                <i class="fas fa-chevron-right pipe-arrow-indicator d-none d-xl-inline"></i>
                            </div>
                            <div class="pipe-num">{{ number_format($sudahBayarUKT) }}</div>
                            <div class="pipe-title">Bayar UKT Lunas</div>
                            <div class="pipe-desc">
                                {{ $lulusTest > 0 ? round($sudahBayarUKT / $lulusTest * 100) : 0 }}% daftar ulang
                            </div>
                            <i class="fas fa-receipt pipe-watermark"></i>
                        </div>

                        <!-- Tahap 6 -->
                        <div class="pipeline-card" style="background: linear-gradient(135deg, #b00020 0%, #e91e63 100%);">
                            <div class="pipe-top">
                                <span class="pipe-step-badge bg-warning text-dark font-weight-bold">Tahap 6 &bull; Final</span>
                            </div>
                            <div class="pipe-num">{{ number_format($sudahNIM) }}</div>
                            <div class="pipe-title">Dapat NIM</div>
                            <div class="pipe-desc">Mahasiswa baru aktif</div>
                            <i class="fas fa-id-card pipe-watermark"></i>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ===== CHARTS ROW ===== --}}
            <div class="row mt-3">
                <!-- Donut — Per Jalur -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="card chart-card h-100">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-pie text-info mr-1"></i>Pendaftar per Jalur</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px">
                            <canvas id="chartJalur"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Bar — Top Prodi -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="card chart-card h-100">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-bar text-warning mr-1"></i>Top 5 Prodi Favorit</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px">
                            <canvas id="chartProdi"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Line — Tren Harian -->
                <div class="col-lg-4 col-12 mb-3">
                    <div class="card chart-card h-100">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-line text-success mr-1"></i>Tren 30 Hari Terakhir</h5>
                        </div>
                        <div class="card-body d-flex align-items-center" style="min-height:220px">
                            <canvas id="chartTren"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== REKAP BATCH AKTIF ===== --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card chart-card">
                        <div class="card-header border-0">
                            <h5 class="card-title mb-0"><i class="fas fa-layer-group text-primary mr-1"></i>Status Batch Pendaftaran</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Batch</th>
                                            <th>Tgl Mulai</th>
                                            <th>Tgl Selesai</th>
                                            <th>Sisa Hari</th>
                                            <th>Terisi / Kuota</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($batchAktif as $i => $b)
                                            <tr class="batch-row-{{ $b['status'] }}">
                                                <td>{{ $i + 1 }}</td>
                                                <td><strong>{{ $b['nama'] }}</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($b['tglmulai'])->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($b['tglselesai'])->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($b['status'] === 'aktif')
                                                        <span class="{{ $b['sisa_hari'] <= 3 ? 'text-danger font-weight-bold' : '' }}">
                                                            {{ $b['sisa_hari'] }} hari
                                                        </span>
                                                    @elseif($b['status'] === 'akan_datang')
                                                        <span class="text-warning">Belum mulai</span>
                                                    @else
                                                        <span class="text-muted">Selesai</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php $pct = $b['kuota'] > 0 ? min(100, round($b['terisi'] / $b['kuota'] * 100)) : 0; @endphp
                                                    <small>{{ $b['terisi'] }} / {{ $b['kuota'] }}</small>
                                                    <div class="progress progress-kuota mt-1">
                                                        <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : ($pct >= 60 ? 'bg-warning' : 'bg-success') }}"
                                                            style="width: {{ $pct }}%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($b['status'] === 'aktif')
                                                        <span class="badge badge-success">Aktif</span>
                                                    @elseif($b['status'] === 'akan_datang')
                                                        <span class="badge badge-warning text-dark">Akan Datang</span>
                                                    @else
                                                        <span class="badge badge-secondary">Berakhir</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada batch aktif</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
@endsection

@section('script')
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var perJalur   = {!! $perJalurJson !!};
    var topProdi   = {!! $topProdiJson !!};
    var trenLabels = {!! $trenLabelsJson !!};
    var trenData   = {!! $trenDataJson !!};

    var COLORS = [
        '#3a7bd5','#11998e','#f7971e','#8e2de2','#c94b4b',
        '#56ab2f','#00b4db','#fd746c','#a18cd1','#ffecd2'
    ];

    // ---- Donut: Per Jalur ----
    if (perJalur.length > 0) {
        new Chart(document.getElementById('chartJalur').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: perJalur.map(d => d.label),
                datasets: [{ data: perJalur.map(d => d.total), backgroundColor: COLORS, borderWidth: 2 }]
            },
            options: {
                responsive: true,
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } } },
                cutoutPercentage: 60,
                cutout: '60%'
            }
        });
    } else {
        document.getElementById('chartJalur').closest('.card-body').innerHTML = '<p class="text-muted text-center my-auto">Belum ada data</p>';
    }

    // ---- Bar: Top Prodi ----
    if (topProdi.length > 0) {
        new Chart(document.getElementById('chartProdi').getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: topProdi.map(d => d.label),
                datasets: [{
                    label: 'Pendaftar',
                    data: topProdi.map(d => d.total),
                    backgroundColor: COLORS.slice(0,5),
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                legend: { display: false },
                plugins: { legend: { display: false } },
                scales: {
                    xAxes: [{
                        ticks: { beginAtZero: true, stepSize: 1, precision: 0 }
                    }],
                    yAxes: [{
                        ticks: { fontSize: 11 }
                    }],
                    x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                    y: { ticks: { font: { size: 11 } } }
                }
            }
        });
    } else {
        document.getElementById('chartProdi').closest('.card-body').innerHTML = '<p class="text-muted text-center my-auto">Belum ada data</p>';
    }

    // ---- Line: Tren Harian ----
    new Chart(document.getElementById('chartTren').getContext('2d'), {
        type: 'line',
        data: {
            labels: trenLabels,
            datasets: [{
                label: 'Pendaftar',
                data: trenData,
                borderColor: '#3a7bd5',
                backgroundColor: 'rgba(58,123,213,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            legend: { display: false },
            plugins: { legend: { display: false } },
            scales: {
                xAxes: [{
                    ticks: { maxTicksLimit: 10, fontSize: 10 }
                }],
                yAxes: [{
                    ticks: { beginAtZero: true, min: 0, stepSize: 1, precision: 0 }
                }],
                x: { ticks: { maxTicksLimit: 10, font: { size: 10 } } },
                y: { beginAtZero: true, min: 0, ticks: { stepSize: 1, precision: 0 } }
            }
        }
    });
});
</script>
@endsection

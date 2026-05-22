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
                    <h1>Admin PMB Universitas Tiga Serangkai</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        {{-- <li class="breadcrumb-item active">Starter Page</li> --}}
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
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $total }}</h3>

                            <p>Total Pendaftar</p>
                        </div>
                        {{-- <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> --}}
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $validasi }}</h3>

                            <p>Konfirmasi Pendaftaran</p>
                        </div>
                        {{-- <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div> --}}
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $aktif }}</h3>

                            <p>Pendaftar Aktif</p>
                        </div>
                        {{-- <div class="icon">
                            <i class="ion ion-person"></i>
                        </div> --}}
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $blmvalidasi }}</h3>

                            <p>Belum Konfirmasi</p>
                        </div>
                        {{-- <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div> --}}
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Featureddd</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Special title treatment</h6>

                            <p class="card-text">With supporting text below as a natural lead-in to additional content.
                                {{ rupiah(50000) }} <i class="fas fa-check text-green"></i>
                            </p>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.
                                {{ rupiah(50000) }} <i class="fas fa-check-circle text-green"></i>
                            </p>
                            <a href="#" class="btn btn-primary" id="testing-btn">Go somewhere</a>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection
@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
    </script>
@endsection

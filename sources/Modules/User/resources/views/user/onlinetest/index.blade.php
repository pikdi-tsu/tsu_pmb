@extends('user::layouts/halamanbelakang/header')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Selamat Datang di PMB Universitas Tiga Serangkai</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('Dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$menu}}</li>
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
                    {{-- @for ($i = 0; $i < 10; $i++) --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">
                                {{$menu}} <span class="badge bg-success text-bold">{{$data->KodePendaftaran}}</span>
                                <span class="text-bold float-right" id="timer"></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <span class="text-bold">Perhatian</span><br>
                            <code>*</code> <span class="text-bold">Waktu Pengerjaan Test 60 Menit</span> <br>
                            <code>*</code> <span class="text-bold">Jika sudah selesai sebelum waktu habis, bisa di klik Submit</span> <br>
                            <code>*</code> <span class="text-bold">Jika waktu pengerjaan habis, maka halaman akan otomatis reload </span><br>
                            <code>*</code> <span class="text-bold">Soal adalah type multiplechoice dan mempunyai 1 jawaban yang benar</span> <br>
                            <code>*</code> <span class="text-bold">Waktu Pengerjaan ada disebelah kanan atas jika test sudah dimulai</span> <br>
                            <code>*</code> <span class="text-bold">Tekan tombol <button type="button" class="btn btn-sm btn-primary" disabled>Mulai Test</button> untuk memulai test</span> <br>

                            <hr>

                            <div class="text-center">
                                <span class="text-bold" style="font-size: 20px;text-align: center;">Test Online Universitas Tiga Serangkai</span> <br>
                                <span class="text-bold" style="font-size: 18px;">Tahun Ajaran {{$data->batch->tahun_akademik}}</span> <br>
                                <span class="text-bold" style="font-size: 18px;">{{$data->batch->nama_batch}}</span> <br>
                                <button type="button" id="btnMulai" class="btn btn-sm btn-primary">Mulai Test</button>
                            </div>
                            <hr>
                            <!-- <span class="text-bold">Skor Test : {{$data->nilai_test ? $data->nilai_test : 0}}</span> -->
                            <hr>
                            <div class="row justify-content-center" style="margin-top: 20px;display: none;" id="show-test">
                                <div class="col-md-6">
                                    <form id="form-test" method="POST" action="{{route('test.save')}}">
                                        @csrf
                                        <input type="hidden" id="IdPendaftaran" name="IdPendaftaran" value="{{encrypt($data->KodePendaftaran)}}">
                                        @foreach ($soal as $row => $s)
                                            @php
                                                $show = $row==0 ? '' : 'none';
                                            @endphp
                                            <div id="page-{{$row+1}}" style="display: {{$show}};">
                                                <div class="form-group mb-3">
                                                    <label>Kode Soal: <span class="badge bg-warning">{{$s->kode_soal}}</span></label> <br>
                                                    <label>{{$row+1}}. {{$s->soal_test}}</label> <br>
                                                    @foreach ($s->jawaban as $key => $j)
                                                        <input type="radio" name="jawaban[{{$row}}]" value="{{$j->id_jawaban}}">
                                                        <span>{{$j->pilihan}}. {{$j->jawaban}}</span><br>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="form-group mb-3">
                                            <button type="button" id="btn-prev" style="display: none;" class="btn btn-secondary btn-sm">Prev</button>
                                            <button type="button" id="btn-simpan" style="display: none;" class="btn btn-success btn-sm float-right">Simpan</button>
                                            <button type="button" id="btn-next" class="btn btn-secondary btn-sm float-right">Next</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endfor --}}
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
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

            $('.select2').select2()

            let no = 1;
            let maks = '{{count($soal)}}'

            let countdown;
            let duration = 60 * 60;

            loadEvent()

            function loadEvent()
            {
                NextEvent()
                PrevEvent()
                StartEvent()
                SubmitEvent()
            }

            function StartEvent()
            {
                $('#btnMulai').on("click", function() {
                    let param = $('#IdPendaftaran').val()
                    Swal.fire({
                        title: "Information",
                        text: "Apakah Anda Akan Memulai Test Sekarang ?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if(result.value){
                            $.ajax({
                                type: "GET",
                                url: '{!! url('OnlineTest/cek_test') !!}'+'/'+param,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    if(data.hasil==1){
                                        notifalert('Information','Anda Sudah tidak bisa Mengerjakan Test Online !','warning')
                                    }else{
                                        notifalert('Information','Selamat Mengerjakan Test !','success')
                                        $('#btnMulai').hide(); // sembunyikan tombol
                                        startTimer();
                                        $('#show-test').show()
                                    }
                                },
                                error: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Gagal Memulai Test !',
                                        text: 'Silahkan Hubungi Admin PMB TSU',
                                        icon: 'error'
                                    }).then((result) => {

                                    });
                                    return;
                                }
                            });
                        }else{
                            return false;
                        }
                    });
                });
            }

            function NextEvent()
            {
                $('#btn-next').click(function (e) {
                    e.preventDefault();
                    no++;
                    let noprev = no-1;
                    $('#page-'+no).show()
                    $('#page-'+noprev).hide()
                    if(no==maks){
                        $(this).hide()
                        $('#btn-simpan').show()
                    }else{
                        $(this).show()
                        $('#btn-simpan').hide()
                        if(no>1){
                           $('#btn-prev').show()
                        }else{
                            $('#btn-prev').hide()
                        }
                    }
                });
            }

            function PrevEvent()
            {
                $('#btn-prev').click(function (e) {
                    e.preventDefault();
                    no--;
                    let noprev = no+1;
                    $('#page-'+no).show()
                    $('#page-'+noprev).hide()
                    if(no==1){
                        $(this).hide()
                        $('#btn-simpan').hide()
                        $('#btn-next').show()
                    }else{
                       $(this).show()
                        $('#btn-simpan').hide()
                       if(no>1){
                           $('#btn-next').show()
                        }else{
                            $('#btn-next').hide()
                        }

                    }
                });
            }

            function SubmitEvent()
            {
                $('#btn-simpan').click(function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Information",
                        text: "Apakah Anda yakin ingin menyelesaikan test ?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if(result.value){
                            $('#loading').show()
                            clearInterval(countdown);
                            $("#form-test").submit();
                        }else{
                            return false;
                        }
                    });
                });
            }

            function formatTime(seconds) {
                let m = Math.floor(seconds / 60);
                let s = seconds % 60;
                return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }

            function startTimer() {
                let timeLeft = duration;
                $("#timer").text(formatTime(timeLeft));

                countdown = setInterval(function() {
                    timeLeft--;
                    $("#timer").text(formatTime(timeLeft));

                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        $('#loading').show()
                        notifalert('Information','Waktu habis! Jawaban otomatis disimpan.','success')
                        // alert("Waktu habis! Jawaban otomatis disimpan.");
                        $("#form-test").submit();
                    }
                }, 1000);
            }
        });

    </script>
@endsection

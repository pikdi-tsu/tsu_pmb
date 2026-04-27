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
                    <h1>{{$menu}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{$menu}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row" style="display: none;">
                                <div class="col-lg-2">
                                    <select class="form-control select2" id="kategori" name="kategori" required>
                                        <option value="" selected disabled>-- Pilih Jenis Pembayaran --</option>
                                        <option value="{{encrypt('pendaftaran')}}">Pendaftaran</option>
                                        <option value="{{encrypt('ukt')}}">UKT</option>
                                    </select>
                                </div>
                                <!-- /.col-lg-6 -->
                                <div class="btn-group">
                                    <button type="button" id="btn-showpembayaran" class="btn btn-primary btn-sm" style="margin-top: 6px;margin-right: 10px;">Show Data</button>
                                    <button type="button" class="btn btn-success btn-sm" style="margin-top: 6px;display:none;">Export Excel</button>
                                    <!-- /input-group -->
                                </div>
                                <!-- /.col-lg-6 -->
                            </div>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>No Registrasi</th>
                                            <th>Kode Transaksi</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                            <th>Approval</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
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

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-modal">Detail Pendaftaran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabel-detail" class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center" style="background-color: rgb(0, 204, 255);">Data Pendaftaran Calon Mahasiswa Baru</th>
                            </tr>
                            <tr>
                                <th>No Registrasi</th>
                                <th id="o-noregist" class="o-detaildaftar"></th>
                                <th>Nama Calon Mahasiswa</th>
                                <th id="o-nama" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Batch Daftar</th>
                                <th id="o-batch" class="o-detaildaftar"></th>
                                <th>Tahun Lulus</th>
                                <th id="o-tahunlulus" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Jalur Daftar</th>
                                <th id="o-jalur" class="o-detaildaftar"></th>
                                <th>Jurusan Sekolah</th>
                                <th id="o-jurusansekolah" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 1</th>
                                <th id="o-prodi1" class="o-detaildaftar"></th>
                                <th>Konfirmasi Daftar</th>
                                <th id="o-konfirmdaftar" class="o-detaildaftar"></th>
                                {{-- <th>Program Studi Pilihan 2</th>
                                <th id="o-prodi2" class="o-detaildaftar"></th> --}}
                                {{-- <th>Program Studi Pilihan 3</th>
                                <th id="o-prodi3" class="o-detaildaftar"></th> --}}
                            </tr>
                            <tr>
                                <th>UKT Program Studi 1</th>
                                <th id="o-uktprodi1" class="o-detaildaftar"></th>
                                <th>Biaya Pendaftaran</th>
                                <th id="o-biayadaftar" class="o-detaildaftar"></th>
                                {{-- <th>UKT Program Studi 2</th>
                                <th id="o-uktprodi2" class="o-detaildaftar"></th> --}}
                                {{-- <th>UKT Program Studi 3</th>
                                <th id="o-uktprodi3" class="o-detaildaftar"></th> --}}
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 2</th>
                                <th id="o-prodi2" class="o-detaildaftar"></th>
                                <th>Tanggal Daftar</th>
                                <th id="o-tgldaftar" class="o-detaildaftar"></th>
                                {{-- <th>Konfirmasi Daftar</th>
                                <th id="o-konfirmdaftar" class="o-detaildaftar"></th> --}}
                                {{-- <th>Biaya Pendaftaran</th>
                                <th id="o-biayadaftar" class="o-detaildaftar"></th> --}}
                            </tr>
                            <tr>
                                <th>UKT Program Studi 2</th>
                                <th id="o-uktprodi2" class="o-detaildaftar"></th>
                                {{-- <th>Tanggal Daftar</th>
                                <th id="o-tgldaftar" class="o-detaildaftar"></th> --}}
                                <th>Waktu Kuliah</th>
                                <th id="o-waktukuliah" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 3</th>
                                <th id="o-prodi3" class="o-detaildaftar"></th>
                                {{-- <th>Status UKT</th>
                                <th id="o-statusukt" class="o-detaildaftar"></th> --}}
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Kategori Beasiswa
                                </th>
                                <th id="o-beasiswa" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>UKT Program Studi 3</th>
                                <th id="o-uktprodi3" class="o-detaildaftar"></th>
                                {{-- <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Tingkat Kejuaraan
                                </th>
                                <th id="o-juarabea" class="o-detaildaftar"></th> --}}
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Keterangan
                                </th>
                                <th id="o-keteranganbea" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Durasi Beasiswa D3
                                </th>
                                <th id="o-durasid3" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Durasi Beasiswa S1
                                </th>
                                <th id="o-durasis1" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Status UKT</th>
                                <th id="o-statusukt" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Tingkat Kejuaraan
                                </th>
                                <th id="o-juarabea" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Rekomendator</th>
                                <th id="o-rekomendator" class="o-detaildaftar" colspan="3"></th>
                            </tr>
                        </thead>
                        <tbody id="detail-bayar" class="o-detaildaftar">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-approval">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-modal">Revisi Bukti Pembayaran Pendaftaran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <code>* Jika Masih Ada Bukti Pembayaran Silahkan Input Pada Keterangan.</code><br>
                    <code>* Bukti Pembayaran yang Sudah Diverifikasi Tidak Dapat diubah lagi</code>
                    <form id="form-approval" action="{{ route('admin.pembayaranpmb.revisi') }}" method="POST">
                        @csrf
                        <input type="hidden" name="iddaftar" id="iddaftar" value="">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Tambahkan Keterangan (Jika Ada Revisi)"></textarea>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btn-closemodalberkas" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-saverevisi" class="btn btn-success">Simpan</button>
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

            function loadEvent()
            {
                tabelPembayaran()
                closemodal()
                submit_notapprove()
            }

            function ShowDataPembayaran()
            {
                $('#btn-showpembayaran').click(function (e) {
                    e.preventDefault();
                    let params = $('#kategori').val()
                    if(params==null||params==''){
                        notifalert('Information','Jenis Pembayaran Harus Diisi','warning')
                    }else{
                        tabelPembayaran()
                    }
                });
            }

            function tabelPembayaran()
            {
                let params = $('#kategori').val();

                let otable = $('#example2').DataTable({
                    destroy: true,
                    processing: true,
                    paging: false,
                    scrollX: true,
                    scrollY: '500px',
                    scrollCollapse: true,
                    serverSide: true,
                    searchDelay: 500,
                    responsive: false,
                    order: [],
                    ajax: {
                        url: '{!! url('admin/PembayaranPMB/TabelPembayaranPMB') !!}',
                        type: 'GET',
                    },
                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nama' },
                        { data: 'noreg' },
                        { data: 'kodetx' },
                        { data: 'jenis' },
                        { data: 'nominal' },
                        { data: 'status' },
                        { data: 'keterangan' },
                        { data: 'approve' },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                    language: {
                        processing: '<i class="fa fa-spinner fa-lg fa-spin"></i>'
                    },
                    drawCallback: function(settings) {
                        ShowDetail()
                        approve()
                        notApprove()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({trigger: "hover"});
                    $('[data-tooltip="tooltip"]').tooltip({trigger: "hover"});
                });
            }

            function ShowDetail()
            {
                $('.btn_detail').click(function (e) {
                    e.preventDefault();
                    let param = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('admin/PembayaranPMB/ShowPembayaranPMB') !!}'+'/'+param,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('.o-detaildaftar').empty()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if(data.hasil==0){
                                notifalert('Information', 'Data Pembayaran Tidak Ditemukan','error')
                            }else{
                                $('#o-noregist').html(data.daftar.KodePendaftaran)
                                $('#o-nama').html(data.daftar.biodata.nama)
                                $('#o-batch').html(data.daftar.batch.nama_batch+' '+data.daftar.batch.tahun_akademik)
                                $('#o-tahunlulus').html(data.daftar.tahun_lulus)
                                $('#o-jalur').html(data.daftar.jalur.KodeJenis+'-'+data.daftar.jalur.jenis_pendaftaran)
                                $('#o-jurusansekolah').html(data.daftar.jurusansekolah.sekolah+'/'+data.daftar.jurusansekolah.jurusan_sekolah)
                                $('#o-prodi1').html(data.daftar.prodi1.jenjang.jenjang+'-'+data.daftar.prodi1.jurusan)
                                $('#o-prodi2').html(data.daftar.prodi2.jenjang.jenjang+'-'+data.daftar.prodi2.jurusan)
                                let txtProdi3 = data.daftar.prodi3 ? data.daftar.prodi3.jenjang.jenjang+'-'+data.daftar.prodi3.jurusan : '-';
                                $('#o-prodi3').html(txtProdi3);
                                // let ukt1 = data.ukt1.biaya_ukt.replace(/\D/g, '')
                                let ukt1 = new Intl.NumberFormat('id-ID').format(data.ukt1.biaya_ukt);
                                $('#o-uktprodi1').html('Rp '+ukt1)
                                let ukt2 = new Intl.NumberFormat('id-ID').format(data.ukt2.biaya_ukt);
                                $('#o-uktprodi2').html('Rp '+ukt2)
                                let ukt3 = data.ukt3 ? new Intl.NumberFormat('id-ID').format(data.ukt3.biaya_ukt) : '0';
                                $('#o-uktprodi3').html('Rp '+ukt3)
                                let konfirmdaftar = data.daftar.konfirm_pendaftaran=='0' ? 'Belum Konfirmasi' : 'Sudah Konfirmasi';
                                $('#o-konfirmdaftar').html(konfirmdaftar)
                                let biayadaftar = data.daftar.jalur.biaya_pendaftaran=='1' ? 'Rp '+new Intl.NumberFormat('id-ID').format(data.daftar.jalur.jml_biaya_pendaftaran) : 'Gratis';
                                let statusdaftar = data.daftar.bayar
                                let warna1 = 'warning'
                                let nama1 = 'waiting'
                                let warna2 = 'warning'
                                let nama2 = 'waiting'
                                for(i=0;i<statusdaftar.length;i++){
                                    if(statusdaftar[i].kategori=='pendaftaran'){
                                        nama1 = statusdaftar[i].status
                                        if(statusdaftar[i].status=='pending'||statusdaftar[i].status=='waiting'){
                                            warna1 = 'warning'
                                        }else if(statusdaftar[i].status=='paid'){
                                            warna1 = 'success'
                                        }else{
                                            warna1 = 'danger'
                                        }
                                    }else{
                                        nama2 = statusdaftar[i].status
                                        if(statusdaftar[i].status=='pending'||statusdaftar[i].status=='waiting'){
                                            warna2 = 'warning'
                                        }else if(statusdaftar[i].status=='paid'){
                                            warna2 = 'success'
                                        }else{
                                            warna2 = 'danger'
                                        }
                                    }
                                }
                                let showstatusdaftar = '<span class="badge bg-'+warna1+'">'+nama1+'</span>'
                                let showstatusUKT = '<span class="badge bg-'+warna2+'">'+nama2+'</span>'

                                $('#o-biayadaftar').html(biayadaftar+' '+showstatusdaftar)
                                $('#o-tgldaftar').html(data.daftar.tgl_daftar)
                                $('#o-waktukuliah').html(data.daftar.waktukuliah.waktu)
                                $('#o-rekomendator').html(data.rekomendator)
                                let statusUkt = data.daftar.jalur.status_ukt=='0' ? 'Gratis '+showstatusUKT : 'Bayar '+showstatusUKT;
                                $('#o-statusukt').html(statusUkt)
                                let beasiswa = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.jenis_beasiswa
                                $('#o-beasiswa').html(beasiswa)
                                let tingkat = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.idtingkat;
                                $('#o-juarabea').html(tingkat==null||tingkat=='-'?'-':data.daftar.jenisbeasiswa.tingkat.tingkat_kejuaraan)
                                let ketbea = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.juara_ke
                                $('#o-keteranganbea').html(ketbea)
                                let durasid3 = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.durasi_d3+' Semester'
                                $('#o-durasid3').html(durasid3)
                                let durasis1 = data.daftar.jenisbeasiswa==null ? '-' : data.daftar.jenisbeasiswa.durasi_s1+' Semester'
                                $('#o-durasis1').html(durasis1)

                                // let history = '<tr>'+
                                //         '<th colspan="4" class="text-center" style="background-color: rgb(0, 204, 255);">History Pembayaran</th>'+
                                //     '</tr>'
                                //     history += '<tr>'+
                                //         '<th>Kode Transaksi</th>'+
                                //         '<th>Jenis Pembayaran</th>'+
                                //         '<th>Status</th>'+
                                //         '<th>Keterangan</th>'+
                                //     '</tr>'
                                // for(i=0;i<data.daftar.bayar.length;i++){
                                //     for(a=0;a<data.daftar.bayar[i].history_transaksi.length;a++){
                                //         history += '<tr>'+
                                //             '<td>'+data.daftar.bayar[i].kode_transaksi+'</td>'+
                                //             '<td>'+data.daftar.bayar[i].kategori+'</td>'+
                                //             '<td>'+data.daftar.bayar[i].history_transaksi[a].status+'</td>'+
                                //             '<td>'+data.daftar.bayar[i].history_transaksi[a].keterangan+'</td>'+
                                //         '</tr>'
                                //     }
                                // }
                                $('#detail-bayar').append(history);
                                $('#modal-detail').modal('show')
                            }
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal Show Data Pembayaran !',
                                text: 'Hubungi Tim IT',
                                icon: 'error'
                            }).then((result) => {
                                // window.isEditing = false;
                            });
                            return;
                        }
                    });
                    return false;
                });
            }

            function approve()
            {
                $('.approve-bayar').click(function (e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    Swal.fire({
                        title: "Information",
                        text: "Konfirmasi Bukti Pembayaran Pendaftaran ?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if(result.value){
                            $.ajax({
                                type: "GET",
                                url: '{!! url('admin/PembayaranPMB/Approve') !!}' + '/' + params,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    if(data.status==false){
                                        notifalert('Information',data.message,'warning');
                                    }else{
                                        notifalert('Information',data.message,'success');
                                    }
                                    $('#example2').DataTable().ajax.reload();
                                },
                                error: function(xhr, status, error) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Silahkan Hubungi Tim IT !',
                                        icon: 'error'
                                    }).then((result) => {
                                        $('#example2').DataTable().ajax.reload();
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

            function notApprove()
            {
                $('.revisi-bayar').click(function (e) {
                    e.preventDefault();
                    $('#modal-approval').modal('show')
                    let params = $(this).data('id')
                    $('#iddaftar').val(params)
                });
            }

            function submit_notapprove()
            {
                $('#btn-saverevisi').click(function (e) {
                    e.preventDefault();
                    let ket = $('#keterangan').val();
                    let btn = $(this);
                    if(ket){
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Keterangan Revisi Bukti Pembayaran Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.value){
                                btn.prop('disabled',true)
                                $('#form-approval').submit();
                            }else{
                                return false;
                            }
                        });
                    }else{
                        notifalert('Information','Keterangan Tidak Boleh Kosong','warning')
                    }
                });
            }

            function closemodal()
            {
                $('#modal-approval').on('hidden.bs.modal', function() {
                    $('#form-approval')[0].reset();
                });
            }


        });
    </script>
@endsection

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
                        <li class="breadcrumb-item active">{{ $menu }}</li>
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
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{ $menu }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="callout callout-info text-sm mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-info-circle"></i> Petunjuk Aksi:</h6>
                                <ul class="mb-0 pl-3">
                                    <li><strong>Keterangan</strong> akan diisi oleh admin saat melakukan validasi berkas.
                                    </li>
                                    <li>Jika Status sudah <span class="badge badge-success">OK / Sesuai</span>, berkas telah
                                        disetujui dan <strong>tidak bisa diganti</strong>.</li>
                                    <li>Jika Status sudah <span class="badge bg-warning">Menunggu..</span>, berkas telah
                                        lengkap dan <strong>menunggu validasi dari admin</strong>.</li>
                                    <li class="mt-2">
                                        <strong>Fungsi Tombol pada Tabel:</strong>
                                        <ul class="mt-2" style="list-style-type: none; padding-left: 0;">
                                            <li class="mb-2">
                                                <button class="btn btn-sm btn-outline-info"
                                                    style="border-radius: 6px; padding: 4px 10px; cursor: default;"><i
                                                        class="fa fa-info-circle"></i></button> :
                                                Untuk melihat detail pendaftaran.
                                            </li>
                                            <li class="mb-2">
                                                <button class="btn btn-sm btn-outline-success"
                                                    style="border-radius: 6px; padding: 4px 10px; cursor: default;"><i
                                                        class="fas fa-upload"></i></button> :
                                                Untuk mengunggah berkas PDF Anda pertama kali.
                                            </li>
                                            <li class="mb-2">
                                                <button class="btn btn-sm btn-outline-warning"
                                                    style="border-radius: 6px; padding: 4px 10px; cursor: default;"><i
                                                        class="fas fa-edit"></i></button> :
                                                Muncul jika berkas Anda ditolak/revisi. Gunakan untuk mengunggah ulang
                                                perbaikan berkas.
                                            </li>
                                            <li class="mb-2">
                                                <button class="btn btn-sm btn-outline-info"
                                                    style="border-radius: 6px; padding: 4px 10px; cursor: default;"><i
                                                        class="fas fa-eye"></i></button> :
                                                Untuk melihat rincian berkas yang harus diupload.
                                            </li>
                                            <li class="mb-2">
                                                <button class="btn btn-sm btn-outline-success"
                                                    style="border-radius: 6px; padding: 4px 10px; cursor: default;"><i
                                                        class="fas fa-check"></i></button> :
                                                Menyetujui untuk <strong>pindah ke Jalur Reguler</strong>.
                                            </li>
                                            <li class="mb-2">
                                                <button class="btn btn-sm btn-outline-danger"
                                                    style="border-radius: 6px; padding: 4px 10px; cursor: default;"><i
                                                        class="fas fa-times"></i></button> :
                                                Menolak pindah jalur <strong>(Perhatian: Anda akan dianggap mengundurkan
                                                    diri)</strong>.
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <div class="table-responsive mb-5">
                                <table id="example2" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>Nama</th>
                                            <th>No Registrasi</th>
                                            <th>Batch Daftar</th>
                                            <th>Jalur Daftar</th>
                                            <th>Jenis Beasiswa</th>
                                            <th class="text-center">Status</th>
                                            <th>Keterangan</th>
                                            <th class="text-center" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="modal fade" id="modal-berkas-user" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="fas fa-file-alt mr-2"></i> Daftar Berkas
                                                Pendaftar</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover w-100">
                                                    <thead class="bg-light text-center">
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>Nama Berkas</th>
                                                            <th width="20%">Status</th>
                                                            <th>Keterangan</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-berkas-modal">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                <th colspan="4" class="text-center" style="background-color: rgb(0, 255, 42);">Data
                                    Pendaftaran Calon Mahasiswa Baru</th>
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
                            </tr>
                            <tr>
                                <th>UKT Program Studi 1</th>
                                <th id="o-uktprodi1" class="o-detaildaftar"></th>
                                <th>Biaya Pendaftaran</th>
                                <th id="o-biayadaftar" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 2</th>
                                <th id="o-prodi2" class="o-detaildaftar"></th>
                                <th>Tanggal Daftar</th>
                                <th id="o-tgldaftar" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>UKT Program Studi 2</th>
                                <th id="o-uktprodi2" class="o-detaildaftar"></th>
                                <th>Waktu Kuliah</th>
                                <th id="o-waktukuliah" class="o-detaildaftar"></th>
                            </tr>
                            
                            <tr>
                                <th>Program Studi Pilihan 3</th>
                                <th id="o-prodi3" class="o-detaildaftar"></th>
                                <th>Status UKT</th>
                                <th id="o-statusukt" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>UKT Program Studi 3</th>
                                <th id="o-uktprodi3" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code><br>
                                    Kategori Beasiswa
                                </th>
                                <th id="o-beasiswa" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>
                                    <code>*Khusus Beasiswa</code><br>
                                    Tingkat Kejuaraan
                                </th>
                                <th id="o-juarabea" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code><br>
                                    Keterangan
                                </th>
                                <th id="o-keteranganbea" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>
                                    <code>*Khusus Beasiswa</code><br>
                                    Durasi Beasiswa D3
                                </th>
                                <th id="o-durasid3" class="o-detaildaftar"></th>
                                <th>
                                    <code>*Khusus Beasiswa</code><br>
                                    Durasi Beasiswa S1
                                </th>
                                <th id="o-durasis1" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th>Rekomedator</th>
                                <th id="o-rekomendator" class="o-detaildaftar"3></th>
                                <th>Berkas Beasiswa</th>
                                <th colspan="3" class="o-detaildaftar" id="o-berkasbeasiswa"></th>
                            </tr>
                        </thead>
                        <tbody id="detail-berkas" class="o-detaildaftar">

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

    <div class="modal fade" id="modal-upload" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="judul-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="judul-modal"><i class="fas fa-cloud-upload-alt mr-2"></i> Upload Berkas
                        Khusus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Peringatan!</strong>
                        <ul class="mb-0 pl-3 mt-1">
                            <li>Pastikan isi file sesuai dengan <strong>Nama Berkas</strong> yang diminta.</li>
                            <li>Format wajib: <strong>.PDF</strong></li>
                            <li>Ukuran maksimal: <strong>2 MB</strong></li>
                        </ul>
                    </div>

                    <form id="form-uploadberkas" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="iddaftar" name="iddaftar" value="">
                        <input type="hidden" id="id_berkas" name="id_berkas" value="">
                        <input type="hidden" id="kode_berkas" name="kode_berkas" value="">

                        <div class="form-group">
                            <label for="berkaskhusus" class="font-weight-normal">Pilih Dokumen PDF <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="berkaskhusus"
                                        name="berkaskhusus" accept="application/pdf" required>
                                    <label class="custom-file-label text-muted" for="berkaskhusus">Browse file...</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between bg-light">
                    <button type="button" id="btn-closemodalberkas" class="btn btn-outline-secondary"
                        data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" id="btn-saveberkas" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Berkas
                    </button>
                </div>
            </div>
        </div>
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

            function loadEvent() {
                tabelBerkasBeasiswa()
                saveBerkas()
                validasiberkas()
                LoadTabelBerkasBawah();
                ShowBerkasBeasiswa();
            }

            // 1. Fungsi Load Tabel Utama
            function tabelBerkasBeasiswa() {
                let otable = $('#example2').DataTable({
                    destroy: true,
                    processing: true,
                    paging: false,
                    scrollX: true,
                    scrollY: '500px',
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('BksBeasiswa.Tabel') !!}',
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'noreg'
                        },
                        {
                            data: 'batch'
                        },
                        {
                            data: 'jalur'
                        },
                        {
                            data: 'beasiswa'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'keterangan'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    language: {
                        processing: '<i class="fa fa-spinner fa-lg fa-spin"></i>'
                    },
                    drawCallback: function(settings) {
                        // Pastikan fungsi ini ada di code Anda yang lain
                        if (typeof setuju === "function") setuju();
                        if (typeof tidaksetuju === "function") tidaksetuju();
                    }
                });
            }

            // 2. Fungsi Load Berkas di dalam Modal
            function LoadTabelBerkasBawah(kodeDaftar) {
                $.ajax({
                    type: "GET",
                    url: "{!! url('BerkasBeasiswa/GetBerkasUser') !!}",
                    data: {
                        kode_daftar: kodeDaftar
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $('#tbody-berkas-modal').html(
                            '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>'
                        );
                    },
                    success: function(response) {
                        let html = '';
                        if (response.hasil === 1 && response.data.length > 0) {
                            $.each(response.data, function(index, b) {
                                let no = index + 1;
                                let actionBtn = '';

                                if (b.is_uploaded) {
                                    let fileUrl = `{!! url('admin/file/FILE_KHUSUS/') !!}/${b.file_name}`;
                                    actionBtn +=
                                        `<a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-info mr-1" style="border-radius: 6px; padding: 4px 10px;" title="Lihat"><i class="fas fa-eye"></i></a>`;

                                    // Jika belum disetujui (1), boleh edit/upload ulang
                                    if (b.status_angka != 1) {
                                        actionBtn +=
                                            `<button class="btn btn-sm btn-outline-warning btn-upload-modal" style="border-radius: 6px; padding: 4px 10px;" data-idberkas="${b.id_berkas}" data-kodeberkas="${b.kode_berkas}" data-namaberkas="${b.nama_berkas}" data-kodedaftar="${response.kode_daftar}"><i class="fas fa-edit"></i></button>`;
                                    }
                                } else {
                                    actionBtn +=
                                        `<button class="btn btn-sm btn-outline-success btn-upload-modal" style="border-radius: 6px; padding: 4px 10px;" data-idberkas="${b.id_berkas}" data-kodeberkas="${b.kode_berkas}" data-namaberkas="${b.nama_berkas}" data-kodedaftar="${response.kode_daftar}"><i class="fas fa-upload"></i></button>`;
                                }

                                html += `<tr>
                        <td class="text-center">${no}</td>
                        <td>${b.nama_berkas}</td>
                        <td class="text-center">${b.status_html}</td>
                        <td>${b.keterangan}</td>
                        <td class="text-center">${actionBtn}</td>
                    </tr>`;
                            });
                        } else {
                            html =
                                '<tr><td colspan="5" class="text-center text-muted">Tidak ada berkas yang perlu diupload.</td></tr>';
                        }
                        $('#tbody-berkas-modal').html(html);
                    }
                });
            }

            // Trigger saat tombol upload di tabel diklik
            $(document).on('click', '.btn-upload-modal', function() {
                // Ambil data dari atribut tombol
                let idberkas = $(this).data('idberkas');
                let kodeberkas = $(this).data('kodeberkas');
                let namaberkas = $(this).data('namaberkas');
                let kodedaftar = $(this).data('kodedaftar');

                // Masukkan ke dalam input hidden di form modal
                $('#iddaftar').val(kodedaftar);
                $('#id_berkas').val(idberkas);
                $('#kode_berkas').val(kodeberkas);

                // Ubah judul modal agar user tahu sedang upload file apa
                $('#judul-modal').text('Upload ' + namaberkas);

                // Tampilkan modal
                $('#modal-upload').modal('show');
            });

            function ShowBerkasBeasiswa() {
                $('#example2').off('click', '.btn-detail').on('click', '.btn-detail', function(e) {
                    e.preventDefault();

                    let param = $(this).data('id');

                    // 1. Panggil AJAX Tabel Bawah
                    if (typeof LoadTabelBerkasBawah === "function") {
                        LoadTabelBerkasBawah(param);
                    }

                    // 2. Panggil AJAX Modal
                    $.ajax({
                        type: "GET",
                        url: '{!! url('BerkasBeasiswa/ShowBerkasBeasiswa') !!}' + '/' + param,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            // TIDAK PERLU $('#loading').show() LAGI
                            $('.o-detaildaftar').empty();
                            $('#detail-berkas').empty();
                        },
                        success: function(data) {
                            // TIDAK PERLU $('#loading').hide() LAGI
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Pendaftaran Tidak Ditemukan',
                                    'error');
                            } else {
                                $('#o-noregist').html(data.daftar.KodePendaftaran);
                                $('#o-nama').html(data.daftar.biodata.nama);
                                $('#o-batch').html(data.daftar.batch.nama_batch + ' ' + data
                                    .daftar.batch.tahun_akademik);
                                $('#o-tahunlulus').html(data.daftar.tahun_lulus);
                                $('#o-jalur').html(data.daftar.jalur.KodeJenis + '-' + data
                                    .daftar.jalur.jenis_pendaftaran);
                                $('#o-jurusansekolah').html(data.daftar.jurusansekolah.sekolah +
                                    '/' + data.daftar.jurusansekolah.jurusan_sekolah);
                                $('#o-prodi1').html(data.daftar.prodi1.jenjang.jenjang + '-' +
                                    data.daftar.prodi1.jurusan);
                                $('#o-prodi2').html(data.daftar.prodi2.jenjang.jenjang + '-' +
                                    data.daftar.prodi2.jurusan);
                                $('#o-prodi3').html(data.daftar.prodi3 ? data.daftar.prodi3.jenjang.jenjang + '-' + data.daftar.prodi3.jurusan : '-');
                                let ukt1 = new Intl.NumberFormat('id-ID').format(data.ukt1
                                    .biaya_ukt);
                                $('#o-uktprodi1').html('Rp ' + ukt1);

                                let ukt2 = new Intl.NumberFormat('id-ID').format(data.ukt2
                                    .biaya_ukt);
                                $('#o-uktprodi2').html('Rp ' + ukt2);
                                
                                let ukt3 = data.ukt3 ? new Intl.NumberFormat('id-ID').format(data.ukt3.biaya_ukt) : '0';
                                $('#o-uktprodi3').html('Rp ' + ukt3);

                                let konfirmdaftar = data.daftar.konfirm_pendaftaran == '0' ?
                                    'Belum Konfirmasi' : 'Sudah Konfirmasi';
                                $('#o-konfirmdaftar').html(konfirmdaftar);

                                let biayadaftar = data.daftar.jalur.biaya_pendaftaran == '1' ?
                                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.daftar
                                        .jalur.jml_biaya_pendaftaran) : 'Gratis';
                                $('#o-biayadaftar').html(biayadaftar);

                                $('#o-tgldaftar').html(data.daftar.tgl_daftar);
                                $('#o-waktukuliah').html(data.daftar.waktukuliah.waktu);

                                $('#o-rekomendator').html(data.rekomendator);

                                let statusUkt = data.daftar.jalur.status_ukt == '0' ? 'Gratis' :
                                    'Bayar';
                                $('#o-statusukt').html(statusUkt);

                                let beasiswa = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.jenis_beasiswa;
                                $('#o-beasiswa').html(beasiswa);

                                let tingkat = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.idtingkat;
                                $('#o-juarabea').html(tingkat == null || tingkat == '-' ? '-' :
                                    data.daftar.jenisbeasiswa.tingkat.tingkat_kejuaraan);

                                let ketbea = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.juara_ke;
                                $('#o-keteranganbea').html(ketbea);

                                let durasid3 = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.durasi_d3 + ' Semester';
                                $('#o-durasid3').html(durasid3);

                                let durasis1 = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.durasi_s1 + ' Semester';
                                $('#o-durasis1').html(durasis1);

                                let namaberkaskhusus = data.daftar.berkas_khusus ? data.daftar
                                    .berkas_khusus : '-';
                                $('#o-berkasbeasiswa').html(namaberkaskhusus);

                                $('#modal-detail').modal('show');
                            }
                        },
                        error: function(data) {
                            // TIDAK PERLU $('#loading').hide() LAGI
                            Swal.fire({
                                title: 'Gagal Show Data Pendaftaran !',
                                text: 'Silahkan Hubungi Admin PMB',
                                icon: 'error'
                            });
                        }
                    });
                });
            }

            function UploadBerkas() {
                // Gunakan .off('click').on('click') agar event tidak bertumpuk jika DataTables direfresh
                $('.btn_upload').off('click').on('click', function(e) {
                    e.preventDefault();

                    let param = $(this).data('id'); // Ambil kode_daftar dari tombol

                    $('#iddaftar').val(param);
                    $('#modal-upload').modal('show');
                });
            }

            function validasiberkas() {
                $('#berkaskhusus').on('change', function() {
                    let file = this.files[0];

                    if (file) {
                        let fileType = file.type;
                        let fileSize = file.size;
                        if (fileType != 'application/pdf') {
                            notifalert('Information', 'Hanya file PDF yang diperbolehkan!', 'warning');
                            $(this).val('');
                            return false;
                        }
                        if (fileSize > 2 * 1024 * 1024) {
                            notifalert('Information', 'Ukuran file maksimal 2 MB!', 'warning');
                            $(this).val('');
                            return false;
                        }
                    }
                });
            }

            function saveBerkas() {
                $('#btn-saveberkas').click(function(e) {
                    e.preventDefault();

                    let berkaskhusus = $('#berkaskhusus').val();

                    if (berkaskhusus == '' || berkaskhusus == null) {
                        notifalert('Information', 'Berkas Tidak Boleh Kosong!', 'warning');
                    } else {
                        let formData = new FormData($('#form-uploadberkas')[0]);
                        Swal.fire({
                            title: "Information",
                            text: "Apakah Berkas Anda Sudah Benar ?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('BksBeasiswa.save') }}",
                                    processData: false,
                                    contentType: false,
                                    data: formData,
                                    dataType: "JSON",
                                    beforeSend: function(response) {
                                        $('#loading').show();
                                    },
                                    success: function(data) {
                                        $('#loading').hide();
                                        Swal.fire({
                                            title: data.title,
                                            text: data.message,
                                            icon: data
                                                .status
                                        }).then((result) => {
                                            $('#btn-closemodalberkas').trigger(
                                                'click');
                                            $('#form-uploadberkas')[0].reset();
                                            if ($.fn.DataTable.isDataTable(
                                                    '#example2')) {
                                                $('#example2').DataTable().ajax
                                                    .reload(null, false);
                                            }
                                            let kodeDaftar = $('#iddaftar')
                                                .val();
                                            LoadTabelBerkasBawah(kodeDaftar);
                                        });
                                        return;
                                    },
                                    error: function(xhr, status, error) {
                                        $('#loading').hide();
                                        Swal.fire({
                                            title: 'Gagal Menyimpan Data',
                                            text: 'Periksa koneksi atau coba lagi.',
                                            icon: 'error'
                                        });
                                        console.log(xhr
                                            .responseText);
                                        return;
                                    }
                                });
                            } else {
                                return false;
                            }
                        });
                    }
                });
            }

            function setuju() {
                $('#example2').on('click', '.setuju-pindah', function(e) {
                    e.preventDefault();
                    let param = $(this).data('id');

                    Swal.fire({
                        title: "Information",
                        text: "Apakah Anda Yakin Akan Pindah Jalur Reguler ?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('BerkasBeasiswa/ApprovalPindahJalur') !!}' + '/' + param + '/' + '1',
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show();
                                },
                                success: function(data) {
                                    $('#loading').hide();
                                    Swal.fire({
                                        title: data.title,
                                        text: data.message,
                                        icon: data.status
                                    }).then((result) => {
                                        $('#example2').DataTable().ajax.reload(
                                            null, false);
                                    });
                                },
                                error: function(data) {
                                    $('#loading').hide();
                                    Swal.fire({
                                        title: 'Gagal Pindah Jalur !',
                                        text: 'Silahkan Hubungi Admin PMB',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            function tidaksetuju() {
                $('#example2').on('click', '.tidaksetuju-pindah', function(e) {
                    e.preventDefault();
                    let param = $(this).data('id');

                    Swal.fire({
                        title: "Information",
                        text: "Jika Anda tidak setuju, anda dianggap mengundurkan diri. Apakah Anda Yakin ?",
                        icon: "warning",
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('BerkasBeasiswa/ApprovalPindahJalur') !!}' + '/' + param + '/' + '-1',
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show();
                                },
                                success: function(data) {
                                    $('#loading').hide();
                                    Swal.fire({
                                        title: data.title,
                                        text: data.message,
                                        icon: data.status
                                    }).then((result) => {
                                        $('#example2').DataTable().ajax.reload(
                                            null, false);
                                    });
                                },
                                error: function(data) {
                                    $('#loading').hide();
                                    Swal.fire({
                                        title: 'Gagal Pindah Jalur !',
                                        text: 'Silahkan Hubungi Admin PMB',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            $('#modal-upload').on('hidden.bs.modal', function() {
                $('#form-uploadberkas')[0].reset();
            });

            $(document).ready(function() {
                // 1. Jalankan inisialisasi tabel utama
                tabelBerkasBeasiswa();

                // 2. Monitoring Loading (Muncul saat DataTables/AJAX ambil data)
                $('#example2').on('processing.dt', function(e, settings, processing) {
                    if (processing) {
                        $('#loading').show();
                    } else {
                        setTimeout(function() {
                            $('#loading').hide();
                        }, 300);
                    }
                });

                // 3. Global AJAX Loading (Muncul saat simpan/hapus data)
                $(document).ajaxStart(function() {
                    $('#loading').show();
                }).ajaxStop(function() {
                    setTimeout(function() {
                        $('#loading').hide();
                    }, 300);
                });

                // 4. Listener Tombol MATA (Lihat Berkas)
                $('#example2').on('click', '.btn-view-berkas', function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    $('#modal-berkas-user').modal('show');
                    if (typeof LoadTabelBerkasBawah === "function") {
                        LoadTabelBerkasBawah(id);
                    }
                });

                // 5. Listener Tombol DETAIL (Ikon List)
                // $('#example2').on('click', '.btn-detail', function(e) {
                //     e.preventDefault();
                //     let id = $(this).data('id');

                //     // --- PILIH SALAH SATU DI BAWAH INI ---

                //     // OPSI A: Jika ingin pindah halaman (Redirect)
                //     // window.location.href = "{{ url('user/berkas/detail') }}/" + id;

                //     // OPSI B: Jika ingin munculkan pesan/alert saja dulu buat ngetes
                //     // alert("Kamu menekan tombol detail untuk ID: " + id);
                // });
            });

        });
    </script>
@endsection

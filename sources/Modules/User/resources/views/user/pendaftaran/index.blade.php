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
                        <li class="breadcrumb-item active"><a href="{{ route('Dashboard') }}" id="btn-dashboard">Dashboard</a>
                        </li>
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
                <!-- /.col-md-6 -->
                <div class="col-md-12">
                    {{-- @for ($i = 0; $i < 10; $i++) --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{ $menu }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6"> <!-- Ubah lebar form di sini -->
                                    <form id="form-fakultas" method="POST" action="#">
                                        @csrf
                                        <input type="hidden" id="IdPendaftaran" name="IdPendaftaran" value="">
                                        <div id="page-1">
                                            <div class="form-group mb-3">
                                                <label for="batch">Batch Pendaftaran <code>*</code></label>
                                                <select class="form-control select2" id="batch" name="batch">
                                                    <option value="" selected disabled>-- Pilih Batch Pendaftaran --
                                                    </option>
                                                    @foreach ($batch as $i)
                                                        <option value="{{ $i->id }}">{{ $i->nama_batch }} -
                                                            {{ $i->tahun_akademik }} -
                                                            {{ tglIndo($i->tglmulai) }}/{{ tglIndo($i->tglselesai) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="jalur">Jalur Pendaftaran <code>*</code></label>
                                                <select class="form-control select2" id="jalur" name="jalur" disabled>
                                                    <option value="" selected disabled>-- Pilih Jalur Pendaftaran --
                                                    </option>
                                                </select>
                                                {{-- <br> --}}
                                                <code>*Abaikan Bila Bukan Jalur Beasiswa</code>
                                                <select class="form-control select2" id="beasiswa" name="beasiswa"
                                                    disabled>
                                                    <option value="" selected disabled>-- Pilih Jalur Beasiswa --
                                                    </option>
                                                </select>
                                                <label for="tingkat"></label>
                                                <input type="text" id="tingkat" placeholder="Tingkat Kejuaraan"
                                                    class="form-control" disabled>
                                                <label for="keterangan"></label>
                                                <textarea class="form-control" id="keterangan" rows="3" placeholder="Keterangan Beasiswa" disabled></textarea>
                                            </div>
                                            <div class="form-group mb-3">
                                                <button type="button" id="btn-next"
                                                    class="btn btn-secondary btn-sm float-right">Next</button>
                                            </div>
                                            <br><br>
                                        </div>
                                        <div id="page-2" style="display: none;">
                                            <div class="form-group mb-3">
                                                <label for="tahunlulus">Tahun Lulus</label>
                                                <select class="form-control select2" id="tahunlulus" name="tahunlulus">
                                                    <option value="" selected disabled>-- Pilih Tahun --</option>
                                                    @php
                                                        $currentYear = date('Y') + 1;
                                                    @endphp
                                                    @for ($i = 0; $i < 10; $i++)
                                                        <option value="{{ $currentYear - $i }}">{{ $currentYear - $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="jurusansekolah">Jurusan Sekolah</label>
                                                <select class="form-control select2" id="jurusansekolah"
                                                    name="jurusansekolah" disabled>
                                                    <option value="" selected disabled>-- Pilih Jurusan Sekolah --
                                                    </option>
                                                    @foreach ($sekolah as $p)
                                                        <option value="{{ $p->id }}">{{ $p->sekolah }} -
                                                            {{ $p->jurusan_sekolah }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="prodi1">Program Studi Pilihan 1</label>
                                                <select class="form-control select2" id="prodi1" name="prodi1" disabled>
                                                    <option value="" selected disabled>-- Pilih Program Studi 1 --
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="prodi2">Program Studi Pilihan 2</label>
                                                <select class="form-control select2" id="prodi2" name="prodi2" disabled>
                                                    <option value="" selected disabled>-- Pilih Program Studi 2 --
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="prodi3">Program Studi Pilihan 3</label>
                                                <select class="form-control select2" id="prodi3" name="prodi3" disabled>
                                                    <option value="" selected disabled>-- Pilih Program Studi 3 --
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="waktukuliah">Waktu Kuliah</label>
                                                <select class="form-control select2" id="waktukuliah" name="waktukuliah"
                                                    disabled>
                                                    <option value="" selected disabled>-- Pilih Waktu Kuliah --
                                                    </option>
                                                    @foreach ($waktu as $q)
                                                        <option value="{{ $q->id }}">{{ $q->waktu }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="rekomendator">Rekomendator <code>*(Opsional)</code></label>
                                                <select class="form-control" id="rekomendator" name="rekomendator"
                                                    disabled>
                                                    <option value="" selected disabled>-- Ketik Nama Rekomendator --
                                                    </option>
                                                </select>
                                                <small class="text-muted">Ketik minimal 2 karakter (Nama)</small>
                                            </div>
                                            <div class="form-group mb-3">
                                                <button type="button" id="btn-prev"
                                                    class="btn btn-secondary btn-sm">Prev</button>
                                                <button type="button" id="submit-daftar"
                                                    class="btn btn-success btn-sm float-right"> <i
                                                        class="fas fa-paper-plane"></i> Submit</button>
                                                <button type="button" id="btn-reset" style="margin-right: 10px;"
                                                    class="btn btn-warning btn-sm float-right">Reset</button>
                                            </div>
                                        </div>
                                        <!-- Buttons -->
                                        {{-- <div class="form-group"> --}}
                                        {{-- <button type="button" id="submit-daftar" class="btn btn-success btn-sm float-right" style="margin-left:10px;"> <i class="fas fa-paper-plane"></i> Submit</button> --}}
                                        {{-- <button id="btn-reset" class="btn btn-warning btn-sm float-right">Reset</button> --}}
                                        {{-- </div> --}}
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive" style="margin-top: 20px;">
                                <code>* Silahkan Konfirmasi Pendaftaran dengan Klik </code> <i
                                    title="Konfirmasi Pendaftaran" class="fas fa-check-circle text-green"></i> <code> yang
                                    ada di kolom Action</code><br>
                                <code>* Jika Sudah Konfirmasi, Data Pendaftaran Tidak Dapat diubah</code><br>
                                <code>* Detail Pendaftaran Bisa dilihat pada tombol </code> <i title="Detail"
                                    class="fa fa-info-circle text-blue"></i> <code> pada kolom Action</code><br>
                                <label for="example2">Riwayat Pendaftaran</label>
                                <table id="example2" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Nomer Registrasi</th>
                                            <th>Batch Pendaftaran</th>
                                            <th>Jalur Pendaftaran</th>
                                            <th>Program Studi Pilihan 1</th>
                                            <th>Program Studi Pilihan 2</th>
                                            <th>Program Studi Pilihan 3</th>
                                            <th>Rekomedator</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
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
                                <th>Rekomedator</th>
                                <th id="o-rekomendator" class="o-detaildaftar"3></th>
                                <th>
                                    <code>*Khusus Beasiswa</code>
                                    <br>
                                    Tingkat Kejuaraan
                                </th>
                                <th id="o-juarabea" class="o-detaildaftar"></th>
                            </tr>
                            <tr>
                                <th colspan="4" style="background-color: rgb(251, 255, 0);"><code>*Berkas yang
                                        diperlukan</code></th>
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
@endsection
@section('script')
    <script>
        var isEditing = false; // Flag penanda status edit

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.select2').select2()

            LoadEvent()

            function LoadEvent() {
                SearchRekomendator()
                ShowJalur()
                ShowBeasiswa()
                ShowDetailBeasiswa()
                openJurusansekolah()
                openProdi()
                openWaktuKuliah()
                Next()
                Prev()
                submitDaftar()
                resetForm()
                tabelPendaftaran()
            }

            window.fakultasHtmlTable = ''; // Menyimpan memori daftar fakultas untuk popup

            function tabelPendaftaran() {
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
                        url: '{!! route('Daftar.TabelDaftar') !!}',
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
                            data: 'prodi1'
                        },
                        {
                            data: 'prodi2'
                        },
                        {
                            data: 'prodi3'
                        },
                        {
                            data: 'rekomendator'
                        },
                        {
                            data: 'status'
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
                        deleteDaftar()
                        editDaftar()
                        ConfirmDaftar()
                        ShowDetail()
                    }
                });

                otable.on('draw', function(event) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger: "hover"
                    });
                    $('[data-tooltip="tooltip"]').tooltip({
                        trigger: "hover"
                    });
                });
            }

            // function SearchRekomendator() {
            //     $('#rekomendator').select2({
            //         theme: 'bootstrap4',
            //         placeholder: '-- Ketik Nama / Kode Rekomendator --',
            //         allowClear: true,
            //         minimumInputLength: 2, // Minimal 2 karakter
            //         ajax: {
            //             url: '{!! route('Daftar.CariRekomendator') !!}',
            //             dataType: 'json',
            //             delay: 250, // Delay agar tidak membebani server saat mengetik
            //             data: function(params) {
            //                 return {
            //                     q: params.term // String yang diketik dikirim sebagai 'q'
            //                 };
            //             },
            //             processResults: function(data) {
            //                 return {
            //                     results: $.map(data, function(item) {
            //                         return {
            //                             text: item.kode_rekomendator + ' - ' + item
            //                                 .nama_rekomendator,
            //                             id: item
            //                                 .kode_rekomendator // Value yang dikirimkan ke tabel Pendaftaran (Kolom rekomendator)
            //                         }
            //                     })
            //                 };
            //             },
            //             cache: true
            //         }
            //     });
            // }
            function SearchRekomendator() {
                $('#rekomendator').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Ketik Nama Rekomendator --',
                    allowClear: true,
                    minimumInputLength: 2,
                    ajax: {
                        url: '{!! route('Daftar.CariRekomendator') !!}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        // PERUBAHAN: Hanya menampilkan nama, tapi ID tetap menyimpan kodenya
                                        text: item.nama_rekomendator + ' - ' + item
                                            .kode_rekomendator,
                                        id: item.kode_rekomendator
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            function ConfirmDaftar() {
                $('.btn_konfirm').off('click').click(function(e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    Swal.fire({
                        title: "Information",
                        text: "Apakah Data Pendaftaran Anda Sudah Benar ? Jika Sudah silahkan Konfirmasi",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('Pendaftaran/KonfirmasiDaftar') !!}' + '/' + params,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: data.title,
                                        text: data.message,
                                        icon: data.status
                                    }).then((result) => {
                                        $('#example2').DataTable().ajax
                                            .reload();
                                        $('#btn-dashboard').trigger('click');
                                    });
                                    return;
                                },
                                error: function(xhr, status, error) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Unsuccessfully Saved Data',
                                        text: 'Check Your Data',
                                        icon: 'error'
                                    }).then((result) => {
                                        $('#example2').DataTable().ajax
                                            .reload();
                                    });
                                    return;
                                }
                            });
                        } else {
                            return false;
                        }
                    });
                });
            }

            // Function baru untuk memuat Jalur dengan Callback
            function loadJalur(batchId, selectedJalur = null, callback = null) {
                if (batchId == null) {
                    $('#jalur').empty().append(
                        '<option value="" selected disabled>-- Pilih Jalur Pendaftaran --</option>');
                    $('#jalur').prop('disabled', true);
                    return;
                }

                $.ajax({
                    type: "GET",
                    url: '{!! url('Pendaftaran/ShowJalur') !!}' + '/' + batchId,
                    dataType: "JSON",
                    beforeSend: function(response) {
                        // Kita tetap kosongkan dulu untuk efek visual loading
                        if (!window.isEditing) $('#loading').show();
                        $('#jalur').prop('disabled', true);
                        $('#jalur').empty();
                    },
                    success: function(data) {
                        if (!window.isEditing) {
                            $('#loading').hide();
                        }

                        if (data.hasil == 0) {
                            notifalert('Information', 'Batch Pendaftaran Sudah Berakhir', 'warning');
                        } else if (data.hasil == -1) {
                            notifalert('Information', 'Jalur Pendaftaran Belum Tersedia', 'warning');
                        } else {
                            let dis1 =
                                '<option value="" selected disabled>-- Pilih Jalur Pendaftaran --</option>';

                            // Gunakan let agar variabel i tidak bocor keluar (safety)
                            for (let i = 0; i < data.jalur.length; i++) {
                                let isSelected = (selectedJalur == data.jalur[i].id) ? 'selected' : '';

                                // Kita simpan data-beasiswa agar bisa dibaca oleh loadBeasiswa nanti
                                dis1 += '<option value="' + data.jalur[i].id + '" data-beasiswa="' +
                                    data.jalur[i].is_beasiswa + '" ' + isSelected + '>' + data.jalur[i]
                                    .jenis_pendaftaran + '</option>';
                            }

                            // --- PERBAIKAN UTAMA DISINI ---
                            // Ganti .append() menjadi .html() agar isi dropdown di-replace total (tidak double)
                            $('#jalur').html(dis1);
                            $('#jalur').prop('disabled', false);

                            // Trigger change jika ada selectedJalur agar loadBeasiswa otomatis jalan
                            if (selectedJalur) {
                                // Gunakan setTimeout kecil untuk memastikan DOM sudah ter-render sempurna sebelum trigger
                                setTimeout(() => {
                                    $('#jalur').trigger('change');
                                }, 50);
                            }

                            // Eksekusi Callback (Penting untuk Edit)
                            if (callback && typeof callback === "function") {
                                callback();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        if (!window.isEditing) $('#loading').hide();
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Error, Silahkan Hubungi Admin !',
                            icon: 'error'
                        });
                    }
                });
            }

            function ShowJalur() {
                $('#batch').on('change', function() {
                    let params = $(this).val();
                    // Panggil function loadJalur biasa tanpa callback khusus
                    loadJalur(params);
                });
            }

            function loadBeasiswa(idJalur = null, selectedBea = null) {
                // Prioritaskan parameter kiriman, baru ambil dari DOM
                let params = idJalur ? idJalur : $('#jalur').val();

                // Kita ambil status REAL dari atribut data-beasiswa di dropdown yang sudah terpilih.
                let isBeasiswa = $('#jalur').find(':selected').data('beasiswa');

                // Fallback: Jika undefined (misal DOM belum ready), anggap 0 (Reguler) biar aman dan tidak error
                if (typeof isBeasiswa === 'undefined') isBeasiswa = 0;

                if (!params) {
                    $('#beasiswa').empty().append(
                        '<option value="" selected disabled>-- Pilih Jalur Beasiswa --</option>');
                    $('#beasiswa').prop('disabled', true);
                    return;
                }

                $.ajax({
                    type: "GET",
                    url: '{!! url('Pendaftaran/ShowBeasiswa') !!}' + '/' + params,
                    dataType: "JSON",
                    beforeSend: function() {
                        // Hanya nyalakan loading jika BUKAN mode edit
                        if (!window.isEditing) {
                            $('#loading').show();
                        }
                        $('#beasiswa').prop('disabled', true);
                        $('#beasiswa').empty();
                        $('#tingkat').val('');
                        $('#keterangan').val('');
                    },
                    success: function(data) {
                        // Matikan loading hanya jika bukan mode edit
                        if (!window.isEditing) {
                            $('#loading').hide();
                        }

                        $('#beasiswa').empty();
                        let dis1 =
                            '<option value="" selected disabled>-- Pilih Jalur Beasiswa --</option>';

                        // Cek apakah array 'bea' ada dan isinya lebih dari 0
                        if (data.bea && data.bea.length > 0) {
                            for (let i = 0; i < data.bea.length; i++) {
                                let selected = (selectedBea == data.bea[i].id) ? 'selected' : '';
                                dis1 += '<option value="' + data.bea[i].id + '" ' + selected + '>' +
                                    data.bea[i].jenis_beasiswa + '</option>';
                            }
                            $('#beasiswa').prop('disabled', false);
                        } else {
                            // Hanya munculkan alert jika 'isBeasiswa' bernilai 1 (True/Beasiswa)
                            if (isBeasiswa == 1) {
                                notifalert('Information', 'Kategori Beasiswa Belum Ada !', 'warning');
                            }
                            $('#beasiswa').prop('disabled', true);
                        }

                        $('#beasiswa').append(dis1);
                        $('#tingkat').val('');
                        $('#keterangan').val('');

                        // Trigger change untuk memuat detail (Tingkat/Keterangan)
                        if (selectedBea) {
                            setTimeout(() => {
                                $('#beasiswa').val(selectedBea).trigger('change');
                            }, 100);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Matikan loading saat terjadi error
                        if (!window.isEditing) {
                            $('#loading').hide();
                        }

                        // Notifikasi ke user saat gagal memuat data dari server/jaringan terputus
                        notifalert('Error',
                            'Gagal memuat data. Silakan periksa koneksi internet atau coba lagi nanti.',
                            'error');
                    }
                });
            }

            function ShowBeasiswa() {
                $('#jalur').on('change', function() {
                    // HANYA jalan jika BUKAN sedang mode editing
                    if (window.isEditing == false) {

                        loadBeasiswa();
                    } else {

                    }
                });
            }

            function ShowDetailBeasiswa() {
                $('#beasiswa').on('change', function() {
                    let params = $(this).val()
                    $.ajax({
                        type: "GET",
                        url: '{!! url('Pendaftaran/ShowDetailBeasiswa') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            if (!window.isEditing) {
                                $('#loading').show();
                            }
                            $('#tingkat').val('')
                            $('#keterangan').val('')
                        },
                        success: function(data) {

                            if (!window.isEditing) {
                                $('#loading').hide();
                            }
                            let tingkat = data.bea.idtingkat == null ? '-' : data.bea.tingkat
                                .tingkat_kejuaraan
                            let keter = data.bea == null ? '-' : data.bea.juara_ke
                            $('#tingkat').val(tingkat)
                            $('#keterangan').val(keter)
                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error, Silahkan Hubungi Admin !',
                                icon: 'error'
                            }).then((result) => {
                                console.log(0)
                            });
                            return;
                        }
                    });
                });
            }

            function openJurusansekolah() {
                $('#tahunlulus').on('change', function() {
                    let tahun = $(this).val()
                    if (tahun != null || tahun != '') {
                        $('#jurusansekolah').prop('disabled', false)
                    } else {
                        $('#jurusansekolah').prop('disabled', true)
                    }
                });
            }

            window.fakultasHtmlTable = '';

            // VARIABEL GLOBAL UNTUK MENYIMPAN HTML POP-UP
            window.fakultasHtmlTable = '';

            window.fakultasHtmlTable = ''; // Menyimpan memori daftar fakultas untuk popup

            function loadProdi(selectedProdi1 = null, selectedProdi2 = null, selectedProdi3 = null, forceBatch =
                null, forceJalur = null, forceJurusan = null, callback = null) {
                let batch = forceBatch ? forceBatch : $('#batch').val();
                let jalur = forceJalur ? forceJalur : $('#jalur').val();
                let jurusansekolah = forceJurusan ? forceJurusan : $('#jurusansekolah').val();

                if (!batch || !jalur || !jurusansekolah) {
                    if (callback && typeof callback === "function") callback();
                    return;
                }

                $.ajax({
                    type: "GET",
                    url: '{!! url('Pendaftaran/ShowProdi') !!}' + '/' + batch + '/' + jalur + '/' + jurusansekolah,
                    dataType: "JSON",
                    beforeSend: function() {
                        if (!window.isEditing) $('#loading').show();
                        $('#prodi1, #prodi2, #prodi3').prop('disabled', true);
                    },
                    success: function(data) {
                        if (!window.isEditing) $('#loading').hide();

                        if (data.hasil == 0) {
                            if (typeof window.isEditing !== 'undefined' && !window.isEditing) {
                                notifalert('Information', 'Data Jurusan Tidak Ditemukan', 'error');
                            }
                        } else {
                            $('#prodi1, #prodi2, #prodi3').prop('disabled', false);

                            let dis1 =
                                '<option value="" selected disabled>-- Pilih Program Studi 1 --</option>';
                            let dis2 =
                                '<option value="" selected disabled>-- Pilih Program Studi 2 --</option>';
                            let dis3 =
                                '<option value="" selected disabled>-- Pilih Program Studi 3 --</option>';

                            let target1 = selectedProdi1 ? $.trim(selectedProdi1.KodeJurusan ||
                                selectedProdi1) : null;
                            let target2 = selectedProdi2 ? $.trim(selectedProdi2.KodeJurusan ||
                                selectedProdi2) : null;
                            let target3 = selectedProdi3 ? $.trim(selectedProdi3.KodeJurusan ||
                                selectedProdi3) : null;

                            // 1. Buat kamus fakultas agar mudah dicocokkan
                            let kamusFakultas = {};
                            if (data.fakultas) {
                                $.each(data.fakultas, function(i, fak) {
                                    kamusFakultas[fak.KodeFakultas] = fak.namafakultas;
                                });
                            }

                            // 2. Kelompokkan prodi
                            let groupedOptions = {};
                            $.each(data.jurusan, function(index, item) {
                                let fCode = item.idfakultas;
                                let namaFakultas = kamusFakultas[fCode] ? kamusFakultas[fCode] :
                                    'Fakultas Lainnya';

                                if (!groupedOptions[namaFakultas]) {
                                    groupedOptions[namaFakultas] = [];
                                }
                                groupedOptions[namaFakultas].push(item);
                            });

                            // 3. Susun Tag HTML Optgroup
                            for (let fakName in groupedOptions) {
                                dis1 += '<optgroup label="' + fakName + '">';
                                dis2 += '<optgroup label="' + fakName + '">';
                                dis3 += '<optgroup label="' + fakName + '">';

                                $.each(groupedOptions[fakName], function(idx, item) {
                                    let kode = $.trim(item.KodeJurusan);
                                    let jenjangTxt = item.jenjang ? item.jenjang.jenjang : '';
                                    let teks = jenjangTxt + (jenjangTxt ? ' - ' : '') + item
                                        .jurusan; // Menghasilkan: S1 - Informatika
                                    let fakultasId = item.idfakultas ? item.idfakultas : '';

                                    let sel1 = (target1 && kode == target1) ? 'selected' : '';
                                    let sel2 = (target2 && kode == target2) ? 'selected' : '';
                                    let sel3 = (target3 && kode == target3) ? 'selected' : '';

                                    dis1 += '<option value="' + kode + '" data-fakultas="' +
                                        fakultasId + '" ' + sel1 + '>' + teks + '</option>';
                                    dis2 += '<option value="' + kode + '" data-fakultas="' +
                                        fakultasId + '" ' + sel2 + '>' + teks + '</option>';
                                    dis3 += '<option value="' + kode + '" data-fakultas="' +
                                        fakultasId + '" ' + sel3 + '>' + teks + '</option>';
                                });

                                dis1 += '</optgroup>';
                                dis2 += '</optgroup>';
                                dis3 += '</optgroup>';
                            }

                            $('#prodi1').html(dis1).trigger('change');
                            $('#prodi2').html(dis2).trigger('change');
                            $('#prodi3').html(dis3).trigger('change');
                            $('#prodi1, #prodi2, #prodi3').select2({
                                theme: 'bootstrap4',
                                width: '100%'
                            });

                            let groupedData = {};
                            if (data.fakultas) {
                                data.fakultas.forEach(f => {
                                    groupedData[f.KodeFakultas] = {
                                        name: f.namafakultas,
                                        prodis: []
                                    };
                                });
                            }
                            data.jurusan.forEach(item => {
                                let fCode = item.idfakultas;
                                if (!groupedData[fCode]) groupedData[fCode] = {
                                    name: "Fakultas " + fCode,
                                    prodis: []
                                };
                                let jenjangTxt = item.jenjang ? item.jenjang.jenjang : '';
                                groupedData[fCode].prodis.push(jenjangTxt + ' - ' + item
                                    .jurusan);
                            });

                            window.fakultasHtmlTable =
                                '<div style="max-height: 250px; overflow-y: auto; text-align: left; font-size: 14px; background: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">';
                            for (let key in groupedData) {
                                if (groupedData[key].prodis.length > 0) {
                                    window.fakultasHtmlTable +=
                                        '<strong style="color: #007bff; display:block; margin-top:10px;">' +
                                        groupedData[key].name +
                                        '</strong><ul style="margin-bottom: 5px; padding-left: 20px;">';
                                    groupedData[key].prodis.forEach(p => {
                                        window.fakultasHtmlTable += '<li>' + p + '</li>';
                                    });
                                    window.fakultasHtmlTable += '</ul>';
                                }
                            }
                            window.fakultasHtmlTable += '</div>';
                        }
                        if (callback && typeof callback === "function") callback();
                    },
                    error: function(xhr, status, error) {
                        if (!window.isEditing) $('#loading').hide();

                        // --- PERBAIKAN: Alert jika server gagal merespons ---
                        notifalert('Error',
                            'Gagal memuat data Program Studi. Silakan periksa koneksi internet atau coba lagi nanti.',
                            'error');

                        if (callback && typeof callback === "function") callback();
                    }
                });
            }

            function openProdi() {
                $('#jurusansekolah').on('change', function() {
                    // if (!window.isEditing) {
                    loadProdi();
                    // }
                });
            }

            function openWaktuKuliah() {
                $(document).off('change.cekkonflik').on('change.cekkonflik', '#prodi1, #prodi2, #prodi3',
                    function() {
                        let currentSelect = $(this);
                        let currentVal = currentSelect.val();

                        if (!currentVal) return;

                        let f1 = $('#prodi1').val() ? $('#prodi1 option:selected').data('fakultas') : null;
                        let f2 = $('#prodi2').val() ? $('#prodi2 option:selected').data('fakultas') : null;
                        let f3 = $('#prodi3').val() ? $('#prodi3 option:selected').data('fakultas') : null;

                        let isConflict = false;
                        let msg = '';

                        // HANYA CEK FAKULTAS
                        if ((f1 && f2 && f1 === f2) || (f1 && f3 && f1 === f3) || (f2 && f3 && f2 === f3)) {
                            isConflict = true;
                            msg = 'Program studi tidak boleh berasal dari Fakultas yang sama!';
                        }

                        if (isConflict) {
                            Swal.fire({
                                title: 'Pilihan Tidak Valid!',
                                html: '<p style="color:red; font-weight:bold;">' + msg +
                                    '</p><b>Daftar Fakultas & Program Studi:</b><hr>' + window
                                    .fakultasHtmlTable,
                                icon: 'warning',
                                width: '600px'
                            });
                            currentSelect.val('').trigger(
                                'change.select2'); // Reset pilihan yang menyebabkan konflik
                        }
                    });

                $(document).on('change', '#prodi3', function() {
                    let prodi3 = $(this).val();
                    if (prodi3 != null && prodi3 !== '') {
                        $('#waktukuliah, #rekomendator').prop('disabled', false);
                    } else {
                        $('#waktukuliah, #rekomendator').prop('disabled', true);
                    }
                });
            }

            function Next() {
                $('#btn-next').click(function(e) {
                    e.preventDefault();
                    let batch = $('#batch').val()
                    let jalur = $('#jalur').val()
                    let selectjalur = $('#jalur').find(':selected');
                    let beasiswa = selectjalur.data('beasiswa')
                    let kategoribeasiswa = $('#beasiswa').val()

                    if (batch == null) {
                        notifalert('Information', 'Isi Batch Pendaftaran Terlebih Dahulu', 'warning')
                    } else if (jalur == null) {
                        notifalert('Information', 'Jalur Pendaftaran Tidak Boleh Kosong', 'warning')
                    } else if (beasiswa == 1) {
                        if (kategoribeasiswa == null) {
                            notifalert('Information', 'Kategori Beasiswa Tidak Boleh kosong', 'warning')
                        } else {
                            $('#page-1').hide()
                            $('#page-2').show()
                        }
                    } else {
                        $('#page-1').hide()
                        $('#page-2').show()
                    }
                });
            }

            function Prev() {
                $('#btn-prev').click(function(e) {
                    e.preventDefault();
                    $('#page-1').show()
                    $('#page-2').hide()
                });
            }

            function validasiDaftar() {
                let tahun = $('#tahunlulus').val();
                let jurusansekolah = $('#jurusansekolah').val();
                let prodi1 = $('#prodi1').val();
                let prodi2 = $('#prodi2').val();
                let prodi3 = $('#prodi3').val();
                let waktukuliah = $('#waktukuliah').val();

                let notif = '';

                if (tahun == null) {
                    notif = 'Tahun Lulus Tidak Boleh Kosong';
                } else if (jurusansekolah == null) {
                    notif = 'Jurusan Sekolah Tidak Boleh Kosong';
                } else if (prodi1 == null) {
                    notif = 'Program Studi Pilihan 1 Tidak Boleh Kosong';
                } else if (prodi2 == null) {
                    notif = 'Program Studi Pilihan 2 Tidak Boleh Kosong';
                } else if (prodi3 == null) {
                    notif = 'Program Studi Pilihan 3 Tidak Boleh Kosong';
                } else if (waktukuliah == null) {
                    notif = 'Waktu Kuliah Tidak Boleh Kosong';
                } else {
                    notif = 'success';
                }
                return notif;
            }

            function submitDaftar() {
                $('#submit-daftar').click(function(e) {
                    e.preventDefault();

                    let validasiku = validasiDaftar();

                    if (validasiku != 'success') {
                        notifalert('Information', validasiku, 'warning');
                    } else {
                        let dataku = $('#form-fakultas').serialize();

                        Swal.fire({
                            title: "Konfirmasi",
                            text: "Apakah Data Pendaftaran Anda Sudah Benar?",
                            icon: "question",
                            showConfirmButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result
                                .isConfirmed) { // Menggunakan isConfirmed lebih standar di SweetAlert2
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('Daftar.StoreDaftar') }}",
                                    data: dataku,
                                    dataType: "JSON",
                                    beforeSend: function() {
                                        $('#submit-daftar').html(
                                            '<i class="fas fa-hourglass"></i> Please Wait'
                                            );
                                        $('#submit-daftar').prop('disabled', true);
                                        $('#loading').show();
                                    },
                                    success: function(data) {
                                        $('#loading').hide();
                                        Swal.fire({
                                            title: data.title || "Berhasil",
                                            text: data.message ||
                                                "Data berhasil disimpan.",
                                            icon: data.status || "success"
                                        }).then((result) => {
                                            $('#submit-daftar').html(
                                                '<i class="fas fa-paper-plane"></i> Submit'
                                                );
                                            $('#submit-daftar').prop('disabled',
                                                false);
                                            $('#btn-reset').trigger('click');

                                            // Pastikan tabel datatable ada sebelum di-reload
                                            if ($.fn.DataTable.isDataTable(
                                                    '#example2')) {
                                                $('#example2').DataTable().ajax
                                                    .reload();
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        $('#loading').hide();

                                        // Ambil pesan error dari server jika ada, jika tidak gunakan default
                                        let errorMessage =
                                            "Terjadi kesalahan saat menyimpan data. Silakan coba lagi nanti atau hubungi admin.";
                                        if (xhr.responseJSON && xhr.responseJSON
                                            .message) {
                                            errorMessage = xhr.responseJSON.message;
                                        } else if (xhr.status === 422) {
                                            errorMessage =
                                                "Mohon periksa kembali isian form Anda.";
                                        }

                                        Swal.fire({
                                            title: 'Gagal Menyimpan Data',
                                            text: errorMessage,
                                            icon: 'error'
                                        }).then((result) => {
                                            $('#submit-daftar').html(
                                                '<i class="fas fa-paper-plane"></i> Submit'
                                                );
                                            $('#submit-daftar').prop('disabled',
                                                false);
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }

            function resetForm() {
                $('#btn-reset').click(function(e) {
                    $('#IdPendaftaran').val(null)
                    $('#batch').val('').trigger('change')
                    $('#beasiswa').empty().append(
                        '<option value="" selected disabled>-- Pilih Jalur Beasiswa --</option>');
                    $('#beasiswa').prop('disabled', true)
                    $('#tingkat').val('')
                    $('#keterangan').val('')
                    $('#tahunlulus').val('').trigger('change')
                    $('#jurusansekolah').val('').trigger('change')
                    $('#jurusansekolah').prop('disabled', true)

                    if ($('#prodi1').hasClass("select2-hidden-accessible")) {
                        $('#prodi1, #prodi2, #prodi3').empty().append(
                                '<option value="" selected disabled>-- Pilih Program Studi --</option>')
                            .prop('disabled', true);
                    }

                    $('#waktukuliah').prop('disabled', true)
                    $('#rekomendator').prop('disabled', true)
                    $('#waktukuliah').val('').trigger('change')
                    $('#btn-prev').trigger('click')
                });
            }

            function editDaftar() {
                $('.btn_edit').off('click').click(function(e) {
                    e.preventDefault();
                    window.isEditing = true;
                    $('#loading').show();

                    let param = $(this).data('id');

                    $.ajax({
                        type: "GET",
                        url: '{!! url('Pendaftaran/ShowDaftar') !!}' + '/' + param,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#btn-reset').trigger('click');
                        },
                        success: function(data) {
                            if (data.hasil == 0) {
                                $('#loading').hide();
                                notifalert('Information', 'Data Pendaftaran Tidak Ditemukan',
                                    'error');
                                window.isEditing = false;
                            } else {
                                $('#IdPendaftaran').val(data.IdDaftar);
                                $('#batch').val(data.daftar.batch_daftar).trigger('change');

                                loadJalur(data.daftar.batch_daftar, data.daftar.jalur_daftar,
                                    function() {
                                        let beaId = null;
                                        if (data.daftar.beasiswa && typeof data.daftar
                                            .beasiswa !== 'object') {
                                            beaId = data.daftar.beasiswa;
                                        } else if (data.daftar.beasiswa && data.daftar
                                            .beasiswa.id) {
                                            beaId = data.daftar.beasiswa.id;
                                        } else if (data.daftar.jenisbeasiswa && data.daftar
                                            .jenisbeasiswa.id) {
                                            beaId = data.daftar.jenisbeasiswa.id;
                                        }

                                        loadBeasiswa(data.daftar.jalur_daftar, beaId);
                                        $('#tahunlulus').val(data.daftar.tahun_lulus)
                                            .trigger('change');

                                        setTimeout(() => {
                                            $('#jurusansekolah').val(data.daftar
                                                .jurusan_sekolah).trigger(
                                                'change');
                                            $('#waktukuliah').val(data.daftar
                                                .waktu_kuliah).trigger('change');
                                            $('#waktukuliah').prop('disabled',
                                                false);
                                            if (data.daftar.rekomendator) {
                                                // Format text diambil dari data.rekomendator yang dikirim Controller
                                                let optionRek = new Option(data
                                                    .rekomendator, data.daftar
                                                    .rekomendator, true, true);
                                                $('#rekomendator').append(optionRek)
                                                    .trigger('change');
                                            } else {
                                                $('#rekomendator').val(null)
                                                    .trigger('change');
                                            }

                                            var onProdiFinished = function() {
                                                setTimeout(() => {
                                                    window.isEditing =
                                                        false;
                                                    $('#waktukuliah')
                                                        .prop(
                                                            'disabled',
                                                            false);
                                                    $('#loading')
                                                        .fadeOut();
                                                }, 500);
                                            };

                                            if (typeof loadProdi === 'function') {
                                                loadProdi(data.daftar.prodi1, data
                                                    .daftar.prodi2, data.daftar
                                                    .prodi3, null, null, null,
                                                    onProdiFinished);
                                            } else {
                                                onProdiFinished();
                                            }

                                        }, 300);
                                    });
                            }
                        },
                        error: function(data) {
                            $('#loading').hide();
                            window.isEditing = false;
                            Swal.fire({
                                title: 'Gagal Memuat Data',
                                text: 'Terjadi kendala saat mengambil data dari server. Silakan periksa koneksi internet Anda atau coba beberapa saat lagi.',
                                icon: 'error'
                            });
                        }
                    });
                    return false;
                });
            }

            function deleteDaftar() {
                $('.btn_delete').click(function(e) {
                    e.preventDefault();
                    let params = $(this).data('id')
                    Swal.fire({
                        title: "Information",
                        text: "Apakah Tidak ingin lanjut pada Pendaftaran Batch Ini ?",
                        icon: "question",
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "GET",
                                url: '{!! url('Pendaftaran/DeleteDaftar') !!}' + '/' + params,
                                dataType: "JSON",
                                beforeSend: function(response) {
                                    $('#loading').show()
                                },
                                success: function(data) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: data.title,
                                        text: data.message,
                                        icon: data.status
                                    }).then((result) => {
                                        $('#btn-reset').trigger('click');
                                        $('#example2').DataTable().ajax
                                            .reload();
                                    });
                                    return;
                                },
                                error: function(xhr, status, error) {
                                    $('#loading').hide()
                                    Swal.fire({
                                        title: 'Unsuccessfully Delete Data',
                                        text: 'Check Your Data',
                                        icon: 'error'
                                    }).then((result) => {
                                        console.log(0)
                                    });
                                    return;
                                }
                            });
                        } else {
                            return false;
                        }
                    });
                });
            }

            function ShowDetail() {
                $('.btn_detail').click(function(e) {
                    e.preventDefault();
                    let param = $(this).data('id')
                    $.ajax({
                        type: "GET",
                        url: '{!! url('Pendaftaran/ShowDaftar') !!}' + '/' + param,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('.o-detaildaftar').empty()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Pendaftaran Tidak Ditemukan',
                                    'error')
                            } else {
                                $('#o-noregist').html(data.daftar.KodePendaftaran)
                                $('#o-nama').html(data.daftar.biodata.nama)
                                $('#o-batch').html(data.daftar.batch.nama_batch + ' ' + data
                                    .daftar.batch.tahun_akademik)
                                $('#o-tahunlulus').html(data.daftar.tahun_lulus)
                                $('#o-jalur').html(data.daftar.jalur.KodeJenis + '-' + data
                                    .daftar.jalur.jenis_pendaftaran)
                                $('#o-jurusansekolah').html(data.daftar.jurusansekolah.sekolah +
                                    '/' + data.daftar.jurusansekolah.jurusan_sekolah)

                                $('#o-prodi1').html(data.daftar.prodi1.jenjang.jenjang + '-' +
                                    data.daftar.prodi1.jurusan)
                                $('#o-prodi2').html(data.daftar.prodi2 ? data.daftar.prodi2
                                    .jenjang.jenjang + '-' + data.daftar.prodi2.jurusan :
                                    '-')
                                let txtProdi3 = data.daftar.prodi3 ? data.daftar.prodi3.jenjang
                                    .jenjang + '-' + data.daftar.prodi3.jurusan : '-';
                                $('#o-prodi3').html(txtProdi3);

                                let ukt1 = new Intl.NumberFormat('id-ID').format(data.ukt1
                                    .biaya_ukt);
                                $('#o-uktprodi1').html('Rp ' + ukt1)
                                let ukt2 = data.ukt2 ? new Intl.NumberFormat('id-ID').format(
                                    data.ukt2.biaya_ukt) : '0';
                                $('#o-uktprodi2').html('Rp ' + ukt2)
                                let ukt3 = data.ukt3 ? new Intl.NumberFormat('id-ID').format(
                                    data.ukt3.biaya_ukt) : '0';
                                $('#o-uktprodi3').html('Rp ' + ukt3)

                                let konfirmdaftar = data.daftar.konfirm_pendaftaran == '0' ?
                                    'Belum Konfirmasi' : 'Sudah Konfirmasi';
                                $('#o-konfirmdaftar').html(konfirmdaftar)
                                let biayadaftar = data.daftar.jalur.biaya_pendaftaran == '1' ?
                                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.daftar
                                        .jalur.jml_biaya_pendaftaran) : 'Gratis';
                                $('#o-biayadaftar').html(biayadaftar)
                                $('#o-tgldaftar').html(data.daftar.tgl_daftar)
                                $('#o-waktukuliah').html(data.daftar.waktukuliah.waktu)
                                $('#o-rekomendator').html(data.rekomendator)
                                let statusUkt = data.daftar.jalur.status_ukt == '0' ? 'Gratis' :
                                    'Bayar';
                                $('#o-statusukt').html(statusUkt)
                                let beasiswa = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.jenis_beasiswa
                                $('#o-beasiswa').html(beasiswa)
                                let tingkat = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.idtingkat;
                                $('#o-juarabea').html(tingkat == null || tingkat == '-' ? '-' :
                                    data.daftar.jenisbeasiswa.tingkat.tingkat_kejuaraan)
                                let ketbea = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.juara_ke
                                $('#o-keteranganbea').html(ketbea)
                                let durasid3 = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.durasi_d3 + ' Semester'
                                $('#o-durasid3').html(durasid3)
                                let durasis1 = data.daftar.jenisbeasiswa == null ? '-' : data
                                    .daftar.jenisbeasiswa.durasi_s1 + ' Semester'
                                $('#o-durasis1').html(durasis1)

                                let berkas = ''
                                berkas += '<tr style="background-color: rgb(0, 238, 255);">' +
                                    '<th colspan="4" class="text-center">' + data.daftar.jalur
                                    .berkasumum.jenis_berkas + '</th>' +
                                    '</tr>'
                                berkas += '<tr style="background-color: rgb(0, 238, 255);">' +
                                    '<th>No</td>' +
                                    '<th>Nama berkas</th>' +
                                    '<th>Keterangan</th>' +
                                    '<th>Format File</th>' +
                                    '</tr>'

                                for (i = 0; i < data.daftar.jalur.berkasumum.berkas
                                    .length; i++) {
                                    let no = i + 1;
                                    berkas += '<tr>' +
                                        '<td>' + no + '</td>' +
                                        '<td>' + data.daftar.jalur.berkasumum.berkas[i]
                                        .nama_berkas + '</td>' +
                                        '<td>' + data.daftar.jalur.berkasumum.berkas[i]
                                        .keterangan + '</td>' +
                                        '<td>' + data.daftar.jalur.berkasumum.berkas[i]
                                        .formatfile + '</td>' +
                                        '</tr>'
                                }

                                let berkaskhusus = data.daftar.jalur.berkas_khusus ? data.daftar
                                    .jalur.berkaskhusus.jenis_berkas : 'Khusus';
                                berkas += '<tr style="background-color: rgb(0, 238, 255);">' +
                                    '<th colspan="4" class="text-center">' + berkaskhusus +
                                    '</th>' +
                                    '</tr>'
                                if (data.daftar.jalur.berkas_khusus) {
                                    berkas +=
                                        '<tr style="background-color: rgb(0, 238, 255);">' +
                                        '<th>No</td>' +
                                        '<th>Nama berkas</th>' +
                                        '<th>Keterangan</th>' +
                                        '<th>Format File</th>' +
                                        '</tr>'
                                    for (i = 0; i < data.daftar.jalur.berkaskhusus.berkas
                                        .length; i++) {
                                        let noo = i + 1;
                                        berkas += '<tr>' +
                                            '<td>' + noo + '</td>' +
                                            '<td>' + data.daftar.jalur.berkaskhusus.berkas[i]
                                            .nama_berkas + '</td>' +
                                            '<td>' + data.daftar.jalur.berkaskhusus.berkas[i]
                                            .keterangan + '</td>' +
                                            '<td>' + data.daftar.jalur.berkaskhusus.berkas[i]
                                            .formatfile + '</td>' +
                                            '</tr>'
                                    }

                                } else {
                                    berkas += '<tr>' +
                                        '<th colspan="4" class="text-center">Tidak Ada Berkas Khusus</th>' +
                                        '</tr>'
                                }

                                $('#detail-berkas').append(berkas)
                                $('#modal-detail').modal('show')
                            }
                        },
                        error: function(data) {
                            $('#loading').hide()
                            Swal.fire({
                                title: 'Gagal Show Data Pendaftaran !',
                                text: 'Silahkan Hubungi Admin PMB TSU',
                                icon: 'error'
                            }).then((result) => {
                                window.isEditing = false;
                            });
                            return;
                        }
                    });
                    return false;
                });
            }

        });
    </script>
@endsection

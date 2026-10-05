@extends('admin::template/admin/header')
@section('title', $title)
@section('link_href')
<style>
    .test-nav-btn { width: 100px; }
</style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$menu}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$menu}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">{{$menu}}</h5>
                        </div>
                        <div class="card-body">
                            <code>* Halaman ini digunakan untuk memantau progres pengerjaan Assessment Calon Mahasiswa.</code><br>
                            <code>* Klik Icon </code> <i title="Detail Test" class="fa fa-info-circle fa-lg text-info"></i> <code> untuk melihat detail pengerjaan dan jawaban peserta.</code>
                            <div class="table-responsive" style="margin-top: 15px;">
                                <table id="tabel-monitoring" class="table table-bordered table-hover text-center" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>No Registrasi</th>
                                            <th>Batch Daftar</th>
                                            <th>Jalur Daftar</th>
                                            <th>Assessment TPA</th>
                                            <th>Assessment HIP</th>
                                            <th>Assessment DISC</th>
                                            <th>Status Keseluruhan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-modal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabel-detail" class="table table-sm table-bordered" style="width: 100%;display:none;">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center text-white" style="background-color: rgb(0, 150, 200);">Data Pendaftaran Calon Mahasiswa Baru</th>
                            </tr>
                            <tr>
                                <th width="25%">No Registrasi</th>
                                <td width="25%" id="o-noregist" class="o-detaildaftar"></td>
                                <th width="25%">Nama Calon Mahasiswa</th>
                                <td width="25%" id="o-nama" class="o-detaildaftar"></td>
                            </tr>
                            <tr>
                                <th>Batch Daftar</th>
                                <td id="o-batch" class="o-detaildaftar"></td>
                                <th>Jalur Daftar</th>
                                <td id="o-jalur" class="o-detaildaftar"></td>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 1</th>
                                <td id="o-prodi1" class="o-detaildaftar"></td>
                                <th>Program Studi Pilihan 2</th>
                                <td id="o-prodi2" class="o-detaildaftar"></td>
                            </tr>
                            <tr>
                                <th>Program Studi Pilihan 3</th>
                                <td id="o-prodi3" class="o-detaildaftar"></td>
                                <td colspan="2" class="text-muted text-center"><em>Hanya Monitoring</em></td>
                            </tr>
                        </thead>
                    </table>

                    <hr>

                    <div id="test-navigation" style="display: none;" class="justify-content-between align-items-center mb-3">
                        <button type="button" id="btn-prev-test" class="btn btn-secondary btn-sm test-nav-btn"><i class="fas fa-chevron-left"></i> Previous</button>
                        <h5 id="test-indicator" class="font-weight-bold m-0 text-primary">Test 1 dari 3</h5>
                        <button type="button" id="btn-next-test" class="btn btn-primary btn-sm test-nav-btn">Next <i class="fas fa-chevron-right"></i></button>
                    </div>

                    <div id="assessment-container" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
    $(function() {
        let assessmentAttempts = [];
        let currentTestIndex = 0;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        loadEvent();

        function loadEvent() {
            tabelMonitoring();
            handleTestNavigation();
        }

        function tabelMonitoring() {
            let otable = $('#tabel-monitoring').DataTable({
                destroy: true,
                processing: true,
                paging: true,
                scrollX: true,
                serverSide: true,
                searchDelay: 500,
                responsive: false,
                order: [],
                ajax: {
                    url: '{!! route('admin.monitoringassesment.tabel') !!}',
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', className: 'text-left' },
                    { data: 'noreg' },
                    { data: 'batch' },
                    { data: 'jalur' },
                    { data: 'tes_tpa' },
                    { data: 'tes_hip' },
                    { data: 'tes_disc' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false },
                ],
                language: {
                    processing: '<i class="fa fa-spinner fa-lg fa-spin"></i>'
                }
            });

            // Re-bind click event setelah tabel di draw
            otable.on('draw', function() {
                detailhasil_test();
            });
            detailhasil_test();
        }

        function detailhasil_test() {
            $('.btn_detail').off('click').on('click', function (e) {
                e.preventDefault();
                let params = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: '{!! url('admin/MonitoringAssesment/DetailTestOnlinePMB') !!}' + '/' + params,
                    dataType: "JSON",
                    beforeSend: function() {
                        $('#loading').show();
                        $('.o-detaildaftar').empty();
                        $('#judul-modal').empty();
                        $('#tabel-detail').hide();

                        $('#test-navigation').removeClass('d-flex').hide();
                        $('#assessment-container').hide().empty();

                        assessmentAttempts = [];
                        currentTestIndex = 0;
                    },
                    success: function(data) {
                        $('#loading').hide();
                        if(data.hasil == 0){
                            notifalert('Information', 'Data Test Tidak Ditemukan','error');
                        } else {
                            $('#judul-modal').html('Monitoring Jawaban Peserta');
                            $('#o-noregist').html(data.daftar.KodePendaftaran);
                            $('#o-nama').html(data.daftar.biodata ? data.daftar.biodata.nama : '-');
                            $('#o-batch').html(data.daftar.batch ? data.daftar.batch.nama_batch + ' ' + data.daftar.batch.tahun_akademik : '-');
                            $('#o-jalur').html(data.daftar.jalur ? data.daftar.jalur.jenis_pendaftaran : '-');

                            $('#o-prodi1').html((data.daftar.prodi1 && data.daftar.prodi1.jenjang ? data.daftar.prodi1.jenjang.jenjang + '-' + data.daftar.prodi1.jurusan : (data.daftar.prodi1 ? data.daftar.prodi1.jurusan : '-')));
                            $('#o-prodi2').html((data.daftar.prodi2 && data.daftar.prodi2.jenjang ? data.daftar.prodi2.jenjang.jenjang + '-' + data.daftar.prodi2.jurusan : (data.daftar.prodi2 ? data.daftar.prodi2.jurusan : '-')));
                            $('#o-prodi3').html((data.daftar.prodi3 && data.daftar.prodi3.jenjang ? data.daftar.prodi3.jenjang.jenjang + '-' + data.daftar.prodi3.jurusan : (data.daftar.prodi3 ? data.daftar.prodi3.jurusan : '-')));

                            if(data.attempts && data.attempts.length > 0) {
                                assessmentAttempts = data.attempts;
                                $('#test-navigation').addClass('d-flex').show();
                                $('#assessment-container').show();
                                renderTestContent(currentTestIndex);
                            } else {
                                $('#assessment-container').html('<div class="alert alert-warning text-center">Peserta belum memulai satupun test.</div>').show();
                            }

                            $('#tabel-detail').slideDown('slow');
                            $('#modal-detail').modal('show');
                        }
                    },
                    error: function() {
                        $('#loading').hide();
                        Swal.fire({ title: 'Error!', text: 'Gagal mengambil data', icon: 'error' });
                    }
                });
            });
        }

        function handleTestNavigation() {
            $('#btn-next-test').click(function() {
                if (currentTestIndex < assessmentAttempts.length - 1) {
                    currentTestIndex++;
                    renderTestContent(currentTestIndex);
                }
            });

            $('#btn-prev-test').click(function() {
                if (currentTestIndex > 0) {
                    currentTestIndex--;
                    renderTestContent(currentTestIndex);
                }
            });
        }

        function renderTestContent(index) {
            let attempt = assessmentAttempts[index];
            let totalTests = assessmentAttempts.length;

            $('#test-indicator').text(`Assessment ${index + 1} dari ${totalTests}`);
            $('#btn-prev-test').prop('disabled', index === 0);
            $('#btn-next-test').prop('disabled', index === totalTests - 1);

            // TRANSLATE STATUS KE BAHASA INDONESIA
            let statusIndo = 'Belum Mulai';
            if (attempt.status === 'finished') statusIndo = 'Selesai';
            else if (attempt.status === 'on_progress' || attempt.status === 'on progress') statusIndo = 'Sedang Dikerjakan';
            else if (attempt.status) statusIndo = attempt.status;

            // 1. SUMMARY UMUM
            let summaryHtml = '';
            let isHIP = (attempt.tipe_engine === 'single_choice' || attempt.tipe_engine === 'likert');

            if (attempt.tipe_engine === 'multiple_choice') {
                let benar = 0, salah = 0;
                if(attempt.answers) {
                    $.each(attempt.answers, function(i, a) { if(a.is_benar == 1) benar++; else salah++; });
                }
                summaryHtml = `<div class="alert alert-info py-2 mb-3"><strong>Ringkasan Assessment TPA:</strong> Benar: <span class="badge bg-success">${benar}</span> | Salah: <span class="badge bg-danger">${salah}</span> | Total Soal: ${attempt.answers ? attempt.answers.length : 0}</div>`;
            } else if (isHIP) {
                let ya = 0, tidak = 0;
                if(attempt.answers) {
                    $.each(attempt.answers, function(i, a) {
                        let jwb1 = (a.jawaban_1 || '').toString().toLowerCase().trim();
                        let optLbl = (a.option_label || '').toString().toLowerCase().trim();

                        // Periksa apakah jawaban_1 berisi angka 1 atau kata "ya"
                        if (a.is_benar == 1 || a.is_benar === '1' || jwb1 === '1' || jwb1 === 'ya' || optLbl === '1' || optLbl === 'ya') {
                            ya++;
                        } else if (jwb1 !== '' && !isNaN(jwb1) && parseInt(jwb1) > 0) {
                            // Tangani juga jika ternyata isinya angka skala likert (2, 3, 4, 5)
                            ya++;
                        } else {
                            tidak++;
                        }
                    });
                }
                summaryHtml = `<div class="alert alert-info py-2 mb-3"><strong>Ringkasan Minat Bakat:</strong> Mendapat Nilai: <span class="badge bg-success">${ya}</span> | Nilai 0: <span class="badge bg-secondary">${tidak}</span> | Total Soal: ${attempt.answers ? attempt.answers.length : 0}</div>`;
            }

            // 2. HEADER & TABEL JAWABAN AKTUAL
            let html = `
                <div class="card card-info card-outline">
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered mb-0">
                            <tr>
                                <th width="20%">Nama Assessment</th>
                                <td width="30%"><strong>${attempt.tipe_test_nama}</strong></td>
                                <th width="20%">Waktu Mulai</th>
                                <td width="30%">${formatTanggalWaktuIndo(attempt.mulai_at)}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span class="badge ${attempt.status === 'finished' ? 'bg-success' : 'bg-warning'}">${statusIndo}</span></td>
                                <th>Waktu Selesai</th>
                                <td>${formatTanggalWaktuIndo(attempt.selesai_at)}</td>
                            </tr>
                        </table>

                        <div class="p-3">${summaryHtml}</div>

                        <h6 class="font-weight-bold ml-3 mt-2">Daftar Jawaban Peserta:</h6>
                        <div class="table-responsive p-2 mb-3" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-striped table-bordered text-sm text-left">
            `;

            // JIKA HIP (Tambahkan Header Kolom Nilai)
            if (isHIP) {
                html += `
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="45%">Soal</th>
                                        <th width="35%">Jawaban Aktual</th>
                                        <th width="15%" class="text-center">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
            } else {
                html += `
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="40%">Soal</th>
                                        <th width="55%">Jawaban Aktual</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
            }

            // MENGISI DATA JAWABAN
            if(attempt.answers && attempt.answers.length > 0) {
                $.each(attempt.answers, function(i, ans) {
                    if (isHIP) {
                        let nilai = 0;
                        let jwb1 = (ans.jawaban_1 || '').toString().toLowerCase().trim();
                        let optLbl = (ans.option_label || '').toString().toLowerCase().trim();

                        // Cek spesifik ke kolom jawaban_1 untuk mencari angka 1
                        if (ans.is_benar == 1 || ans.is_benar === '1') {
                            nilai = 1;
                        } else if (jwb1 === '1' || jwb1 === 'ya' || jwb1 === 'benar') {
                            nilai = 1;
                        } else if (optLbl === '1' || optLbl === 'ya' || optLbl === 'benar') {
                            nilai = 1;
                        } else if (jwb1 !== '' && !isNaN(jwb1)) {
                            nilai = parseInt(jwb1); // Jika nilainya berupa angka lain (2, 3, 4, dst)
                        }

                        // Tampilkan teks dari option_label, tapi jika kosong baru tampilkan jawaban_1
                        let jwbAktual = ans.option_label || ans.jawaban_1 || '-';
                        // Opsional: Jika jwbAktual malah menampilkan angka "1", dan kamu ingin melihat teks soalnya, pastikan relasi option_id-nya sudah terisi.

                        html += `
                            <tr>
                                <td class="text-center">${i + 1}</td>
                                <td>${ans.pertanyaan}</td>
                                <td>${jwbAktual}</td>
                                <td class="text-center font-weight-bold text-primary">${nilai}</td>
                            </tr>
                        `;
                    } else {
                        html += `
                            <tr>
                                <td class="text-center">${i + 1}</td>
                                <td>${ans.pertanyaan}</td>
                                <td>${formatJawaban(attempt.tipe_engine, ans)}</td>
                            </tr>
                        `;
                    }
                });
            } else {
                let colSpan = isHIP ? 4 : 3;
                html += `<tr><td colspan="${colSpan}" class="text-center">Belum ada jawaban tersimpan / sedang dikerjakan.</td></tr>`;
            }
            html += `</tbody></table></div>`;

            // 3. JIKA DISC, TAMBAHKAN GRID KOTAK & GRAFIK DI BAWAH TABEL JAWABAN
            if (attempt.tipe_engine === 'disc') {
                let ans = attempt.answers || [];
                let l1 = {D:0, I:0, S:0, C:0, star:0};
                let l2 = {D:0, I:0, S:0, C:0, star:0};

                ans.forEach(a => {
                    let m = a.most_disc ? a.most_disc.toUpperCase() : '';
                    let k = a.least_disc ? a.least_disc.toUpperCase() : '';
                    if(m === 'D') l1.D++; else if(m === 'I') l1.I++; else if(m === 'S') l1.S++; else if(m === 'C') l1.C++; else if(m === '*') l1.star++;
                    if(k === 'D') l2.D++; else if(k === 'I') l2.I++; else if(k === 'S') l2.S++; else if(k === 'C') l2.C++; else if(k === '*') l2.star++;
                });

                let tot1 = l1.D + l1.I + l1.S + l1.C + l1.star;
                let tot2 = l2.D + l2.I + l2.S + l2.C + l2.star;
                let l3 = { D: l1.D - l2.D, I: l1.I - l2.I, S: l1.S - l2.S, C: l1.C - l2.C };

                let urlPdf = '{!! url("admin/TestAssesment/PrintDISC") !!}/' + attempt.id;
                html += `
                    <div class="p-3 border-top bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                            <h5 class="font-weight-bold text-info mb-0">REKAPITULASI & GRAFIK DISC</h5>
                            <a href="${urlPdf}" target="_blank" class="btn btn-warning btn-sm text-dark font-weight-bold "><i class="fas fa-print"></i> Cetak PDF DISC</a>
                        </div>
                `;

                html += `
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered text-center table-sm font-weight-bold" style="border: 2px solid #000; font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="border-bottom: 2px solid #000;">No</th><th style="background:#ffeb3b; border-bottom: 2px solid #000;">P</th><th style="background:#6ee7b7; border-bottom: 2px solid #000;">K</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">P</th><th style="background:#d8b4e2; border-right: 2px solid #000; border-bottom: 2px solid #000;">K</th>
                                    <th style="border-bottom: 2px solid #000;">No</th><th style="background:#ffeb3b; border-bottom: 2px solid #000;">P</th><th style="background:#6ee7b7; border-bottom: 2px solid #000;">K</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">P</th><th style="background:#d8b4e2; border-right: 2px solid #000; border-bottom: 2px solid #000;">K</th>
                                    <th style="border-bottom: 2px solid #000;">No</th><th style="background:#ffeb3b; border-bottom: 2px solid #000;">P</th><th style="background:#6ee7b7; border-bottom: 2px solid #000;">K</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">P</th><th style="background:#d8b4e2; border-bottom: 2px solid #000;">K</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                for(let i=0; i<8; i++) {
                    let col1 = ans[i] || {};
                    let col2 = ans[i+8] || {};
                    let col3 = ans[i+16] || {};

                    html += `<tr>`;
                    html += `<td style="border-left: 2px solid #000;">${i+1}</td>
                             <td style="background:#fff9c4;">${col1.most_urutan || ''}</td>
                             <td style="background:#a7f3d0;">${col1.least_urutan || ''}</td>
                             <td style="background:#f3e8ff;">${col1.most_disc || ''}</td>
                             <td style="background:#f3e8ff; border-right: 2px solid #000;">${col1.least_disc || ''}</td>`;
                    html += `<td>${i+9}</td>
                             <td style="background:#fff9c4;">${col2.most_urutan || ''}</td>
                             <td style="background:#a7f3d0;">${col2.least_urutan || ''}</td>
                             <td style="background:#f3e8ff;">${col2.most_disc || ''}</td>
                             <td style="background:#f3e8ff; border-right: 2px solid #000;">${col2.least_disc || ''}</td>`;
                    html += `<td>${i+17}</td>
                             <td style="background:#fff9c4;">${col3.most_urutan || ''}</td>
                             <td style="background:#a7f3d0;">${col3.least_urutan || ''}</td>
                             <td style="background:#f3e8ff;">${col3.most_disc || ''}</td>
                             <td style="background:#f3e8ff; border-right: 2px solid #000;">${col3.least_disc || ''}</td>`;
                    html += `</tr>`;
                }
                html += `</tbody></table></div>`;

                html += `
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <table class="table table-bordered text-center table-sm font-weight-bold" style="border: 2px solid #000;">
                                <thead>
                                    <tr class="bg-white">
                                        <th style="border-bottom: 2px solid #000;">Line</th><th style="border-bottom: 2px solid #000;">D</th><th style="border-bottom: 2px solid #000;">I</th><th style="border-bottom: 2px solid #000;">S</th><th style="border-bottom: 2px solid #000;">C</th><th style="border-bottom: 2px solid #000;">*</th><th style="border-bottom: 2px solid #000;">tot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>1</td><td>${l1.D}</td><td>${l1.I}</td><td>${l1.S}</td><td>${l1.C}</td><td>${l1.star}</td><td class="text-danger">${tot1}</td></tr>
                                    <tr><td>2</td><td>${l2.D}</td><td>${l2.I}</td><td>${l2.S}</td><td>${l2.C}</td><td>${l2.star}</td><td class="text-danger">${tot2}</td></tr>
                                    <tr style="background:#b0bec5;"><td>3</td><td>${l3.D}</td><td>${l3.I}</td><td>${l3.S}</td><td>${l3.C}</td><td></td><td></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;

                html += `
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <h6 class="font-weight-bold border border-dark p-1 mb-0">GRAPH 1 MOST<br><small>Mask Public Self</small></h6>
                            <div style="border:1px solid #000; padding:10px; background:#fff;">
                                <canvas id="discChart1" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="font-weight-bold border border-dark p-1 mb-0">GRAPH 2 LEAST<br><small>Core Private Self</small></h6>
                            <div style="border:1px solid #000; padding:10px; background:#fff;">
                                <canvas id="discChart2" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="font-weight-bold border border-dark p-1 mb-0">GRAPH 3 CHANGE<br><small>Mirror Perceived Self</small></h6>
                            <div style="border:1px solid #000; padding:10px; background:#fff;">
                                <canvas id="discChart3" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                `;

                let res = attempt.hasil_disc;
                    if(res) {
                        // Fungsi pintar untuk mencegah [object Object]
                        let safeTitle = (val) => {
                            if (!val) return '-';
                            if (typeof val === 'string') return val;
                            if (typeof val === 'object') {
                                // Coba cari key yang umum dipakai, jika tidak ada tampilkan raw JSON
                                if (val.nama) return val.nama;
                                if (val.karakter) return val.karakter;
                                return JSON.stringify(val);
                            }
                            return val;
                        };

                        let safeList = (val) => {
                            if (!val) return '';
                            let arr = Array.isArray(val) ? val : (typeof val === 'object' ? Object.values(val) : [val]);
                            return arr.map(s => `<li>${typeof s === 'object' ? JSON.stringify(s) : s}</li>`).join('');
                        };

                        html += `
                            <div class="row border-top pt-3">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold text-decoration-underline">Kepribadian Saat di Publik</h6>
                                    <div class="text-primary font-weight-bold mb-2">${safeTitle(res.karakter_publik)}</div>
                                    <ul class="pl-3 text-sm">${safeList(res.sifat_publik)}</ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold text-decoration-underline">Kepribadian Asli</h6>
                                    <div class="text-primary font-weight-bold mb-2">${safeTitle(res.karakter_asli)}</div>
                                    <ul class="pl-3 text-sm">${safeList(res.sifat_asli)}</ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold text-decoration-underline">Kepribadian Saat Tertekan</h6>
                                    <div class="text-primary font-weight-bold mb-2">${safeTitle(res.karakter_tekanan)}</div>
                                    <ul class="pl-3 text-sm">${safeList(res.sifat_tekanan)}</ul>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <h6 class="font-weight-bold">Deskripsi Kepribadian:</h6>
                                    <p class="text-sm text-justify border p-2 bg-white">${typeof res.deskripsi === 'object' ? JSON.stringify(res.deskripsi) : (res.deskripsi || '-')}</p>
                                    <h6 class="font-weight-bold mt-2">Job Match:</h6>
                                    <p class="text-sm text-justify border p-2 bg-white">${typeof res.job_match === 'object' ? JSON.stringify(res.job_match) : (res.job_match || '-')}</p>
                                </div>
                            </div>
                        `;
                    }
                    html += `</div>`; // end bg-light

                setTimeout(() => {
                    renderDiscChart('discChart1', l1);
                    renderDiscChart('discChart2', l2);
                    renderDiscChart('discChart3', l3, true);
                }, 500);
            }

            html += `</div></div>`;
            $('#assessment-container').html(html);
        }

        function formatJawaban(engine, ans) {
            // Tambahkan kembali kondisi khusus untuk DISC di sini
            if (engine === 'disc') {
                return `<span class="text-success font-weight-bold">P:</span> ${ans.most_label || '-'} <br>
                        <span class="text-danger font-weight-bold">K:</span> ${ans.least_label || '-'}`;
            } else if (engine === 'multiple_choice') {
                let text = ans.option_label || '-';
                if(ans.is_benar == 1) text += ` <i class="fas fa-check-circle text-success" title="Benar"></i>`;
                else if(ans.is_benar == 0 && ans.option_label) text += ` <i class="fas fa-times-circle text-danger" title="Salah"></i>`;
                return text;
            } else if (engine === 'single_choice' || engine === 'likert' || engine === 'dual_scale') {
                return ans.option_label || '-';
            }
            return ans.jawaban_1 || ans.option_label || '-';
        }

        // FUNGSI MENGGAMBAR GRAFIK
        function renderDiscChart(canvasId, dataSkor, isLine3 = false) {
            let canvas = document.getElementById(canvasId);
            if(!canvas) return;

            if(window[canvasId] instanceof Chart) {
                window[canvasId].destroy();
            }

            // Tentukan min/max axis Y.
            // Jika Line 3 (bisa minus), axis dari -24 sampai 24. Jika Line 1/2, dari 0 sampai 24.
            let yMin = isLine3 ? -24 : 0;
            let yMax = 24;

            window[canvasId] = new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['D', 'I', 'S', 'C'],
                    datasets: [{
                        label: 'Skor Raw',
                        data: [dataSkor.D, dataSkor.I, dataSkor.S, dataSkor.C],
                        borderColor: '#3b82f6',
                        backgroundColor: '#3b82f6',
                        borderWidth: 2,
                        pointRadius: 5,
                        fill: false,
                        tension: 0
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: yMin,
                            max: yMax,
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        $('#modal-detail').on('hidden.bs.modal', function () {
            $('.o-detaildaftar').empty();
            $('#judul-modal').empty();
            $('#tabel-detail').hide();
            $('#test-navigation').removeClass('d-flex').hide();
            $('#assessment-container').hide().empty();
        });
    });
</script>
@endsection

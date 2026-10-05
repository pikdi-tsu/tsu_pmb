@extends('user::layouts/halamanbelakang/header')
@section('title', $title)
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ isset($active_attempt) ? 'Sesi Ujian Berlangsung' : 'Selamat Datang di PMB Universitas Tiga Serangkai' }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('Dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $menu }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- ========================================================== --}}
                    {{-- STATE 1: RUANG UJIAN (JIKA ADA SESI AKTIF)               --}}
                    {{-- ========================================================== --}}
                    @if (isset($active_attempt) || isset($is_preparing))
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h5 class="m-0">
                                    {{ $test_info->nama_test }}
                                    <span class="text-danger text-bold float-right" id="countdown-timer">
                                        Sisa Waktu: <span id="time-display">Menghitung...</span>
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body">
                                {{-- TAMPILAN PERSIAPAN SEBELUM FULLSCREEN --}}
                                <input type="hidden" id="tipe_test_id" value="{{ $test_info->id }}">

                                <div id="area-persiapan" class="p-3"
                                    style="display: {{ isset($is_preparing) ? 'block' : 'none' }};">
                                    {{-- Atas: Informasi --}}
                                    <div class="text-center mb-5">
                                        <h3>Persiapan Ujian: <span class="text-primary">{{ $test_info->nama_test }}</span>
                                        </h3>
                                        <p class="text-muted" style="font-size: 1.1rem;">Durasi Ujian:
                                            <b>{{ $test_info->durasi_menit }} Menit</b>
                                        </p>
                                    </div>

                                    {{-- Tengah 1: Peringatan Pengawasan --}}
                                    <div class="alert alert-warning mb-4" style="border-left: 5px solid #ffc107;">
                                        <h5><i class="fas fa-exclamation-triangle"></i> Peringatan Sistem Pengawasan</h5>
                                        <p class="mb-0">Ujian ini diawasi secara otomatis. Anda <b>wajib</b>
                                            mengerjakannya dalam mode Layar Penuh (Fullscreen). <b>Maksimal peringatan
                                                keluar layar adalah 2 kali. Pelanggaran ke-3 = otomatis disubmit!</b></p>
                                    </div>

                                    {{-- Tengah 1.5: Deskripsi & Cara Pengerjaan --}}
                                    <div class="card mb-4 border-secondary shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title text-secondary mb-3"><i
                                                    class="fas fa-info-circle text-info"></i> Petunjuk Pengerjaan</h5>

                                            @if ($test_info->tipe_engine == 'disc' || $test_info->tipe_engine == '1')
                                                <p class="card-text mb-0">Subtes ini bertujuan untuk melihat gambaran
                                                    kepribadian dan gaya kerja Anda. <strong>Tidak ada jawaban yang benar
                                                        atau salah.</strong> Pada setiap nomor, terdapat empat pernyataan.
                                                    Tugas Anda adalah memilih satu pernyataan yang <strong>Paling
                                                        (P)</strong> menggambarkan diri Anda, dan satu pernyataan yang
                                                    <strong>Kurang (K)</strong> menggambarkan diri Anda.
                                                </p>
                                            @elseif ($test_info->tipe_engine == 'multiple_choice' || $test_info->tipe_engine == '5')
                                                <p class="card-text mb-0">Subtes ini berupa soal Pilihan Ganda (Multiple
                                                    Choice) untuk mengukur potensi akademik Anda. Silakan baca soal dengan
                                                    saksama dan pilih <strong>satu jawaban yang paling tepat</strong> dari
                                                    pilihan yang tersedia (A, B, C, atau D).</p>
                                            @elseif ($test_info->tipe_engine == 'single_choice' || $test_info->tipe_engine == '2')
                                                <p class="card-text mb-0">Subtes ini menguji ketepatan analisis Anda
                                                    terhadap sebuah pernyataan atau studi kasus. Silakan baca dengan teliti
                                                    dan tentukan apakah pernyataan yang disajikan bernilai
                                                    <strong>Benar</strong> atau <strong>Salah</strong>.
                                                </p>
                                            @else
                                                <p class="card-text mb-0">Silakan ikuti instruksi yang tertera pada layar
                                                    untuk menyelesaikan subtes ini dengan sebaik-baiknya.</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Tengah 2: Contoh Soal Hardcode --}}
                                    <div class="card mb-5 border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0 text-bold">Contoh Format Soal (Hanya Latihan)</h6>
                                        </div>
                                        <div class="card-body bg-light">

                                            {{-- KONDISI 1: DISC --}}
                                            @if ($test_info->tipe_engine == 'disc' || $test_info->tipe_engine == '1')
                                                <div class="alert alert-info py-2"><small>Pilih satu kolom <b>P (Paling)</b>
                                                        dan satu kolom <b>K (Kurang)</b> yang paling menggambarkan diri
                                                        Anda. (Tidak ada jawaban benar/salah)</small></div>
                                                <table class="table table-bordered text-center bg-white">
                                                    <thead style="background-color: #ff0000; color: white;">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>P</th>
                                                            <th>K</th>
                                                            <th class="text-left">Gambaran Diri</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td rowspan="2" class="align-middle">C1</td>
                                                            <td style="background-color: #ffff00;"><input type="radio"
                                                                    name="c_p" class="latihan-disc"></td>
                                                            <td style="background-color: #90ee90;"><input type="radio"
                                                                    name="c_k" class="latihan-disc"></td>
                                                            <td class="text-left">Berani mengambil risiko</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #ffff00;"><input type="radio"
                                                                    name="c_p" class="latihan-disc"></td>
                                                            <td style="background-color: #90ee90;"><input type="radio"
                                                                    name="c_k" class="latihan-disc"></td>
                                                            <td class="text-left">Berhati-hati dan teliti</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div id="feedback-disc" class="mt-2 font-weight-bold"></div>

                                                {{-- KONDISI 2: TPA (Pilihan Ganda) --}}
                                            @elseif ($test_info->tipe_engine == 'multiple_choice' || $test_info->tipe_engine == '5')
                                                <p class="font-weight-bold">C1. Sinonim dari kata "EVOKASI" adalah...</p>
                                                <div class="form-check">
                                                    <input type="radio" name="c_tpa" class="latihan-radio"
                                                        value="salah" id="tpa_a">
                                                    <label for="tpa_a">A. Penilaian</label>
                                                </div>
                                                <div class="form-check">
                                                    {{-- Value di-set "benar" untuk jawaban yang tepat --}}
                                                    <input type="radio" name="c_tpa" class="latihan-radio"
                                                        value="benar" id="tpa_b">
                                                    <label for="tpa_b">B. Penggugah rasa</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="c_tpa" class="latihan-radio"
                                                        value="salah" id="tpa_c">
                                                    <label for="tpa_c">C. Provokasi</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="c_tpa" class="latihan-radio"
                                                        value="salah" id="tpa_d">
                                                    <label for="tpa_d">D. Finalisasi</label>
                                                </div>
                                                {{-- Tempat memunculkan tulisan benar/salah --}}
                                                <div id="feedback-jawaban" class="mt-3 font-weight-bold"></div>

                                                {{-- KONDISI 3: HIP (Benar / Salah) --}}
                                            @elseif ($test_info->tipe_engine == 'single_choice' || $test_info->tipe_engine == '2')
                                                <p class="font-weight-bold">C1. Ibukota negara Indonesia saat ini (2024)
                                                    adalah Jakarta.</p>
                                                <div class="form-check">
                                                    {{-- Value di-set "benar" untuk jawaban yang tepat --}}
                                                    <input type="radio" name="c_hip" class="latihan-radio"
                                                        value="benar" id="hip_benar">
                                                    <label for="hip_benar">Benar</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="c_hip" class="latihan-radio"
                                                        value="salah" id="hip_salah">
                                                    <label for="hip_salah">Salah</label>
                                                </div>
                                                {{-- Tempat memunculkan tulisan benar/salah --}}
                                                <div id="feedback-jawaban" class="mt-3 font-weight-bold"></div>
                                            @else
                                                <p class="text-danger">Format contoh soal belum tersedia untuk subtes ini.
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Bawah: Tombol Mulai Ujian Asli --}}
                                    <div class="text-center mt-5 mb-3">
                                        <button type="button" id="btn-mulai-fullscreen"
                                            class="btn btn-primary btn-lg px-5 py-3 shadow">
                                            <i class="fas fa-expand"></i> Mulai Ujian Sesungguhnya
                                        </button>
                                    </div>
                                </div>

                                {{-- AREA UJIAN (DISAAT FULLSCREEN AKTIF) --}}
                                <div id="area-ujian" style="display: none;">
                                    <form id="form-ujian" action="{{ route('assessment.finish') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="attempt_id" id="attempt_id"
                                            value="{{ isset($active_attempt) ? $active_attempt->id : '' }}">

                                        {{-- 1. BAGIAN SOAL --}}
                                        @foreach ($questions as $index => $q)
                                            <div class="question-container" id="question-{{ $index }}"
                                                data-qid="{{ $q->id }}"
                                                style="display: {{ $index == 0 ? 'block' : 'none' }};">

                                                @if ($test_info->tipe_engine == 'disc' || $test_info->tipe_engine == '1')
                                                    {{-- TAMPILAN KHUSUS DISC (Teks Pertanyaan Tidak Ditampilkan) --}}
                                                    <div class="table-responsive mt-3">
                                                        <table class="table table-bordered text-center">
                                                            <thead class="text-white" style="background-color: #ff0000;">
                                                                {{-- Warna Header Merah seperti referensi --}}
                                                                <tr>
                                                                    <th width="5%" class="align-middle"
                                                                        style="background-color: #d3d3d3; color: black;">
                                                                        No.
                                                                    </th>
                                                                    <th width="5%" class="align-middle"
                                                                        style="background-color: #ffff00; color: black;">P
                                                                    </th>
                                                                    <th width="5%" class="align-middle"
                                                                        style="background-color: #90ee90; color: black;">K
                                                                    </th>
                                                                    <th class="text-left align-middle">Gambaran Diri</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {{-- Hitung jumlah opsi untuk menentukan nilai rowspan --}}
                                                                @php
                                                                    $jmlOpsi = count($q->options);
                                                                @endphp

                                                                @foreach ($q->options as $idx => $opt)
                                                                    <tr>
                                                                        {{-- Tampilkan TD Nomor hanya pada iterasi opsi pertama --}}
                                                                        @if ($idx == 0)
                                                                            <td rowspan="{{ $jmlOpsi }}"
                                                                                class="align-middle font-weight-bold"
                                                                                style="font-size: 1.2rem; background-color: #d3d3d3;">
                                                                                {{-- Mengambil nomor urut soal dari parent loop --}}
                                                                                {{ $loop->parent->iteration ?? $index + 1 }}
                                                                            </td>
                                                                        @endif

                                                                        <td class="align-middle"
                                                                            style="background-color: #ffff00;">
                                                                            <input type="radio"
                                                                                style="transform: scale(1.5);"
                                                                                class="answer-option-disc"
                                                                                name="p_{{ $q->id }}"
                                                                                value="{{ $opt->id }}"
                                                                                data-question="{{ $q->id }}"
                                                                                data-type="most"
                                                                                {{ isset($saved_answers[$q->id]) && $saved_answers[$q->id]['most_option_id'] == $opt->id ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td class="align-middle"
                                                                            style="background-color: #90ee90;">
                                                                            <input type="radio"
                                                                                style="transform: scale(1.5);"
                                                                                class="answer-option-disc"
                                                                                name="k_{{ $q->id }}"
                                                                                value="{{ $opt->id }}"
                                                                                data-question="{{ $q->id }}"
                                                                                data-type="least"
                                                                                {{ isset($saved_answers[$q->id]) && $saved_answers[$q->id]['least_option_id'] == $opt->id ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td class="text-left align-middle">
                                                                            {{ $opt->label }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    {{-- TAMPILAN TPA / PILIHAN GANDA BIASA --}}
                                                    {{-- TEKS PERTANYAAN PINDAH KE SINI AGAR HANYA MUNCUL DI NON-DISC --}}
                                                    <p class="text-bold">{{ $index + 1 }}. {{ $q->pertanyaan }}</p>

                                                    @foreach ($q->options as $opt)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input answer-option" type="radio"
                                                                name="q_{{ $q->id }}"
                                                                id="opt_{{ $opt->id }}" value="{{ $opt->id }}"
                                                                data-question="{{ $q->id }}"
                                                                {{ isset($saved_answers[$q->id]) && $saved_answers[$q->id]['option_id'] == $opt->id ? 'checked' : '' }}>

                                                            <label class="form-check-label" for="opt_{{ $opt->id }}"
                                                                style="cursor: pointer;">
                                                                @if (!empty($opt->kode))
                                                                    <span
                                                                        class="text-bold mr-1">{{ $opt->kode }}.</span>
                                                                @endif
                                                                {{ $opt->label }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                {{-- INDIKATOR TERSIMPAN & TOMBOL SELESAI --}}
                                                <div class="mt-4">
                                                    <span class="text-success text-sm saved-indicator font-weight-bold"
                                                        id="indicator-{{ $q->id }}" style="display:none;">
                                                        <i class="fas fa-check"></i> Jawaban Tersimpan!
                                                    </span>

                                                    @if ($index == count($questions) - 1)
                                                        <br><br>
                                                        <button type="submit" class="btn btn-success"
                                                            id="btn-submit-ujian">
                                                            <i class="fas fa-paper-plane"></i> Selesai Ujian
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </form>

                                    {{-- 2. NAVIGASI SOAL (DIPINDAH KE LUAR LOOP SOAL) --}}
                                    <div class="card mt-4 mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 font-weight-bold">Navigasi Soal</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap" style="gap: 8px;">
                                                @foreach ($questions as $index => $q)
                                                    @php
                                                        $isAnswered = false;
                                                        if (isset($saved_answers[$q->id])) {
                                                            if (
                                                                $test_info->tipe_engine == 'disc' ||
                                                                $test_info->tipe_engine == '1'
                                                            ) {
                                                                if (
                                                                    !empty($saved_answers[$q->id]['most_option_id']) &&
                                                                    !empty($saved_answers[$q->id]['least_option_id'])
                                                                ) {
                                                                    $isAnswered = true;
                                                                }
                                                            } else {
                                                                if (!empty($saved_answers[$q->id]['option_id'])) {
                                                                    $isAnswered = true;
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    <button type="button"
                                                        class="btn btn-sm btn-nav-question {{ $isAnswered ? 'btn-success' : 'btn-danger' }}"
                                                        data-target="{{ $index }}"
                                                        id="nav-btn-{{ $q->id }}"
                                                        style="width: 40px; height: 40px; font-weight: bold;">
                                                        {{ $index + 1 }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            <div class="mt-3">
                                                <span class="badge bg-success p-2 mr-2"><i class="fas fa-check"></i> Sudah
                                                    Dijawab</span>
                                                <span class="badge bg-danger p-2"><i class="fas fa-times"></i> Belum
                                                    Dijawab</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                </div>

                {{-- ========================================================== --}}
                {{-- STATE 2: DASHBOARD UTAMA (JIKA TIDAK ADA SESI AKTIF)     --}}
                {{-- ========================================================== --}}
            @else
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">
                            {{ $menu }} <span
                                class="badge bg-success text-bold">{{ $data->KodePendaftaran ?? '' }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($test as $key => $p)
                            @php
                                // Cek status tes ini dan tes sebelumnya untuk mengunci urutan
                                $attempt = $attempts[$p->id] ?? null;
                                $prevTest = $test->get($key - 1) ?? null;
                                $prevAttempt = $prevTest ? $attempts[$prevTest->id] ?? null : null;
                            @endphp
                            <div>
                                <hr>
                                <span class="text-bold">{{ $key + 1 }}. {{ $p->nama_test }}</span><br>
                                <span class="text-bold">Durasi : {{ $p->durasi_menit }} Menit</span> <br>

                                @if (!$attempt)
                                    <span class="text-bold">Status : Belum dikerjakan</span><br>
                                    @if ($prevTest && (!$prevAttempt || $prevAttempt->status != 'finished'))
                                        <button class="btn btn-secondary btn-sm" disabled>Belum Bisa</button>
                                    @else
                                        <a href="{{ route('assessment.prepare', $p->id) }}"
                                            class="btn btn-primary btn-sm">Mulai Test</a>
                                    @endif
                                @elseif($attempt->status == 'in_progress')
                                    <span class="text-bold">Status : Sedang dikerjakan</span><br>
                                    <a href="{{ route('assessment.index') }}"
                                        class="btn btn-warning btn-sm">Lanjutkan</a>
                                @elseif($attempt->status == 'finished')
                                    <span class="text-bold">Status : Selesai</span><br>
                                    <button class="btn btn-success btn-sm" disabled>Selesai</button>
                                @endif
                                <hr>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
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

            @if (isset($active_attempt) || isset($is_preparing))
                const engineType = "{{ $test_info->tipe_engine }}";
                const totalQuestions = {{ count($questions) }};
                let isTimeOut = false;
                let isSubmitting = false;
                let isUjianAktif = false;
                let isReloading = false;
                let cancelReloadTimer = null; // Timer khusus untuk deteksi batal refresh
                let isPreparingPage = {{ isset($is_preparing) ? 'true' : 'false' }};
                const maxPelanggaran = 2;

                // Enkripsi Local Storage
                let currentAttemptId = $('#attempt_id').val();
                let secretKey = currentAttemptId ? btoa('sistem_pengawas_' + currentAttemptId) : null;

                let pelanggaran = 0;
                if (secretKey && localStorage.getItem(secretKey)) {
                    try {
                        pelanggaran = parseInt(atob(localStorage.getItem(secretKey)));
                        if (isNaN(pelanggaran)) pelanggaran = 0;
                    } catch (e) {
                        pelanggaran = 0;
                    }
                }

                let sisaDetik = {{ $sisa_waktu_detik }};
                let isTimerStarted = {{ $is_timer_started ? 'true' : 'false' }};

                const areaPersiapan = $('#area-persiapan');
                const areaUjian = $('#area-ujian');

                function masukModeFullscreen() {
                    let elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen().catch(err => {});
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    }
                }

                if (isPreparingPage && !isTimerStarted) {
                    $(document).one('click', function() {
                        masukModeFullscreen();
                    });
                }

                // ==========================================================
                // FITUR RESUME: Kondisi saat halaman di-refresh / jaringan putus
                // ==========================================================
                if (isTimerStarted) {
                    areaPersiapan.hide();
                    $('#countdown-timer').show();

                    if (pelanggaran > maxPelanggaran) {
                        Swal.fire({
                            title: 'Ujian Dihentikan!',
                            text: 'Sistem mencatat Anda telah melewati batas maksimal pelanggaran pada sesi sebelumnya. Jawaban disubmit otomatis.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 4000,
                            willClose: () => {
                                bersihkanMemoriPelanggaran();
                                document.getElementById('form-ujian').submit();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Sesi Ujian Ditemukan',
                            text: 'Anda sebelumnya keluar atau me-refresh halaman. Waktu ujian Anda tetap berjalan. Klik tombol di bawah untuk kembali masuk ke mode ujian.',
                            icon: 'info',
                            allowOutsideClick: false,
                            confirmButtonText: 'Lanjutkan Ujian',
                            confirmButtonColor: '#28a745'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                masukModeFullscreen();
                                areaUjian.fadeIn();
                                initCountdownTimer();
                                setTimeout(() => {
                                    isUjianAktif = true;
                                }, 1000);
                            }
                        });
                    }
                }

                function bersihkanMemoriPelanggaran() {
                    let attId = $('#attempt_id').val();
                    let currentSecretKey = attId ? btoa('sistem_pengawas_' + attId) : (typeof secretKey !==
                        'undefined' ? secretKey : null);
                    if (currentSecretKey) localStorage.removeItem(currentSecretKey);
                }

                // EVENT: TOMBOL MULAI UJIAN
                $(document).on('click', '#btn-mulai-fullscreen', function(e) {
                    e.preventDefault();
                    masukModeFullscreen();

                    if (!isTimerStarted) {
                        Swal.fire({
                            title: 'Menyiapkan Soal...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('assessment.start_exam') }}",
                            type: "POST",
                            data: {
                                tipe_test_id: $('#tipe_test_id').val()
                            },
                            success: function(res) {
                                Swal.close();
                                if (res.status === 'success') {
                                    window.location.href = "{{ route('assessment.index') }}";
                                    return;
                                }
                            },
                            error: function() {
                                Swal.close();
                                Swal.fire('Error',
                                    'Gagal memulai ujian. Cek koneksi internet Anda.',
                                    'error');
                            }
                        });
                    }
                });

                initPreventExit();
                initNavigation();
                initAutoSaveUmum();
                initAutoSaveDisc();
                initSubmitValidation();

                document.addEventListener('fullscreenchange', checkPelanggaran);
                document.addEventListener('webkitfullscreenchange', checkPelanggaran);
                document.addEventListener('mozfullscreenchange', checkPelanggaran);
                document.addEventListener('MSFullscreenChange', checkPelanggaran);
                document.addEventListener('visibilitychange', checkPelanggaran);
                initLatihanTpaHip();
                initLatihanDisc();
                window.addEventListener('blur', checkPelanggaran);

                // ==========================================================
                // LOGIKA ANTI-CHEAT UTAMA (Dengan Jeda Cerdas 1500ms)
                // ==========================================================
                function checkPelanggaran() {
                    if (!isUjianAktif || isSubmitting) return;

                    let terdeteksiMelanggar = false;
                    if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !
                        document.msFullscreenElement) terdeteksiMelanggar = true;
                    if (document.hidden || document.visibilityState === 'hidden') terdeteksiMelanggar = true;
                    if (!document.hasFocus()) terdeteksiMelanggar = true;

                    if (terdeteksiMelanggar) {
                        isUjianAktif = false; // Tahan sensor sementara

                        setTimeout(() => {
                            // Jika saat jeda habis ternyata user sedang me-refresh, HENTIKAN proses penghukuman!
                            if (isReloading) {
                                // Jika layar mengecil karena user klik 'Cancel' refresh, paksa fullscreen ulang tanpa hukuman
                                if (!document.fullscreenElement) {
                                    Swal.fire({
                                        title: 'Layar Terkecil',
                                        text: 'Aktivitas sistem terdeteksi. Silakan kembali ke mode ujian.',
                                        icon: 'info',
                                        allowOutsideClick: false,
                                        confirmButtonText: 'Kembali Fullscreen'
                                    }).then((res) => {
                                        if (res.isConfirmed) {
                                            masukModeFullscreen();
                                            setTimeout(() => {
                                                isUjianAktif = true;
                                            }, 1000);
                                        }
                                    });
                                } else {
                                    isUjianAktif = true;
                                }
                                return; // Keluar dari fungsi, JANGAN tambah angka pelanggaran
                            }

                            // --- JIKA BUKAN REFRESH (Pindah Tab / Alt+Tab Murni) ---
                            pelanggaran++;
                            if (secretKey) localStorage.setItem(secretKey, btoa(pelanggaran.toString()));

                            if (pelanggaran > maxPelanggaran) {
                                isSubmitting = true;
                                Swal.fire({
                                    title: 'Ujian Dihentikan!',
                                    text: 'Batas maksimal pelanggaran telah terlewati. Jawaban Anda disubmit otomatis.',
                                    icon: 'error',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    timer: 4000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        bersihkanMemoriPelanggaran();
                                        document.getElementById('form-ujian').submit();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Peringatan Sistem!',
                                    text: `Anda terdeteksi keluar dari layar ujian! Peringatan ke-${pelanggaran} dari ${maxPelanggaran}.`,
                                    icon: 'warning',
                                    allowOutsideClick: false,
                                    confirmButtonText: 'Kembali ke Ujian'
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        masukModeFullscreen();
                                        setTimeout(() => {
                                            isUjianAktif = true;
                                        }, 1000);
                                    }
                                });
                            }
                        }, 1500); // Jeda diperpanjang agar tidak bentrok dengan loading browser
                    }
                }

                setInterval(function() {
                    if (isUjianAktif && secretKey) {
                        let dataDiStorage = localStorage.getItem(secretKey);
                        let angkaDiStorage = 0;
                        if (dataDiStorage) {
                            try {
                                angkaDiStorage = parseInt(atob(dataDiStorage));
                                if (isNaN(angkaDiStorage)) angkaDiStorage = 0;
                            } catch (e) {
                                angkaDiStorage = 0;
                            }
                        }
                        if (angkaDiStorage < pelanggaran) {
                            localStorage.setItem(secretKey, btoa(pelanggaran.toString()));
                        }
                    }
                }, 1500);

                // ==========================================================
                // FUNGSI PENCEGAHAN RELOAD & PENANDA BENDERA (Direvisi)
                // ==========================================================
                function initPreventExit() {
                    window.addEventListener('beforeunload', function(e) {
                        isReloading = true; // Kunci menyala, Anti-Cheat dimatikan
                        clearTimeout(cancelReloadTimer); // Hapus timer batal jika ada

                        if (!isSubmitting && isUjianAktif) {
                            e.preventDefault();
                            e.returnValue = 'Ujian sedang berlangsung. Yakin ingin me-refresh?';
                        }
                    });

                    // Cerdas: Jika user klik 'Cancel' pada pop-up refresh, window akan kembali fokus
                    window.addEventListener('focus', function() {
                        if (isReloading) {
                            // Tunggu 2 detik untuk memastikan layar benar-benar stabil sebelum menyalakan Anti-Cheat lagi
                            cancelReloadTimer = setTimeout(() => {
                                isReloading = false;
                            }, 2000);
                        }
                    });
                }

                function initNavigation() {
                    $('.btn-next, .btn-prev, .btn-nav-question').click(function() {
                        let targetId = $(this).data('target');
                        $('.question-container').hide();
                        $('#question-' + targetId).show();
                    });
                }

                function initAutoSaveUmum() {
                    $('.answer-option').change(function() {
                        let qId = $(this).data('question');
                        let optId = $(this).val();
                        let indicator = $('#indicator-' + qId);
                        let nextIndex = parseInt($(this).closest('.question-container').attr('id').replace(
                            'question-', '')) + 1;
                        showSavingIndicator(indicator, qId);
                        $.ajax({
                            url: "{{ route('assessment.save_answer') }}",
                            type: "POST",
                            data: {
                                attempt_id: $('#attempt_id').val(),
                                question_id: qId,
                                option_id: optId
                            },
                            success: function() {
                                showSuccessIndicator(indicator);
                                autoNextQuestion(nextIndex);
                            },
                            error: function() {
                                showErrorIndicator(indicator);
                            }
                        });
                    });
                }

                function initAutoSaveDisc() {
                    $('.answer-option-disc').change(function() {
                        let qId = $(this).data('question');
                        let type = $(this).data('type');
                        let val = $(this).val();
                        let indicator = $('#indicator-' + qId);
                        let nextIndex = parseInt($(this).closest('.question-container').attr('id').replace(
                            'question-', '')) + 1;
                        if (type === 'most') {
                            $(`input[name="k_${qId}"][value="${val}"]`).prop('checked', false);
                        } else {
                            $(`input[name="p_${qId}"][value="${val}"]`).prop('checked', false);
                        }
                        let mostVal = $(`input[name="p_${qId}"]:checked`).val();
                        let leastVal = $(`input[name="k_${qId}"]:checked`).val();
                        let payload = {
                            attempt_id: $('#attempt_id').val(),
                            question_id: qId
                        };
                        if (mostVal !== undefined) payload.most_option_id = mostVal;
                        if (leastVal !== undefined) payload.least_option_id = leastVal;
                        if (mostVal !== undefined && leastVal !== undefined) {
                            $('#nav-btn-' + qId).removeClass('btn-danger').addClass('btn-success');
                            autoNextQuestion(nextIndex);
                        } else {
                            $('#nav-btn-' + qId).removeClass('btn-success').addClass('btn-danger');
                        }
                        showSavingIndicator(indicator, qId);
                        $.ajax({
                            url: "{{ route('assessment.save_answer') }}",
                            type: "POST",
                            data: payload,
                            success: function() {
                                showSuccessIndicator(indicator);
                            },
                            error: function() {
                                showErrorIndicator(indicator);
                            }
                        });
                    });
                }

                function initCountdownTimer() {
                    if (sisaDetik <= 0) {
                        prosesWaktuHabis();
                        return;
                    }
                    updateTampilanWaktu();
                    let timerInterval = setInterval(function() {
                        sisaDetik--;
                        if (sisaDetik <= 0) {
                            clearInterval(timerInterval);
                            prosesWaktuHabis();
                        } else {
                            updateTampilanWaktu();
                        }
                    }, 1000);
                }

                function updateTampilanWaktu() {
                    let h = Math.floor(sisaDetik / 3600);
                    let m = Math.floor((sisaDetik % 3600) / 60);
                    let s = Math.floor(sisaDetik % 60);
                    $('#time-display').text((h > 0 ? (h < 10 ? "0" + h : h) + "j " : "") + (m < 10 ? "0" + m : m) +
                        "m " + (s < 10 ? "0" + s : s) + "d");
                }

                function prosesWaktuHabis() {
                    $('#time-display').text("Waktu Habis!");
                    isTimeOut = true;
                    isSubmitting = true;
                    Swal.fire({
                        title: 'Waktu Habis!',
                        text: 'Menyimpan jawaban...',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    bersihkanMemoriPelanggaran();
                    document.getElementById('form-ujian').submit();
                }

                function initSubmitValidation() {
                    $('#form-ujian').on('submit', function(e) {
                        if (isTimeOut) return true;
                        e.preventDefault();
                        let answered = true;
                        if (engineType === 'disc' || engineType === '1') {
                            $('.question-container').each(function() {
                                let qId = $(this).data('qid');
                                if ($(`input[name="p_${qId}"]:checked`).length === 0 || $(
                                        `input[name="k_${qId}"]:checked`).length === 0) answered =
                                    false;
                            });
                        } else {
                            if ($('.answer-option:checked').length < totalQuestions) answered = false;
                        }
                        if (!answered) {
                            Swal.fire({
                                title: 'Belum Selesai!',
                                text: 'Masih ada soal yang belum dijawab.',
                                icon: 'warning',
                                confirmButtonText: 'Tunjukkan Soal'
                            }).then(() => {
                                $('.btn-nav-question.btn-danger').first().click();
                                $('html, body').animate({
                                    scrollTop: $("#form-ujian").offset().top - 50
                                }, 500);
                            });
                            return false;
                        }
                        Swal.fire({
                            title: 'Selesai Ujian?',
                            text: "Yakin jawaban sudah benar?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Selesai!'
                        }).then((res) => {
                            if (res.isConfirmed) {
                                isSubmitting = true;
                                Swal.fire({
                                    title: 'Menyimpan Sesi...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                                bersihkanMemoriPelanggaran();
                                document.getElementById('form-ujian').submit();
                            }
                        });
                    });
                }

                function showSavingIndicator(ind, qId) {
                    ind.text('Menyimpan...').removeClass('text-success text-danger').addClass('text-warning')
                        .show();
                    if (engineType !== 'disc' && engineType !== '1') $('#nav-btn-' + qId).removeClass('btn-danger')
                        .addClass('btn-success');
                }

                function showSuccessIndicator(ind) {
                    ind.html('<i class="fas fa-check"></i> Tersimpan').removeClass('text-warning').addClass(
                        'text-success');
                    setTimeout(() => ind.fadeOut(), 2000);
                }

                function showErrorIndicator(ind) {
                    ind.html('<i class="fas fa-times"></i> Gagal!').removeClass('text-warning').addClass(
                        'text-danger');
                }

                function autoNextQuestion(idx) {
                    if (idx < totalQuestions) setTimeout(function() {
                        $('.question-container').hide();
                        $('#question-' + idx).show();
                    }, 500);
                }

                function initLatihanTpaHip() {
                    const latihanRadios = document.querySelectorAll('.latihan-radio');
                    const feedbackContainer = document.getElementById('feedback-jawaban');

                    // Jika elemen tidak ditemukan (karena user sedang buka subtes DISC), hentikan fungsi
                    if (!feedbackContainer) return;

                    latihanRadios.forEach(radio => {
                        radio.addEventListener('change', function() {
                            if (this.value === 'benar') {
                                feedbackContainer.innerHTML =
                                    '<span class="text-success"><i class="fas fa-check-circle"></i> Jawaban Simulasi Anda Benar!</span>';
                            } else {
                                feedbackContainer.innerHTML =
                                    '<span class="text-danger"><i class="fas fa-times-circle"></i> Jawaban Simulasi Anda Salah. Coba lagi!</span>';
                            }
                        });
                    });
                }

                // 2. Fungsi khusus untuk menangani soal DISC (tidak ada Benar/Salah)
                function initLatihanDisc() {
                    const discRadios = document.querySelectorAll('.latihan-disc');
                    const feedbackDisc = document.getElementById('feedback-disc');

                    // Jika elemen tidak ditemukan (karena user sedang buka subtes TPA/HIP), hentikan fungsi
                    if (!feedbackDisc) return;

                    discRadios.forEach(radio => {
                        radio.addEventListener('change', function() {
                            feedbackDisc.innerHTML =
                                '<span class="text-primary"><i class="fas fa-info-circle"></i> Pilihan Anda berhasil ditandai. Ini adalah simulasi interaksi.</span>';
                        });
                    });
                }
            @endif
        });
    </script>
@endsection

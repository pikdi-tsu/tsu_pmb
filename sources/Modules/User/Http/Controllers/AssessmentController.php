<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Assessment\Assessment_Attempts;
use App\Models\Assessment\Assessment_TipeTest;
use App\Models\Assessment\Assessment_Questions;
use App\Models\Assessment\Assessment_Answers;
use App\Models\User\Pendaftaran;

class AssessmentController extends Controller
{
    private function getKodePendaftaran()
    {
        $bioId = decrypt(session('user')->_biodata);
        $pendaftaran = Pendaftaran::where('biodata_id', $bioId)->first();
        return $pendaftaran ? $pendaftaran->KodePendaftaran : null;
    }

    public function index()
    {
        $bioId = decrypt(session('user')->_biodata);
        $kodePendaftaran = $this->getKodePendaftaran(); // Ambil Kode Pendaftaran Asli

        // 1. CEK SESI AKTIF menggunakan $kodePendaftaran
        $activeAttempt = Assessment_Attempts::where('kodependaftaran', $kodePendaftaran)
            ->where('status', 'in_progress')
            ->first();

        if ($activeAttempt) {
            $testInfo = Assessment_TipeTest::find($activeAttempt->tipe_test_id);

            // ==========================================
            // PENGECEKAN WAKTU DAN TIMER
            // ==========================================
            // Cek apakah timer BENAR-BENAR sudah dijalankan (selesai_at tidak null)
            if ($activeAttempt->selesai_at) {
                $selesai_at = \Carbon\Carbon::parse($activeAttempt->selesai_at);
                $sekarang = \Carbon\Carbon::now();

                // Cek apakah waktu sudah habis secara server-side
                if ($sekarang >= $selesai_at) {
                    // Auto-submit via backend jika user me-refresh saat waktu habis
                    return $this->processFinishTest($activeAttempt->id);
                }
                
                // Hitung sisa detik berdasarkan waktu server
                $sisaWaktuDetik = $sekarang->diffInSeconds($selesai_at);
                $isTimerStarted = true; // Tandai timer sudah jalan
            } else {
                // Jika belum mulai (masih baca peringatan), kasih waktu full tapi jangan jalankan perhitungan
                $sisaWaktuDetik = $testInfo->durasi_menit * 60;
                $isTimerStarted = false; // Tandai timer belum jalan
            }
            // ==========================================


            $questions = Assessment_Questions::where('tipe_test_id', $testInfo->id)
                ->where('isactive', 1)->orderBy('urutan')->get();

            $questionIds = $questions->pluck('id')->toArray();
            $allOptions = \DB::table('pmb_assessment_question_options')
                ->whereIn('question_id', $questionIds)
                ->orderBy('urutan')->get()->groupBy('question_id');

            foreach ($questions as $q) {
                $q->options = $allOptions->get($q->id, collect());
            }

            $savedAnswers = Assessment_Answers::where('attempt_id', $activeAttempt->id)
                ->get()->keyBy('question_id')->toArray();

            return view('user::user.assessment.index', [
                'title' => 'Mengerjakan: ' . $testInfo->nama_test,
                'menu' => 'Sesi Ujian',
                'active_attempt' => $activeAttempt,
                'test_info' => $testInfo,
                'questions' => $questions,
                'saved_answers' => $savedAnswers,
                'selesai_at' => $activeAttempt->selesai_at,
                'sisa_waktu_detik' => $sisaWaktuDetik,
                'is_timer_started' => $isTimerStarted // Variabel penanda untuk Javascript
            ]);
        }

        // 2. TAMPILAN DASHBOARD NORMAL
        $status = Pendaftaran::join('pmb_master_batchpendaftaran as a', 'pmb_pendaftaran.batch_daftar', '=', 'a.id')
            ->where('pmb_pendaftaran.biodata_id', $bioId)
            ->whereRaw('? BETWEEN a.tglmulai and a.tglselesai', [date('Y-m-d')])
            ->selectRaw('pmb_pendaftaran.*')
            ->first();

        $tests = Assessment_TipeTest::where('isactive', 1)->orderBy('urutan')->get();
        
        // Cek attempt di dashboard juga pakai $kodePendaftaran
        $attempts = Assessment_Attempts::where('kodependaftaran', $kodePendaftaran)->get()->keyBy('tipe_test_id');

        return view('user::user.assessment.index', [
            'title' => 'Assessment Test',
            'menu' => 'Assessment Test',
            'data' => $status,
            'test' => $tests,
            'attempts' => $attempts
        ]);
    }

    public function prepareTest($id)
    {
        $kodePendaftaran = $this->getKodePendaftaran();

        $exists = Assessment_Attempts::where('kodependaftaran', $kodePendaftaran)
            ->where('tipe_test_id', $id)
            ->first();

        if ($exists && $exists->status == 'finished') {
            return redirect()->route('assessment.index')->with('error', 'Anda sudah menyelesaikan tes ini.');
        }

        if ($exists && $exists->status == 'in_progress') {
            return redirect()->route('assessment.index');
        }

        $testInfo = Assessment_TipeTest::findOrFail($id);

        return view('user::user.assessment.index', [
            'title' => 'Persiapan: ' . $testInfo->nama_test,
            'menu' => 'Persiapan Ujian',
            'is_preparing' => true,
            'test_info' => $testInfo,
            'questions' => collect(), // Kosongkan soal saat persiapan agar tidak dapat diintip via inspect element
            'saved_answers' => [], 
            'sisa_waktu_detik' => $testInfo->durasi_menit * 60,
            'is_timer_started' => false
        ]);
    }

    public function startExam(Request $request)
    {
        $kodePendaftaran = $this->getKodePendaftaran();
        $tipeTestId = $request->tipe_test_id;

        $testInfo = Assessment_TipeTest::findOrFail($tipeTestId);
        $durasi = (int) $testInfo->durasi_menit;

        $attempt = Assessment_Attempts::where('kodependaftaran', $kodePendaftaran)
            ->where('tipe_test_id', $tipeTestId)
            ->first();

        if (!$attempt) {
            // Pencatatan Attempts Baru Dieksekusi Disini
            $attempt = Assessment_Attempts::create([
                'kodependaftaran' => $kodePendaftaran,
                'tipe_test_id' => $tipeTestId,
                'mulai_at' => Carbon::now(),
                'selesai_at' => Carbon::now()->addMinutes($durasi),
                'status' => 'in_progress'
            ]);
        } 

        return response()->json([
            'status' => 'success',
            'attempt_id' => $attempt->id,
            'sisa_detik' => $durasi * 60
        ]);
    }


    // Method finish dipecah agar bisa dipanggil otomatis oleh sistem jika waktu habis
    public function finishTest(Request $request)
    {
        return $this->processFinishTest($request->attempt_id);
    }


    private function processFinishTest($attemptId)
    {
        $kodePendaftaran = $this->getKodePendaftaran();
        $attempt = Assessment_Attempts::where('id', $attemptId)
            ->where('kodependaftaran', $kodePendaftaran)
            ->firstOrFail();

        // 1. INSERT JAWABAN KOSONG UNTUK SOAL YANG BELUM DIJAWAB
        $allQuestionIds = Assessment_Questions::where('tipe_test_id', $attempt->tipe_test_id)
            ->where('isactive', 1)->pluck('id')->toArray();
        $answeredQuestionIds = Assessment_Answers::where('attempt_id', $attemptId)
            ->pluck('question_id')->toArray();

        $unansweredIds = array_diff($allQuestionIds, $answeredQuestionIds);

        if (!empty($unansweredIds)) {
            $insertData = [];
            $now = Carbon::now();
            foreach ($unansweredIds as $qId) {
                $insertData[] = [
                    'attempt_id' => $attemptId,
                    'question_id' => $qId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            Assessment_Answers::insert($insertData);
        }

        // 2. UPDATE STATUS ATTEMPT JADI FINISHED
        $attempt->update(['status' => 'finished']);

        // ==============================================================
        // 3. CEK APAKAH SELURUH RANGKAIAN TES SUDAH SELESAI
        // ==============================================================
        $kodePendaftaran = $attempt->kodependaftaran;
        $bioId = decrypt(session('user')->_biodata);

        // Hitung total tes yang aktif di sistem
        $totalActiveTests = Assessment_TipeTest::where('isactive', 1)->count();

        // Hitung tes yang sudah diselesaikan peserta ini
        $completedTests = Assessment_Attempts::where('kodependaftaran', $kodePendaftaran)
            ->where('status', 'finished')
            ->count();

        // Jika semua tes sudah diselesaikan
        if ($completedTests >= $totalActiveTests) {
            $pendaftaran = Pendaftaran::where('KodePendaftaran', $kodePendaftaran)
                ->where('biodata_id', $bioId)
                ->first();

            if ($pendaftaran) {
                // Update Step seperti di kode asli Anda
                $pendaftaran->update([
                    'tgl_test' => now(),
                    'validasi_test' => '0',       // 0 artinya menunggu konfirmasi admin (Step kuning)
                    'status_test' => '1',         // 1 artinya sudah ikut ujian
                    'nilai_test' => 0,            // Nilai bisa diset 0 dulu, atau dihitungkan nanti oleh Admin/Sistem
                    'current_step' => $pendaftaran->current_step + 1,
                    'updated_at' => now()
                ]);
            }

            // Arahkan ke Dashboard dengan pesan spesial
            return redirect()->route('assessment.index')->with('success', 'Seluruh rangkaian Assessment Test berhasil diselesaikan! Silakan pantau status Anda pada dashboard pendaftaran.');
        }

        // Jika masih ada subtest lain, cukup kembali ke menu subtest
        return redirect()->route('assessment.index')->with('success', 'Subtest berhasil diselesaikan! Silakan lanjutkan ke subtest berikutnya.');
    }

    // Method AJAX untuk Auto-Save Jawaban
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required',
            'question_id' => 'required',
        ]);

        // ==========================================
        // Pengecekan Kepemilikan (Cegah IDOR) & Waktu Server-Side
        // ==========================================
        $kodePendaftaran = $this->getKodePendaftaran();
        $attempt = Assessment_Attempts::where('id', $request->attempt_id)
            ->where('kodependaftaran', $kodePendaftaran)
            ->first();

        // Tolak request jika sesi tidak ditemukan ATAU waktu server sudah melewati waktu selesai
        if (!$attempt || \Carbon\Carbon::now() >= \Carbon\Carbon::parse($attempt->selesai_at)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Waktu ujian sudah habis atau sesi tidak valid! Jawaban tidak disimpan.'
            ], 403);
        }
        // ==========================================


        // Siapkan data yang mau diupdate (Lanjut jika waktu masih aman)
        $updateData = [];
        if ($request->has('option_id')) {
            $updateData['option_id'] = $request->option_id;
        }
        if ($request->has('most_option_id')) {
            $updateData['most_option_id'] = $request->most_option_id;
        }
        if ($request->has('least_option_id')) {
            $updateData['least_option_id'] = $request->least_option_id;
        }

        Assessment_Answers::updateOrCreate(
            ['attempt_id' => $request->attempt_id, 'question_id' => $request->question_id],
            $updateData
        );

        return response()->json(['status' => 'success', 'message' => 'Tersimpan']);
    }
    
}


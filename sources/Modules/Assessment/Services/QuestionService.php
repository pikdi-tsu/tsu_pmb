<?php
namespace Modules\Assessment\Services;

use App\Models\Assessment\Assessment_TipeTest;
use App\Models\Assessment\Assessment_QuestionOptions;
use App\Models\Assessment\Assessment_Questions;

use Illuminate\Support\Facades\DB;

class QuestionService
{
    public function store($request)
    {
        return DB::transaction(function () use ($request) {

            $testType = Assessment_TipeTest::find($request->test_type_id);
            $engine   = $testType->tipe_engine;

            $ceknosoal = Assessment_Questions::where('tipe_test_id',$request->test_type_id)->count();
            $question = Assessment_Questions::create([
                'tipe_test_id' => $request->test_type_id,
                'pertanyaan'   => $request->pertanyaan,
                'urutan'       => $ceknosoal+1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => session('session')->nip
            ]);

            foreach ($request->options as $index => $option) {

                $kode = null;
                $isBenar = 0;

                // MULTIPLE CHOICE
                if ($engine === 'multiple_choice') {
                    $kode = chr(65 + $index);
                    $isBenar = ($request->correct_option == $index) ? 1 : 0;
                }

                // SINGLE CHOICE
                if ($engine === 'single_choice') {
                    $isBenar = 0;
                }

                // DISC
                if ($engine === 'disc') {
                    $isBenar = 0;
                }

                Assessment_QuestionOptions::create([
                    'question_id' => $question->id,
                    'label'       => $option['label'],
                    'kode'        => $kode,
                    'nilai'       => $option['nilai'] ?? null,
                    'disc_tipe'   => $option['disc_tipe'] ?? null,
                    'urutan'      => $index + 1,
                    'is_benar'    => $isBenar,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => session('session')->nip
                ]);
            }

            $data = array(
                'title' => 'Berhasil',
                'status' => 'success',
                'message' => 'Soal berhasil disimpan'
            );

            return $data;
        });
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $question = Assessment_Questions::findOrFail($request->IdSoal);
            $ceknosoal = Assessment_Questions::where('tipe_test_id',$request->test_type_id)->count();
            $question->update([
                'tipe_test_id' => $request->test_type_id,
                'pertanyaan'   => $request->pertanyaan,
                // 'urutan'       => $ceknosoal+1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => session('session')->nip
            ]);

            $testType = Assessment_TipeTest::find($question->tipe_test_id);
            $engine = $testType->tipe_engine;
            // Hapus semua option lama
            Assessment_QuestionOptions::where('question_id', $question->id)->delete();

            foreach ($request->options as $index => $opt) {
                $isBenar = 0;
                if($engine == 'multiple_choice'){
                    $isBenar = ($request->correct_option == $index) ? 1 : 0;
                }
                Assessment_QuestionOptions::create([
                    'question_id' => $question->id,
                    'label'       => $opt['label'],
                    'nilai'       => $opt['nilai'] ?? null,
                    'disc_tipe'   => $opt['disc_tipe'] ?? null,
                    'is_benar'    => $isBenar,
                    'urutan'      => $index + 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => session('session')->nip
                ]);
            }

            DB::commit();
            $data = array(
                'title' => 'Berhasil',
                'status' => 'success',
                'message' => 'Soal berhasil diperbarui'
            );
            return $data;

        } catch (\Exception $e) {

            DB::rollBack();
            $data = array(
                'title' => 'Gagal',
                'status' => 'error',
                'message' => $e
            );
            return $data;
        }
    }
}

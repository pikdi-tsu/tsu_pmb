<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master_Rekomendator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Modules\Admin\Http\Controllers\masterdata\RekomendatorController;


class DaftarRekomendatorController extends Controller
{
    public function index()
    {
        $kategori = DB::table('pmb_master_kategori_rekomendator')->whereNotIn('kategori_rekomendator',['DOSEN','TENDIK'])->where('isactive', '1')->get();
        $data = array(
            'title' => 'Daftar Rekomendator',
            'menu' => 'Daftar Rekomendator',
            'kategori' => $kategori // Kirim ke View
        );
        return view('user::daftar_rekomendator.index', $data);
    }

    private function generateKodeRekomendator($kodeKategori)
    {
        $kategori = DB::table('pmb_master_kategori_rekomendator')->where('kode_kategori', $kodeKategori)->first();

        if (!$kategori) {
            return 'UNKNOWN0001';
        }

        $prefix = $kategori->kode_kategori;

        // PERBAIKAN: Cari murni berdasarkan awalan kode_rekomendator-nya saja (mengabaikan kolom kategori)
        // dan urutkan berdasarkan kodenya secara menurun (Z-A) agar dapat angka terbesar
        $lastData = Master_Rekomendator::where('kode_rekomendator', 'like', $prefix . '%')
            ->orderBy('kode_rekomendator', 'desc')
            ->first();

        if (!$lastData || empty($lastData->kode_rekomendator)) {
            $newNumber = 1;
        } else {
            $lastKode = $lastData->kode_rekomendator;
            $lastNumber = (int) substr($lastKode, strlen($prefix));
            $newNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function save(Request $post)
    {
        DB::beginTransaction();
        $kode = $this->generateKodeRekomendator($post->kategori);

        $post->validate([
            'nama_rekomendator' => 'required',
            'kategori' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'no_rekening' => 'required',
            'atasnama_rekening' => 'required',
            'nama_bank' => 'required'
        ], [
            'nama_rekomendator.required' => 'Nama wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'pekerjaan.required' => 'Pekerjaan wajib diisi',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'no_rekening.required' => 'Nomor Rekening wajib diisi',
            'atasnama_rekening.required' => 'Atas Nama Rekening wajib diisi',
            'nama_bank.required' => 'Nama Bank wajib diisi',
        ]);

        try {
            $arrayIn = array(
                'kode_rekomendator' => $kode,
                'kategori'          => $post->kategori,
                'nama_rekomendator' => $post->nama_rekomendator,
                'alamat'            => $post->alamat,
                'pekerjaan'         => $post->pekerjaan,
                'no_hp'             => $post->no_hp,
                'email'             => $post->email,
                'no_rekening'       => $post->no_rekening,
                'atasnama_rekening' => $post->atasnama_rekening,
                'nama_bank'         => $post->nama_bank,
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => 'System',
                'isactive'          => '0'
            );
            Master_Rekomendator::insert($arrayIn);
            DaftarRekomendatorController::sendEmail($post->email, $post->nama_rekomendator, $kode, "Notifikasi Kode Rekomendator");
            DB::commit();

            $status = ['title' => 'Berhasil', 'status' => 'success', 'message' => 'Data Rekomendator Berhasil Disimpan'];
            return redirect()->route('indexing')->with('alert', $status);
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('Gagal simpan data rekomendator: ' . $e->getMessage());
            $status = ['title' => 'Gagal', 'status' => 'error', 'message' => 'Data Rekomendator Gagal Disimpan. Silakan coba kembali.'];
            return redirect()->back()->with('alert', $status);
        }
    }

    public static function sendEmail($email, $nama, $kode, $subject)
    {
        try {
            Mail::send('user::login/rekomendator_email', ['nama' => $nama, 'kode' => $kode], function ($message) use ($subject, $email) {
                $message->subject($subject);
                $message->to($email);
            });
            return 1;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email rekomendator: ' . $e->getMessage());
            return 0;
        }
    }
}

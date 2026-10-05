<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User\Biodata;
use App\Models\User\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Session;

class KartuPesertaController extends Controller
{
    /**
     * Download / Preview Kartu Peserta Ujian PMB
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $kode
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $kode = null)
    {
        // 1. Ambil pendaftaran
        if ($kode) {
            // Jika diakses oleh Admin dengan kode terenkripsi
            try {
                $kodePendaftaran = decrypt($kode);
            } catch (\Exception $e) {
                $kodePendaftaran = $kode;
            }
            $pendaftaran = Pendaftaran::where('KodePendaftaran', $kodePendaftaran)
                ->where('isactive', 1)
                ->with([
                    'biodata',
                    'batch',
                    'jalur',
                    'jenisbeasiswa',
                    'prodi1.jenjang',
                    'prodi2.jenjang',
                    'waktukuliah'
                ])
                ->first();
        } else {
            // Diakses oleh Peserta yang sedang login
            if (!session()->has('user') || !isset(session('user')->_biodata)) {
                return redirect()->route('LoginPMB')->with('alert', [
                    'title' => 'Perhatian',
                    'message' => 'Silakan login terlebih dahulu.',
                    'status' => 'warning'
                ]);
            }

            $bioId = decrypt(session('user')->_biodata);
            $pendaftaran = Pendaftaran::where('biodata_id', $bioId)
                ->where('isactive', 1)
                ->orderby('created_at', 'desc')
                ->with([
                    'biodata',
                    'batch',
                    'jalur',
                    'jenisbeasiswa',
                    'prodi1.jenjang',
                    'prodi2.jenjang',
                    'waktukuliah'
                ])
                ->first();
        }

        if (!$pendaftaran) {
            return redirect()->back()->with('alert', [
                'title' => 'Data Tidak Ditemukan',
                'message' => 'Data pendaftaran tidak ditemukan.',
                'status' => 'error'
            ]);
        }

        // 2. Validasi Pembayaran PMB
        // Kartu peserta baru boleh diunduh jika pembayaran PMB sudah approved (current_step > 3 atau bayar_pendaftaran == 1)
        if ($pendaftaran->bayar_pendaftaran != '1' && $pendaftaran->current_step <= 3) {
            return redirect()->back()->with('alert', [
                'title' => 'Belum Dapat Diunduh',
                'message' => 'Kartu Peserta Ujian baru dapat diunduh setelah Pembayaran Biaya Pendaftaran PMB dikonfirmasi oleh panitia.',
                'status' => 'warning'
            ]);
        }

        $biodata = $pendaftaran->biodata;

        // 3. Siapkan Logo Universitas (Base64)
        $logoBase64 = null;
        $logoPath = base_path('../public/assets/user/img/logotsu.png');
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 4. Siapkan Foto Peserta (Base64)
        $photoBase64 = null;
        if ($biodata && $biodata->photo) {
            $photoPath = storage_path('app/FILE_PHOTOPROFILE/' . $biodata->photo);
            if (file_exists($photoPath)) {
                $type = pathinfo($photoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($photoPath);
                $photoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        // Fallback default avatar jika peserta belum upload foto profil
        if (!$photoBase64) {
            $defaultAvatar = storage_path('app/FILE_PHOTOPROFILE/user.png');
            if (file_exists($defaultAvatar)) {
                $type = pathinfo($defaultAvatar, PATHINFO_EXTENSION);
                $data = file_get_contents($defaultAvatar);
                $photoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $data = [
            'daftar'      => $pendaftaran,
            'bio'         => $biodata,
            'logo'        => $logoBase64,
            'photo'       => $photoBase64,
            'tglCetak'    => date('d F Y'),
        ];

        // 5. Generate PDF
        $pdf = Pdf::loadView('user::kartu_peserta', $data);
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Kartu_Peserta_' . str_replace('/', '_', $pendaftaran->KodePendaftaran) . '.pdf';
        return $pdf->stream($fileName);
    }
}

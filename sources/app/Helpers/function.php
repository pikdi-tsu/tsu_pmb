<?php

use App\Models\Admin\PegawaiModel;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

if (!function_exists('defaultpassword')) {
    function defaultpassword()
    {
        return 'Password123';
    }
}

if (!function_exists('namaku')) {
    function namaku($nip)
    {
        $cek = PegawaiModel::where('nik',$nip)->select('nama')->first();
        $nama = '-';
        if($cek){
            $nama = $cek->nama;
        }
        return $nama;
    }
}

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('photo_profile')) {
    function photo_profile()
    {
        $photo = 'user.png';
        if (session()->has('session')) {
            $nik   = session('session')->nip ?? null;
            $email = session('session')->email ?? null;
            if ($nik && $email) {
                $cek = User::where('nik', $nik)->where('email', $email)->first();
                if ($cek && !empty($cek->photo_profile_pmb)) {
                    $photo = $cek->photo_profile_pmb;
                }
            }
        }

        return $photo;
    }
}

if (!function_exists('SendEmail')) {
    function SendEmail($email, $nama, $data, $jenis, $subject)
    {
        try {
            Mail::send('admin::template/layout/email', ['nama' => $nama, 'data' => $data, 'jenis' => $jenis], function ($message) use ($subject, $email) {
                $message->subject($subject);
                $message->to($email);
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
}

if (!function_exists('generateOrderId')) {
    function generateOrderId($transaksiId)
    {
        // Format: ORDER-20250829-00001-1234
        return 'ORDER-' . date('YmdHis') . '-' . str_pad($transaksiId, 5, '0', STR_PAD_LEFT) . '-' . rand(1000, 9999);
    }
}

#- nama hari
if (! function_exists('Hari')) {
    function Hari($str)
    {
        if ($str == 'Sun') $str = 'Minggu';
        if ($str == 'Mon') $str = 'Senin';
        if ($str == 'Tue') $str = 'Selasa';
        if ($str == 'Wed') $str = 'Rabu';
        if ($str == 'Thu') $str = 'Kamis';
        if ($str == 'Fri') $str = 'Jumat';
        if ($str == 'Sat') $str = 'Sabtu';
        return $str;
    }
}

#-- funsi nama bulan
if (! function_exists('Bulan')) {
    function Bulan($str)
    {
        if ($str == '1' or $str == '01') $str = 'Januari';
        elseif ($str == '2' or $str == '02') $str = 'Februari';
        elseif ($str == '3' or $str == '03') $str = 'Maret';
        elseif ($str == '4' or $str == '04') $str = 'April';
        elseif ($str == '5' or $str == '05') $str = 'Mei';
        elseif ($str == '6' or $str == '06') $str = 'Juni';
        elseif ($str == '7' or $str == '07') $str = 'Juli';
        elseif ($str == '8' or $str == '08') $str = 'Agustus';
        elseif ($str == '9' or $str == '09') $str = 'September';
        elseif ($str == '10') $str = 'Oktober';
        elseif ($str == '11') $str = 'November';
        elseif ($str == '12') $str = 'Desember';
        return $str;
    }
}

#-- Format TanggalInonesia
if (! function_exists('tglIndo')) {
    function tglIndo($str)
    {
        if ($str != '') :
            list($Thn, $Bln, $Tgl) = explode('-', $str, 3);
            $str = $Tgl . ' ' . Bulan($Bln) . ' ' . $Thn;
            return $str;
        else :
            return '';
        endif;
    }
}

#-- funsi nama bulan romawi
if (! function_exists('BulanRomawi')) {
    function BulanRomawi($str)
    {
        if ($str == '1' or $str == '01') $str = 'I';
        elseif ($str == '2' or $str == '02') $str = 'II';
        elseif ($str == '3' or $str == '03') $str = 'III';
        elseif ($str == '4' or $str == '04') $str = 'IV';
        elseif ($str == '5' or $str == '05') $str = 'V';
        elseif ($str == '6' or $str == '06') $str = 'VI';
        elseif ($str == '7' or $str == '07') $str = 'VII';
        elseif ($str == '8' or $str == '08') $str = 'VIII';
        elseif ($str == '9' or $str == '09') $str = 'IX';
        elseif ($str == '10') $str = 'X';
        elseif ($str == '11') $str = 'XI';
        elseif ($str == '12') $str = 'XII';
        return $str;
    }
}

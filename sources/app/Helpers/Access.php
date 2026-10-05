<?php

use App\Models\Admin\GroupUserModel;
use App\Models\Admin\User;

if (!function_exists('checkmenu')) {
    function checkmenu($modul, $menu)
    {
        if (session('admin') == 'admin' || session('namagroup') == 'Super Admin') {
            return true;
        }

        $g = session('groupuser');
        if ($g && $g->where('Modul', $modul)->where('Menu', $menu)->first()) {
            return true;
        }

        // Fallback jika session groupuser belum ter-refresh dengan data modul baru
        if (session()->has('session') && isset(session('session')->nip)) {
            $user = User::where('nik', session('session')->nip)->where('isactive', 1)->first();
            if ($user) {
                $hasAccess = GroupUserModel::where('KodeGroupUser', $user->privilege_pmb)
                    ->where('Modul', $modul)
                    ->where('Menu', $menu)
                    ->exists();

                if ($hasAccess) {
                    $freshGroup = GroupUserModel::where('KodeGroupUser', $user->privilege_pmb)->get();
                    session()->put('groupuser', $freshGroup);
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('checkmodul')) {
    function checkmodul($modul)
    {
        if (session('admin') == 'admin' || session('namagroup') == 'Super Admin') {
            return true;
        }

        $g = session('groupuser');
        if ($g && $g->where('Modul', $modul)->first()) {
            return true;
        }

        // Fallback jika session groupuser belum ter-refresh
        if (session()->has('session') && isset(session('session')->nip)) {
            $user = User::where('nik', session('session')->nip)->where('isactive', 1)->first();
            if ($user) {
                $hasAccess = GroupUserModel::where('KodeGroupUser', $user->privilege_pmb)
                    ->where('Modul', $modul)
                    ->exists();

                if ($hasAccess) {
                    $freshGroup = GroupUserModel::where('KodeGroupUser', $user->privilege_pmb)->get();
                    session()->put('groupuser', $freshGroup);
                    return true;
                }
            }
        }

        return false;
    }
}

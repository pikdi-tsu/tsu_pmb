<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Admin\LogAktivitas;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Session;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Log Aktivitas Admin',
            'menu'  => 'Log Aktivitas Admin',
        ];
        return view('admin::logaktivitas.index', $data);
    }

    public function tabel(Request $request)
    {
        $query = LogAktivitas::orderBy('created_at', 'desc');

        // Filter tanggal
        if ($request->filled('tgl_dari')) {
            $query->whereDate('created_at', '>=', $request->tgl_dari);
        }
        if ($request->filled('tgl_sampai')) {
            $query->whereDate('created_at', '<=', $request->tgl_sampai);
        }
        // Filter modul
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }
        // Filter admin
        if ($request->filled('admin_nik')) {
            $query->where('admin_nik', 'LIKE', '%' . $request->admin_nik . '%');
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('waktu', fn($d) => $d->created_at ? \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i:s') : '-')
            ->addColumn('admin', fn($d) => '<strong>' . e($d->admin_nik) . '</strong><br><small>' . e($d->admin_nama) . '</small>')
            ->addColumn('modul_aksi', fn($d) => '<span class="badge badge-info">' . e($d->modul) . '</span><br><small>' . e($d->aksi) . '</small>')
            ->addColumn('target', fn($d) => $d->target_kode ?: '-')
            ->addColumn('keterangan', fn($d) => $d->keterangan ?: '-')
            ->rawColumns(['admin', 'modul_aksi'])
            ->make(true);
    }

    /**
     * Ambil daftar modul unik untuk filter dropdown.
     */
    public function modulList()
    {
        $list = LogAktivitas::select('modul')->distinct()->orderBy('modul')->pluck('modul');
        return response()->json($list);
    }
}

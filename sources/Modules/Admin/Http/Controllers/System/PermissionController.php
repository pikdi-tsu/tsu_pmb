<?php

namespace Modules\Admin\Http\Controllers\System;

use App\Http\Controllers\MiddlewareController;
use App\Services\TsuErrorHandlerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\MenuSidebar;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends MiddlewareController
{
    public function __construct()
    {
        $this->registerPermissions('system:permission');
    }

    public function index()
    {
        $this->guard('view', 'system:permission');

        $allPerms = Permission::all();
        $stats = [
            'total'  => $allPerms->count(),
            'pmb'    => $allPerms->filter(fn ($p) => str_starts_with($p->name, 'pmb:'))->count(),
            'system' => $allPerms->filter(fn ($p) => str_starts_with($p->name, 'system:'))->count(),
            'users'  => $allPerms->filter(fn ($p) => str_starts_with($p->name, 'users:'))->count(),
        ];

        $title = 'Role Permissions';
        $menu = 'Role Permissions';
        $menuIcon = MenuSidebar::where('route', 'admin.system.permissions.index')->value('icon') ?? 'fas fa-file-shield';

        return view('admin::system.permission.index', compact('stats', 'title', 'menu', 'menuIcon'));
    }

    public function datatable()
    {
        $this->guard('view', 'system:permission');

        $data = Permission::query()->orderBy('name', 'asc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $parts = explode(':', $row->name);
                $modul = $parts[0] ?? '';
                $badgeStyle = 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;';
                if ($modul === 'pmb') {
                    $badgeStyle = 'background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;';
                } elseif ($modul === 'system') {
                    $badgeStyle = 'background:#fef3c7; color:#92400e; border:1px solid #fde68a;';
                } elseif ($modul === 'users') {
                    $badgeStyle = 'background:#dcfce7; color:#166534; border:1px solid #bbf7d0;';
                }

                return '<div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                            <span class="badge font-weight-700" style="' . $badgeStyle . ' font-size: 0.73rem; padding: 0.25rem 0.5rem; border-radius: 4px;">' . e(strtoupper($modul)) . '</span>
                            <code class="font-weight-600 px-2 py-1" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; font-size:0.83rem; color:var(--tsu-primary-dark, #094b54);">' . e($row->name) . '</code>
                        </div>';
            })
            ->addColumn('guard_name', function ($row) {
                return '<span class="badge badge-light border text-secondary font-weight-600" style="padding: 0.3rem 0.6rem; border-radius: 6px;"><i class="fas fa-shield-alt mr-1 text-primary"></i>' . e($row->guard_name) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return $this->getActionButtons($row, 'system:permission', [
                    'delete_url' => route('admin.system.permissions.destroy', $row->id),
                    'can_edit'   => false, // Permission name is immutable to prevent breakage
                ]);
            })
            ->rawColumns(['name', 'guard_name', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $this->guardStore($request->id, 'system:permission');

        $request->validate([
            'name' => [
                'required',
                'regex:/^[a-z0-9_-]+:[a-z0-9_-]+:[a-z0-9_-]+$/',
                Rule::unique(config('app.table.permissions'), 'name')->where('guard_name', 'web'),
            ],
        ], [
            'name.regex' => 'Format permission harus modul:fitur:aksi (huruf kecil, contoh: pmb:pendaftaran:view)',
        ]);

        try {
            Permission::create(['name' => strtolower(trim($request->name)), 'guard_name' => 'web']);
            return back()->with('success', 'Permission baru berhasil dibuat!');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_PERM_STORE_FAIL]', 'Gagal menyimpan permission baru.', 'Gagal Create Permission.', $request);
        }
    }

    public function destroy($id)
    {
        $this->guard('delete', 'system:permission');

        try {
            $permission = Permission::query()->findOrFail($id);
            $permission->delete();
            return back()->with('success', 'Permission berhasil dihapus!');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_PERM_DELETE_FAIL]', 'Gagal menghapus permission.', "Gagal Hapus Permission ID: $id.");
        }
    }
}

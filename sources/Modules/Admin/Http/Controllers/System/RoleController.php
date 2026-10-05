<?php

namespace Modules\Admin\Http\Controllers\System;

use App\Http\Controllers\MiddlewareController;
use App\Models\Admin\User;
use App\Services\TsuErrorHandlerService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Admin\Models\MenuSidebar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends MiddlewareController
{
    public function __construct()
    {
        $this->registerPermissions('system:role');
    }

    public function index()
    {
        $this->guard('view', 'system:role');

        $stats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'core_roles' => Role::where('is_identity', 0)->where(function ($q) {
                $q->where('name', 'like', '%admin%')->orWhereIn('name', ['dosen', 'tendik', 'panitia pmb']);
            })->count(),
            'assigned_users' => User::has('roles')->count(),
        ];

        $title = 'Role Matrix';
        $menu = 'Roles';
        $menuIcon = MenuSidebar::where('route', 'admin.system.roles.index')->value('icon') ?? 'fas fa-user-shield';

        return view('admin::system.role.index', compact('stats', 'title', 'menu', 'menuIcon'));
    }

    // JSON Datatable
    public function datatable()
    {
        $this->guard('view', 'system:role');

        $data = Role::query()->withCount('permissions')->orderBy('name', 'asc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return '<div class="d-flex align-items-center"><span class="font-weight-600 text-dark" style="font-size: 0.88rem;"><i class="fas fa-shield-alt mr-2" style="color: var(--tsu-primary, #094b54);"></i>' . e($row->name) . '</span></div>';
            })
            ->addColumn('permissions_count', function ($row) {
                $moduleName = 'super admin ' . config('app.module.name', 'pmb');
                if (in_array($row->name, ['super admin', $moduleName], true)) {
                    return '<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; font-weight:700; padding:0.35rem 0.75rem; border-radius:6px;"><i class="fas fa-crown mr-1"></i> Full Access</span>';
                }

                return '<span class="badge badge-primary px-2 py-1" style="font-size: 0.82rem; font-weight:600;"><i class="fas fa-key mr-1"></i> ' . $row->permissions_count . ' Permissions</span>';
            })
            ->editColumn('is_identity', function ($row) {
                if ($row->is_identity) {
                    return '<span class="badge" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-weight:600; padding:0.35rem 0.65rem; border-radius:6px;"><i class="fas fa-globe mr-1"></i> Global (Vault)</span>';
                }
                return '<span class="badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a; font-weight:600; padding:0.35rem 0.65rem; border-radius:6px;"><i class="fas fa-cube mr-1"></i> Lokal (PMB)</span>';
            })
            ->addColumn('action', function ($row) {
                return $this->getActionButtons($row, 'system:role', [
                    'edit_url'   => route('admin.system.roles.edit', $row->id),
                    'use_modal'  => true,
                    'can_edit'   => true,
                    'can_delete' => true,
                    'delete_url' => route('admin.system.roles.destroy', $row->id),
                ]);
            })
            ->rawColumns(['name', 'permissions_count', 'is_identity', 'action'])
            ->make(true);
    }

    // Sync Role dari TSU Homebase Vault
    public function sync()
    {
        $this->guard('create', 'system:role');

        try {
            $result = DB::transaction(function () {
                $baseUrl = config('app.tsu_homebase.url');
                $clientId = config('app.oauth.client.id');
                $clientSecret = config('app.oauth.client.secret');

                if (!$clientId || !$clientSecret) {
                    throw new \Exception('[TSU_AUTH_CONFIG] Konfigurasi HOMEBASE_CLIENT_ID / SECRET belum diisi di .env');
                }

                // Ambil Client Access Token
                $tokenResponse = Http::withoutVerifying()
                    ->withHeaders(['X-Sync-Secret' => config('app.pikdi.key.sync')])
                    ->asForm()->post($baseUrl . '/oauth/token', [
                        'grant_type'    => 'client_credentials',
                        'client_id'     => $clientId,
                        'client_secret' => $clientSecret,
                        'scope'         => '',
                    ]);

                if ($tokenResponse->failed()) {
                    throw new \Exception('[TSU_AUTH_FAIL] Gagal Otorisasi ke Homebase! Cek Client ID/Secret.');
                }

                $accessToken = $tokenResponse->json()['access_token'] ?? null;
                if (!$accessToken) {
                    throw new \Exception("[TSU_TOKEN_EMPTY] Respon token dari Homebase kosong.");
                }

                // Ambil Data Role
                $dataResponse = Http::withoutVerifying()
                    ->withHeaders(['X-Sync-Secret' => config('app.pikdi.key.sync')])
                    ->withToken($accessToken)
                    ->timeout(10)
                    ->get($baseUrl . '/api/v1/roles/sync-list');

                if ($dataResponse->failed()) {
                    throw new \Exception("[TSU_API_ERR] Gagal mengambil data Role dari Homebase. Status: " . $dataResponse->status());
                }

                $rolesFromHomebase = $dataResponse->json()['data'] ?? [];

                if (empty($rolesFromHomebase) || !is_array($rolesFromHomebase)) {
                    throw new \Exception("[TSU_DATA_INVALID] Data role dari Homebase kosong atau format salah!");
                }

                $addedCount = 0;
                $validGlobalRoles = [];

                foreach ($rolesFromHomebase as $item) {
                    $rName = is_array($item) ? ($item['name'] ?? null) : $item;

                    if ($rName) {
                        $nameLower = strtolower(trim($rName));
                        $validGlobalRoles[] = $nameLower;
                        $role = Role::updateOrCreate(
                            ['name' => $nameLower, 'guard_name' => 'web'],
                            ['is_identity' => 1]
                        );
                        if ($role->wasRecentlyCreated) {
                            $addedCount++;
                        }
                    }
                }

                $deletedCount = Role::query()
                    ->where('guard_name', 'web')
                    ->where('is_identity', 1)
                    ->whereNotIn('name', $validGlobalRoles)
                    ->delete();

                return ['added' => $addedCount, 'deleted' => $deletedCount];
            });

            $msg = "<h6 class='font-weight-bold mb-2'>Sinkronisasi Roles Selesai!</h6>";
            $msg .= "<ul class='mb-0 pl-3' style='list-style-type: disc;'>";
            if ($result['added'] > 0) {
                $msg .= "<li><b>+{$result['added']}</b> Role Global Baru ditambahkan.</li>";
            }
            if ($result['deleted'] > 0) {
                $msg .= "<li><b>-{$result['deleted']}</b> Role Global Usang dihapus.</li>";
            }
            if ($result['added'] === 0 && $result['deleted'] === 0) {
                $msg .= "<li>Data Role Global sudah <b>Up-to-Date</b>.</li>";
            }
            $msg .= "</ul>";

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            $defaultError = 'Terjadi kesalahan sistem saat sinkronisasi Role.';
            if ($e instanceof ConnectionException) {
                $defaultError = 'Gagal menghubungi Server Homebase. Cek koneksi internet.';
            }
            return TsuErrorHandlerService::handleHtml($e, '[TSU_ROLE_CRITICAL]', $defaultError, 'Gagal Sync Role.');
        }
    }

    public function create()
    {
        $this->guard('create', 'system:role');

        $permissions = Permission::query()->orderBy('name')->get();

        $groupedPermissions = $permissions->groupBy(function ($item) {
            $parts = explode(':', $item->name);
            return count($parts) > 1 ? ucfirst($parts[1]) : 'Umum';
        });

        return view('admin::system.role.create_modal', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $this->guardStore($request->id, 'system:role');

        $request->validate([
            'name'        => 'required|string|max:50|unique:' . config('app.table.roles') . ',name',
            'permissions' => 'array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $role = Role::create([
                    'name'        => strtolower(trim($request->name)),
                    'guard_name'  => 'web',
                    'is_identity' => 0,
                ]);

                $permissions = $request->permissions ?? [];
                $role->syncPermissions($permissions);

                app()[PermissionRegistrar::class]->forgetCachedPermissions();
            });

            return back()->with('success', 'Role lokal baru berhasil dibuat!');

        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_ROLE_STORE_FAIL]', 'Gagal menyimpan role baru.', 'Gagal Create Role.', $request);
        }
    }

    public function edit($id)
    {
        $this->guard('edit', 'system:role');

        $role = Role::query()->findOrFail($id);

        $permissions = Permission::query()->orderBy('name')->get();

        $groupedPermissions = $permissions->groupBy(function ($item) {
            $parts = explode(':', $item->name);
            return count($parts) > 1 ? ucfirst($parts[1]) : 'Umum';
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin::system.role.edit_modal', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->guard('edit', 'system:role');

        $role = Role::query()->findOrFail($id);

        $rules = [
            'name'        => 'required|string|max:50|unique:' . config('app.table.roles') . ',name,' . $id,
            'permissions' => 'array',
        ];

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $role) {
                if ($request->filled('name')) {
                    $role->name = strtolower(trim($request->name));
                    $role->save();
                }

                $role->syncPermissions($request->permissions ?? []);

                app()[PermissionRegistrar::class]->forgetCachedPermissions();
            });

            return back()->with('success', 'Role berhasil diperbarui!');

        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_ROLE_UPD_FAIL]', 'Gagal menyimpan perubahan role.', "Gagal Update Role ID: $id.", $request);
        }
    }

    public function destroy($id)
    {
        $this->guard('delete', 'system:role');

        try {
            $role = Role::findOrFail($id);

            $role->delete();
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return back()->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_ROLE_DELETE_FAIL]', 'Gagal menghapus role.', "Gagal Hapus Role ID: $id.");
        }
    }
}

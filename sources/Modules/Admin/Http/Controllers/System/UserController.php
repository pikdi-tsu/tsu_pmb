<?php

namespace Modules\Admin\Http\Controllers\System;

use App\Http\Controllers\MiddlewareController;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use App\Services\TsuErrorHandlerService;
use App\Services\UserSyncService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\MenuSidebar;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends MiddlewareController
{
    public function __construct()
    {
        $this->registerPermissions('system:user');
    }

    public function index()
    {
        $this->guard('view', 'system:user');

        $hasTendik = Role::where('name', 'tendik')->exists();
        $hasDosen = Role::where('name', 'dosen')->exists();
        $adminRoles = Role::whereIn('name', ['super admin', 'super admin pmb', 'admin', 'admin pmb'])->pluck('name')->toArray();

        $stats = [
            'total'  => User::count(),
            'tendik' => $hasTendik ? User::role('tendik')->count() : 0,
            'dosen'  => $hasDosen ? User::role('dosen')->count() : 0,
            'admin'  => !empty($adminRoles) ? User::role($adminRoles)->distinct('id')->count() : 0,
        ];

        $title = 'Data Pengguna Modul';
        $menu = 'Users';
        $menuIcon = MenuSidebar::where('route', 'admin.system.users.index')->value('icon') ?? 'fas fa-users';

        return view('admin::system.user.index', compact('stats', 'title', 'menu', 'menuIcon'));
    }

    public function datatable()
    {
        $this->guard('view', 'system:user');

        $data = User::query()->with(['roles', 'pegawai'])->orderBy('last_login_at', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('avatar', function ($row) {
                $url = $row->profile_photo_url;
                $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($row->name ?? 'User') . '&color=094B54&background=D0EEF2&bold=true';
                return '<div class="d-flex justify-content-center align-items-center"><img src="' . $url . '" onerror="this.onerror=null;this.src=\'' . $fallback . '\';" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #ffffff;" alt="User Image"></div>';
            })
            ->editColumn('name', function ($row) {
                $nama = $row->name ?? ($row->pegawai->nama ?? $row->pegawai->NAMA ?? $row->username ?? 'User');
                $nik = $row->nik ?? '-';
                return '<div style="line-height: 1.3;">
                            <span class="font-weight-600 text-dark" style="font-size: 0.88rem;">' . e($nama) . '</span><br>
                            <span class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-id-badge mr-1"></i> NIP/NIK: ' . e($nik) . '</span>
                        </div>';
            })
            ->editColumn('email', function ($row) {
                return '<div style="font-size: 0.82rem; color: #475569;"><i class="far fa-envelope mr-1 text-primary"></i> ' . e($row->email) . '</div>';
            })
            ->addColumn('roles', function ($row) {
                $badges = [];
                foreach ($row->roles as $role) {
                    if ($role->is_identity) {
                        $badges[] = '<span class="badge" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-size: 0.72rem; padding: 0.2rem 0.45rem; border-radius: 4px;"><i class="fas fa-globe mr-1"></i>' . e($role->name) . '</span>';
                    } else {
                        $badges[] = '<span class="badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a; font-size: 0.72rem; padding: 0.2rem 0.45rem; border-radius: 4px;"><i class="fas fa-cube mr-1"></i>' . e($role->name) . '</span>';
                    }
                }

                if (empty($badges)) {
                    return '<span class="badge badge-light border text-muted" style="font-size: 0.72rem;"><i class="fas fa-minus mr-1"></i> No Role</span>';
                }

                return '<div class="d-flex flex-wrap" style="gap: 4px;">' . implode('', $badges) . '</div>';
            })
            ->editColumn('isactive', function ($row) {
                return $row->isactive
                    ? '<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; font-size:0.75rem; padding:0.25rem 0.5rem; border-radius:5px;"><i class="fas fa-check-circle mr-1"></i> Aktif</span>'
                    : '<span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; font-size:0.75rem; padding:0.25rem 0.5rem; border-radius:5px;"><i class="fas fa-times-circle mr-1"></i> Non-Aktif</span>';
            })
            ->editColumn('last_login_at', function ($row) {
                if (!$row->last_login_at) {
                    return '<span class="text-muted" style="font-size: 0.75rem;">Belum pernah</span>';
                }
                return '<span style="font-size: 0.78rem; color: #475569;" title="' . e($row->last_login_at) . '">' . \Carbon\Carbon::parse($row->last_login_at)->diffForHumans() . '</span>';
            })
            ->addColumn('action', function ($row) {
                $user = auth()->user();
                $isSuperAdmin = $user && ($user->hasRole(['super admin', 'super admin pmb']) || session('namagroup') == 'Super Admin');
                $canEdit = $isSuperAdmin || ($user && $user->can('system:user:edit'));
                $canDelete = $isSuperAdmin || ($user && $user->can('system:user:delete'));

                $btn = '<div class="d-flex align-items-center justify-content-center" style="gap: 4px;">';

                if ($canEdit) {
                    $btn .= '<button type="button"
                                data-id="' . $row->id . '"
                                data-name="' . e($row->name ?? $row->username) . '"
                                class="btn btn-sm btn-edit-role"
                                style="background: #fefce8; color: #a16207; border: 1px solid #fef08a; border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem;"
                                title="Atur Role">
                                <i class="fas fa-user-tag mr-1"></i> Roles
                             </button>';
                }

                if ($canDelete && $row->id !== auth()->id()) {
                    $btn .= '<form action="' . route('admin.system.users.destroy', $row->id) . '" method="POST" style="display:inline;" class="form-delete">
                                ' . csrf_field() . ' ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-delete" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem;" title="Hapus User"><i class="fas fa-trash"></i></button>
                            </form>';
                }

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['avatar', 'name', 'email', 'roles', 'isactive', 'last_login_at', 'action'])
            ->make(true);
    }

    public function sync(UserSyncService $syncer)
    {
        $this->guard('create', 'system:user');

        try {
            $homebaseUrl = config('app.tsu_homebase.url');
            $clientId = config('app.oauth.client.id');
            $clientSecret = config('app.oauth.client.secret');

            if (!$clientId || !$clientSecret) {
                throw new \Exception('[TSU_AUTH_CONFIG] Konfigurasi HOMEBASE_CLIENT_ID / SECRET belum diisi di .env');
            }

            // Ambil Client Access Token
            $tokenResponse = Http::withoutVerifying()
                ->withHeaders(['X-Sync-Secret' => config('app.pikdi.key.sync')])
                ->asForm()->post($homebaseUrl . '/oauth/token', [
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

            // Tarik data user
            $apiUrl = $homebaseUrl . '/api/v1/users/sync';
            $stats = ['processed' => 0, 'updated' => 0, 'uptodate' => 0, 'failed' => 0, 'skipped' => 0];

            User::query()->whereNotNull('email')->each(function ($user) use ($apiUrl, $accessToken, $syncer, &$stats) {
                $email = $user->email;
                try {
                    $response = Http::withoutVerifying()
                        ->withToken($accessToken)
                        ->withHeaders([
                            'Accept'        => 'application/json',
                            'X-Sync-Secret' => config('app.pikdi.key.sync'),
                        ])
                        ->timeout(15)
                        ->post($apiUrl, ['emails' => [$email]]);

                    if ($response->successful()) {
                        $usersData = $response->json()['data'] ?? [];
                        if (empty($usersData)) {
                            $stats['uptodate']++;
                        } else {
                            foreach ($usersData as $userData) {
                                try {
                                    $result = $syncer->handle($userData, null, true);
                                    $stats['processed']++;
                                    if (!empty($result['affected'])) {
                                        $stats['updated']++;
                                    } else {
                                        $stats['uptodate']++;
                                    }
                                } catch (\Exception $e) {
                                    if (str_contains($e->getMessage(), '[TSU_DENIED_ACCESS]')) {
                                        $stats['skipped']++;
                                    } else {
                                        $stats['failed']++;
                                        Log::error("[TSU_USER_FAIL] Gagal proses user: " . ($userData['email'] ?? 'Unknown'), ['msg' => $e->getMessage()]);
                                    }
                                }
                            }
                        }
                    } else {
                        $stats['failed']++;
                        Log::error("[TSU_USER_API_ERR] Gagal sync user {$email}: Status " . $response->status(), ['body' => $response->body()]);
                    }
                } catch (\Exception $e) {
                    $stats['failed']++;
                    Log::error("[TSU_USER_CONN_ERR] Gagal koneksi sync user {$email}: " . $e->getMessage());
                }
            });

            $msg = "<h6 class='font-weight-bold mb-2'>Laporan Sinkronisasi User</h6>";
            $msg .= "<ul class='mb-0 pl-3' style='list-style-type: disc;'>";
            $msg .= "<li>Total user diperiksa: <b>{$stats['processed']}</b></li>";
            if ($stats['updated'] > 0) {
                $msg .= "<li>Data diperbarui: <b>{$stats['updated']}</b> user</li>";
            }
            if ($stats['uptodate'] > 0) {
                $msg .= "<li>Data up to date: {$stats['uptodate']} user</li>";
            }
            if ($stats['skipped'] > 0) {
                $msg .= "<li class='text-warning font-weight-bold'>Dilewati: {$stats['skipped']} user</li>";
            }
            if ($stats['failed'] > 0) {
                $msg .= "<li class='text-danger font-weight-bold'>Gagal diproses: {$stats['failed']} user</li>";
            }
            $msg .= "</ul>";

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            $defaultError = 'Terjadi kesalahan sistem saat sinkronisasi user.';
            if ($e instanceof ConnectionException) {
                $defaultError = 'Gagal menghubungi Server Homebase. Cek koneksi internet.';
            }
            return TsuErrorHandlerService::handleHtml($e, '[TSU_SYS_CRITICAL]', $defaultError, 'Gagal Sync User.');
        }
    }

    public function edit($id)
    {
        $this->guard('edit', 'system:user');

        $user = User::with('roles')->findOrFail($id);

        $allRoles = Role::orderBy('is_identity', 'desc')->orderBy('name', 'asc')->get();
        $userRoles = $user->roles()->pluck('name')->toArray();

        return view('admin::system.user.edit_modal', compact('user', 'allRoles', 'userRoles'));
    }

    public function update(Request $request, $id)
    {
        $this->guard('edit', 'system:user');

        $request->validate([
            'roles' => 'nullable|array',
        ]);

        try {
            $user = User::findOrFail($id);

            // Protect Role Global jika ada aturan khusus, atau sync seluruh role yang dipilih
            $submittedRoles = $request->roles ?? [];
            $user->syncRoles($submittedRoles);

            return redirect()->back()->with('success', 'Role untuk user ' . ($user->name ?? $user->username) . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_UPD_FAIL]', 'Gagal menyimpan perubahan role user.', "Gagal Update User ID: $id", $request);
        }
    }

    public function destroy($id)
    {
        $this->guard('delete', 'system:user');

        try {
            $user = User::findOrFail($id);

            if (auth()->id() == $id) {
                return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
            }

            $user->delete();
            return back()->with('success', 'User berhasil dikeluarkan dari modul ini!');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_USER_DELETE_FAIL]', 'Gagal menghapus user.', "Gagal Hapus User ID: $id.");
        }
    }
}

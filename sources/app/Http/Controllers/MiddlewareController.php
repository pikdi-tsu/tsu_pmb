<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class MiddlewareController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Fungsi Middleware permission di controller
     * @param string $prefix (Contoh: 'system:user', 'system:role')
     */
    protected function registerPermissions(string $prefix): void
    {
        // Gembok View (Index, Show)
        $this->middleware("permission:{$prefix}:view")->only(['index', 'show']);

        // Gembok Create (Create, Store)
        $this->middleware("permission:{$prefix}:create")->only(['create', 'store']);

        // Gembok Edit (Edit, Update)
        $this->middleware("permission:{$prefix}:edit")->only(['edit', 'update']);

        // Gembok Delete (Destroy)
        $this->middleware("permission:{$prefix}:delete")->only(['destroy']);
    }

    /**
     * Helper action buttons untuk DataTables
     */
    protected function getActionButtons(object $row, string $permissionKey, array $options = [], string $editClass = 'btn-edit', string $deleteClass = 'btn-delete')
    {
        $defaultOptions = [
            'edit_class'   => 'btn-edit',
            'delete_class' => 'btn-delete',
            'edit_url'     => null,
            'delete_url'   => null,
            'can_edit'     => null,
            'can_delete'   => null,
            'use_modal'    => true,
        ];

        $opt = array_merge($defaultOptions, $options);

        $user = auth()->user();
        $isSuperAdmin = $user && ($user->hasRole(['super admin', 'super admin pmb']) || session('namagroup') == 'Super Admin');

        $canEdit   = is_null($opt['can_edit'])
            ? ($isSuperAdmin || ($user && $user->can($permissionKey . ':edit')))
            : $opt['can_edit'];
        $canDelete = is_null($opt['can_delete'])
            ? ($isSuperAdmin || ($user && $user->can($permissionKey . ':delete')))
            : $opt['can_delete'];

        if (!$canEdit && !$canDelete) {
            return '<div class="text-center">
                    <span class="badge badge-secondary p-1 shadow-sm" style="cursor: not-allowed; opacity:0.7" title="Akses Dibatasi">
                        <i class="fas fa-lock mr-1"></i> Locked
                    </span>
                </div>';
        }

        $btn = '<div class="text-center" style="white-space:nowrap">';

        // Edit
        if ($canEdit) {
            $url = $opt['edit_url'] ?? '#';

            if ($opt['use_modal'] === false) {
                $btn .= '<a href="' . $url . '" class="btn btn-warning btn-sm mr-1" title="Edit Data">
                        <i class="fas fa-pencil-alt"></i>
                     </a>';
            } else {
                $btn .= '<button type="button"
                            data-id="' . $row->id . '"
                            data-name="' . ($row->name ?? '') . '"
                            data-url="' . $url . '"
                            class="btn btn-warning btn-sm ' . $opt['edit_class'] . ' mr-1"
                            title="Edit Data">
                        <i class="fas fa-pencil-alt"></i>
                     </button>';
            }
        } else {
            $btn .= '<button type="button" class="btn btn-secondary btn-sm mr-1" disabled style="opacity:0.6"><i class="fas fa-lock"></i></button>';
        }

        // Delete
        if ($canDelete) {
            $actionUrl = $opt['delete_url'] ?? '#';
            $btn .= '<form action="' . $actionUrl . '" method="POST" style="display:inline;" class="form-delete">
                    ' . csrf_field() . ' ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm ' . $opt['delete_class'] . '" title="Hapus"><i class="fas fa-trash"></i></button>
                </form>';
        } else {
            $btn .= '<button type="button" class="btn btn-secondary btn-sm" disabled style="opacity:0.6"><i class="fas fa-lock"></i></button>';
        }

        $btn .= '</div>';

        return $btn;
    }

    /**
     * Cek permission manual
     */
    protected function guard($action, $permissionKey)
    {
        $user = auth()->user();
        if ($user && ($user->hasRole(['super admin', 'super admin pmb']) || session('namagroup') == 'Super Admin')) {
            return;
        }

        $permission = $permissionKey . ':' . $action;

        if (!$user || !$user->can($permission)) {
            $actualAction = ucfirst($action);
            abort(403, 'Akses Dibatasi! Anda tidak memiliki izin: ' . $actualAction);
        }
    }

    /**
     * Cek permission khusus store/save
     */
    protected function guardStore($id, $permissionKey)
    {
        $action = $id ? 'edit' : 'create';
        $this->guard($action, $permissionKey);
    }
}

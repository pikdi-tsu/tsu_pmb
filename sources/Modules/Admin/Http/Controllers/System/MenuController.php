<?php

namespace Modules\Admin\Http\Controllers\System;

use App\Http\Controllers\MiddlewareController;
use App\Services\TsuErrorHandlerService;
use Illuminate\Http\Request;
use Modules\Admin\Models\MenuSidebar;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends MiddlewareController
{
    public function __construct()
    {
        $this->registerPermissions('system:menu');
    }

    public function index()
    {
        $permissions = Permission::query()->orderBy('name')->pluck('name', 'name');
        $parents = $this->getHierarchicalParents();

        $stats = [
            'total'  => MenuSidebar::count(),
            'root'   => MenuSidebar::whereNull('parent_id')->count(),
            'sub'    => MenuSidebar::whereNotNull('parent_id')->count(),
            'active' => MenuSidebar::where('isactive', 1)->count(),
        ];

        $menuIcon = MenuSidebar::where('route', 'admin.system.menus.index')->value('icon') ?? 'fas fa-bars';

        return view('admin::system.menu.index', [
            'title'       => 'Manajemen Menu Sidebar',
            'menu'        => 'Menus Management',
            'menuIcon'    => $menuIcon,
            'stats'       => $stats,
            'permissions' => $permissions,
            'parents'     => $parents,
        ]);
    }

    private function getHierarchicalParents($ignoreId = null)
    {
        $nodes = MenuSidebar::query()->whereNull('parent_id')->orderBy('order')->with('children')->get();
        $options = [];

        foreach ($nodes as $node) {
            if ($node->id === $ignoreId) {
                continue;
            }

            $options[$node->id] = $node->name;
            $this->recurseChildren($node, $options, $ignoreId, 1);
        }

        return $options;
    }

    private function recurseChildren($parent, &$options, $ignoreId, $depth)
    {
        foreach ($parent->children as $child) {
            if ($child->id === $ignoreId) {
                continue;
            }

            $prefix = str_repeat('— ', $depth);
            $options[$child->id] = $prefix . $child->name;

            if ($child->children && $child->children->count()) {
                $this->recurseChildren($child, $options, $ignoreId, $depth + 1);
            }
        }
    }

    public function datatable()
    {
        $this->guard('view', 'system:menu');

        $allMenus = MenuSidebar::query()->orderBy('order')->get();
        $sorted = $this->sortTree($allMenus);

        return DataTables::of($sorted)
            ->editColumn('name', function ($row) {
                $indent = $row->depth * 25;
                $isRoot = $row->depth === 0;

                $branchIcon = '';
                if ($row->depth > 0) {
                    $branchIcon = '<i class="fas fa-level-up-alt fa-rotate-90 mr-2" style="color: #94a3b8; font-size: 0.75rem;"></i>';
                }

                $fontStyle = $isRoot ? 'font-weight: 700; color: #0f172a; font-size: 0.88rem;' : 'font-weight: 500; color: #334155; font-size: 0.84rem;';
                $badgeType = $isRoot
                    ? '<span class="badge badge-light border text-muted ml-2 font-weight-normal" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">MAIN</span>'
                    : '<span class="badge badge-light border text-muted ml-2 font-weight-normal" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">SUB L' . $row->depth . '</span>';

                return '<div class="d-flex align-items-center" style="padding-left: ' . $indent . 'px;">'
                    . $branchIcon .
                    '<span style="' . $fontStyle . '">' . e($row->name) . '</span>'
                    . $badgeType .
                    '</div>';
            })
            ->editColumn('icon', function ($row) {
                if (!$row->icon) {
                    return '<span class="text-muted" style="font-size: 0.75rem;">-</span>';
                }
                return '<div class="d-flex align-items-center"><i class="' . e($row->icon) . ' mr-2" style="font-size: 0.95rem; color: var(--tsu-primary, #094b54); width: 16px;"></i><code style="font-size: 0.75rem; background: #f8fafc; padding: 0.15rem 0.35rem; border: 1px solid #e2e8f0; border-radius: 4px; color: #475569;">' . e($row->icon) . '</code></div>';
            })
            ->editColumn('route', function ($row) {
                if (!$row->route || $row->route === '#') {
                    return '<span class="badge" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-size: 0.72rem; padding: 0.2rem 0.5rem; border-radius: 4px;"><i class="fas fa-folder mr-1"></i> Dropdown (No URL)</span>';
                }
                return '<code style="font-size: 0.8rem; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 0.2rem 0.45rem; border-radius: 4px;"><i class="fas fa-link mr-1"></i>' . e($row->route) . '</code>';
            })
            ->addColumn('permission', function ($row) {
                if ($row->permission_name) {
                    return '<span class="badge" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 0.73rem; padding: 0.25rem 0.5rem; border-radius: 5px;"><i class="fas fa-shield-alt mr-1"></i>' . e($row->permission_name) . '</span>';
                }
                return '<span class="badge" style="background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; font-size: 0.7rem; padding: 0.2rem 0.4rem; border-radius: 4px;"><i class="fas fa-globe mr-1"></i> Public</span>';
            })
            ->editColumn('status', function ($row) {
                return $row->isactive
                    ? '<span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.75rem; padding: 0.25rem 0.55rem; border-radius: 5px;"><i class="fas fa-check-circle mr-1"></i> Aktif</span>'
                    : '<span class="badge" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-size: 0.75rem; padding: 0.25rem 0.55rem; border-radius: 5px;"><i class="fas fa-times-circle mr-1"></i> Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $user = auth()->user();
                $isSuperAdmin = $user && ($user->hasRole(['super admin', 'super admin pmb']) || session('namagroup') == 'Super Admin');
                $canEdit = $isSuperAdmin || ($user && $user->can('system:menu:edit'));
                $canDelete = $isSuperAdmin || ($user && $user->can('system:menu:delete'));

                $isSystemCore = str_starts_with($row->route ?? '', 'admin.system.');

                $btn = '<div class="d-flex align-items-center justify-content-center" style="gap: 4px;">';

                if ($canEdit) {
                    $btn .= '<a href="' . route('admin.system.menus.edit', $row->id) . '"
                                class="btn btn-sm btn-edit-menu"
                                style="background: #fefce8; color: #a16207; border: 1px solid #fef08a; border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem;"
                                title="Edit Menu">
                                <i class="fas fa-pen"></i>
                            </a>';
                }

                if ($canDelete) {
                    if ($isSystemCore) {
                        $btn .= '<button class="btn btn-sm" disabled style="background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem; cursor: not-allowed;" title="Menu Inti Sistem (Terkunci)"><i class="fas fa-lock"></i></button>';
                    } else {
                        $btn .= '<form action="' . route('admin.system.menus.destroy', $row->id) . '" method="POST" style="display:inline;" class="form-delete">
                                    ' . csrf_field() . ' ' . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-delete" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem;" title="Hapus Menu"><i class="fas fa-trash"></i></button>
                                </form>';
                    }
                }

                $btn .= '</div>';
                return $btn;
            })
            ->setRowClass(function ($d) {
                return $d->depth === 0 ? 'tsu-row-root font-weight-bold' : '';
            })
            ->rawColumns(['name', 'icon', 'route', 'permission', 'status', 'action'])
            ->make(true);
    }

    private function sortTree($menus, $parentId = null, $depth = 0)
    {
        $result = collect([]);
        $children = $menus->where('parent_id', $parentId)->sortBy('order');

        foreach ($children as $child) {
            $child->setAttribute('depth', $depth);
            $result->push($child);
            $result = $result->merge($this->sortTree($menus, $child->id, $depth + 1));
        }

        return $result;
    }

    public function store(Request $request)
    {
        $this->guardStore($request->id, 'system:menu');

        $request->validate([
            'name'  => 'required|string|max:100',
            'order' => 'required|integer',
        ]);

        try {
            MenuSidebar::create([
                'name'            => trim($request->name),
                'icon'            => $request->icon ?: 'fas fa-box',
                'type'            => $request->type ?: 'item',
                'route'           => $request->route ?: '#',
                'permission_name' => $request->permission_name ?: null,
                'parent_id'       => $request->parent_id ?: null,
                'order'           => $request->order ?? 0,
                'isactive'        => $request->has('isactive') ? 1 : 0,
            ]);

            return back()->with('success', 'Menu baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_MENU_STORE_FAIL]', 'Gagal menyimpan menu baru.', 'Gagal Create Menu.', $request);
        }
    }

    public function edit($id)
    {
        $this->guard('edit', 'system:menu');

        $menu = MenuSidebar::findOrFail($id);
        $permissions = Permission::query()->orderBy('name')->pluck('name', 'name');
        $parents = $this->getHierarchicalParents($id);

        return view('admin::system.menu.edit_modal', compact('menu', 'permissions', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $this->guard('edit', 'system:menu');

        $menu = MenuSidebar::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:100',
            'order' => 'required|integer',
        ]);

        try {
            $menu->update([
                'name'            => trim($request->name),
                'icon'            => $request->icon ?: 'fas fa-box',
                'type'            => $request->type ?: 'item',
                'route'           => $request->route ?: '#',
                'permission_name' => $request->permission_name ?: null,
                'parent_id'       => $request->parent_id ?: null,
                'order'           => $request->order ?? 0,
                'isactive'        => $request->has('isactive') ? 1 : 0,
            ]);

            return back()->with('success', 'Menu berhasil diperbarui!');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_MENU_UPDATE_FAIL]', 'Gagal memperbarui menu.', "Gagal Update Menu ID: $id.", $request);
        }
    }

    public function destroy($id)
    {
        $this->guard('delete', 'system:menu');

        try {
            $menu = MenuSidebar::withCount('children')->findOrFail($id);

            if ($menu->children_count > 0) {
                return redirect()->back()->with('error', '<b>Gagal Menghapus!</b> Menu ini masih memiliki ' . $menu->children_count . ' sub-menu.');
            }

            $menu->delete();
            return redirect()->route('admin.system.menus.index')->with('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            return TsuErrorHandlerService::handleHtml($e, '[TSU_MENU_DELETE_FAIL]', 'Gagal menghapus menu.', "Gagal Hapus Menu ID: $id.");
        }
    }
}

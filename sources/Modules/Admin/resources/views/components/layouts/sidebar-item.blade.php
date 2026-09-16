@php
    $user = auth()->user();
    $isSuperAdmin = $user && ($user->hasRole(['super admin', 'super admin pmb', 'admin', 'admin pmb']) || session('namagroup') == 'Super Admin');

    // Cek Permission
    if (!$isSuperAdmin && $menu->permission_name && (!$user || !$user->can($menu->permission_name))) {
        return;
    }

    // Filter Children yang diizinkan
    $visibleChildren = $menu->children->filter(function ($child) use ($user, $isSuperAdmin) {
        if ($isSuperAdmin) {
            return true;
        }
        return empty($child->permission_name) || ($user && $user->can($child->permission_name));
    });

    $hasChildren = $visibleChildren->isNotEmpty();
    $isFolder = empty($menu->route) || $menu->route === '#';

    if ($isFolder && !$hasChildren) {
        return;
    }

    $isActive = $menu->isActive();
    $currentLevel = isset($level) ? $level : 0;
    $mainIcon = $menu->icon ?: ($currentLevel > 0 ? 'far fa-circle' : 'fas fa-box');

    $href = '#';
    if (!$hasChildren && !empty($menu->route) && $menu->route !== '#') {
        $href = \Illuminate\Support\Facades\Route::has($menu->route) ? route($menu->route) : url($menu->route);
    }
@endphp

<li class="nav-item {{ $hasChildren && $isActive ? 'menu-open' : '' }}">
    <a href="{{ $href }}"
       class="nav-link {{ $isActive ? 'active' : '' }}"
       style="{{ $currentLevel > 0 ? 'padding-left: ' . ($currentLevel * 1.25 + 0.75) . 'rem;' : '' }}">
        <i class="nav-icon {{ $mainIcon }}"></i>
        <p>
            {{ $menu->name }}
            @if($hasChildren)
                <i class="right fas fa-angle-left"></i>
            @endif
        </p>
    </a>

    @if($hasChildren)
        <ul class="nav nav-treeview" style="{{ $isActive ? 'display: block;' : '' }}">
            @foreach ($visibleChildren as $child)
                @include('admin::components.layouts.sidebar-item', ['menu' => $child, 'level' => $currentLevel + 1])
            @endforeach
        </ul>
    @endif
</li>

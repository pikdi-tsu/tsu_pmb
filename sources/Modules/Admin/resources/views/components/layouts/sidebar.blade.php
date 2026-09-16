@foreach ($menus as $menu)
    @include('admin::components.layouts.sidebar-item', ['menu' => $menu, 'level' => 0])
@endforeach

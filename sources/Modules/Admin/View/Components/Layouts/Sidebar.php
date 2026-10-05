<?php

namespace Modules\Admin\View\Components\Layouts;

use Illuminate\View\Component;
use Modules\Admin\Models\MenuSidebar;

class Sidebar extends Component
{
    public $menus;

    public function __construct()
    {
        $this->menus = MenuSidebar::query()->whereNull('parent_id')
            ->where('isactive', 1)
            ->with(['children' => function ($query) {
                $query->where('isactive', 1)->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();
    }

    public function render()
    {
        return view('admin::components.layouts.sidebar');
    }
}

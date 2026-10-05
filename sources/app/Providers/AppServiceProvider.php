<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $superAdminModule = 'super admin ' . config('app.module.name', 'pmb');
        Gate::before(static function ($user, $ability) use ($superAdminModule) {
            return ($user->hasRole(['super admin', $superAdminModule, 'admin pmb', 'admin']) || session('namagroup') === 'Super Admin') ? true : null;
        });
    }
}

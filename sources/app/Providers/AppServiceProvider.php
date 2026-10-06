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
            // Hanya super admin yang melewati semua permission. Role lain (termasuk admin pmb)
            // mengikuti permission yang dicentang di menu Role.
            return ($user->hasRole(['super admin', $superAdminModule]) || session('namagroup') === 'Super Admin') ? true : null;
        });
    }
}

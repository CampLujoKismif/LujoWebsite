<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\CampInstance;
use Illuminate\Support\Facades\Blade;

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
        // Role directives
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole($role)): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('anyrole', function ($roles) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole($roles)): ?>";
        });

        Blade::directive('endanyrole', function () {
            return "<?php endif; ?>";
        });

        // Permission directives
        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission($permission)): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('anypermission', function ($permissions) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyPermission($permissions)): ?>";
        });

        Blade::directive('endanypermission', function () {
            return "<?php endif; ?>";
        });

        // Admin directive
        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->isAdmin()): ?>";
        });

        Blade::directive('endadmin', function () {
            return "<?php endif; ?>";
        });

        // Camp access directives
        Blade::directive('campaccess', function ($campId) {
            return "<?php if(auth()->check() && auth()->user()->canAccessCampData($campId)): ?>";
        });

        Blade::directive('endcampaccess', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('campPermission', function ($campId, $permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermissionInCamp($permission, $campId)): ?>";
        });

        Blade::directive('endcampPermission', function () {
            return "<?php endif; ?>";
        });

        // Share active camp sessions with the public layout
        View::composer('components.layouts.public', function ($view) {
            $activeCampSessions = CampInstance::with('camp')
                ->where('is_active', true)
                ->orderBy('start_date')
                ->get();

            $view->with('activeCampSessions', $activeCampSessions);
        });
    }
}

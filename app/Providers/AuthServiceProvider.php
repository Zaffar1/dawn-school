<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 1. Grant Super Admin full access bypass
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });

        // Define manage-hostel explicitly
        Gate::define('manage-hostel', function ($user) {
            return $user->hasRole('super-admin');
        });

        // 2. Dynamically define gates based on permissions table
        // We wrap this in a schema check to prevent migration errors during fresh setup
        try {
            if (app()->runningInConsole() || Schema::hasTable('permissions')) {
                $permissions = Permission::all();
                foreach ($permissions as $permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasPermission($permission->slug);
                    });
                }
            }
        } catch (\Exception $e) {
            // Silence exceptions during database setup/migrations
        }
    }
}

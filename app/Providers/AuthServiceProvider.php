<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('user-permission-province', function ($user) {
            if ($user->role == config('constants.ROLES.GENERAL')) return true;
        });

        Gate::define('user-permission-province-district', function ($user) {
            if ($user->role == config('constants.ROLES.GENERAL') || $user->role == config('constants.ROLES.PROVINCE')) return true;
        });

        Gate::define('user-permission-province-district-ward', function ($user) {
            if ($user->role == config('constants.ROLES.GENERAL')
                || $user->role == config('constants.ROLES.PROVINCE')
                || $user->role == config('constants.ROLES.DISTRICT')) return true;
        });

        Gate::define('user-permission-province-district-ward-hamlet', function ($user) {
            if ($user->role == config('constants.ROLES.GENERAL')
                || $user->role == config('constants.ROLES.PROVINCE')
                || $user->role == config('constants.ROLES.DISTRICT')
                || $user->role == config('constants.ROLES.WARD')) return true;
        });

        Gate::define('user-permission-district', function ($user) {
            if ($user->role == config('constants.ROLES.PROVINCE')) return true;
        });

        Gate::define('user-permission-ward', function ($user) {
            if ($user->role == config('constants.ROLES.DISTRICT')) return true;
        });

        Gate::define('user-permission-hamlet', function ($user) {
            if ($user->role == config('constants.ROLES.WARD')) return true;
        });

        Gate::define('permission-manage-user', function ($user) {
            if ($user->role != config('constants.ROLES.HAMLET')) return true;
        });

        Gate::define('create-citizen', function ($user) {
            if ($user->role == config('constants.ROLES.HAMLET')) return true;
        });
    }
}

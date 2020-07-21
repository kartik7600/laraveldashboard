<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // admin authenticat gate
        Gate::define('admin-auth', function ($user) {
            if( $user->user_role == 'admin')
            {
                return true;
            }

            return false;
        });

        // manager authenticate gate
        Gate::define('manager-auth', function ($user) {
            if( $user->user_role == 'account_manager')
            {
                return true;
            }

            return false;
        });

        // client authenticate gate
        Gate::define('client-auth', function ($user) {
            if( $user->user_role == 'client')
            {
                return true;
            }

            return false;
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('Admin', function($user){
            return $user->role == 'Admin';
        });

         Gate::define('User', function($user){
            return $user->role == 'user';
        });

         Gate::define('Petugas', function($user){
            return $user->role == 'petugas';
        });
    }
}

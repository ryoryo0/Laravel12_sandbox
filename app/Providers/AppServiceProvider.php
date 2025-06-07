<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
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
        if (Request::is('admin/*')) {
            Config::set('session.cookie', 'admin_session');
        } elseif (Request::is('customer/*')) {
            Config::set('session.cookie', 'customer_session');
        }
    }
}

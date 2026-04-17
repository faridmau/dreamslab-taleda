<?php

namespace App\Providers;

use App\Auth\MagentoUserProvider;
use Illuminate\Support\Facades\Auth;
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
        // Register custom Magento user provider for authentication
        Auth::provider('magento', function ($app, array $config) {
            return new MagentoUserProvider($app['hash'], $config['model']);
        });
    }
}

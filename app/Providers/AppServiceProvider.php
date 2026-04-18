<?php

namespace App\Providers;

use App\Auth\MagentoUserProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
            fn (): View => view('partials.custom-login-section'),
        );
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

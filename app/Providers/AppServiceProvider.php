<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

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
        // Register custom OIDC Socialite Provider (ODPC10 IDP)
        if (class_exists('Laravel\Socialite\Facades\Socialite')) {
            $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
            $socialite->extend(
                'oidc',
                function ($app) use ($socialite) {
                    $config = $app['config']['services.oidc'];
                    return $socialite->buildProvider(\App\Providers\OidcProvider::class, $config);
                }
            );
        }
    }
}

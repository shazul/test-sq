<?php

namespace Pimeo\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Pimeo\Services\Navigator;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'navigator',
            function () {
                return new Navigator(app(Router::class));
            }
        );
    }
}

<?php

namespace Pimeo\Providers;

use Illuminate\Support\ServiceProvider;
use SevenShores\Hubspot\Factory;

class HubSpotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Factory::class, function () {
            return Factory::create(config('services.hubspot.api_key'));
        });
    }
}

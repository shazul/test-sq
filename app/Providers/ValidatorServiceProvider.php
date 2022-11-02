<?php

namespace Pimeo\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $validators = config('validator.validators', []);
        $validatorsPath = config('validator.path');

        foreach ($validators as $name => $validator) {
            Validator::extend($name, $validatorsPath . $validator . '@validate');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}

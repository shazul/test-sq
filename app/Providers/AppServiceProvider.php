<?php

namespace Pimeo\Providers;

use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laracasts\Generators\GeneratorsServiceProvider;
use Pimeo\Models\Language;
use Validator;
use VTalbot\RepositoryGenerator\RepositoryGeneratorServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('languages_contains_english', function ($attribute, $value) {
            $englishID = Language::whereCode('en')->first()->id;
            return collect($value)->contains($englishID);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(GeneratorsServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(RepositoryGeneratorServiceProvider::class);
            $this->app->register(DebugbarServiceProvider::class);
        }
    }
}

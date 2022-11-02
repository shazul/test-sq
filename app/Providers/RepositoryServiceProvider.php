<?php

namespace Pimeo\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $repositories = config('repository.repositories', []);

        foreach ($repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }
}

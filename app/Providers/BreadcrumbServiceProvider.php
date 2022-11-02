<?php

namespace Pimeo\Providers;

use Illuminate\Support\ServiceProvider;
use Pimeo\Services\Breadcrumb\Breadcrumb;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            Breadcrumb::class,
            function () {
                $title = trans(config('breadcrumb.home_crumb.title'));
                $link = config('breadcrumb.home_crumb.link');
                $icon = config('breadcrumb.home_crumb.icon', null);

                return (new Breadcrumb())->add($title, $link, $icon);
            }
        );
    }
}

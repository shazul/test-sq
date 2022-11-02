<?php

namespace Pimeo\Providers;

use Illuminate\Support\ServiceProvider;
use Pimeo\Http\ViewComposers\BreadcrumbComposer;
use Pimeo\Http\ViewComposers\CompanyComposer;
use Pimeo\Http\ViewComposers\LanguageComposer;
use Pimeo\Http\ViewComposers\UnitFieldComposer;
use Pimeo\Http\ViewComposers\UserComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers
        view()->composer(['partials.menu-useraccount', 'partials.sidebar'], UserComposer::class);

        view()->composer(['partials.mainheader', 'pim.*'], LanguageComposer::class);
        view()->composer(['partials.mainheader', 'pim.*'], CompanyComposer::class);

        view()->composer('partials.breadcrumb', BreadcrumbComposer::class);
        view()->composer('pim.attributes._form', UnitFieldComposer::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

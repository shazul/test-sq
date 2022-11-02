<?php

namespace Pimeo\Providers;

use Collective\Html\HtmlBuilder;
use Illuminate\Support\ServiceProvider;

class HtmlMacrosServiceProvider extends ServiceProvider
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
        HtmlBuilder::macro('delete', function (
            $route,
            $parameters,
            $label,
            array $labelParameters = [],
            $message,
            array $messageParameters = []
        ) {
            return '<a href="' . route($route, $parameters) . '"
                       class="btn btn-danger btn-delete pull-right"
                       data-body="' . e(trans($message, $messageParameters)) . '">
                    <i class="fa fa-fw fa-trash"></i>
                        ' . trans($label, $labelParameters) . '
                    </a>';
        });
    }
}

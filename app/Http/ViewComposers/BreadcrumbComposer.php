<?php

namespace Pimeo\Http\ViewComposers;

use Illuminate\View\View;
use Pimeo\Services\Breadcrumb\Breadcrumb;

class BreadcrumbComposer
{
    /**
     * The breadcrumb instance.
     *
     * @var Breadcrumb
     */
    protected $breadcrumb;

    /**
     * Create a new breadcrumb composer.
     *
     * @param Breadcrumb $breadcrumb
     */
    public function __construct(Breadcrumb $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('crumbs', $this->breadcrumb->getCrumbs());
    }
}

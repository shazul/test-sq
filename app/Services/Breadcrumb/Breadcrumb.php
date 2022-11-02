<?php

namespace Pimeo\Services\Breadcrumb;

use Illuminate\Support\Collection;

class Breadcrumb
{
    /**
     * The crumbs bag.
     *
     * @var Collection
     */
    protected $crumbs;

    /**
     * Create a new instance of the breadcrumb.
     */
    public function __construct()
    {
        $this->crumbs = collect();
    }

    /**
     * Add a new crumb to the bag.
     *
     * @param  string       $title
     * @param  string|array $link
     * @param  string       $icon
     * @return $this
     */
    public function add($title, $link = null, $icon = null)
    {
        $crumb = $this->createCrumb();

        $crumb->fill(compact('title', 'link', 'icon'));

        $this->crumbs->push($crumb);

        return $this;
    }

    /**
     * Get the crumbs bag.
     *
     * @return Collection
     */
    public function getCrumbs()
    {
        return $this->crumbs;
    }

    /**
     * Create a new crumb.
     *
     * @return Crumb
     */
    protected function createCrumb()
    {
        return app(Crumb::class);
    }
}

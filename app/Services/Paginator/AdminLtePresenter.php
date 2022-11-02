<?php

namespace Pimeo\Services\Paginator;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\BootstrapFourPresenter;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Support\HtmlString;

class AdminLtePresenter extends BootstrapFourPresenter
{
    /**
     * Classes of the pagination element.
     *
     * @var array
     */
    protected $classNames = ['pagination'];

    /**
     * Create a new Bootstrap presenter instance.
     *
     * @param  \Illuminate\Contracts\Pagination\Paginator $paginator
     * @param  array                                      $classNames
     * @param  \Illuminate\Pagination\UrlWindow|null      $window
     */
    public function __construct(PaginatorContract $paginator, array $classNames = [], UrlWindow $window = null)
    {
        parent::__construct($paginator, $window);

        $this->classNames = array_unique(array_merge($this->classNames, $classNames));
    }

    /**
     * Render the given paginator.
     *
     * @return \Illuminate\Contracts\Support\Htmlable|string
     */
    public function render()
    {
        if ($this->hasPages()) {
            return new HtmlString(
                sprintf(
                    '<ul class="%s">%s %s %s</ul>',
                    join(' ', $this->classNames),
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton()
                )
            );
        }

        return '';
    }
}

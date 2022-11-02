<?php

namespace Pimeo\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class Navigator extends Collection
{
    public $route;

    public $parameters;

    public $icon;

    public $text;

    /**
     * @var bool
     */
    public $header;

    /**
     * @var array
     */
    public $includeRoutes;

    /**
     * @var array
     */
    public $namedParameters;

    /**
     * @var string[]
     */
    public $blacklistedAccess = [];

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Navigator
     */
    protected $lastItem;

    public function __construct(
        Router $router,
        $route = null,
        $text = null,
        $icon = null,
        $header = false
    ) {
        $this->router = $router;
        $this->parseRoute($route);
        $this->text = $text;
        $this->icon = $icon;
        $this->header = $header;

        parent::__construct();
    }

    protected function isCurrent()
    {
        if ($this->includeRoutes) {
            foreach ($this->includeRoutes as $route) {
                if ($this->router->currentRouteNamed($route)) {
                    return true;
                }
            }
        }

        return $this->router->currentRouteNamed($this->route);
    }

    protected function isSameParameters()
    {
        if ($this->parameters) {
            $namedParameters = $this->router->current()->parameters();

            foreach ($namedParameters as $key => $parameter) {
                if (isset($this->namedParameters[$key]) &&
                    ($this->namedParameters[$key] == '*' || (is_callable($this->namedParameters[$key]) &&
                    $this->namedParameters[$key]($parameter, $this->parameters)
                    ))) {
                        unset($namedParameters[$key]);
                }
            }

            $parameters = array_values($namedParameters);

            foreach ($parameters as $key => $parameter) {
                if ($parameter instanceof Model) {
                    if ($parameter->id != $this->parameters[$key]) {
                        return false;
                    }
                } elseif (isset($this->parameters[$key]) && $parameter != $this->parameters[$key]) {
                    return false;
                }
            }
        }

        return true;
    }

    public function isActive()
    {
        if ($this->isCurrent() && $this->isSameParameters()) {
            return true;
        }

        foreach ($this->items as $item) {
            if ($item->isActive()) {
                return true;
            }
        }

        return false;
    }

    public function add($route, $text, $icon = 'circle-o', callable $callback = null)
    {
        $blacklisted = $this->checkIfAccessIsBlacklisted($text);

        if (!$blacklisted) {
            $item = new self($this->router, $route, $text, $icon);

            if (!is_null($callback)) {
                $callback($item);
            }

            $this->push($item);

            $this->lastItem = $item;
        }

        return $this;
    }

    public function header($text)
    {
        $item = new self($this->router, $this->route, $text, '', true);

        $this->push($item);

        return $this;
    }

    protected function parseRoute($route)
    {
        if (is_string($route)) {
            $this->route = $route;
        }

        if (is_array($route)) {
            $this->route = array_shift($route);
            $this->parameters = $route;
        }
    }

    public function allIncludes(array $routes)
    {
        foreach ($this->items as $item) {
            $item->includeRoutes = $routes;
        }

        return $this;
    }

    public function allWithParameters(array $parameters)
    {
        foreach ($this->items as $item) {
            $item->namedParameters = $parameters;
        }

        return $this;
    }

    public function includes(array $routes)
    {
        $this->lastItem->includeRoutes = $routes;

        return $this;
    }

    public function withParameters(array $parameters)
    {
        $this->lastItem->namedParameters = $parameters;

        return $this;
    }

    public function setBlacklistedAccess($blacklistMenuAccess)
    {
        $this->blacklistedAccess = $blacklistMenuAccess;

        return  $this;
    }

    /**
     * The text received in this function is a translation keys.
     * Based on translation key, we can easily guess which model is blacklisted.
     *
     * @param String $text
     * @return bool
     */
    private function checkIfAccessIsBlacklisted($text)
    {
        $routeType = last(explode('.', $text));

        return in_array($routeType, $this->blacklistedAccess);
    }
}

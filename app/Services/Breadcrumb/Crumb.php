<?php

namespace Pimeo\Services\Breadcrumb;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class Crumb
 *
 * @property bool         $current
 * @property string|array $link
 * @property string       $title
 * @property string       $icon
 */
class Crumb
{
    /**
     * The crumb's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The Http Request instance.
     *
     * @var Request
     */
    protected $request;

    /**
     * Create a new instance of the crumb,
     *
     * @param Request $request
     * @param array   $attributes
     */
    public function __construct(Request $request, array $attributes = [])
    {
        $this->request = $request;
        $this->fill($attributes);
    }

    /**
     * Get if the crumb link match the current route.
     *
     * @return bool
     */
    protected function getCurrentAttribute()
    {
        if (isset($this->attributes['current'])) {
            return $this->attributes['current'];
        }

        $parameters = array_merge((array)$this->attributes['link'], []);
        $routeName = array_shift($parameters);

        $this->attributes['current'] = $current = $this->request->route()->getName() == $routeName;

        return $current;
    }

    /**
     * Get the route of the crumb.
     *
     * @return string
     */
    protected function getLinkAttribute()
    {
        if (is_array($this->attributes['link'])) {
            $parameters = $this->attributes['link'];
            $routeName = array_shift($parameters);

            return route($routeName, $parameters);
        }

        return route($this->attributes['link']);
    }

    /**
     * Get an attribute of the crumb.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        $method = 'get' . Str::studly($key) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return array_get($this->attributes, $key, null);
    }

    /**
     * Set an attribute with the given value.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $method = 'set' . Str::studly($key) . 'Attribute';
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }

        array_set($this->attributes, $key, $value);
    }

    /**
     * Fill the crumb with the given attributes.
     *
     * @param array $attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $this->set($attribute, $value);
        }
    }

    /**
     * Get an attribute of the crumb.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Set an attribute with the given value.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
}

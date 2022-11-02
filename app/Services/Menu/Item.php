<?php

namespace Pimeo\Services\Menu;

use Illuminate\Support\Str;
use Lavary\Menu\Item as BaseItem;
use Lavary\Menu\Link;

class Item extends BaseItem
{
    /**
     * Item's icon
     *
     * @var string
     */
    public $icon;

    /**
     * Item's text before title
     *
     * @var string
     */
    public $prependTitle;

    /**
     * Item's text after title
     *
     * @var string
     */
    public $appendTitle;

    /**
     * Creates a new Lavary\Menu\MenuItem instance.
     *
     * @param  string  $nickname
     * @param  string  $title
     * @param  string  $url
     * @param  array  $attributes
     * @param  int  $parent
     * @param  \Pimeo\Services\Menu\Menu  $builder
     * @return void
     */
    public function __construct($builder, $id, $nickname, $title, $options)
    {
        $this->builder     = $builder;
        $this->id          = $id;
        $this->title       = $title;
        $this->nickname    = camel_case(Str::ascii($nickname));
        $this->attributes  = $this->builder->extractAttributes($options);
        $this->parent      = (is_array($options) && isset($options['parent'])) ? $options['parent'] : null;
        
        
        // Storing path options with each link instance.
        if (!is_array($options)) {
            $path = ['url' => $options];
        } elseif (isset($options['raw']) && $options['raw'] == true) {
            $path = null;
        } else {
            $path = array_only($options, ['url', 'route', 'action', 'secure']);
        }

        if (!is_null($path)) {
            $path['prefix'] = $this->builder->getLastGroupPrefix();
        }

        
        $this->link = $path ? new Link($path) : null;
        
        // Activate the item if items's url matches the request uri
        if (true === $this->builder->conf('auto_activate')) {
            $this->checkActivationStatus();
        }
    }

    /**
     * Creates a sub Item
     *
     * @param  string  $nickname
     * @param  string  $title
     * @param  string|array  $options
     * @return void
     */
    public function add($nickname, $title = '', $options = '')
    {
        if (strlen($title) == 0) {
            $title = $nickname;
        }

        if (!is_array($options)) {
            $url = $options;
            $options = [];
            $options['url'] = $url;
        }

        $options['parent'] = $this->id;

        return $this->builder->add($nickname, $title, $options);
    }

    /**
     * Appends text or html to the item
     *
     * @return Lavary\Menu\Item
     */
    public function append($html)
    {
        $this->appendTitle .= $html;

        return $this;
    }

    /**
     * Prepends text or html to the item
     *
     * @return Lavary\Menu\Item
     */
    public function prepend($html)
    {
        $this->prependTitle = $html . $this->prependTitle;

        return $this;
    }

    /**
     * Prepends a Font Awesome icon
     *
     * @param  string $icon
     * @return Lavary\Menu\Item
     */
    public function prependIcon($icon = 'fa-circle-o')
    {
        $attributes['class'] = 'fa ' . $icon;
        $iconBefore = $this->faIcon($attributes);
        $this->icon = $iconBefore;

        return $this;
    }

    /**
     * Appends an open/closed icon
     *
     * @param  string $icon
     */
    public function parentIcon($icon = 'fa fa-angle-left pull-right')
    {
        $attributes['class'] = $icon;
        $parentIcon = $this->faIcon($attributes);
        $this->append($parentIcon);
    }

    /**
     * Creates a Font Awesome Icon
     *
     * @param array $attributes
     * @return string
     */
    public function faIcon($attributes = [])
    {
        $attributes = $this->builder->attributes($attributes);
        $icon = "<i{$attributes}></i>";

        return $icon;
    }
}

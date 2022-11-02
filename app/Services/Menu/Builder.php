<?php

namespace Pimeo\Services\Menu;

use Lavary\Menu\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    /**
     * Adds an item to the menu
     *
     * @param  string  $nickname
     * @param  string  $title
     * @param  string|array  $acion
     * @return Pimeo\Services\Menu\Item $item
     */
    public function add($nickname, $title = '', $options = '')
    {
        if (strlen($title) == 0) {
            $title = $nickname;
        }
    
        $item = new Item($this, $this->id(), $nickname, $title, $options);
                      
        $this->items->push($item);

        // stroing the last inserted item's id
        $this->last_id = $item->id;
        
        return $item;
    }

    /**
     * Add section header content
     *
     * @return Pimeo\Services\Menu\Item
     */
    public function header($nickname, $title, array $options = [])
    {
        $options['raw'] = true;
        $options['class'] = 'header ' . (isset($options['class']) ? $options['class'] : '');

        return $this->add($nickname, $title, $options);
    }
}

<?php

namespace Pimeo\Console\Commands\Exceptions;

use Exception;

class InvalidParentException extends Exception
{
    /**
     * @var string
     */
    private $parent_name;

    public function __construct($message = "", $name = "")
    {
        parent::__construct($message);
        $this->parent_name = $name;
    }

    public function getParentName()
    {
        return $this->parent_name;
    }
}

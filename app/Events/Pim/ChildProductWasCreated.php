<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Models\ChildProduct;

class ChildProductWasCreated extends Event
{
    use SerializesModels;

    public $childProduct;

    public function __construct(ChildProduct $childProduct)
    {
        $this->childProduct = $childProduct;
    }
}

<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Models\Attribute;

class AttributeWasDeleted extends Event
{
    use SerializesModels;

    public $attribute;

    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }
}

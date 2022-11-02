<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeValue;

class AttributeWasUpdated extends Event
{
    use SerializesModels;

    public $attribute;

    public $oldValues;

    public function __construct(Attribute $attribute, $oldValues)
    {
        $this->attribute = $attribute;
        $this->oldValues = $oldValues;
    }
}

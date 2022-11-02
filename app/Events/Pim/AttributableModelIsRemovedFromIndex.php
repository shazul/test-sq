<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\Attributable;

class AttributableModelIsRemovedFromIndex extends Event
{
    use SerializesModels;

    public $attributableModelId;

    public $indexer;

    public $class;

    public function __construct($id, $class, Indexer $indexer)
    {
        $this->attributableModelId = $id;
        $this->class               = $class;
        $this->indexer             = $indexer;
    }
}

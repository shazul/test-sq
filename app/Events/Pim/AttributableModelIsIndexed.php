<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Indexer\Indexer;
use Pimeo\Models\Attributable;

class AttributableModelIsIndexed extends Event
{
    use SerializesModels;

    public $attributableModel;

    public $indexer;

    public function __construct(Attributable $model, Indexer $indexer)
    {
        $this->attributableModel = $model;
        $this->indexer           = $indexer;
    }
}

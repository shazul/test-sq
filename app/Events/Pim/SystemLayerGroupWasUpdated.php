<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;
use Pimeo\Models\LayerGroup;

class SystemLayerGroupWasUpdated extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct(LayerGroup $layerGroup)
    {
        parent::__construct(
            $layerGroup->system,
            $layerGroup->system->id,
            $layerGroup->system->company,
            SystemIndexer::class
        );
    }
}

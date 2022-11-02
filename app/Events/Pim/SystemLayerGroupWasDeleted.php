<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;

class SystemLayerGroupWasDeleted extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct($parentSystem, $languages, $company)
    {
        parent::__construct(
            $parentSystem,
            $parentSystem->id,
            $company,
            SystemIndexer::class
        );
    }
}

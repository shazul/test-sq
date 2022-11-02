<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;
use Pimeo\Models\System;

class SystemWasCreated extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct(System $system)
    {
        parent::__construct(
            $system,
            $system->id,
            $system->company,
            SystemIndexer::class
        );
    }
}

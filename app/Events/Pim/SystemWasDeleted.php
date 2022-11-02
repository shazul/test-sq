<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;

class SystemWasDeleted extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct($systemId, $languages, $company)
    {
        parent::__construct(
            null,
            $systemId,
            $company,
            SystemIndexer::class
        );
    }
}

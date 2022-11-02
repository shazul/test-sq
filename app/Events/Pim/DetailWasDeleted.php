<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\DetailIndexer;

class DetailWasDeleted extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct($detailId, $languages, $company)
    {
        parent::__construct(
            null,
            $detailId,
            $company,
            DetailIndexer::class
        );
    }
}

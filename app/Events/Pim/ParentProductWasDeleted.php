<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;

class ParentProductWasDeleted extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct($productId, $languages, $company)
    {
        parent::__construct(
            null,
            $productId,
            $company,
            ProductIndexer::class
        );
    }
}

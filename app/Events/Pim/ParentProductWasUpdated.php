<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Models\ParentProduct;

class ParentProductWasUpdated extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct(ParentProduct $parentProduct)
    {
        parent::__construct(
            $parentProduct,
            $parentProduct->id,
            $parentProduct->company,
            ProductIndexer::class
        );
    }
}

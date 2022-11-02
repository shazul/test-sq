<?php

namespace Pimeo\Events\Pim;

use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Models\ChildProduct;

class ChildProductWasUpdated extends IndexDocumentEvent
{
    public $childProduct;

    public function __construct(ChildProduct $childProduct)
    {
        if (! $childProduct->parentProduct) {
            return;
        }

        parent::__construct(
            $childProduct->parentProduct,
            $childProduct->parentProduct->id,
            $childProduct->parentProduct->company,
            ProductIndexer::class
        );
        $this->childProduct = $childProduct;
    }
}

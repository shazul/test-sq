<?php

namespace Pimeo\Events\Pim;

use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Models\ParentProduct;

class ChildProductWasDeleted extends IndexDocumentEvent
{
    public $childProduct;

    public function __construct(ParentProduct $product)
    {
        parent::__construct(
            $product,
            $product->id,
            $product->company,
            ProductIndexer::class
        );
    }
}

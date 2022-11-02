<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\SpecificationIndexer;
use Pimeo\Models\Specification;

class SpecificationWasCreated extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct(Specification $specification)
    {
        parent::__construct(
            $specification,
            $specification->id,
            $specification->company,
            SpecificationIndexer::class
        );
    }
}

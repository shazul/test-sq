<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\SpecificationIndexer;

class SpecificationWasDeleted extends IndexDocumentEvent
{
    use SerializesModels;

    public $company;

    public $id;

    public function __construct($specificationId, $languages, $company)
    {
        parent::__construct(
            null,
            $specificationId,
            $company,
            SpecificationIndexer::class
        );
    }
}

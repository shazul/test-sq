<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Events\Event;
use Pimeo\Indexer\ModelIndexers\TechnicalBulletinIndexer;

class TechnicalBulletinWasDeleted extends IndexDocumentEvent
{
    use SerializesModels;

    public $id;

    public $company;

    public function __construct($technicalBulletinId, $languages, $company)
    {
        parent::__construct(
            null,
            $technicalBulletinId,
            $company,
            TechnicalBulletinIndexer::class
        );
    }
}

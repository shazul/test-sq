<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\TechnicalBulletinIndexer;
use Pimeo\Models\TechnicalBulletin;

class TechnicalBulletinWasUpdated extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct(TechnicalBulletin $technicalBulletin)
    {
        parent::__construct(
            $technicalBulletin,
            $technicalBulletin->id,
            $technicalBulletin->company,
            TechnicalBulletinIndexer::class
        );
    }
}

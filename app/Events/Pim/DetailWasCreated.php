<?php

namespace Pimeo\Events\Pim;

use Illuminate\Queue\SerializesModels;
use Pimeo\Indexer\ModelIndexers\DetailIndexer;
use Pimeo\Models\Detail;

class DetailWasCreated extends IndexDocumentEvent
{
    use SerializesModels;

    public function __construct(Detail $detail)
    {
        parent::__construct(
            $detail,
            $detail->id,
            $detail->company,
            DetailIndexer::class
        );
        $this->model = $detail;
    }
}

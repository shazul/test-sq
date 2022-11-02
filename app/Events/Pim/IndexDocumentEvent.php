<?php

namespace Pimeo\Events\Pim;

use Pimeo\Events\Event;
use Pimeo\Models\Attributable;

abstract class IndexDocumentEvent extends Event
{
    /** @var Attributable $model */
    public $model;

    public $id;

    public $company;

    public $indexer;

    public function __construct($model, $id, $company, $indexer)
    {
        $this->model   = $model;
        $this->id      = $id;
        $this->company = $company;
        $this->indexer = $indexer;
    }
}

<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\Layer;

class DeleteSystemLayer extends Job
{
    /**
     * The system to delete.
     *
     * @var Layer
     */
    protected $layer;

    /**
     * Create a new job instance.
     *
     * @param Layer $layer
     */
    public function __construct(Layer $layer)
    {
        $this->layer = $layer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new SystemWasUpdated($this->layer->layerGroup->system));
    }
}

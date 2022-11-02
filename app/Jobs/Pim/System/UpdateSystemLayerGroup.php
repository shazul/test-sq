<?php
namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemLayerGroupWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\LayerGroup;

class UpdateSystemLayerGroup extends Job
{
    /**
     * The specification to delete.
     *
     * @var LayerGroup
     */
    protected $layer_group;

    /**
     * Create a new job instance.
     *
     * @param LayerGroup $layer_group
     */
    public function __construct(LayerGroup $layer_group)
    {
        $this->layer_group = $layer_group;
    }

    public function handle()
    {
        event(new SystemLayerGroupWasUpdated($this->layer_group));
    }
}

<?php
namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemLayerGroupWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\LayerGroup;

class DeleteSystemLayerGroup extends Job
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
        $parent_system = $this->layer_group->system;
        $company = $parent_system->company;
        $languages = $company->languages;

        event(new SystemLayerGroupWasDeleted($parent_system, $languages, $company));
    }
}

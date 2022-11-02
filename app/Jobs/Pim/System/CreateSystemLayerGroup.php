<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemLayerGroupWasCreated;
use Pimeo\Jobs\Job;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\System;

class CreateSystemLayerGroup extends Job
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * Create a new job instance.
     *
     * @param array $fields
     */
    public function __construct(array $fields, System $system)
    {
        $this->fields = $fields;
        $this->system = $system;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $systemLayerGroup = LayerGroup::create([
            'system_id' => $this->system->id,
            'name'      => $this->fields['name'],
            'position'  => $this->fields['position'],
        ]);

        $this->updateSystemStatus();

        event(new SystemLayerGroupWasCreated($systemLayerGroup->fresh()));
    }

    private function updateSystemStatus()
    {
        $updateSystem = app(UpdateSystem::class, [$this->system, []]);
        $updateSystem->updateStatus($this->system);
    }
}

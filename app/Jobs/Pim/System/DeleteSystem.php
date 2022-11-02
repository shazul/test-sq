<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\System;

class DeleteSystem extends Job
{
    /**
     * The system to delete.
     *
     * @var System
     */
    protected $system;

    /**
     * Create a new job instance.
     *
     * @param System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->system->id;
        $company = $this->system->company;
        $languages = $company->languages;

        $this->system->layerGroups()->delete();
        $this->system->linkAttributes()->delete();
        $this->system->mediaLinks()->delete();
        $this->system->delete();

        event(new SystemWasDeleted($id, $languages, $company));
    }
}

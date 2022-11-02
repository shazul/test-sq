<?php

namespace Pimeo\Jobs\Pim\System;

use Pimeo\Events\Pim\SystemWasCreated;
use Pimeo\Jobs\Pim\Product\Create;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Company;
use Pimeo\Repositories\SystemRepository;

class CreateSystem extends Create
{
    /**
     * Create a new job instance.
     *
     * @param array $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
    }

    /**
     * Execute the job.
     *
     * @param SystemRepository $repository
     * @return void
     */
    public function handle(SystemRepository $repository)
    {
        $system = $repository->create([
            'company_id'     => current_company()->id,
            'created_by'     => auth()->user()->id,
            'updated_by'     => auth()->user()->id,
            'status'         => AttributableModelStatus::INCOMPLETE_STATUS,
        ]);

        $system->buildingComponents()->sync($this->fields['buildingComponents']);
        $this->product = $system;
        $this->addAttributes($this->fields['attributes']);
        $this->addMedia($this->fields['media']);

        event(new SystemWasCreated($system->fresh()));
    }
}

<?php

namespace Pimeo\Jobs\Pim\Specification;

use Pimeo\Events\Pim\SpecificationWasCreated;
use Pimeo\Jobs\Pim\Product\Create;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Repositories\SpecificationRepository;

class CreateSpecification extends Create
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
     * @param SpecificationRepository $repository
     * @return void
     */
    public function handle(SpecificationRepository $repository)
    {
        $specification = $repository->create([
            'company_id'   => current_company()->id,
            'created_by'   => auth()->user()->id,
            'updated_by'   => auth()->user()->id,
            'status'       => AttributableModelStatus::COMPLETE_STATUS,
        ]);

        $this->product = $specification;
        $this->addAttributes($this->fields['attributes']);
        $this->addMedia($this->fields['media']);

        event(new SpecificationWasCreated($specification->fresh()));
    }
}

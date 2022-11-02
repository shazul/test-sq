<?php

namespace Pimeo\Jobs\Pim\Specification;

use Pimeo\Events\Pim\SpecificationWasUpdated;
use Pimeo\Jobs\Pim\Product\Update;
use Pimeo\Models\Specification;

class UpdateSpecification extends Update
{
    /**
     * @var Specification
     */
    protected $specification;

    /**
     * Create a new job instance.
     *
     * @param Specification $specification
     * @param array $fields
     */
    public function __construct(Specification $specification, array $fields)
    {
        $this->specification = $specification;
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
        $this->updateStatus($this->specification);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->updateAttributes($this->specification, $this->fields['attributes']);
        $this->updateMedia($this->specification, $this->fields['media']);

        event(new SpecificationWasUpdated($this->specification->fresh()));
    }

    protected function addMissingFields($attributes)
    {
        // Ajout des min required fields manquants
        $createSpecification = app(CreateSpecification::class);
        $createSpecification->product = $this->specification;
        $createSpecification->addAttributes($attributes);
    }
}

<?php

namespace Pimeo\Jobs\Pim\Specification;

use Pimeo\Events\Pim\SpecificationWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\Specification;

class DeleteSpecification extends Job
{
    /**
     * The specification to delete.
     *
     * @var Specification
     */
    protected $specification;

    /**
     * Create a new job instance.
     *
     * @param Specification $specification
     */
    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->specification->id;
        $company = $this->specification->company;
        $languages = $company->languages;

        $this->specification->linkAttributes()->delete();
        $this->specification->mediaLinks()->delete();
        $this->specification->delete();

        event(new SpecificationWasDeleted($id, $languages, $company));
    }
}

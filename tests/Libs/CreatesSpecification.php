<?php

namespace Tests\Libs;

use Carbon\Carbon;
use Pimeo\Events\Pim\SpecificationWasCreated;
use Pimeo\Events\Pim\SpecificationWasUpdated;
use Pimeo\Jobs\Pim\Specification\CreateSpecification;
use Pimeo\Jobs\Pim\Specification\UpdateSpecification;
use Pimeo\Models\Attribute;
use Pimeo\Models\Language;
use Pimeo\Models\Specification;
use Pimeo\Repositories\SpecificationRepository;

trait CreatesSpecification
{
    /**
     * @return mixed
     */
    public function createValidSpecification()
    {
        $data = $this->getRequiredAttributeForCreation(Attribute::MODEL_TYPE_SPECIFICATION);

        $create_request = new CreateSpecification($data);
        $this->expectsEvents(SpecificationWasCreated::class);
        $create_request->handle(app(SpecificationRepository::class));

        return Specification::all()->last()->id;
    }

    /**
     * @param Specification $specification
     * @return Specification
     */
    public function editValidSpecification(Specification $specification)
    {
        $data = $this->getModelAttributeForUpdate($specification);

        Carbon::setTestNow(Carbon::now()->addMinute());
        $update_request = new UpdateSpecification($specification, $data);
        $this->expectsEvents(SpecificationWasUpdated::class);
        $update_request->handle(app(SpecificationRepository::class));

        $updated_specification = Specification::all()->last();

        return $updated_specification;
    }
}

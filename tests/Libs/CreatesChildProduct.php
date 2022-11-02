<?php

namespace Tests\Libs;

use Carbon\Carbon;
use Pimeo\Events\Pim\ChildProductWasCreated;
use Pimeo\Events\Pim\ChildProductWasUpdated;
use Pimeo\Jobs\Pim\ChildProduct\UpdateChildProduct;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Repositories\ChildProductRepository;

trait CreatesChildProduct
{
    /**
     * @param bool $all
     * @param bool $published
     *
     * @return ChildProduct
     */
    public function createValidChildProduct($all = false, $published = false)
    {
        if ($all === true) {
            $data = $this->getAllAttributesForCreation(Attribute::MODEL_TYPE_CHILD_PRODUCT);
        } else {
            $data = $this->getRequiredAttributeForCreation(Attribute::MODEL_TYPE_CHILD_PRODUCT);
        }
        $data['company_id'] = current_company()->id;
        $data['company_catalog_id'] = current_company()->companyCatalogs->first()->id;

        if ($published) {
            $data['status'] = AttributableModelStatus::PUBLISHED_STATUS;
        } else {
            $data['status'] = AttributableModelStatus::DRAFT_STATUS;
        }

        $childRepository = app(ChildProductRepository::class);

        $this->expectsEvents(ChildProductWasCreated::class);

        return $childRepository->create($data);
    }

    /**
     * @param ChildProduct $childProduct
     * @return ChildProduct
     */
    public function editChildProduct(ChildProduct $childProduct)
    {
        $data = $this->getModelAttributeForUpdate($childProduct);

        Carbon::setTestNow(Carbon::now()->addMinute());
        $update_request = new UpdateChildProduct($childProduct, $data);
        $this->expectsEvents(ChildProductWasUpdated::class);
        $update_request->handle();

        $updated_product = ChildProduct::all()->last();

        return $updated_product;
    }
}

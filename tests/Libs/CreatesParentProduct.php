<?php

namespace Tests\Libs;

use Pimeo\Events\Pim\ParentProductWasCreated;
use Pimeo\Jobs\Pim\ParentProduct\CreateParentProduct;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\Language;
use Pimeo\Models\Nature;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\ParentProductRepository;

trait CreatesParentProduct
{
    /**
     * @return ParentProduct
     */
    public function createValidParentProduct($all = false, $published = false)
    {
        if ($all === true) {
            $data = $this->getAllAttributesForCreation(Attribute::MODEL_TYPE_PARENT_PRODUCT);
        } else {
            $data = $this->getRequiredAttributeForCreation(Attribute::MODEL_TYPE_PARENT_PRODUCT);
        }
        $data['company_id'] = current_company()->id;
        $data['nature_id'] = Nature::first()->id;

        $this->expectsEvents(ParentProductWasCreated::class);
        $parentProduct = dispatch(new CreateParentProduct($data, $published));

        return $parentProduct;
    }
}

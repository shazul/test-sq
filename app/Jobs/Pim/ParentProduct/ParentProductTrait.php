<?php

namespace Pimeo\Jobs\Pim\ParentProduct;

use Pimeo\Models\AttributableModelStatus;

trait ParentProductTrait
{
    public function setStatus()
    {
        if ($this->publish === true) {
            $this->product->status = AttributableModelStatus::PUBLISHED_STATUS;
        } else {
            $this->product->status = AttributableModelStatus::DRAFT_STATUS;
        }
    }

    public function setNewAndStarProduct()
    {
        $this->product->new_product = isset($this->fields['new_product']);
        $this->product->star_product= isset($this->fields['star_product']);
    }
}

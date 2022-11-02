<?php

namespace Pimeo\Jobs\Pim\ChildProduct;

use Pimeo\Events\Pim\ChildProductWasUpdated;
use Pimeo\Jobs\Pim\ParentProduct\UpdateParentProduct;
use Pimeo\Jobs\Pim\Product\Update;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Repositories\ParentProductRepository;

class UpdateChildProduct extends Update
{
    private $child_product;
    private $publish;

    /**
     * Create a new job instance.
     *
     * @param $child_product
     * @param array $fields
     */
    public function __construct(ChildProduct $child_product, array $fields, $publish = false)
    {
        $this->child_product = $child_product;
        $this->fields = $fields;
        $this->setDefaultValues(['media']);
        $this->publish = $publish;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateAttributes($this->child_product, $this->fields['attributes']);
        $this->updateMedia($this->child_product, $this->fields['media']);
        $this->updateStatus();

        event(new ChildProductWasUpdated($this->child_product));
    }

    protected function updateStatus($product = null)
    {
        if ($this->publish === true) {
            $this->child_product->status = AttributableModelStatus::PUBLISHED_STATUS;
        } else {
            $this->child_product->status = AttributableModelStatus::DRAFT_STATUS;
        }
        $this->child_product->save();

        // $this->updateStatusParent();
    }

    private function updateStatusParent()
    {
        $parentRepo = app(ParentProductRepository::class);

        $hasRequiredAttributes = $parentRepo->hasRequiredAttributes($this->child_product->parentProduct->id);

        if ($hasRequiredAttributes === true) {
            $updateParentProduct = app(UpdateParentProduct::class, [$this->child_product->parentProduct, []]);
            $updateParentProduct->updateStatus();
        }
    }
}

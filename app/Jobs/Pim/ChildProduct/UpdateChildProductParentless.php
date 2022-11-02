<?php

namespace Pimeo\Jobs\Pim\ChildProduct;

use Pimeo\Jobs\Job;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;

class UpdateChildProductParentless extends Job
{
    private $fields;
    private $child_product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ChildProduct $child_product, array $fields)
    {
        $this->child_product = $child_product;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateParent($this->fields['parent_product']);
        $this->updateStatus();
    }

    /**
     * Updates the parent id
     *
     * @param $parentId
     */
    private function updateParent($parentId)
    {
        $this->child_product->parent_product_id = $parentId;
        $this->child_product->save();
    }

    private function updateStatus()
    {
        if ($this->child_product->status == AttributableModelStatus::PARENTLESS_STATUS) {
            $this->child_product->status = AttributableModelStatus::DRAFT_STATUS;
            $this->child_product->save();
        }
    }
}

<?php

namespace Pimeo\Jobs\Pim\ParentProduct;

use Pimeo\Events\Pim\ParentProductWasDeleted;
use Pimeo\Jobs\Job;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\ParentProduct;

class DeleteParentProduct extends Job
{
    /**
     * The parent product to delete.
     *
     * @var ParentProduct
     */
    protected $product;

    /**
     * Create a new job instance.
     *
     * @param ParentProduct $parent_product
     */
    public function __construct(ParentProduct $parent_product)
    {
        $this->product = $parent_product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->product->id;
        $company = $this->product->company;
        $languages = $company->languages;

        $this->product->mediaLinks()->delete();
        $this->product->linkAttributes()->delete();
        // $this->updateStatusChilds();
        $this->product->delete();

        event(new ParentProductWasDeleted($id, $languages, $company));
    }

    private function updateStatusChilds()
    {
        ChildProduct::whereParentProductId($this->product->id)->update([
            'parent_product_id' => null,
            'status'            => AttributableModelStatus::INCOMPLETE_STATUS,
        ]);
    }
}

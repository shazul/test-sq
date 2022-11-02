<?php

namespace Pimeo\Jobs\Pim\ChildProduct;

use Pimeo\Events\Pim\ChildProductWasUpdated;
use Pimeo\Events\Pim\ParentProductWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Jobs\Pim\ParentProduct\UpdateParentProductNature;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\ParentProductRepository;

class UpdateChildProductParent extends Job
{
    private $fields;
    private $child_product;
    private $parentRepo;

    /**
     * Create a new job instance.
     *
     * @param ChildProduct $child_product
     * @param array $fields
     */
    public function __construct(ChildProduct $child_product, array $fields)
    {
        $this->child_product = $child_product;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @param ParentProductRepository $parentRepo
     */
    public function handle(ParentProductRepository $parentRepo)
    {
        $this->parentRepo = $parentRepo;
        $oldParentID = $this->child_product->parent_product_id;

        if (isset($this->fields['remove-parent'])) {
            $this->removeParent();
        } else {
            $newParentID = $this->fields['parent_product'];

            $this->updateParent($newParentID);
            $this->cleanLinkAttributesIfNewNature($oldParentID, $newParentID);
        }

        event(new ParentProductWasUpdated(ParentProduct::find($oldParentID)));
        event(new ChildProductWasUpdated($this->child_product));
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

    private function cleanLinkAttributesIfNewNature($oldParentID, $newParentID)
    {
        /** @var ParentProduct $newParentProduct */
        $newParentProduct = ParentProduct::find($newParentID);
        /** @var ParentProduct $oldParentProduct */
        $oldParentProduct = ParentProduct::find($oldParentID);


        if ($newParentProduct->nature_id != $oldParentProduct->nature_id) {
            $updateParentProductNatureJob = new UpdateParentProductNature(
                $newParentProduct,
                $newParentProduct->nature_id
            );
            $updateParentProductNatureJob->deleteLinkAttributesWithoutNewNature(
                $newParentProduct->nature_id,
                $this->child_product,
                'Pimeo\Models\ChildProduct'
            );
        }
    }

    private function removeParent()
    {
        // Supprime tous les linkAttributes non required
        LinkAttribute::whereAttributableId($this->child_product->id)
            ->whereHas('attribute', function ($query) {
                $query->where('is_min_requirement', '!=', true);
            })
            ->where('attributable_type', 'Pimeo\Models\ChildProduct')
        ->delete();

        $this->child_product->parent_product_id = null;
        $this->child_product->status = AttributableModelStatus::PARENTLESS_STATUS;
        $this->child_product->save();
    }
}

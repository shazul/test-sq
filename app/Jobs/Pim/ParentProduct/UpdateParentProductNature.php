<?php

namespace Pimeo\Jobs\Pim\ParentProduct;

use Pimeo\Jobs\Job;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\ParentProduct;

class UpdateParentProductNature extends Job
{
    private $product;
    private $new_nature_id;

    /**
     * Create a new job instance.
     *
     * @param ParentProduct $product
     * @param int           $new_nature_id
     */
    public function __construct(ParentProduct $product, $new_nature_id)
    {
        $this->product = $product;
        $this->new_nature_id = $new_nature_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateNature();
        $this->cleanOldLinkAttributes();
    }

    private function updateNature()
    {
        $this->product->nature_id = $this->new_nature_id;
        $this->product->save();
    }

    private function cleanOldLinkAttributes()
    {
        $this->deleteLinkAttributesWithoutNewNature($this->new_nature_id, $this->product, 'Pimeo\Models\ParentProduct');

        $linkedChildProducts = ChildProduct::whereParentProductId($this->product->id)->get();
        $linkedChildProducts->each(function ($childProduct) {
            $this->deleteLinkAttributesWithoutNewNature(
                $this->new_nature_id,
                $childProduct,
                'Pimeo\Models\ChildProduct'
            );
        });
    }

    public function deleteLinkAttributesWithoutNewNature($newNatureID, $product, $type)
    {
        $linkAttributes = LinkAttribute::whereAttributableId($product->id)
            ->whereHas('attribute', function ($query) {
                $query->where('is_min_requirement', '!=', true);
            })
            ->where('attributable_type', $type)
        ->get();

        foreach ($linkAttributes as $linkAttribute) {
            $attributeHasNewNature = $linkAttribute->attribute->natures->keyBy('id')->has($newNatureID);
            if (!$attributeHasNewNature) {
                $linkAttribute->delete();
            }
        }
    }
}

<?php

namespace Pimeo\Transformers;

use Pimeo\Models\ParentProduct;

class ParentProductTransformer extends Transformer
{
    public function transform(ParentProduct $product)
    {
        return array_merge(
            [
                'id'         => $product->id,
                'company_id' => $product->company_id,
            ],
            $this->flattenAttribute($product),
            $this->includeChildProducts($product)
        );
    }

    protected function includeChildProducts(ParentProduct $parentProduct)
    {
        if (!$parentProduct->relationLoaded('childProducts')) {
            return [];
        }

        $children = [];

        foreach ($parentProduct->childProducts as $product) {
            $children[] = array_merge([
                'id'                => $product->id,
                'company_id'        => $product->company_id,
                'parent_product_id' => $product->parent_product_id,
            ], $this->flattenAttribute($product));
        }

        return ['children' => $children];
    }
}

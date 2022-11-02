<?php

namespace Pimeo\Transformers;

use Pimeo\Models\ChildProduct;
use Pimeo\Models\Media;

class ChildProductTransformer extends Transformer
{
    public function transform(ChildProduct $product)
    {
        return array_merge([
            'id'                         => $product->id,
            'company_id'                 => $product->company_id,
            'parent_product_id'          => $product->parent_product_id,
            'company_catalog_product_id' => $product->company_catalog_product_id,
        ], $this->flattenAttribute($product), $this->flattenMedia($product));
    }

    protected function flattenMedia(ChildProduct $product)
    {
        $medias = [];

        foreach ($product->mediaLinks as $mediaLink) {
            $medias[$mediaLink->media_id] = [
                'id'   => $mediaLink->media->id,
                'name' => $mediaLink->media->name,
                'code' => $mediaLink->media->code,
            ];
        }

        $medias = array_values($medias);

        return ['medias' => $medias];
    }
}

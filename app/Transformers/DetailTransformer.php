<?php

namespace Pimeo\Transformers;

use Pimeo\Models\Detail;

class DetailTransformer extends Transformer
{
    public function transform(Detail $detail)
    {
        return array_merge([
            'id'         => $detail->id,
            'company_id' => $detail->company_id,
        ], $this->flattenAttribute($detail));
    }
}

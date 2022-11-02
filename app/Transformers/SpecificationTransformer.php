<?php

namespace Pimeo\Transformers;

use Pimeo\Models\Specification;

class SpecificationTransformer extends Transformer
{
    public function transform(Specification $specification)
    {
        return array_merge([
            'id'         => $specification->id,
            'company_id' => $specification->company_id,
        ], $this->flattenAttribute($specification));
    }
}

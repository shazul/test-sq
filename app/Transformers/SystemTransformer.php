<?php

namespace Pimeo\Transformers;

use Pimeo\Models\System;

class SystemTransformer extends Transformer
{
    public function transform(System $system)
    {
        return array_merge([
            'id'         => $system->id,
            'company_id' => $system->company_id,
        ], $this->flattenAttribute($system));
    }
}

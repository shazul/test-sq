<?php

namespace Pimeo\Transformers;

use Pimeo\Models\TechnicalBulletin;

class TechnicalBulletinTransformer extends Transformer
{
    public function transform(TechnicalBulletin $technicalBulletin)
    {
        return array_merge([
            'id'         => $technicalBulletin->id,
            'company_id' => $technicalBulletin->company_id,
        ], $this->flattenAttribute($technicalBulletin));
    }
}

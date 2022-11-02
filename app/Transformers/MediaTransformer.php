<?php

namespace Pimeo\Transformers;

use League\Fractal\TransformerAbstract;
use Pimeo\Models\Media;

class MediaTransformer extends TransformerAbstract
{
    public function transform(Media $media)
    {
        return [
            'id'   => $media->id,
            'code' => $media->code,
            'name' => $media->name,
        ];
    }
}

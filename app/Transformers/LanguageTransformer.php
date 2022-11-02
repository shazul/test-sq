<?php

namespace Pimeo\Transformers;

use League\Fractal\TransformerAbstract;
use Pimeo\Models\Language;

class LanguageTransformer extends TransformerAbstract
{
    public function transform(Language $language)
    {
        return [
            'name' => $language->name,
            'code' => $language->code,
        ];
    }
}

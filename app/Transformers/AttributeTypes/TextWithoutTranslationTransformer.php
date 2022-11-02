<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

class TextWithoutTranslationTransformer extends AbstractTypeTransformer
{
    /**
     * Transform the given values.
     *
     * @param LinkAttribute $linkAttribute
     *
     * @return mixed
     */
    public function transform(LinkAttribute $linkAttribute)
    {
        return $linkAttribute->values;
    }
}

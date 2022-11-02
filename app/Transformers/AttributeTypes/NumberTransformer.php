<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

class NumberTransformer extends AbstractTypeTransformer
{
    /**
     * Transform the given values.
     *
     * @param LinkAttribute $linkAttribute
     *
     * @return array|float
     */
    public function transform(LinkAttribute $linkAttribute)
    {
        return $linkAttribute->values;
    }
}

<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

class UnitTransformer extends AbstractTypeTransformer
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
        $values = $linkAttribute->values;

        if (array_key_exists('metric', $values)) {
            array_set($values, 'metric', (float)$values['metric']);
            array_set($values, 'imperial', (float)$values['imperial']);
        }

        return $values;
    }
}

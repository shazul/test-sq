<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

class ChoiceTransformer extends AbstractTypeTransformer
{
    /**
     * Transform the given values.
     *
     * @param LinkAttribute $linkAttribute
     *
     * @return mixed
     * @internal param LinkAttributeValue $linkAttributeValues
     */
    public function transform(LinkAttribute $linkAttribute)
    {
        $values = $linkAttribute->attribute->value->values;
        $keys = $linkAttribute->values['keys'];

        foreach ($values as $lang => $value) {
            $value = array_only($value, array_intersect(array_values($keys), array_keys($value)));

            if (array_get($this->options, 'multiple', 0) === 0) {
                $values[$lang] = array_shift($value);
            } else {
                $values[$lang] = array_values($value);
            }
        }

        return $values;
    }
}

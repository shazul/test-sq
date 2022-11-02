<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

class TextTransformer extends AbstractTypeTransformer
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

        if (array_get($this->options, 'sub_type') == 'link') {
            $ids = explode(array_get($this->options, 'separator', ','), $values);

            $values = [];
            foreach ($ids as $id) {
                $values[] = route('api.product', $id);
            }
        }

        return $values;
    }
}

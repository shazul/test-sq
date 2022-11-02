<?php

namespace Pimeo\Jobs\Pim\Product;

use Pimeo\Jobs\Job;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\LinkMedia;

class Create extends Job
{
    /**
     * @var array
     */
    protected $fields;

    public $product;

    /**
     * Add required Attributes to product.
     *
     * @param array $attributes
     */
    public function addAttributes($attributes = [])
    {
        foreach ($attributes as $attribute_id => $attribute_value) {
            $linkAttribute = new LinkAttribute(['attribute_id' => $attribute_id]);
            $field_code = $linkAttribute->attribute->type->code;
            $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
            $fieldType = app($fieldClass);

            $fieldValue = $fieldType->formToValues($attribute_value);

            $linkAttribute->values = $fieldValue;
            $this->product->linkAttributes()->save($linkAttribute);
        }
    }

    /**
     * Add media to parent product.
     *
     * @param array $media_ids
     */
    public function addMedia($media_ids = [])
    {
        if (!empty($media_ids)) {
            foreach ($media_ids as $media) {
                $linkMedia = new LinkMedia(['media_id' => $media]);

                $this->product->mediaLinks()->save($linkMedia);
            }
        }
    }

    /**
     * Set default values if they don't exist in Form.
     *
     * @param array
     */
    protected function setDefaultValues($fields = [])
    {
        foreach ($fields as $fieldsBlock) {
            if (!array_has($this->fields, $fieldsBlock)) {
                $this->fields[$fieldsBlock] = [];
            }
        }
    }
}

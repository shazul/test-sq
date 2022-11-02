<?php

namespace Pimeo\Jobs\Pim\Product;

use Pimeo\Jobs\Job;
use Pimeo\Models\AttributableModelStatus;

class Update extends Job
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * Update required Attributes in product.
     *
     * @param $product
     * @param array $attributes
     */
    public function updateAttributes($product, $attributes = [])
    {
        foreach ($product->linkAttributes as $linkAttribute) {
            $field_code = $linkAttribute->attribute->type->code;
            $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
            $fieldType = app($fieldClass);

            $fieldValue = $fieldType->formToValues(
                $attributes[$linkAttribute->attribute_id]
            );
            $linkAttribute->values = $fieldValue;
            $linkAttribute->save();

            unset($attributes[$linkAttribute->attribute_id]);
        }
        if (method_exists($this, 'addMissingFields')) {
            $this->addMissingFields($attributes);
        }
    }

    /**
     * Update media in product.
     *
     * @param $product
     * @param array  $media_ids
     */
    protected function updateMedia($product, $media_ids = [])
    {
        if (!empty($media_ids)) {
            $product->mediaLinks()->whereNotIn('media_id', $media_ids)->delete();

            foreach ($media_ids as $media) {
                $product->mediaLinks()->updateOrCreate(['media_id' => $media]);
            }
        } else {
            $product->mediaLinks()->delete();
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

    protected function updateStatus($product)
    {
        // Peut seulement sauvegarder si champs sont valide, donc le produit est complet
        $product->status = AttributableModelStatus::COMPLETE_STATUS;
        $product->save();
    }
}

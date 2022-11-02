<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\AttributeValue;

class ImageChoiceMultiple extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        $attributeValues = AttributeValue::whereAttributeId($this->attribute->id)->first()->values[$this->languageCode];
        foreach ($attributeValues as $noValue => $value) {
            $attrs['choices'][$noValue] = [
                'label'     => $value['name'],
                'image-src' => getenv('FILES_ADDRESS') . '/' . $value['image'],
            ];
        }
        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['attr']['class'] = 'form-control image-picker-label';

        $attrs['multiple'] = true;
        $attrs['template'] = 'vendor.laravel-form-builder.select-image';
        $attrs['selected'] = !empty($this->values) ? array_values($this->values['keys']) : [];

        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'choice';
        $field['attrs'] = $attrs;
        $fields[] = $field;

        return $fields;
    }

    public function formToValues($form)
    {
        $values = ['keys' => $form];

        return $values;
    }

    public function getDefaultValues()
    {
        return [];
    }

    public function formatValues(array $values, array $languages)
    {
        $data = $this->getDefaultValues();
        if (!empty($values)) {
            $data = ['keys' => (array)$values];
        }

        return $data;
    }
}

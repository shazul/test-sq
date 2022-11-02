<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\AttributeValue;

class ChoiceMultiple extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        $attributeValue = AttributeValue::whereAttributeId($this->attribute->id)->first();

        $attrs['choices'] = $attributeValue->values[$this->languageCode];
        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['multiple'] = true;
        $attrs['selected'] = !empty($this->values) ? array_values((array)$this->values['keys']) : [];

        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'choice';
        $field['attrs'] = $attrs;
        $fields[] = $field;

        return $fields;
    }

    public function formToValues($form)
    {
        if ($form == 'novalue') {
            $form = $this->getDefaultValues();
        }

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

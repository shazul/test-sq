<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\AttributeValue;

class ChoiceImageNoDisplay extends Field
{
    public function getFields()
    {
        $fields = $attrs = $fieldValues = [];

        $fieldDbValues = AttributeValue::whereAttributeId($this->attribute->id)->first()->values[$this->languageCode];
        $fieldValues = array_pluck($fieldDbValues, 'name');

        $attrs['choices'] = $fieldValues;
        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'choice';
        $attrs['selected'] = isset($this->values) ? array_values($this->values) : [];
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

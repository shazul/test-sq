<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\AttributeValue;

class Choice extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        $choices['novalue'] = trans('attribute.default_choice');
        $choice_array = AttributeValue::whereAttributeId($this->attribute->id)->first()->values[$this->languageCode];
        foreach ($choice_array as $key => $value) {
            $choices[$key] = $value;
        }

        $attrs['choices'] = $choices;
        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'choice';
        $attrs['selected'] = isset($this->values['keys']) ? array_values((array)$this->values['keys']) : [];
        $field['attrs'] = $attrs;
        $fields[] = $field;

        return $fields;
    }

    public function formToValues($form)
    {
        if ($form == 'novalue') {
            $form = $this->getDefaultValues();
        }

        $values = ['keys' => (array)$form];

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

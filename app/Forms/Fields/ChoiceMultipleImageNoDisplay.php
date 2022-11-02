<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\AttributeValue;

class ChoiceMultipleImageNoDisplay extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        $fieldDbValues = AttributeValue::whereAttributeId($this->attribute->id)->first()->values[$this->languageCode];
        $attrs['choices'] = $this->buildChoiceValues($fieldDbValues);

        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['multiple'] = true;
        $attrs['selected'] = !empty($this->values['keys']) ? array_values($this->values['keys']) : [];

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

    private function buildChoiceValues($fieldDbValues)
    {
        $options = [];
        foreach ($fieldDbValues as $key => $values) {
            $options[$key] = $values['name'];
        }

        return $options;
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

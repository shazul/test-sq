<?php

namespace Pimeo\Forms\Fields;

class Number extends Field
{
    public function getFields()
    {
        $attrs = [];

        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['value'] = $this->getValues();
        $attrs['id'] = $this->attribute->id;

        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'number';
        $field['attrs'] = $attrs;

        $fields[] = $field;

        return $fields;
    }

    public function getDefaultValues()
    {
        return '';
    }

    public function formatValues(array $values, array $languages)
    {
        $data = $this->getDefaultValues();
        if (!empty($values)) {
            $data = array_first($values);
        }

        return $data;
    }
}

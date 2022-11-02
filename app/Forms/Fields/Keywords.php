<?php

namespace Pimeo\Forms\Fields;

class Keywords extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        $values = $this->getValues();
        if (!empty($values) && isset($values['keys'])) {
            $values = $values['keys'];
        }

        foreach ($values as $code => $value) {
            $language_code = language_code_trans($code);
            $value         = array_combine($value, $value);

            $attrs['label']            = $this->attribute->label->values[$this->languageCode] .
                ' (' . $language_code . ')';
            $attrs['choices']          = $value;
            $attrs['template']         = 'vendor.laravel-form-builder.select2-tags';
            $attrs['attr']['class']    = 'form-control select2-tags';
            $attrs['attr']['multiple'] = true;
            $attrs['selected']         = $value;

            $field['name']  = "attributes[{$this->attribute->id}][{$code}]";
            $field['type']  = 'select';
            $field['attrs'] = $attrs;
            $fields[]       = $field;
        }

        return $fields;
    }

    public function formToValues($form)
    {
        if ($form == 'novalue') {
            $form = $this->getDefaultValues();
        }

        foreach ($form as $languageCode => $value) {
            if ($form[$languageCode] == 'novalue') {
                $form[$languageCode] = [];
            }
        }

        $values = ['keys' => $form];

        return $values;
    }

    public function getDefaultValues()
    {
        $values = [
            'keys' => [],
        ];

        foreach (current_company()->languages as $language) {
            $values['keys'][$language->code] = [];
        }

        return $values;
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

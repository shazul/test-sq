<?php

namespace Pimeo\Forms\Fields;

use Illuminate\Support\Facades\Request;
use Pimeo\Models\Language;

class TextMultiple extends Field
{
    public function getFields()
    {
        $attrs = [];

        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['template'] = 'vendor.laravel-form-builder.text-multiple';

        if (isset(Request::old('attributes')[$this->attribute->id])) {
            $attrs['value'] = Request::old('attributes')[$this->attribute->id];
        } else {
            $attrs['value'] = $this->getValues();
        }

        $attrs['type'] = 'text';
        $field['name'] = "attributes[{$this->attribute->id}]";
        $attrs['dotted_name'] = "attributes.{$this->attribute->id}";
        $field['type'] = 'text';
        $field['attrs'] = $attrs;

        $fields[] = $field;

        return $fields;
    }

    public function getDefaultValues()
    {
        $values = [];
        $languages = get_current_company_languages();
        foreach ($languages as $language) {
            $values[$language->code] = [];
        }
        return $values;
    }

    public function formatValues(array $values, array $languages)
    {
        $data = [];
        /** @var Language $language */
        foreach ($languages as $language) {
            $data[$language->code] = $values;
        }
        return $data;
    }
}

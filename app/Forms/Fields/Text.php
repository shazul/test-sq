<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\Language;

class Text extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        foreach ($this->getValues() as $code => $value) {
            $language_code = language_code_trans($code);
            $attrs['label'] = $this->attribute->label->values[$this->languageCode] . ' (' . $language_code . ')';
            $attrs['value'] = $value;
            $field['name'] = "attributes[{$this->attribute->id}][{$code}]";
            $field['type'] = 'text';
            $field['attrs'] = $attrs;
            $fields[] = $field;
        }

        return $fields;
    }

    public function getDefaultValues()
    {
        $values = [];
        $languages = get_current_company_languages();
        foreach ($languages as $language) {
            $values[$language->code] = '';
        }
        return $values;
    }

    public function formatValues(array $values, array $languages)
    {
        $data = [];
        /** @var Language $language */
        foreach ($languages as $language) {
            $data[$language->code] = $values[0];
        }
        return $data;
    }
}

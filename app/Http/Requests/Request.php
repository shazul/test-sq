<?php

namespace Pimeo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Contain all validation rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Add a new rules or edit an existing one
     *
     * @param String $field_name
     * @param String $validation_rule
     */
    protected function addRules($field_name, $validation_rule)
    {
        $this->rules[$field_name] = $validation_rule;
    }

// SEE attributeRules for uses.
//    /**
//     * @param Mixed[] $array
//     * @return bool
//     */
//    public function isMultidimensional(array $array)
//    {
//        return (count($array) != count($array, 1));
//    }
//
//    /**
//     * Will create new validator for each values
//     *
//     * @param Int $attr_id
//     * @param Mixed[] $attr_values
//     */
//    protected function addValidationToMultidimensionalArray($attr_id, $attr_values)
//    {
//        $base_key = $attr_id;
//        if ($this->checkIfFileAttribute($attr_values)) {
//            foreach ($attr_values as $lang_code => $image) {
//                if (isset($image[0]['empty_file'])) {
//                    $this->addRules($base_key . ".{$lang_code}.image", 'required');
//                }
//            }
//        } else {
//            for ($key = count($attr_values['fr']) - 1; $key >= 0; $key--) {
//                $this->addRules($base_key . '.fr.' . $key, 'required');
//                $this->addRules($base_key . '.en.' . $key, 'required');
//            }
//        }
//    }
//
//    /**
//     * Check if the current attribute is a file or files input.
//     * If the current field have a least one of these ( full_name or empty_file ), it's a file or files
//     *
//     * @param Mixed[] $attr_values
//     * @return bool
//     */
//    protected function checkIfFileAttribute($attr_values)
//    {
//        return isset($attr_values['fr'][0]['full_name']) || isset($attr_values['fr'][0]['empty_file']);
//    }

    protected function attributeRules()
    {
        $attributes = $this->get('attributes', []);

        foreach ($attributes as $attr_id => $attr_values) {
            if (is_array($attr_values) && array_has($attr_values, get_default_language_code())) {
//                Pour l'instant le type "image_choice_multiple" n'est jamais utilisé.
//                if ($this->isMultidimensional($attr_values)) {
//                    $this->addValidationToMultidimensionalArray('attributes.' . $attr_id, $attr_values);
//                } else {
                    $languages = get_current_company_languages();
                foreach ($languages as $language) {
                    $this->addRules('attributes.' . $attr_id . '.' . $language->code, 'required');
                }
            } elseif (is_array($attr_values)) {
                foreach ($attr_values as $key => $value) {
                    $validation = 'required';

                    if (in_array($key, ['metric', 'imperial'])) {
                        $validation .= '|numeric';
                    }

                    $this->addRules('attributes.' . $attr_id . '.' . $key, $validation);
                }
            } else {
                $this->addRules('attributes.' . $attr_id, 'required|not_in_custom:novalue');
            }
        }

        return $this->rules;
    }

    /**
     * @return array
     */
    protected function setAddAttributeRules()
    {
        return [
            'link_attribute.*' => 'required',
        ];
    }
}

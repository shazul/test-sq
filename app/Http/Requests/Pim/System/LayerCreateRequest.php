<?php

namespace Pimeo\Http\Requests\Pim\System;

use Pimeo\Http\Requests\Request;

class LayerCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit', $this->route('system'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::input('type_layer') == 'parent') {
            $rules = [
                'parent_product' => 'required',
            ];
        } else {
            $languages = get_current_company_languages();

            $rules = [];
            foreach ($languages as $language) {
                $rules['nom_' . $language->code] = 'required';
                $rules['fonction_' . $language->code] = 'required';
            }
        }

        return $rules;
    }
}

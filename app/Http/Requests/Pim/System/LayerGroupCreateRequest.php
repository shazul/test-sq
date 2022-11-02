<?php

namespace Pimeo\Http\Requests\Pim\System;

use Pimeo\Http\Requests\Request;

class LayerGroupCreateRequest extends Request
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
        $languages = $languages = get_current_company_languages();
        $rules = [];

        foreach ($languages as $language) {
            $rules['name.' . $language->code] = 'required';
        }

        return $rules;
    }
}

<?php

namespace Pimeo\Http\Requests\Pim\System\LayerGroup;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\LayerGroup;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', LayerGroup::class);
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

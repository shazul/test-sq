<?php

namespace Pimeo\Http\Requests\Pim\System;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\System;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', System::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->attributeRules(), [
            'buildingComponents' => 'required|not_in_custom:novalue'
        ]);
    }

    public function messages()
    {
        return [
            'buildingComponents.*' => trans('building-component.form.errors.required')
        ];
    }
}

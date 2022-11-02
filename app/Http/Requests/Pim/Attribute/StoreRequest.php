<?php

namespace Pimeo\Http\Requests\Pim\Attribute;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\Attribute;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Attribute::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label.*'            => 'required',
            'attribute_type_id'  => 'required',
            'natures'            => 'required_if:has_nature,1',
            'buildingComponents' => 'required_if:has_building_component,1',
            'choice.*.*'         => 'required_if:attribute_type_id,choice,choice_multiple,choice_checkbox_multiple',
            'choice.*.*.name'    => 'required_if:attribute_type_id,choice_multiple_image_no_display',
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'choice.*.*'                     => 'All fields of the choices must be filled.',
            'buildingComponents.required_if' => trans('building-component.form.errors.required')
        ];
    }
}

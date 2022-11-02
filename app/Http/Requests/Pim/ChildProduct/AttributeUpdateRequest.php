<?php

namespace Pimeo\Http\Requests\Pim\ChildProduct;

use Pimeo\Http\Requests\Request;

class AttributeUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit', $this->route('child_product'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->setAddAttributeRules();
    }
}

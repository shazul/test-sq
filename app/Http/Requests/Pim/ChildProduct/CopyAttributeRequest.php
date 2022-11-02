<?php

namespace Pimeo\Http\Requests\Pim\ChildProduct;

use Pimeo\Http\Requests\Request;

class CopyAttributeRequest extends Request
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
        return [
            'child_product_id' => 'required|exists:child_products,id',
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'child_product_id' => trans('child-product.edit.child-product-missing'),
        ];
    }
}

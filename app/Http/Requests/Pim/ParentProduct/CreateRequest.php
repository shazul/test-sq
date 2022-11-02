<?php

namespace Pimeo\Http\Requests\Pim\ParentProduct;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\ParentProduct;

class CreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', ParentProduct::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

<?php

namespace Pimeo\Http\Requests\Pim\Company;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\Company;

class CreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Company::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'required|max:255',
            'languages'  => "required|array|min:1|languages_contains_english",
        ];
    }
}

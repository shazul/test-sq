<?php

namespace Pimeo\Http\Requests\Pim\TechnicalBulletin;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\TechnicalBulletin;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', TechnicalBulletin::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->attributeRules();
    }
}

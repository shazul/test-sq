<?php

namespace Pimeo\Http\Requests\Pim\System\Layer;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\Layer;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Layer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['parent_product'] = 'required_if:type_layer,parent';
        $languages = get_current_company_languages();
        foreach ($languages as $language) {
            $rules['nom_' . $language->code] = 'required_unless:type_layer,parent';
            $rules['fonction_' . $language->code] = 'required_unless:type_layer,parent';
        }
        return $rules;
    }
}

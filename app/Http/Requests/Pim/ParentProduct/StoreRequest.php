<?php

namespace Pimeo\Http\Requests\Pim\ParentProduct;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\Attribute;
use Pimeo\Models\ParentProduct;

class StoreRequest extends Request
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
        if ($this->has('publish')) {
            return $this->attributeRules();
        }

        // Pour la sauvegarde de brouillon seul le nom du produit est required
        $productNameAttributeID = Attribute::whereCompanyId(current_company()->id)->whereName('name')
            ->whereModelType('parent_product')->first()->id;

        $languages = $languages = get_current_company_languages();
        $rules = [];

        foreach ($languages as $language) {
            $rules['attributes.' . $productNameAttributeID . '.' . $language->code] = 'required';
        }

        return $rules;
    }
}

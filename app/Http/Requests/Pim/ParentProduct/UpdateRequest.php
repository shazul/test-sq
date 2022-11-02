<?php

namespace Pimeo\Http\Requests\Pim\ParentProduct;

use Pimeo\Http\Requests\Request;
use Pimeo\Models\Attribute;
use Pimeo\Repositories\AttributeRepository;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit', $this->route('parent_product'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(AttributeRepository $attributesRepo)
    {
        if ($this->has('publish')) {
            $attributes = $this->get('attributes');

            $required_attributes = $attributesRepo->allRequired('parent_product')->pluck('id');

            // Garder seulement les attributs obligatoires pour les valider
            foreach ($attributes as $noAttribute => $attr) {
                if (!$required_attributes->contains($noAttribute)) {
                    unset($attributes[$noAttribute]);
                }
            }

            $this->attributes->set('attributes', $attributes);
            return $this->attributeRules();
        }

        // Pour la sauvegarde de brouillon seul le nom du produit est required
        $productNameAttributeID = Attribute::whereCompanyId(current_company()->id)->whereName('name')
            ->whereModelType('parent_product')->first()->id;

        $languages = get_current_company_languages();
        $rules = [];

        foreach ($languages as $language) {
            $rules['attributes.' . $productNameAttributeID . '.' . $language->code] = 'required';
        }

        return $rules;
    }
}

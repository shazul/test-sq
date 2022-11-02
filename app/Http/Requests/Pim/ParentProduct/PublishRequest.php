<?php

namespace Pimeo\Http\Requests\Pim\ParentProduct;

use Pimeo\Http\Requests\Request;
use Pimeo\Repositories\AttributeRepository;

class PublishRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(AttributeRepository $attributesRepo)
    {
        $attributes = $this->get('attributes');

        $required_attributes = $attributesRepo->allRequired('parent_product')->pluck('id');

        // Garder seulement les attributs obligatoires pour les valider
        foreach ($attributes as $noAttribute => $attr) {
            if (!$required_attributes->contains($noAttribute)) {
                unset($attributes[$noAttribute]);
            }
        }

        $this->attributes->set('attributes', $attributes);

        $languages = get_current_company_languages();
        $promotion_attributes = $attributesRepo->findPromotions();
        foreach ($promotion_attributes as $attribute) {
            if (request()->has('attributes.' . $attribute->id)) {
                foreach ($languages as $language) {
                    $file_path = 'attributes.' . $attribute->id . '.' . $language->code . '.file.0.full_name';
                    $link_path = 'attributes.' . $attribute->id . '.' . $language->code . '.link';
                    $this->addRules($file_path, 'required_with:' . $link_path);
                    $this->addRules($link_path, 'required_with:' . $file_path);
                }
            }
        }

        return $this->attributeRules();
    }
}

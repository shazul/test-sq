<?php

namespace Pimeo\Http\Requests\Pim\ChildProduct;

use Pimeo\Http\Requests\Request;
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
        return $this->user()->can('edit', $this->route('child_product'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param AttributeRepository $attributesRepo
     * @return array
     */
    public function rules(AttributeRepository $attributesRepo)
    {
        $attributes = $this->get('attributes');

        $required_attributes = $attributesRepo->allRequired('child_product')->pluck('id');

        // Garder seulement les attributs obligatoires pour les valider
        foreach ($attributes as $noAttribute => $attr) {
            if (!$required_attributes->contains($noAttribute)) {
                unset($attributes[$noAttribute]);
            }
        }

        $this->attributes->set('attributes', $attributes);

        return $this->attributeRules();
    }
}

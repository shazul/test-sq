<?php

namespace Pimeo\Repositories;

use Illuminate\Support\Facades\Auth;
use Pimeo\Models\Attributable;
use Pimeo\Models\Attribute;
use Pimeo\Models\Nature;
use Pimeo\Models\System;

class AttributeRepository
{
    /**
     * Get all instance of Attribute.
     *
     * @param  string $model_type
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Attribute[]
     */
    public function all($model_type)
    {
        return Attribute::with(['label', 'type'])
                        ->whereCompanyIdAndModelType(current_company()->id, $model_type)
                        ->get();
    }

    /**
     * Find an instance of Attribute with the given ID.
     *
     * @param  int $id
     *
     * @return \Pimeo\Models\Attribute
     */
    public function find($id)
    {
        return Attribute::whereCompanyId(current_company()->id)->find($id);
    }

    /**
     * Create a new instance of Attribute.
     *
     * @param  array $attributes
     *
     * @return \Pimeo\Models\Attribute
     */
    public function create(array $attributes = [])
    {
        $count = Attribute::where('name', 'LIKE', $attributes['name'] . '%')->count();

        $attributes['name'] .= '-' . $count;

        return Attribute::create($attributes);
    }

    /**
     * Update the Attribute with the given attributes.
     *
     * @param  int $id
     * @param  array $attributes
     *
     * @return bool|int
     */
    public function update($id, array $attributes = [])
    {
        return Attribute::find($id)->update($attributes);
    }

    /**
     * Delete an entry with the given ID.
     *
     * @param  int $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return Attribute::find($id)->delete();
    }

    /**
     * Get all instance of Attribute that are required.
     *
     * @param  string $model_type
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Attribute[]
     */
    public function allRequired($model_type)
    {
        return Attribute::with(['label', 'type'])
                        ->where('company_id', current_company()->id)
                        ->where('model_type', $model_type)
                        ->where('is_min_requirement', true)
                        ->get();
    }

    /**
     * Get all instance of Attribute.
     *
     * @param $productNature
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Pimeo\Models\Attribute[]
     */
    public function allWhereNature($productNature, $model_type)
    {
        return Attribute::where('model_type', $model_type)->whereCompanyId(current_company()->id)
                        ->whereHas('natures', function ($query) use ($productNature) {
                            $query->whereId($productNature);
                        })
                        ->with('label')
                        ->get();
    }

    public function allNotLinkedToProduct($product, $product_nature, $model_type)
    {
        if ($product_nature != null) {
            $attributesList = $this->allWhereNature($product_nature, $model_type);
        } else {
            $attributesList = $this->all($model_type);
        }

        $attributes = [];

        $language_code = Auth::user()->getLanguageCode();

        $productAttributesIDs = $product->linkAttributes->pluck('attribute_id');
        // Les attributs required sont deja tjs affichés dans les formulaires
        $requiredAttributesIDs = $this->allRequired($model_type)->pluck('id');

        foreach ($attributesList as $attribute) {
            // Ne pas afficher les attributs déjà associés au produit
            $alreadyInProduct = $productAttributesIDs->contains($attribute->id);
            $alreadyShown     = $requiredAttributesIDs->contains($attribute->id);
            if (! $alreadyInProduct && ! $alreadyShown) {
                $attributes[] = [
                    'id'   => $attribute->id,
                    'text' => $attribute->label->values[$language_code],
                ];
            }
        }

        return $attributes;
    }

    public function allNotLinkedToSystem(System $system)
    {
        $attributesList = Attribute::where('model_type', Attribute::MODEL_TYPE_SYSTEM)
                                   ->whereCompanyId(current_company()->id)
                                   ->whereHas('buildingComponents', function ($query) use ($system) {
                                       $query->whereIn('id', $system->building_component_ids);
                                   })
                                   ->with('label')
                                   ->get();

        $attributes = [];

        $language_code = Auth::user()->getLanguageCode();

        $productAttributesIDs  = $system->linkAttributes->pluck('attribute_id');
        $requiredAttributesIDs = $this->allRequired(Attribute::MODEL_TYPE_SYSTEM)->pluck('id');

        foreach ($attributesList as $attribute) {
            // Ne pas afficher les attributs déjà associés au produit
            $alreadyInSystem = $productAttributesIDs->contains($attribute->id);
            $alreadyShown    = $requiredAttributesIDs->contains($attribute->id);
            if (! $alreadyInSystem && ! $alreadyShown) {
                $attributes[] = [
                    'id'   => $attribute->id,
                    'text' => $attribute->label->values[$language_code],
                ];
            }
        }

        return $attributes;
    }

    public function getAllNatureIds()
    {
        $nature = Nature::all();
        return $nature->pluck('id');
    }

    public function findPromotions()
    {
        return Attribute::whereName('promotion')->get();
    }
}

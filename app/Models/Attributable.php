<?php

namespace Pimeo\Models;

use Illuminate\Support\Collection;

abstract class Attributable extends Model
{
    protected $buildingComponentAttributeName = null;
    protected $functionAttributeName = null;

    abstract public function getName($languageCode);

    abstract public function isIndexable();

    /**
     * @param $languageCode
     * @param $companyId
     *
     * @return null|Collection
     */
    public function getBuildingComponents($languageCode, $companyId)
    {
        return $this->extractValueFromLinkAttribute(
            $this->buildingComponentAttributeName,
            $languageCode,
            $companyId
        );
    }

    public function getFunction($languageCode, $companyId)
    {
        return $this->extractValueFromLinkAttribute(
            $this->functionAttributeName,
            $languageCode,
            $companyId
        );
    }

    private function extractValueFromLinkAttribute($attributeName, $languageCode, $companyId)
    {
        if (is_null($attributeName)) {
            return null;
        }

        $attribute = $this->getAttributeByNameAndModelType($attributeName, $companyId);

        $linkAttribute = LinkAttribute::where('attributable_id', $this->id)
            ->where('attribute_id', $attribute->id)->first();

        $values = null;
        if (isset($linkAttribute->values['keys'])) {
            $attributeValues = collect($attribute->value->values[$languageCode]);
            $values = $attributeValues->only($linkAttribute->values['keys']);
        }

        return $values;
    }

    private function getAttributeByNameAndModelType($attributeName, $companyId)
    {
        $attributeQuery = Attribute::where('name', $attributeName)
            ->where('company_id', $companyId);

        if ($this->table == 'parent_products') {
            $attributeQuery->where('model_type', 'parent_product');
        }

        return $attributeQuery->first();
    }
}

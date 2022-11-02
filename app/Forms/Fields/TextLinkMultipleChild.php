<?php

namespace Pimeo\Forms\Fields;

use Illuminate\Support\Facades\DB;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;

class TextLinkMultipleChild extends Field
{
    protected $company;

    public function getFields()
    {
        $attrs = $childProducts = [];

        $products = DB::table('child_products')
            ->select(['child_products.id', 'values'])
            ->join('link_attributes', 'link_attributes.attributable_id', '=', 'child_products.id')
            ->join('attributes AS a', 'a.id', '=', 'link_attributes.attribute_id')
            ->where('link_attributes.attributable_type', 'Pimeo\Models\ChildProduct')
            ->where('a.name', 'child_product_name')
            ->where('status', '!=', AttributableModelStatus::FRESH_STATUS)
            ->get();

        foreach ($products as $product) {
            $childProducts[$product->id] = json_decode($product->values, true)[$this->languageCode];
        }

        $attrs['choices'] = $childProducts;
        $attrs['label'] = trans('child-product.index.title');
        $attrs['template'] = 'vendor.laravel-form-builder.select2';
        $attrs['attr']['class'] = 'form-control multi-select2';
        $attrs['multiple'] = true;
        $attrs['selected'] = $this->getValues();

        $field['name'] = "child_products";
        $field['type'] = 'choice';
        $field['attrs'] = $attrs;
        return $field;
    }

    public function formToValues($form)
    {
        return implode(",", $form);
    }

    public function getDefaultValues()
    {
        return [];
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getValues()
    {
        if ($this->getProduct() == null) {
            return $this->getDefaultValues();
        } else {
            $childProducts = ChildProduct::whereParentProductId($this->getProduct()->id)->get();
            return $childProducts->pluck('id')->toArray();
        }
    }
}

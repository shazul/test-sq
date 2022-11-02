<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Repositories\ParentProductRepository;

class ParentChoice extends Field
{
    /** @var ParentProductRepository */
    public $parentRepo;
    protected $company;
    private $product;

    public function __construct()
    {
        parent::__construct();
        $this->parentRepo = app(ParentProductRepository::class);
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getFields()
    {
        $parentProductsList = $this->parentRepo->allForListing();
        $parentProducts = [];
        foreach ($parentProductsList as $product_id => $product) {
            $parentProducts[$product_id] = $product['name'][$this->languageCode];
        }

        $attrs['choices'] = $parentProducts;
        $attrs['label'] = trans('parent-product.form.input_label');
        $attrs['attr']['class'] = 'form-control select-2';
        if (isset($this->product->parent_product_id)) {
            $attrs['selected'] = $this->product->parent_product_id;
        }

        $field['name'] = 'parent_product';
        $field['type'] = 'choice';
        $field['attrs'] = $attrs;

        return $field;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function getDefaultValues()
    {
        return [];
    }
}

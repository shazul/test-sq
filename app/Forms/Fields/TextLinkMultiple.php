<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\Language;
use Pimeo\Repositories\ParentProductRepository;

class TextLinkMultiple extends Field
{
    public $parents;
    protected $company;

    /**
     * @param ParentProductRepository $parents
     */
    public function __construct(ParentProductRepository $parents)
    {
        parent::__construct();
        $this->parents = $parents;
    }

    public function getFields()
    {
        $fields = $attrs = $parentProducts = [];

        $products = $this->parents->allInCompany(current_company()->id);

        foreach ($products as $product) {
            $parentProducts[$product->id] = $product->linkAttributes->first()->values[$this->languageCode];
        }

        $attrs['choices'] = $parentProducts;
        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['template'] = 'vendor.laravel-form-builder.select2';
        $attrs['attr']['class'] = 'multi-select2';
        $attrs['attr']['style'] = 'width:100%';
        $attrs['multiple'] = true;
        $attrs['selected'] = !empty($this->values) ? explode(",", $this->values) : [];

        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'choice';
        $field['attrs'] = $attrs;
        $fields[] = $field;
        return $fields;
    }

    public function formToValues($form)
    {
        if ($form == 'novalue') {
            $form = $this->getDefaultValues();
        }

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

    public function formatValues(array $values, array $languages)
    {
        $data = $this->getDefaultValues();
        /** @var Language $language */
        foreach ($languages as $language) {
            $data[$language->code] = $values[0];
        }
        return $data;
    }
}

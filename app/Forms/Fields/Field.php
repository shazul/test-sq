<?php

namespace Pimeo\Forms\Fields;

use Illuminate\Support\Facades\Auth;
use Pimeo\Models\User;

abstract class Field
{
    protected $attribute;

    protected $values = null;

    private $product;

    protected $languageCode;

    abstract public function getFields();

    abstract public function getDefaultValues();

    public function __construct()
    {
        $this->setLanguageCode();
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function formToValues($form)
    {
        return $form;
    }

    public function setValues($values = null)
    {
        $this->values = is_null($values) ? $this->getDefaultValues() : $values;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    private function setLanguageCode()
    {
        $this->languageCode = Auth::user()->getLanguageCode();
    }

    public function formatValues(array $values, array $language)
    {
        return [];
    }
}

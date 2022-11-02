<?php

namespace Pimeo\Forms;

use Kris\LaravelFormBuilder\Form;

class ParentProductShowForm extends Form
{
    public function buildForm()
    {
        $attributes = $this->getData('attributes')
            ->load('attribute', 'attribute.type', 'attribute.label', 'attribute.value');

        foreach ($attributes as $attribute) {
            $field_code = $attribute->attribute->type->code;
            $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
            $fieldType = app($fieldClass);

            $fieldType->setAttribute($attribute->attribute);

            $values = null;
            if (!is_null($attribute->values)) {
                $values = $attribute->values;
            }

            $fieldType->setValues($values);

            $fieldType->setProduct($this->getData('product'));

            if (method_exists($fieldType, 'setCompany')) {
                $fieldType->setCompany(auth()->user()->activeCompany);
            }

            foreach ($fieldType->getFields() as $field) {
                $this->add($field['name'], $field['type'], $field['attrs']);
            }
        }

        // add Media
        $field_code = 'ChoiceMedia';
        $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
        $fieldType = app($fieldClass);
        $fieldType->setValues(array_pluck($this->getData('medias')->toArray(), 'media_id'));
        $field = $fieldType->getFields();
        $this->add($field['name'], $field['type'], $field['attrs']);

        // add Child products
        $field_code = 'TextLinkMultipleChild';
        $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
        $fieldType = app($fieldClass);
        $fieldType->setCompany(auth()->user()->companies->first()->id);
        $fieldType->setProduct($this->getData('product'));
        $field = $fieldType->getFields();
        $this->add($field['name'], $field['type'], $field['attrs']);

        $this->add(
            'back',
            'static',
            [
                'tag'    => 'a',
                'label'  => false,
                'value'  => '<i class="fa fa-fw fa-reply"></i>' . trans('parent-product.show.back'),
                'attr'   => [
                    'href'  => route('parent-product.index'),
                    'class' => 'btn btn-info',
                ],
            ]
        );
    }
}

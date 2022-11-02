<?php

namespace Pimeo\Forms;

use Kris\LaravelFormBuilder\Form;
use Pimeo\Models\Attribute;

class TechnicalBulletinShowForm extends Form
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
                $values = $attribute->values->values;
            }

            $fieldType->setValues($values);

            $fieldType->setProduct($this->getData(Attribute::MODEL_TYPE_TECHNICAL_BULLETIN));

            if (method_exists($fieldType, 'setCompany')) {
                $fieldType->setCompany(auth()->user()->companies->first()->id);
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

        $this->add('back', 'static', [
            'label' => false,
            'tag'   => 'a',
            'value' => '<i class="fa fa-fw fa-reply"></i>' . trans('technical-bulletin.show.back'),
            'attr'  => [
                'href'  => route('technical-bulletin.index'),
                'class' => 'btn btn-info',
            ],
        ]);
    }
}

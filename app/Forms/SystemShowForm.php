<?php

namespace Pimeo\Forms;

use Kris\LaravelFormBuilder\Form;

class SystemShowForm extends BaseForm
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

            $fieldType->setProduct($this->getData('system'));

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

        $this->addBuildingComponentsField($this->getData('building_component_ids'));

        $this->add(
            'back',
            'static',
            [
                'tag'    => 'a',
                'label'  => false,
                'value'  => '<i class="fa fa-fw fa-reply"></i>' . trans('system.show.back'),
                'attr'   => [
                    'href'  => route('system.index'),
                    'class' => 'btn btn-info',
                ],
            ]
        );
    }
}

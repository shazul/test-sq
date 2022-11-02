<?php

namespace Pimeo\Forms;

class SpecificationCreateForm extends BaseForm
{
    public function buildForm()
    {
        foreach ($this->getData('attributes') as $key => $attribute) {
            $field_code = $attribute->type->code;
            $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
            $fieldType = app($fieldClass);

            $fieldType->setAttribute($attribute);
            $fieldType->setValues();

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
        $field = $fieldType->getFields();
        $this->add($field['name'], $field['type'], $field['attrs']);

        $this->addSaveField('specification.create.save');
    }
}

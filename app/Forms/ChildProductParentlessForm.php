<?php

namespace Pimeo\Forms;

class ChildProductParentlessForm extends BaseForm
{
    public function buildForm()
    {
        $field_code = 'ParentChoice';
        $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
        $fieldType = app($fieldClass);
        $fieldType->setProduct($this->getData('product'));
        $field = $fieldType->getFields();
        $this->add($field['name'], $field['type'], $field['attrs']);

        $this->addSaveField();
    }
}

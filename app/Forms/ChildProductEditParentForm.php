<?php

namespace Pimeo\Forms;

class ChildProductEditParentForm extends BaseForm
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

        $this->add(
            'remove-parent',
            'submit',
            [
                'label'  => trans('child-product.edit-parent.remove-parent'),
                'attr'   => [
                    'id'       => 'remove-parent',
                    'name'     => 'remove-parent',
                    'value'    => 'remove-parent',
                    'class'    => 'btn btn-danger',
                ],
            ]
        );
    }
}

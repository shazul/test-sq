<?php

namespace Pimeo\Forms;

class ParentProductCreateForm extends BaseForm
{
    public function buildForm()
    {
        foreach ($this->getData('attributes') as $attribute) {
            $fieldType = $this->createFieldForAttribute($attribute);

            $this->addFieldsForFieldType($fieldType);
        }

        $this->addNatureField();
        $this->addMediaField();
        $this->addParentNewProductField();
        $this->addParentStarProductField();
        $this->addSaveField('child-product.edit.draft');

        $this->add(
            'publish',
            'submit',
            [
                'label'  => trans('child-product.edit.publish'),
                'attr'   => [
                    'id'       => 'publish',
                    'name'     => 'publish',
                    'value'    => 'publish',
                    'class'    => 'btn btn-success',
                    'disabled' => 'disabled',
                ],
            ]
        );
    }
}

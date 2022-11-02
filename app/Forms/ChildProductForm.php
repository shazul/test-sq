<?php

namespace Pimeo\Forms;

use Pimeo\Models\AttributableModelStatus;

class ChildProductForm extends BaseForm
{
    public function buildForm()
    {
        $linkAttributes = $this->getData('attributes')
            ->load('attribute', 'attribute.type', 'attribute.label', 'attribute.value');
        $childProduct = $this->getData('product');

        foreach ($linkAttributes as $linkAttribute) {
            $fieldType = $this->createField($linkAttribute);

            $fieldType->setProduct($childProduct);

            $this->addFieldsForFieldType($fieldType);
        }

        $this->addMissingRequiredAttributes($linkAttributes);
        $this->addMediaField();

        if ($childProduct->status == AttributableModelStatus::DRAFT_STATUS) {
            $this->addSaveField();
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
        } else { //PUBLISHED
            $this->add(
                'publish',
                'submit',
                [
                    'label'  => trans('parent-product.edit.save'),
                    'attr'   => [
                        'id'       => 'publish',
                        'name'     => 'publish',
                        'value'    => 'publish',
                        'class'    => 'btn btn-success',
                        'disabled' => 'disabled',
                    ],
                ]
            );
            $this->addSaveField('child-product.edit.draft');
        }
    }
}

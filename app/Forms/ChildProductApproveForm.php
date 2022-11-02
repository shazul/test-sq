<?php

namespace Pimeo\Forms;

use Pimeo\Models\LinkAttribute;

class ChildProductApproveForm extends BaseForm
{
    public function buildForm()
    {
        $linkAttributes = $this->getData('attributes')
            ->load('attribute', 'attribute.type', 'attribute.label', 'attribute.value');

        /** @var LinkAttribute $linkAttribute */
        foreach ($linkAttributes as $linkAttribute) {
            $fieldType = $this->createField($linkAttribute);

            $fieldType->setProduct($this->getData('product'));

            $this->addFieldsForFieldType($fieldType);
        }

        $this->add(
            'approve',
            'submit',
            [
                'label'  => trans('child-product.approve.approve'),
                'attr'   => [
                    'name'  => 'approve',
                    'value' => 'approve',
                    'class' => 'btn btn-success',
                ],
            ]
        );

        $this->add(
            'delete',
            'submit',
            [
                'label'  => trans('child-product.approve.delete'),
                'attr'   => [
                    'name'  => 'delete',
                    'value' => 'delete',
                    'class' => 'btn btn-danger',
                ],
            ]
        );
    }
}

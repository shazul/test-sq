<?php

namespace Pimeo\Forms;

class DetailEditForm extends BaseForm
{
    public function buildForm()
    {
        $linkAttributes = $this->getData('attributes')
            ->load('attribute', 'attribute.type', 'attribute.label', 'attribute.value');

        foreach ($linkAttributes as $linkAttribute) {
            $fieldType = $this->createField($linkAttribute);

            $fieldType->setProduct($this->getData('detail'));

            $this->addFieldsForFieldType($fieldType);
        }

        $this->addMissingRequiredAttributes($linkAttributes);
        $this->addMediaField();
        $this->addSaveField();
    }
}

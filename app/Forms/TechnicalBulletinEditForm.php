<?php

namespace Pimeo\Forms;

use Pimeo\Models\Attribute;

class TechnicalBulletinEditForm extends BaseForm
{
    public function buildForm()
    {
        $linkAttributes = $this->getData('attributes')
            ->load('attribute', 'attribute.type', 'attribute.label', 'attribute.value');

        foreach ($linkAttributes as $linkAttribute) {
            $fieldType = $this->createField($linkAttribute);

            $fieldType->setProduct($this->getData(Attribute::MODEL_TYPE_TECHNICAL_BULLETIN));

            $this->addFieldsForFieldType($fieldType);
        }

        $this->addMissingRequiredAttributes($linkAttributes);
        $this->addMediaField();
        $this->addSaveField();
    }
}

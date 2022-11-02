<?php

namespace Pimeo\Forms;

class SystemEditForm extends BaseForm
{
    public function buildForm()
    {
        $linkAttributes = $this->getData('attributes')
            ->load('attribute', 'attribute.type', 'attribute.label', 'attribute.value');

        foreach ($linkAttributes as $linkAttribute) {
            $fieldType = $this->createField($linkAttribute);

            $fieldType->setProduct($this->getData('system'));

            $this->addFieldsForFieldType($fieldType);
        }

        $this->addMissingRequiredAttributes($linkAttributes);
        $this->addMediaField();
        $this->addBuildingComponentsField($this->getData('building_component_ids'));
        $this->addSaveField();
    }
}

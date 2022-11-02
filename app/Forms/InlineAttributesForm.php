<?php

namespace Pimeo\Forms;

class InlineAttributesForm extends BaseForm
{
    public function buildForm()
    {
        foreach ($this->getData('linkAttributes') as $linkAttribute) {
            $fieldType = $this->createField($linkAttribute);

            $this->addFieldsForFieldType($fieldType);
        }
    }
}

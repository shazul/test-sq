<?php

namespace Pimeo\Forms;

class ChildProductCreateForm extends BaseForm
{
    public function buildForm()
    {
        foreach ($this->getData('attributes') as $attribute) {
            $fieldType = $this->createFieldForAttribute($attribute);

            $this->addFieldsForFieldType($fieldType);
        }

        $this->addMediaField();
        $this->addSaveField('child-product.edit.draft');
    }
}

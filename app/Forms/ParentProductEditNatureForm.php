<?php

namespace Pimeo\Forms;

class ParentProductEditNatureForm extends BaseForm
{
    public function buildForm()
    {
        $parentProduct = $this->getData('product');

        $this->addNatureField($parentProduct->nature_id);

        $this->addSaveField();
    }
}

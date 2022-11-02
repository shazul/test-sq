<?php

namespace Pimeo\Forms;

use Pimeo\Forms\Fields\ParentChoice;
use Pimeo\Models\Language;

class SystemLayerCreateForm extends BaseForm
{
    public function buildForm()
    {
        $languages = get_current_company_languages();
        $type_layer = 'parent';
        $fields = $this->generateDefaultValues($languages);
        $position = 1;

        // Modification
        if ($this->getData('layer') != null) {
            $layer = $this->getData('layer');

            if ($layer->parent_product_id == null) {
                $type_layer = 'substrat';
            }

            $position = $layer->position;
            foreach ($languages as $language) {
                $fields[$language->id]['name']['value'] = $layer['product_name'][$language->code];
                $fields[$language->id]['function']['value'] = $layer['product_function'][$language->code];
            }
        }

        $this->add('type_layer', 'choice', [
            'choices'  => ['parent' => trans('system.type.parent'), 'substrat' => trans('system.type.substrat')],
            'selected' => [$type_layer],
            'expanded' => true,
            'multiple' => false,
            'attrs'    => ['class' => 'radio'],
        ]);

        $fieldType = new ParentChoice;
        $fieldType->setProduct($this->getData('layer'));
        $field = $fieldType->getFields();
        $field['attrs']['attr']['class'] = 'form-control select-2';
        $field['attrs']['attr']['style'] = 'width: 100%';
        $this->add($field['name'], $field['type'], $field['attrs']);

        foreach ($fields as $field) {
            $this->add($field['name']['key'], $field['type'], ['value' => $field['name']['value']]);
            $this->add($field['function']['key'], $field['type'], ['value' => $field['function']['value']]);
        }

        $this->add('position', 'number', ['value' => $position]);

        $this->addSaveField('system.layer.edit.save');
    }

    /**
     * @param Language[] $languages
     * @return mixed[]
     */
    private function generateDefaultValues($languages)
    {
        $fields = [];
        foreach ($languages as $language) {
            $fields[$language->id] = [
                'name' => [
                    'key'   => 'nom_' . $language->code,
                    'value' => '',
                ],
                'function' => [
                    'key'   => 'fonction_' . $language->code,
                    'value' => '',
                ],
                'type' => 'text',
            ];
        }

        return $fields;
    }
}

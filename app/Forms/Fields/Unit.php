<?php

namespace Pimeo\Forms\Fields;

class Unit extends Field
{
    public function getFields()
    {
        $attrs = [];

        $attrs['label'] = $this->attribute->label->values[$this->languageCode];
        $attrs['template'] = 'vendor.laravel-form-builder.unit';
        $attrs['specs'] = $this->attribute->type->specs;
        $attrs['values'] = $this->getValues();
        $attrs['id'] = $this->attribute->id;
        $attrs['operation'] = $this->getOperation();

        $field['name'] = "attributes[{$this->attribute->id}]";
        $field['type'] = 'text';

        //set default preferred
        if (!isset($attrs['values']['preferred'])) {
            $attrs['values']['preferred'] = 'metric';
        }
        $field['attrs'] = $attrs;
        $fields[] = $field;

        return $fields;
    }

    public function getOperation()
    {
        return '*';
    }

    public function formToValues($form)
    {
        return [
            'metric'    => $form['metric'],
            'imperial'  => $form['imperial'],
            'preferred' => $form['preferred'],
        ];
    }

    public function getDefaultValues()
    {
        return [
            'metric'    => '',
            'imperial'  => '',
            'preferred' => '',
        ];
    }
}

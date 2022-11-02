<?php

namespace Pimeo\Forms\Fields;

class Files extends FieldFile
{
    public function getFields()
    {
        $fields = parent::getFields();
        foreach ($fields as &$field) {
            $field['attrs']['type'] = 'file';
        }
        return $fields;
    }
}

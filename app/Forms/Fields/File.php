<?php

namespace Pimeo\Forms\Fields;

class File extends FieldFile
{
    public function getFields()
    {
        $fields = parent::getFields();
        foreach ($fields as &$field) {
            $field['attrs']['dropzone_options'] = [
                'maxFiles' => 1,
            ];

            $field['attrs']['type'] = 'file';
        }
        return $fields;
    }
}

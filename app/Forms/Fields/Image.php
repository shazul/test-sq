<?php

namespace Pimeo\Forms\Fields;

class Image extends FieldFile
{
    public function getFields()
    {
        $fields = parent::getFields();
        foreach ($fields as &$field) {
            $field['attrs']['dropzone_options'] = [
                'acceptedFiles' => 'image/*',
                'maxFiles'      => 1,
            ];
            $field['attrs']['type'] = "image";
        }
        return $fields;
    }
}

<?php

namespace Pimeo\Forms\Fields;

use Pimeo\Models\Media;

class ChoiceMedia extends Field
{
    public function getFields()
    {
        $attrs['choices'] = array_pluck(Media::all()->toArray(), 'name', 'id');
        $attrs['label'] = trans('views.medias');
        $attrs['multiple'] = true;
        $attrs['expanded'] = true;
        $attrs['selected'] = isset($this->values) ? array_values($this->values) : [];
        $field['name'] = 'media';
        $field['type'] = 'choice';
        $field['attrs'] = $attrs;

        return $field;
    }

    public function getDefaultValues()
    {
        return [];
    }
}

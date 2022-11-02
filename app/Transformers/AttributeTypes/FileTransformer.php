<?php

namespace Pimeo\Transformers\AttributeTypes;

use Pimeo\Models\LinkAttribute;

class FileTransformer extends AbstractTypeTransformer
{
    /**
     * Transform the given values.
     *
     * @param LinkAttribute $linkAttribute
     *
     * @return mixed
     */
    public function transform(LinkAttribute $linkAttribute)
    {
        $values = $linkAttribute->values;
        $keys = ['name', 'file_path', 'file_size'];

        foreach ($values as $lang => $files) {
            if (array_get($this->options, 'multiple', 0)) {
                foreach ($files as $key => $file) {
                    $values[$lang][$key] = array_only($file, $keys);
                }
            } else {
                if (is_array($files)) {
                    foreach ($files as $key => $file) {
                        $values[$lang][$key] = array_only($file, $keys);
                    }
                } else {
                    $values[$lang] = array_only($files, $keys);
                }
            }
        }

        return $values;
    }
}

<?php

namespace Pimeo\Forms\Fields;

use Illuminate\Support\Facades\Request;

class FieldFile extends Field
{
    public function getFields()
    {
        $fields = $attrs = [];

        foreach ($this->getValues() as $code => $value) {
            $language_code = language_code_trans($code);
            $attrs['label'] = $this->attribute->label->values[$this->languageCode] . ' (' . $language_code . ')';
            if (isset(Request::old('attributes')[$this->attribute->id])) {
                $attrs['files'] = Request::old('attributes')[$this->attribute->id];
            } else {
                $attrs['files'] = $this->getValues();
            }
            $attrs['template'] = 'vendor.laravel-form-builder.dropzone-image';
            $attrs['attr']['class'] = '';
            $attrs['id'] = $this->attribute->id;
            $attrs['lang'] = $code;
            $attrs['dropzone_options'] = [];

            $field['name'] = "attributes[{$this->attribute->id}][{$code}]";
            $field['type'] = 'file';
            $field['attrs'] = $attrs;

            /**
             * In case there's an error while submitting the form, we won't get any of the data in the "file" options.
             * The only data return by the POST submit will be the "full_name" which looks like the following string
             * patate.jpgahdflsakdlh210721oljk3h28j.jpg. With that said, you need to retrieve the non-hashed name
             * contained in the "full_name". As it just happen we already have a magnificent method that does
             * that for us. We just pass the Closure as a reference in the options array.
             *
             * @param $filename
             * @return String
             */
            $field['attrs']['file_to_image_name'] = function ($filename) {
                return $this->getFileRealName($filename);
            };

            $fields[] = $field;
        }
        return $fields;
    }

    protected function formatFullNameToFile($full_name)
    {
        $path_parts = pathinfo($full_name);

        $filesize = 0;
        if (file_exists(getenv('FILES_PATH_LOCAL') . $full_name)) {
            $filesize = filesize(getenv('FILES_PATH_LOCAL') . $full_name);
        }

        $name = $this->getFileRealName($full_name);

        return [
            'name'      => $name,
            'extension' => $path_parts['extension'],
            'file_path' => $path_parts['dirname'] . '/' . $path_parts['basename'],
            'file_size' => $filesize,
        ];
    }

    public function formToValues($form)
    {
        if (isset($form['filesToDelete'])) {
            foreach ($form['filesToDelete'] as $file) {
                unset($file);
            }

            unset($form['filesToDelete']);
        }

        foreach ($form as $lang => $files) {
            foreach ($files as $noFile => $file) {
                if (isset($file['full_name'])) {
                    $form[$lang][$noFile] = $this->formatFullNameToFile($file['full_name']);
                }
            }
        }

        return $form;
    }

    public function getDefaultValues()
    {
        $values = [];
        $languages = get_current_company_languages();
        foreach ($languages as $language) {
            $values[$language->code] = '';
        }
        return $values;
    }

    /**
     * The file original name is contain within the full name of the file (see below)
     *
     * (Original)(--Hashed original name--)
     * image.jpgahdflsakdlh210721oljk3h28j.jpg
     *
     * @param String $filename
     * @return String
     */
    public function getFileRealName($filename)
    {
        $path_info = pathinfo($filename);

        if (isset($path_info['extension'])) {
            preg_match("/(.*?)(?={$path_info['extension']}|$)/", $path_info['filename'], $output);

            if (isset($output[1])) {
                $name = $output[1] . $path_info['extension'];
            } else {
                $name = $filename;
            }
        } else {
            $name = $path_info['filename'];
        }

        return $name;
    }
}

<?php

namespace Pimeo\Forms\Fields;

use Illuminate\Support\Facades\Request;

class Promotion extends FieldFile
{
    public function getFields()
    {
        $fields = $attrs = [];

        foreach ($this->getValues() as $code => $value) {
            $language_code = language_code_trans($code);
            $lang_code = $this->languageCode;

            $files = [];
            if (isset(Request::old('attributes')[$this->attribute->id]['file'])) {
                $files[$code] = Request::old('attributes')[$this->attribute->id]['file'];
            } elseif (isset($this->getValues()[$code]['file'])) {
                $files[$code] = $this->getValues()[$code]['file'];
            }

            $attrs['label'] = trans('attribute_types.promotion_file') . ' (' . $language_code . ')';
            $attrs['files'] = $files;
            $attrs['template'] = 'vendor.laravel-form-builder.dropzone-image';
            $attrs['attr']['class'] = '';
            $attrs['id'] = $this->attribute->id;
            $attrs['lang'] = $code;
            $attrs['dropzone_options'] = [
                'acceptedFiles' => 'image/*',
                'maxFiles'      => 1,
            ];
            $attrs['type'] = 'image';

            $field['name'] = "attributes[{$this->attribute->id}][{$code}][file]";
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
             *
             * @return String
             */
            $field['attrs']['file_to_image_name'] = function ($filename) {
                return $this->getFileRealName($filename);
            };

            $fields[] = $field;

            unset($field, $attrs);

            $link = '';
            if (isset($value['link'])) {
                $link = $value['link'];
            }

            $attrs['label'] = trans('attribute_types.promotion_link') . ' (' . $language_code . ')';
            $attrs['value'] = $link;

            $field['name'] = "attributes[{$this->attribute->id}][{$code}][link]";
            $field['type'] = 'text';
            $field['attrs'] = $attrs;

            $fields[] = $field;
        }

        return $fields;
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
            if (isset($files['file'])) {
                foreach ($files['file'] as $noFile => $file) {
                    if (isset($file['full_name'])) {
                        $form[$lang]['file'][$noFile] = $this->formatFullNameToFile($file['full_name']);
                    }
                }
            }
        }

        return $form;
    }
}

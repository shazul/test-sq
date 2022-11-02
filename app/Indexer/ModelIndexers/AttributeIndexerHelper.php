<?php

namespace Pimeo\Indexer\ModelIndexers;

use Pimeo\ImageHelper\ImageHelper;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeType;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Detail;
use Pimeo\Models\Language;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Repositories\ParentProductRepository;

class AttributeIndexerHelper
{
    private $parentProductRepository;

    /** @var ImageHelper */
    private $image_helper;

    private $languages = [];

    public $rootAttributes = [
        'search_keywords',
    ];

    /**
     * AttributeIndexerHelper constructor.
     *
     * @param Language[] $languages
     */
    public function __construct($languages)
    {
        $this->parentProductRepository = new ParentProductRepository();
        $this->image_helper            = new ImageHelper();
        $this->languages               = $languages;
    }

    /**
     * @param string $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return
     */
    public function buildAttributeValueData(
        $language_code,
        $link_attribute_value,
        Attribute $attribute
    ) {
        /** @var AttributeType $type */
        $type      = $attribute->type;
        $type_spec = $type->specs['type'];

        $method = $type_spec . 'BuildValues';
        if (isset($type->specs['sub_type'])) {
            $method = $type_spec . $type->specs['sub_type'] . 'BuildValues';
        }

        if (method_exists($this, $method)) {
            return $this->$method($language_code, $link_attribute_value, $attribute, $type);
        } else {
            throw new \InvalidArgumentException('Method type ' . $method . ' does not exist');
        }
    }

    /**
     * @param System|Specification|Detail|ParentProduct|ChildProduct|TechnicalBulletin $model_with_attributes
     * @param $language_code
     * @param $name_attribute
     * @param $complementary_attribute
     *
     * @return array
     */
    public function createAttributesByLang(
        $model_with_attributes,
        $language_code,
        $name_attribute,
        $complementary_attribute
    ) {
        $attributes = [];

        /** @var LinkAttribute $link_attribute */
        foreach ($model_with_attributes->linkAttributes as $link_attribute) {
            $link_attribute_value = $link_attribute->values;

            if ($this->emptyLinkAttributeValue($link_attribute_value)) {
                continue;
            }

            /** @var Attribute $attribute */
            $attribute = $link_attribute->attribute;

            if ($attribute->name == $name_attribute) {
                $attributes['name'] = $link_attribute_value[$language_code];
            } elseif ($this->isRootAttribute($attribute)) {
                $value = $this->buildAttributeValueData(
                    $language_code,
                    $link_attribute_value,
                    $attribute
                );

                $attributes[$attribute->index_key] = $value;
            } elseif ($attribute->name == $complementary_attribute) {
                $data = explode(',', $link_attribute_value);
                if (!is_array($data)) {
                    $data = [$data];
                }
                $attributes['complementary'] = $data;
            } else {
                //TODO should be removed after data normalisation.
                if ($attribute->name == "building_component" && $model_with_attributes instanceof System) {
                    continue;
                }

                $value = $this->buildAttributeValueData(
                    $language_code,
                    $link_attribute_value,
                    $attribute
                );

                if (!is_null($value)) {
                    $attributes['attributes'][] = $value;
                }
            }
        }

        return $attributes;
    }

    /**
     * @param string $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function unitBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type      = $attribute->type;
        $preferred = $link_attribute_value['preferred'];

        $split_lang_code = explode('_', $language_code);
        $locale          = $split_lang_code[0];

        $unit_type = trans('attribute_types.units.' . $type->specs[$preferred . '_annotation'], [], 'message', $locale);

        $non_preferred = $preferred === 'metric' ? 'imperial' : 'metric';

        $annotation = $type->specs[$non_preferred . '_annotation'];
        $converted  = trans('attribute_types.units.' . $annotation, [], 'message', $locale);

        $value = [
            [
                'value'     => $link_attribute_value[$preferred],
                'metric'    => $link_attribute_value['metric'],
                'imperial'  => $link_attribute_value['imperial'],
                'converted' => $link_attribute_value[$non_preferred] . ' ' . $converted,
            ],
        ];

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $value, $unit_type);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param $attribute
     * @param $type
     *
     * @return array
     */
    public function keywordsBuildValues($language_code, $link_attribute_value, $attribute, $type)
    {
        $values = !empty($link_attribute_value) ? $link_attribute_value['keys'] : [];

        return isset($values[$language_code]) ? $values[$language_code] : [];
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function choiceBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type = $attribute->type;

        /** @var AttributeValue $attribute_values */
        $attribute_values = $attribute->value;
        $values           = [];

        $link_values = $link_attribute_value['keys'];
        if (is_array($link_values)) {
            foreach ($link_values as $value) {
                $values[] = $this->getChoiceAttributeValueFromValues($language_code, $attribute_values, $value);
            }
        } else {
            $values[] = $this->getChoiceAttributeValueFromValues($language_code, $attribute_values, $link_values);
        }

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $values);
    }

    private function baseChoiceImage($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type = $attribute->type;

        /** @var AttributeValue $attribute_values */
        $attribute_values = $attribute->value;
        $values           = [];

        $link_values = $link_attribute_value['keys'];
        if (is_array($link_values)) {
            foreach ($link_values as $value) {
                $values[] = $this->getChoiceImageAttributeValueFromValues($language_code, $attribute_values, $value);
            }
        } else {
            $values[] = $this->getChoiceImageAttributeValueFromValues($language_code, $attribute_values, $link_values);
        }

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $values);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function choiceimageBuildValues(
        $language_code,
        $link_attribute_value,
        Attribute $attribute
    ) {
        return $this->baseChoiceImage($language_code, $link_attribute_value, $attribute);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function choiceimageNoDisplayBuildValues(
        $language_code,
        $link_attribute_value,
        Attribute $attribute
    ) {
        return $this->baseChoiceImage($language_code, $link_attribute_value, $attribute);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function textBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        return $this->basicTextAttribute($language_code, $link_attribute_value, $attribute);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function textWithoutTranslationBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type  = $attribute->type;
        $value = [['value' => $link_attribute_value]];

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $value);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function texturlBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        return $this->basicTextAttribute($language_code, $link_attribute_value, $attribute);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function textareaBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        return $this->basicTextAttribute($language_code, $link_attribute_value, $attribute);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    public function numberBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type  = $attribute->type;
        $value = [['value' => $link_attribute_value]];

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $value);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array|null
     */
    public function fileBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type = $attribute->type;
        if (empty($link_attribute_value)) {
            return null;
        }

        $files = $link_attribute_value[$language_code];

        $values = [];
        foreach ($files as $file) {
            if (isset($file['empty_file'])) {
                return null;
            }

            $value = [
                // 'date' => $link_attribute_value->created_at->format('d/m/Y'),
                'size' => $this->bytesToSize($language_code, $file['file_size']),
                'url'  => getenv('FILES_ADDRESS') . '/' . $file['file_path'],
                'type' => $file['extension'],
            ];

            if ($attribute->name == 'product_image' || $attribute->name == 'system_3d_model') {
                if (!$this->image_helper->thumbnailExist($file['file_path'])) {
                    $this->image_helper->createResizeImages($file['file_path']);
                }

                $value['url_thumbnail'] = getenv('FILES_ADDRESS') . '/' . $this->image_helper->
                    getThumbnailName($file['file_path']);
            }

            $values[] = $value;
        }

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $values);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array|null
     */
    public function promotionBuildValues($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type       = $attribute->type;
        $promotions = $link_attribute_value[$language_code];

        if (isset($promotions['file']) && !isset($promotions['file'][0]['empty_file']) && isset($promotions['link'])) {
            $values = [
                [
                    'url'   => $promotions['link'],
                    'image' => getenv('FILES_ADDRESS') . '/' . $promotions['file'][0]['file_path'],
                ],
            ];
        } else {
            return null;
        }

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $values);
    }

    /**
     * @param array $data_per_index
     * @param $indexPerLanguageCode
     * @param $field_name
     * @param $field_model_id
     *
     * @return array
     */
    public function addSlugs($data_per_index, $indexPerLanguageCode, $field_name, $field_model_id)
    {
        foreach ($data_per_index as $index => $data) {
            $data = [];
            foreach ($indexPerLanguageCode as $lang_code => $elastic_index) {
                $split_code = explode('_', $lang_code);
                $slug_data  = [];

                $slug_data['code'] = $split_code[0];
                $slug_data['name'] = $this->slugifyName(
                    $data_per_index[$elastic_index][$field_name],
                    $data_per_index[$elastic_index][$field_model_id]
                );

                $data[] = $slug_data;
            }
            $data_per_index[$index]['slugs'] = $data;
        }

        return $data_per_index;
    }

    /**
     * @param $language_code
     * @param AttributeValue $attribute_values
     * @param $value
     *
     * @return array
     */
    public function getChoiceAttributeValueFromValues($language_code, AttributeValue $attribute_values, $value)
    {
        return [
            'value' => $attribute_values->values[$language_code][$value],
        ];
    }

    /**
     * @param $language_code
     * @param AttributeValue $attribute_values
     * @param $value
     *
     * @return array
     */
    public function getChoiceImageAttributeValueFromValues($language_code, AttributeValue $attribute_values, $value)
    {
        $image = '';
        if (isset($attribute_values->values[$language_code][$value]['image'])) {
            $image = $attribute_values->values[$language_code][$value]['image'];
        }

        return [
            'value' => $attribute_values->values[$language_code][$value]['name'],
            'url'   => getenv('FILES_ADDRESS') . '/' . $image,
        ];
    }

    /**
     * @param $language_code
     * @param Attribute $attribute
     * @param AttributeType $type
     * @param array $values
     * @param string $unit_type
     *
     * @return array
     */
    public function getBaseAttributeValue(
        $language_code,
        Attribute $attribute,
        AttributeType $type,
        array $values,
        $unit_type = ''
    ) {
        $attribute_type    = $type->code;
        $attribute_options = $attribute->options;
        $name              = $attribute->name;

        if (isset($attribute_options['special_index_key']) && $attribute_options['special_index_key'] != '') {
            $attribute_type = $attribute_options['special_index_key'];
            $name           = $attribute->options['special_index_key'];
        }


        $label = $attribute->label->values[$language_code];
        if (is_array($label)) {
            $label = $label['value'];
        }

        return [
            'label'     => $label,
            'type'      => $attribute_type,
            'name'      => $name,
            'unit_type' => $unit_type,
            'values'    => $values,
        ];
    }

    /**
     * @param $complementary_products
     * @param $website_media_id
     *
     * @return array
     */
    public function validateComplementaryProduct($complementary_products, $website_media_id)
    {
        $validated_complementary_products = [];
        foreach ($complementary_products as $parent_product_id) {
            if ($parent_product_id != '' && $this->validateParentProduct($parent_product_id, $website_media_id)) {
                $validated_complementary_products[] = $parent_product_id;
            }
        }

        return $validated_complementary_products;
    }

    /**
     * @param $parent_product_id
     * @param $website_media_id
     *
     * @return bool
     */
    public function validateParentProduct($parent_product_id, $website_media_id)
    {
        $valid = false;

        /** @var ParentProduct $parent */
        $parent = ParentProduct::whereHas('mediaLinks', function ($query) use ($website_media_id) {
            $query->where('media_id', $website_media_id);
        })->find($parent_product_id);

        if (!is_null($parent) && $parent->status == AttributableModelStatus::PUBLISHED_STATUS) {
            $valid = true;
        }

        return $valid;
    }

    /**
     * Convert bytes to human readable format
     *
     * @param string $language_code
     * @param string $bytes bytes Size in bytes to convert
     * @param int $precision
     *
     * @return string
     */
    public function bytesToSize($language_code, $bytes, $precision = 2)
    {
        $language_size = [];
        foreach ($this->languages as $language) {
            $language_size[$language->code] = [
                'B'  => trans('units.bytes.bytes'),
                'KB' => trans('units.bytes.kb'),
                'MB' => trans('units.bytes.mb'),
                'GB' => trans('units.bytes.gb'),
                'TB' => trans('units.bytes.tb'),
            ];
        }

        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;

        if (($bytes >= 0) && ($bytes < $kilobyte)) {
            return $bytes . ' ' . $language_size[$language_code]['B'];
        } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
            return round($bytes / $kilobyte, $precision) . ' ' . $language_size[$language_code]['KB'];
        } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
            return round($bytes / $megabyte, $precision) . ' ' . $language_size[$language_code]['MB'];
        } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
            return round($bytes / $gigabyte, $precision) . ' ' . $language_size[$language_code]['GB'];
        } elseif ($bytes >= $terabyte) {
            return round($bytes / $terabyte, $precision) . ' ' . $language_size[$language_code]['TB'];
        } else {
            return $bytes . ' ' . $language_size[$language_code]['B'];
        }
    }

    /**
     * create a slug
     *
     * @param $name
     * @param $id
     *
     * @return string
     */
    public function slugifyName($name, $id)
    {
        return str_slug($name . ' ' . $id);
    }

    /**
     * @param $language_code
     * @param $link_attribute_value
     * @param Attribute $attribute
     *
     * @return array
     */
    private function basicTextAttribute($language_code, $link_attribute_value, Attribute $attribute)
    {
        /** @var AttributeType $type */
        $type = $attribute->type;

        $values = [];

        $texts = $link_attribute_value[$language_code];
        if (is_array($texts)) {
            foreach ($texts as $text) {
                $values[] = ['value' => $text];
            }
        } else {
            $values[] = ['value' => $texts];
        }

        return $this->getBaseAttributeValue($language_code, $attribute, $type, $values);
    }

    private function emptyLinkAttributeValue($link_attribute_value)
    {
        //continue the loop if the link attribute has no value
        if (is_null($link_attribute_value)) {
            return true;
        }

        // Si a des keys, mais sont vide, skip
        if (isset($link_attribute_value['keys']) && empty($link_attribute_value['keys'])) {
            return true;
        }

        $language_empty = [];
        foreach ($this->languages as $language) {
            if (isset($link_attribute_value[$language->code]) && empty($link_attribute_value[$language->code])) {
                $language_empty[$language->code] = true;
            } else {
                $language_empty[$language->code] = false;
            }
        }

        if (in_array(true, $language_empty, true)) {
            return true;
        }

        return false;
    }

    private function isRootAttribute($attribute)
    {
        return in_array($attribute->index_key, $this->rootAttributes);
    }
}

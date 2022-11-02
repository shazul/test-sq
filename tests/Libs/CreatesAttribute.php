<?php

namespace Tests\Libs;

use Auth;
use Faker\Factory;
use Pimeo\Models\Attribute;
use Pimeo\Models\BuildingComponent;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Detail;
use Pimeo\Models\Language;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Media;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Repositories\AttributeRepository;

trait CreatesAttribute
{
    /**
     * @param Language[] $languages
     * @return string[]
     */
    public function getImageTypeData($languages)
    {
        $data = [];
        foreach ($languages as $language) {
            $data[$language->code][]['empty_file'] = "";
        }
        return $data;
    }

    /**
     * @param Language[] $languages
     * @return string[]
     */
    public function getTextMultipleTypeData($languages)
    {
        $faker = Factory::create();
        $data = [];

        foreach ($languages as $language) {
            $data[$language->code] = $faker->sentences(3);
        }

        return  $data;
    }

    /**
     * @param Language[] $languages
     * @return string[]
     */
    public function getTextTypeData($languages)
    {
        $faker = Factory::create();
        $data = [];

        foreach ($languages as $language) {
            $data[$language->code] = $faker->sentence(3);
        }

        return $data;
    }

    /**
     * @param Language[] $languages
     * @return string[]
     */
    public function getTextWithoutTranslationTypeData($languages)
    {
        $faker = Factory::create();
        $data = [];

        $data[] = $faker->sentence(3);

        return $data;
    }

    /**
     * @param Language[] $languages
     * @return string[]
     */
    public function getTextareaTypeData($languages)
    {
        $faker = Factory::create();
        $data = [];

        foreach ($languages as $language) {
            $data[$language->code] = $faker->sentence(10);
        }

        return $data;
    }

    public function getKeywordsTypeData($languages)
    {
        $faker = Factory::create();
        $data  = [];

        foreach ($languages as $language) {
            $keywords = [];

            for ($i = 0; $i < 10; $i++) {
                $keywords[] = $faker->sentence(rand(1, 2));
            }

            $data[$language->code] = $keywords;
        }

        return $data;
    }

    /**
     * @param Language[] $languages
     * @return mixed[]
     */
    public function getFileTypeData($languages)
    {
        $faker = Factory::create();
        $data = [];

        $filename = $faker->name;

        foreach ($languages as $language) {
            $data[$language->code][] = [
                'name'      => $language->code . '_' . $filename . '.pdf',
                'extension' => '.pdf',
                'file_path' => date('Y-m-d') . '/' . $language->code . '_' . $filename . '.pdf',
                'file_size' => $faker->numberBetween(10000, 500000),
            ];
        }

        return  $data;
    }

    /**
     * @param Language[] $languages
     * @return mixed[]
     */
    public function getFilesTypeData($languages)
    {
        $faker = Factory::create();
        $data = [];

        $first_filename = $faker->name;
        $second_filename = $faker->name;

        foreach ($languages as $language) {
            $data[$language->code][] = [
                'name'      => $language->code . '_' . $first_filename . '.pdf',
                'extension' => 'pdf',
                'file_path' => date('Y-m-d') . '/' . $language->code . '_' . $first_filename . '.pdf',
                'file_size' => $faker->numberBetween(10000, 500000),
            ];

            $data[$language->code][] = [
                'name'      => $language->code . '_' . $second_filename . '.dwg',
                'extension' => 'dwg',
                'file_path' => date('Y-m-d') . '/' . $language->code . '_' . $second_filename . '.dwg',
                'file_size' => $faker->numberBetween(10000, 500000),
            ];
        }

        return $data;
    }

    public function getTextLinkMultipleTypeData()
    {
        return [1, 2];
    }

    public function getTextHyperlinkTypeData($languages)
    {
        return $this->getTextTypeData($languages);
    }

    private function getUnitTypeData()
    {
        $faker = Factory::create();

        return [
            'metric'    => $faker->randomNumber(),
            'imperial'  => $faker->randomNumber(),
            'preferred' => 'metric',
        ];
    }

    public function getUnitKgm2ToLbsft2TypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getUnitLToGalTypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getUnitM2ToFt2TypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getUnitCelsiusTypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getUnitKgToLbsTypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getUnitMToFtTypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getUnitMmToMilTypeData()
    {
        return $this->getUnitTypeData();
    }

    public function getNumberTypeData()
    {
        $faker = Factory::create();

        return $faker->randomNumber();
    }

    public function getUnitMmToInTypeData()
    {
        return $this->getUnitTypeData();
    }

    /**
     * @param string $modelName
     * @param Language[] $languages
     * @return mixed[]
     */
    public function getRequiredAttributeForCreation($modelName)
    {
        $attributes = app(AttributeRepository::class)->allRequired($modelName);

        return $this->getDataForAttributes($attributes);
    }

    /**
     * @param string $modelName
     * @param Language[] $languages
     * @return mixed[]
     */
    public function getAllAttributesForCreation($modelName)
    {
        $attributes = app(AttributeRepository::class)->all($modelName);

        return $this->getDataForAttributes($attributes);
    }

    /**
     * @param  Attribute[] $attributes
     * @return array $data
     */
    private function getDataForAttributes($attributes)
    {
        $data = [];

        $languages = auth()->user()->getCompany()->languages;

        foreach ($attributes as $attribute) {
            $this->buildAttributeData($attribute, $data, $languages);
        }

        $data['media'][] = Media::where('code', 'website')->first()->id;
        $data['buildingComponents'] = [BuildingComponent::forCurrentCompany()->get()->first()->id];

        return $data;
    }

    /**
     * @param System|Specification|Detail|TechnicalBulletin|ChildProduct|ParentProduct $model
     * @return mixed[]
     */
    public function getModelAttributeForUpdate($model)
    {
        $data = [];

        /** @var LinkAttribute[] $linked_attributes */
        $linked_attributes = $model->linkAttributes()->get();

        $languages = auth()->user()->getCompany()->languages;

        foreach ($linked_attributes as $linked_attribute) {
            $attribute = $linked_attribute->attribute;
            $this->buildAttributeData($attribute, $data, $languages);
        }

        $data['media'][] = Media::where('code', 'website')->first()->id;
        $data['buildingComponents'] = [BuildingComponent::forCurrentCompany()->first()->id];

        return $data;
    }

    /**
     * @param Attribute $attribute
     * @param mixed[] $data
     * @param Language[] $languages
     */
    private function buildAttributeData(Attribute $attribute, &$data, $languages)
    {
        if ($attribute->value['values']) {
            $data['attributes'][$attribute->id] = [key(array_first($attribute->value['values']))];
        } else {
            $method = camel_case('get_' . $attribute->type->code . '_type_data');
            $data['attributes'][$attribute->id] = $this->$method($languages);
        }
    }
}

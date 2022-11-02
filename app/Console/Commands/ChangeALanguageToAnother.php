<?php

namespace Pimeo\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\Layer;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\System;

class ChangeALanguageToAnother extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:changeLanguageToAnothers {--company= : ID of the company}
                                                             {--languageCode= : language code to change}
                                                             {--newLanguageCode= : new code language}
                                                             {--newLanguageName= : new name for language}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'browse all data of a company and change a language to another.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $companyId = $this->option('company');
        $currentLanguageCode = $this->option('languageCode');
        $newLanguageCode = $this->option('newLanguageCode');
        $newLanguageName = $this->option('newLanguageName');

        if (is_null($newLanguageName)
            || is_null($newLanguageName)
            || is_null($newLanguageName)
            || is_null($newLanguageName)
        ) {
            throw new \LogicException('All options are required');
        }

        $company = Company::find($companyId);

        $companyAttributes = Attribute::whereCompanyId($company->id)->get();
        $attributeValues = $this->getAttributeValues($companyAttributes);
        $attributeLabels = $this->getAttributeLabels($companyAttributes);
        $systems = System::whereCompanyId($company->id)->get();
        $layerGroups = $this->getSystemGroupLayer($systems);
        $layers = $this->getSystemGroupLayerLayers($layerGroups);
        $linkAttributes = LinkAttribute::whereIn('attribute_id', $companyAttributes->pluck('id'))->get();

        $this->changeAttributeValuesOrLabelOrLinkAttribute($attributeValues, $currentLanguageCode, $newLanguageCode);
        $this->changeAttributeValuesOrLabelOrLinkAttribute($attributeLabels, $currentLanguageCode, $newLanguageCode);
        $this->changeAttributeValuesOrLabelOrLinkAttribute($linkAttributes, $currentLanguageCode, $newLanguageCode);
        $this->changeGroupLayer($layerGroups, $currentLanguageCode, $newLanguageCode);
        $this->changeLayer($layers, $currentLanguageCode, $newLanguageCode);
        $this->changeLanguageInCompany($currentLanguageCode, $newLanguageCode, $newLanguageName);
    }

    /**
     * @param string $currentLanguageCode
     * @param string $newLanguageCode
     * @param string $newLanguageName
     */
    private function changeLanguageInCompany($currentLanguageCode, $newLanguageCode, $newLanguageName)
    {
        $language = Language::whereCode($currentLanguageCode)->first();
        if (!is_null($language)) {
            $language->name = $newLanguageName;
            $language->code = $newLanguageCode;
            $language->save();
        }
    }

    private function changeAttributeValuesOrLabelOrLinkAttribute(
        $attributeValuesOrLabelOrLinkAttribute,
        $currentLanguageCode,
        $newLanguageCode
    ) {
        /** @var AttributeValue|AttributeLabel|LinkAttribute $value */
        foreach ($attributeValuesOrLabelOrLinkAttribute as $value) {
            $currentValues = $value->values;
            if (!empty($currentValues[$currentLanguageCode])) {
                $data = $currentValues[$currentLanguageCode];

                unset($currentValues[$currentLanguageCode]);
                $currentValues[$newLanguageCode] = $data;

                $value->values = $currentValues;
                $value->save();
            }
        }
    }

    private function changeLayer($layers, $currentLanguageCode, $newLanguageCode)
    {
        /** @var Layer $layer */
        foreach ($layers as $layer) {
            if (!empty($layer->product_name[$currentLanguageCode])
                && !empty($layer->product_function[$currentLanguageCode])
            ) {
                $productName = $layer->product_name;
                $productFunction = $layer->product_function;

                $productNameData = $productName[$currentLanguageCode];
                $productFunctionData = $productFunction[$currentLanguageCode];

                unset($productName[$currentLanguageCode]);
                unset($productFunction[$currentLanguageCode]);

                $productName[$newLanguageCode] = $productNameData;
                $productFunction[$newLanguageCode] = $productFunctionData;
                $layer->product_name = $productName;
                $layer->product_function = $productFunction;
                $layer->save();
            }
        }
    }

    /**
     * @param $layerGroups
     * @param $currentLanguageCode
     * @param $newLanguageCode
     */
    private function changeGroupLayer($layerGroups, $currentLanguageCode, $newLanguageCode)
    {
        /** @var LayerGroup $layerGroup */
        foreach ($layerGroups as $layerGroup) {
            if (!empty($layerGroup[$currentLanguageCode])) {
                $name = $layerGroup->name;
                $nameData = $name[$currentLanguageCode];
                unset($name[$currentLanguageCode]);
                $name[$newLanguageCode] = $nameData;
                $layerGroup->name = $name;
                $layerGroup->save();
            }
        }
    }

    /**
     * @param Attribute[]|Collection $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAttributeValues($attributes)
    {
        $values = [];
        foreach ($attributes as $attribute) {
            $attributeValues = $attribute->value;
            if (!is_null($attributeValues)) {
                $values[] = $attributeValues;
            }
        }

        return collect($values);
    }

    /**
     * @param Attribute[]|Collection $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAttributeLabels($attributes)
    {
        $values = [];
        foreach ($attributes as $attribute) {
            $values[] = $attribute->label;
        }

        return collect($values);
    }

    /**
     * @param System[]|Collection $systems
     *
     * @return \Illuminate\Support\Collection
     */
    private function getSystemGroupLayer($systems)
    {
        $values = [];
        foreach ($systems as $system) {
            $values[] = $system->layerGroups;
        }

        return collect($values);
    }

    /**
     * @param LayerGroup[]|Collection $layerGroups
     *
     * @return \Illuminate\Support\Collection
     */
    private function getSystemGroupLayerLayers($layerGroups)
    {
        $values = [];
        foreach ($layerGroups as $layerGroup) {
            $values[] = $layerGroup->layers;
        }

        return collect($values);
    }
}

<?php

namespace Pimeo\Console\Commands;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use League\Csv\Reader;
use Pimeo\Forms\Fields\Field;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\ChildProductRepository;

class ImportChildProducts extends BaseImport
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:import:child-products {path : Path to CSV file to import}
                                                           {--company= : ID of the company}
                                                           {--language= : Language of the file to import}
                                                           {--plan= : Path to the JSON plan file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import child products';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var ChildProductRepository
     */
    protected $repository;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @var Language
     */
    protected $language;

    /**
     * @var string
     */
    protected $csvContent;

    /**
     * @var Collection
     */
    protected $lines;

    /**
     * @var Collection
     */
    protected $plan;

    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * @var Collection
     */
    protected $preparedData;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param ChildProductRepository $repository
     */
    public function __construct(Filesystem $files, ChildProductRepository $repository)
    {
        parent::__construct();

        $this->files        = $files;
        $this->repository   = $repository;
        $this->attributes   = collect();
        $this->preparedData = collect();

        try {
            Auth::loginUsingId(1); // Login in as Super Admin (Field language requirements)
        } catch (\Exception $e) {
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Import Child Products');

        try {
            $this
                ->verifyOptions()
                ->verifyFile()
                ->readCsv()
                ->prepareAttributes()
                ->prepareData()
                ->importData();
        } catch (\Exception $e) {
            throw $e;
        }

        $this->info('Importation completed!');
    }

    private function prepareAttributes()
    {
        $this->write('> Preparing attributes');

        $this->plan->each(function ($plan) {
            $this->prepareAttribute($plan);
        });

        $this->done();

        return $this;
    }

    private function prepareAttribute($plan)
    {
        $this->detail($plan['attributeCode']);

        if (in_array($plan['attributeCode'], ['parent_name', 'null'], true)) {
            $this->manageException($plan);
        } else {
            $attribute = Attribute::whereCompanyId($this->company->id)
                ->whereName($plan['attributeCode'])
                ->whereModelType('child_product')
                ->first();

            if (null === $attribute) {
                $this->failed("Attribute '{$plan['attributeCode']}' not found");
                die(1);
            }

            $this->attributes->push(array_merge(
                array_only($plan, ['start', 'end']),
                ['attribute' => $attribute]
            ));
        }
    }

    private function manageException($plan)
    {
        $data = [];

        switch ($plan['attributeCode']) {
            case 'parent_name':
                $data = [
                    'is_parent_product_name' => true,
                ];
                break;
        }

        if (!empty($data)) {
            $this->attributes->push(array_merge(
                array_only($plan, ['start', 'end']),
                $data
            ));
        }
    }

    private function importData()
    {
        $this->write('> Importing data');

        $total = $this->lines->count();

        $this->preparedData->each(function ($data, $index) use ($total) {
            $position = ++$index;
            $this->detail("{$position} on {$total}");
            $this->createChildProduct($data);
        });

        $this->done();

        return $this;
    }

    private function prepareData()
    {
        $this->write('> Preparing data');

        $total = $this->lines->count();

        //TODO
        $this->lines->each(function ($line, $index) use ($total) {
            $position = $index + 1;

            $this->detail("{$position} on {$total}");

            $data = collect();
            $this->attributes->each(function ($attribute) use ($line, $data) {
                $values = collect(
                    array_slice($line, $attribute['start'], max(1, $attribute['end'] - $attribute['start'] + 1))
                );

                $values              = $values->filter(function ($value) {
                    return $value !== '';
                });
                $attribute['values'] = $values;
                $data->push($attribute);
            });

            $this->preparedData->push($data);
        });

        $this->done();

        return $this;
    }

    private function readCsv()
    {
        $this->write('> Reading CSV');

        $csv = Reader::createFromString($this->csvContent);

        $this->lines = collect($csv->fetchAll());

        $this->done($this->lines->count() . ' found');

        return $this;
    }

    private function verifyFile()
    {
        $this->write('> Verifying file');

        $path = $this->argument('path');

        try {
            $this->detail('Opening path');
            $this->csvContent = $this->openFile($path);
        } catch (\Exception $e) {
            $this->failed("File '{$path}' could not be opened");
            die(1);
        }

        $this->done();

        return $this;
    }

    /**
     * @return $this
     */
    private function verifyOptions()
    {
        $this->write('> Verifying options');

        $this->detail('Checking options');

        $companyId    = $this->option('company');
        $languageCode = $this->option('language');
        $plan         = $this->option('plan');

        if (null === $companyId) {
            $this->failed('Company ID is required');
            die(1);
        }

        if (null === $languageCode) {
            $this->failed('Language Code is required');
            die(1);
        }

        if (null === $plan) {
            $this->failed('JSON plan file is required');
            die(1);
        }

        $this->detail('Checking for company');

        $this->company = Company::find($companyId);

        if (null === $this->company) {
            $this->failed("Company ID '{$companyId}' not found");
            die(1);
        }

        $this->detail('Checking for language');

        $this->language = Language::whereCode($languageCode)->first();

        if (null === $this->language) {
            $this->failed("Language '{$languageCode}' not found");
            die(1);
        }

        try {
            $this->detail('Checking for plan file');

            $this->plan = collect(json_decode($this->files->get($plan), true));
        } catch (\Exception $e) {
            $this->failed("Plan file '{$plan}' could not be opened");
            die(1);
        }

        $this->done();

        return $this;
    }

    private function createChildProduct($data)
    {
        $data       = collect($data);
        $attributes = collect();

        $childProduct = new ChildProduct;

        $data->each(function ($attribute, $index) use ($attributes, $childProduct) {
            if (array_key_exists('attribute', $attribute)) {
                $attributes->push($this->createChildProductAttributeValue($attribute));
            } elseif (array_key_exists('is_parent_product_name', $attribute)) {
                $childProduct->parentProduct()->associate($this->findParentByName($attribute['values'][0]));
            }
        });

        $childProduct->status             = AttributableModelStatus::DRAFT_STATUS;
        $childProduct->company_catalog_id = $this->company->id;
        $childProduct->company_id         = $this->company->id;
        $childProduct->save();
        $childProduct->linkAttributes()->saveMany($attributes->filter());
    }

    private function createChildProductAttributeValue($data)
    {
        /** @var Attribute $attribute */
        $attribute = $data['attribute'];
        /** @var Collection $values */
        $values = $data['values'];

        if ($values->isEmpty()) {
            return null;
        }

        $attributeType  = $attribute->type;
        $attributeValue = $attribute->value;

        if (null !== $attributeValue) {
            $values->transform(function ($value) use ($attributeValue) {
                $value = strtolower($value);

                $attributeValues = collect($attributeValue->valuesByLangCode($this->language->code));

                if (is_array($attributeValues[0]) && array_key_exists('name', $attributeValues[0])) {
                    $values = collect(array_pluck($attributeValues, 'name'));

                    $values->transform(function ($value) {
                        return strtolower($value);
                    });

                    $index = array_search($value, $values->all(), true);
                } else {
                    $attributeValues->transform(function ($value) {
                        return strtolower($value);
                    });

                    $index = array_search($value, $attributeValues->all(), true);
                }

                if ($index === false) {
                    // TODO: Skip empty value
                }

                return $index;
            });
        }

        $fieldClass = 'Pimeo\Forms\Fields\\' . ucfirst(camel_case($attributeType->code));

        /** @var Field $field */
        $field = new $fieldClass;
        $field->setAttribute($attribute);

        $languages = $this->company->languages->all();

        $linkAttribute               = new LinkAttribute;
        $linkAttribute->attribute_id = $attribute->id;
        $linkAttribute->values       = $field->formatValues($values->toArray(), $languages);

        return $linkAttribute;
    }

    /**
     * @param $parentName
     *
     * @return ParentProduct
     */
    private function findParentByName($parentName)
    {
        $parentNameAttributeCode = 'name';
        $attribute               = Attribute::whereName($parentNameAttributeCode)
            ->where('is_parent_attribute', true)
            ->where('company_id', $this->company->id)
            ->first();

        $linkAttribute = LinkAttribute::whereAttributeId($attribute->id)
            ->where(
                'values',
                'like',
                '%' . str_replace('\\', '\\\\', json_encode(strtoupper($parentName))) . '%'
            )
            ->first();

        return ParentProduct::whereId($linkAttribute->attributable_id)->first();
    }
}

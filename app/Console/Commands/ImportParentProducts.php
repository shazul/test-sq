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
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Nature;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\ParentProductRepository;

class ImportParentProducts extends BaseImport
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soprema:import:parent-products {path : Path to CSV file to import}
                                                           {--company= : ID of the company}
                                                           {--language= : Language of the file to import}
                                                           {--plan= : Path to the JSON plan file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import parent products';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var ParentProductRepository
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
     * @param ParentProductRepository $repository
     */
    public function __construct(Filesystem $files, ParentProductRepository $repository)
    {
        parent::__construct();

        $this->files = $files;
        $this->repository = $repository;
        $this->attributes = collect();
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

        $this->info('Import Parent Products');

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

        if (in_array($plan['attributeCode'], ['nature', 'null'], true)) {
            $this->manageException($plan);
        } else {
            $attribute = Attribute::whereCompanyId($this->company->id)
                ->whereName($plan['attributeCode'])
                ->whereModelType('parent_product')
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
            case 'nature':
                $data = [
                    'is_parent_column' => true,
                    'field'            => 'nature',
                    'where_field'      => 'code',
                    'class'            => Nature::class,
                    'trans'            => 'attribute.natures',
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
            $this->createParentProduct($data);
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

                $values = $values->filter(function ($value) {
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

        $companyId = $this->option('company');
        $languageCode = $this->option('language');
        $plan = $this->option('plan');

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

    private function createParentProduct($data)
    {
        $data = collect($data);
        $attributes = collect();

        $parentProduct = new ParentProduct;

        $data->each(function ($attribute, $index) use ($attributes, $parentProduct) {
            if (array_key_exists('attribute', $attribute)) {
                $attributes->push($this->createParentProductAttributeValue($attribute));
            } elseif (array_key_exists('is_parent_column', $attribute)) {
                /** @var \Eloquent|Builder $value */
                $value = new $attribute['class'];

                $whereValue = $attribute['values'][0];
                if (array_key_exists('trans', $attribute)) {
                    $whereValue = array_search($attribute['values'][0], trans($attribute['trans']), true);
                }

                $attribute_id = $value->where($attribute['where_field'], $whereValue)->first()->id;
                $parentProduct->{$attribute['field'] . '_id'} = $attribute_id;
            }
        });

        $parentProduct->company_id = $this->company->id;
        $parentProduct->status = AttributableModelStatus::DRAFT_STATUS;

        $parentProduct->save();
        $parentProduct->linkAttributes()->saveMany($attributes->filter());
    }

    private function createParentProductAttributeValue($data)
    {
        /** @var Attribute $attribute */
        $attribute = $data['attribute'];
        /** @var Collection $values */
        $values = $data['values'];

        if ($values->isEmpty()) {
            return null;
        }

        $attributeType = $attribute->type;
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

        $linkAttribute = new LinkAttribute;
        $linkAttribute->attribute_id = $attribute->id;
        $linkAttribute->values = $field->formatValues($values->toArray(), $languages);

        return $linkAttribute;
    }
}

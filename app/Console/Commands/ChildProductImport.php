<?php

namespace Pimeo\Console\Commands;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Repositories\ChildProductRepository;
use Symfony\Component\Console\Helper\ProgressBar;

class ChildProductImport extends BaseImport
{
    const ATTRIBUTE_TYPE_NAME = 'child_product_name';

    const ATTRIBUTE_TYPE_LENGTH = 'child_product_length';

    const ATTRIBUTE_TYPE_WIDTH = 'child_product_width';

    const MEASUREMENT_SYSTEM = 'metric';

    public $attributeCodes = [
        'child_product_code',
        'child_product_name',
        'child_product_length',
        'child_product_width',
        'child_product_number_per_pallet',
    ];

    public $languages = [];

    /** @var ChildProductRepository */
    private $childProductRepo;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param ChildProductRepository $childProductRepo
     */
    public function __construct(Filesystem $files, ChildProductRepository $childProductRepo)
    {
        $this->childProductRepo = $childProductRepo;

        $this->header = $this->getHeader();

        $this->signature = 'soprema:import:childProducts {companyId : The ID of the company which products belong to}';

        $this->description = 'Import child products';

        parent::__construct();

        $this->files = $files;
    }

    /**
     * @return String[]
     */
    protected function getHeader()
    {
        return $this->attributeCodes;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filesImport = $data = [];

        $this->info('Import products');

        $this->write('> Retrieving company');

        $company = $this->fetchCompany($this->argument('companyId'));

        $this->done();

        $this->write('> Retrieving languages');

        $this->languages = $company->languages->pluck('name', 'code');

        $this->done();

        foreach ($this->languages as $langCode => $language) {
            $filesImport[$langCode] = $this->ask("Filepath for this language: $language");
        }

        $this->write('> Reading files');

        foreach ($filesImport as $lang => $file) {
            $file = $this->openFile($file);
            $data[$lang] = $this->readFile($file);
        }

        $this->done();

        $this->write('> Preparing data');

        $newProducts = $this->prepareData($data);

        $this->done();

        $this->write('> Verifying data');

        foreach ($newProducts as $product) {
            $this->verifyProduct($product);
        }

        $this->done();

        $this->write('> Creating products');

        $this->createProducts($company, $newProducts);

        $this->done();
    }

    /**
     * Fetch a company.
     *
     * @param int $companyId
     * @return Company
     */
    public function fetchCompany($companyId)
    {
        return Company::query()->where('id', $companyId)->first();
    }

    /**
     * Prepare data parsed from CSV.
     *
     * @param string[] $data
     * @return string[]
     */
    protected function prepareData(array $data)
    {
        $newProducts = [];

        foreach ($data as $language => $products) {
            foreach ($products as $product) {
                $productCode = $product['child_product_code'];

                foreach ($product as $attrName => $attrValue) {
                    switch ($attrName) {
                        case self::ATTRIBUTE_TYPE_NAME:
                            $newProducts[$productCode][$attrName][$language] = $attrValue;
                            break;
                        case self::ATTRIBUTE_TYPE_LENGTH:
                        case self::ATTRIBUTE_TYPE_WIDTH:
                            $attribute = $this->fetchAttribute($attrName);
                            $attrNewValue = $attrValue * $attribute->type->specs['imperial_measurement'];
                            $newProducts[$productCode][$attrName]['metric'] = $attrValue;
                            $newProducts[$productCode][$attrName]['imperial'] = $attrNewValue;
                            $newProducts[$productCode][$attrName]['preferred'] = self::MEASUREMENT_SYSTEM;
                            break;
                        default:
                            $newProducts[$productCode][$attrName] = $attrValue;
                    }
                }
            }
        }

        return $newProducts;
    }

    /**
     * Verify all required attributes are present in processed data from CSV.
     *
     * @param string[] $product
     * @throws Exception
     */
    protected function verifyProduct(array $product)
    {
        foreach ($this->attributeCodes as $attribute) {
            if ($attribute == self::ATTRIBUTE_TYPE_NAME) {
                foreach ($this->languages as $langCode => $language) {
                    if (!isset($product[$attribute][$langCode]) || empty($product[$attribute][$langCode])) {
                        throw new Exception("
                            Missing Attribute {$attribute} for language {$language}
                            in product {$product['child_product_code']}
                        ");
                    }
                }
            } else {
                if (!isset($product[$attribute]) || empty($product[$attribute])) {
                    throw new Exception("Missing Attribute {$attribute} in product {$code}");
                }
            }
        }
    }

    /**
     * Create products for the given attributes.
     *
     * @param  Company $company
     * @param  string[] $products
     *
     * @return string
     * @throws Exception
     */
    public function createProducts(Company $company, $products)
    {
        $progress = new ProgressBar($this->getOutput(), count($products));
        $progress->start();
        try {
            foreach ($products as $attributes) {
                $newProduct = $this->createChildProduct($company);

                $this->createChildProductAttributes($newProduct, $attributes);
                $progress->advance();
            }

            $progress->clear();
            $this->clearLineUp();
        } catch (Exception $e) {
            $progress->clear();

            $this->clearLineUp();
            $this->clearLineUp();
            $this->failed($e->getMessage());

            throw $e;
        }
    }

    /**
     * Create a product for the given company and catalog.
     *
     * @param Company $company
     * @return ChildProduct
     */
    protected function createChildProduct(Company $company)
    {
        $product = new ChildProduct;

        $catalog = $company->companyCatalogs->first();

        $product->company_id = $company->id;
        $product->company_catalog_id = $catalog->id;
        $product->status = AttributableModelStatus::PARENTLESS_STATUS;

        $product->save();

        return $product;
    }

    /**
     * Create attributes for a given child product.
     *
     * @param ChildProduct $child_product
     * @param string[] $attributes
     *
     * @throws Exception
     */
    protected function createChildProductAttributes(ChildProduct $child_product, array $attributes)
    {
        foreach ($attributes as $attribute_name => $attribute_value) {
            $attribute = Attribute::whereName($attribute_name)
                ->where('model_type', '=', 'child_product')
                ->first();

            if (is_null($attribute)) {
                throw new Exception("Attribute with name {$attribute_name} is undefined");
            }

            $child_product->linkAttributes()->create([
                'attribute_id' => $attribute->id,
                'values' => $attribute_value,
            ]);
        }
    }

    /**
     * Fetch an attribute.
     *
     * @param string $name
     * @return Attribute
     */
    protected function fetchAttribute($name)
    {
        $attribute = Attribute::whereName($name)
            ->where('model_type', '=', 'child_product')
            ->first();

        return $attribute;
    }
}

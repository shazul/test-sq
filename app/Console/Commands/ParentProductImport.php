<?php

namespace Pimeo\Console\Commands;

use CompanyTableSeeder;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\LinkAttributeValue;
use Pimeo\Models\ParentProduct;
use Symfony\Component\Console\Helper\ProgressBar;

class ParentProductImport extends BaseImport
{
    const ATTRIBUTE_TYPE_NAME = 'name';

    const ATTRIBUTE_TYPE_PRODUCT_NATURE = 'product_nature';

    const ATTRIBUTE_TYPE_PRODUCT_FUNCTION = 'product_function';

    const ATTRIBUTE_TYPE_PRODUCT_ROLE = 'product_role';

    const ATTRIBUTE_TYPE_BUILDING_COMPONENT = 'building_component';

    const ATTRIBUTE_TYPE_PRODUCT_DESCRIPTION = 'product_description';

    const ATTRIBUTE_TYPE_PRODUCT_BENEFITS = 'product_benefits';

    const ATTRIBUTE_TYPE_PRODUCT_TEST_NORM_APPROBATION = 'product_test_norm_approbations';

    const ATTRIBUTE_TYPE_COMPLEMENTARY_PRODUCT = 'complementary_product';

    const ATTRIBUTE_TYPE_PRODUCT_SIGNATURE = 'product_signature';

    const ATTRIBUTE_TYPE_PRODUCT_INSTALLATION_VIDEO = 'product_installation_video';

    const ATTRIBUTE_TYPE_PRODUCT_PROMOTIONAL_VIDEO = 'product_promotional_video';

    const FRENCH_CODE = 'fr';

    const ENGLISH_CODE = 'en';

    const FRENCH_PRODUCT_NAME_ROW = 'product_name_fr';

    const ENGLISH_PROCUCT_NAME_ROW = 'product_name_en';

    const WEB_DISPLAY_ROW = 'web_display';

    const PRODUCT_NATURE_ROW = 'product_nature';

    const PRODUCT_FUNCTION_1_ROW = 'function_1';

    const PRODUCT_FUNCTION_2_ROW = 'function_2';

    const PRODUCT_FUNCTION_3_ROW = 'function_3';

    const PRODUCT_ROLE_1 = 'product_role_1';

    const PRODUCT_ROLE_2 = 'product_role_2';

    const PRODUCT_ROLE_3 = 'product_role_3';

    const BUILDING_COMPONENT_1 = 'building_component_1';

    const BUILDING_COMPONENT_2 = 'building_component_2';

    const BUILDING_COMPONENT_3 = 'building_component_3';

    const BUILDING_COMPONENT_4 = 'building_component_4';

    const BUILDING_COMPONENT_5 = 'building_component_5';

    const BUILDING_COMPONENT_6 = 'building_component_6';

    const BUILDING_COMPONENT_7 = 'building_component_7';

    const BUILDING_COMPONENT_8 = 'building_component_8';

    const COMMERCIAL_DESCRIPTION_FR = 'commercial_description_fr';

    const COMMERCIAL_DESCRIPTION_EN = 'commercial_description_en';

    const BENEFIT_1_FR = 'benefit_1_fr';

    const BENEFIT_2_FR = 'benefit_2_fr';

    const BENEFIT_3_FR = 'benefit_3_fr';

    const BENEFIT_1_EN = 'benefit_1_en';

    const BENEFIT_2_EN = 'benefit_2_en';

    const BENEFIT_3_EN = 'benefit_3_en';

    const COMPLEMENTARY_PRODUCT_1 = 'complementary_product_1';

    const COMPLEMENTARY_PRODUCT_2 = 'complementary_product_2';

    const COMPLEMENTARY_PRODUCT_3 = 'complementary_product_3';

    const TEST_STANDARD_APPROVAL_1 = 'test_standard_approval_1';

    const TEST_STANDARD_APPROVAL_2 = 'test_standard_approval_2';

    const TEST_STANDARD_APPROVAL_3 = 'test_standard_approval_3';

    const TEST_STANDARD_APPROVAL_4 = 'test_standard_approval_4';

    const TEST_STANDARD_APPROVAL_5 = 'test_standard_approval_5';

    const TEST_STANDARD_APPROVAL_6 = 'test_standard_approval_6';

    const SIGNATURE_1 = 'signature_1';

    const SIGNATURE_2 = 'signature_2';

    const SIGNATURE_3 = 'signature_3';

    const INSTALLATION_VIDEO_FR = 'installation_video_fr';

    const INSTALLATION_VIDEO_EN = 'installation_video_en';

    const PROMOTIONAL_VIDEO_FR = 'promotional video_fr';

    const PROMOTIONAL_VIDEO_EN = 'promotional video_en';

    /**
     * Contain the requested attribute model type string
     *
     * @var string
     */
    const ATTRIBUTE_MODEL_TYPE = 'parent_product';

    /**
     * @var array
     */
    public $attribute_link_values_mapping = [];

    /**
     * @var array
     */
    protected $attributeMapping = [];

    /**
     * ParentProductImport constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->header = $this->getHeader();

        $this->signature = 'soprema:import:parentProducts {file : CSV containing the parent products}';

        $this->description = 'Import and create parent products';

        parent::__construct();

        $this->files = $files;
    }

    /**
     * @return String[]
     */
    protected function getHeader()
    {
        return [
            self::FRENCH_PRODUCT_NAME_ROW,
            self::ENGLISH_PROCUCT_NAME_ROW,
            self::WEB_DISPLAY_ROW,
            self::PRODUCT_NATURE_ROW,
            self::PRODUCT_FUNCTION_1_ROW,
            self::PRODUCT_FUNCTION_2_ROW,
            self::PRODUCT_FUNCTION_3_ROW,
            self::PRODUCT_ROLE_1,
            self::PRODUCT_ROLE_2,
            self::PRODUCT_ROLE_3,
            self::BUILDING_COMPONENT_1,
            self::BUILDING_COMPONENT_2,
            self::BUILDING_COMPONENT_3,
            self::BUILDING_COMPONENT_4,
            self::BUILDING_COMPONENT_5,
            self::BUILDING_COMPONENT_6,
            self::BUILDING_COMPONENT_7,
            self::BUILDING_COMPONENT_8,
            self::COMMERCIAL_DESCRIPTION_FR,
            self::COMMERCIAL_DESCRIPTION_EN,
            self::BENEFIT_1_FR,
            self::BENEFIT_2_FR,
            self::BENEFIT_3_FR,
            self::BENEFIT_1_EN,
            self::BENEFIT_2_EN,
            self::BENEFIT_3_EN,
            self::COMPLEMENTARY_PRODUCT_1,
            self::COMPLEMENTARY_PRODUCT_2,
            self::COMPLEMENTARY_PRODUCT_3,
            self::TEST_STANDARD_APPROVAL_1,
            self::TEST_STANDARD_APPROVAL_2,
            self::TEST_STANDARD_APPROVAL_3,
            self::TEST_STANDARD_APPROVAL_4,
            self::TEST_STANDARD_APPROVAL_5,
            self::TEST_STANDARD_APPROVAL_6,
            self::SIGNATURE_1,
            self::SIGNATURE_2,
            self::SIGNATURE_3,
            self::INSTALLATION_VIDEO_FR,
            self::INSTALLATION_VIDEO_EN,
            self::PROMOTIONAL_VIDEO_FR,
            self::PROMOTIONAL_VIDEO_EN,
        ];
    }

    /**
     * @throws Exception
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $this->info('Import products');

        $this->write('> Opening file');

        $file = $this->openFile($this->argument('file'));

        $this->done();

        $this->write('> Reading file');

        $data = $this->readFile($file);

        $this->done();

        $this->write('> Sorting CSV data');

        $data = $this->sortCsvData($data);

        $this->done();

        $this->write('> Retrieving company');

        $company = $this->fetchCompany();

        $this->done();

        $this->write('> Creating Parent Products');

        $row_by_parent_product_id = $this->createParentProducts($data, $company);

        $this->done();

        $this->write('> Creating complementary products');

        $this->createComplementaryProducts($row_by_parent_product_id);

        $this->done();
    }

    /**
     * @param array $data
     * @return array
     */
    protected function sortCsvData(array $data)
    {
        $sorted_data = [];

        $progress = new ProgressBar($this->getOutput(), count($data));
        $progress->start();

        /** Map attribute function by keys*/
        $sorted_function_by_attribute = $this->getAttributeMethodMapping();

        foreach ($data as $row) {
            $sorted_row = [];
            foreach ($sorted_function_by_attribute as $attribute_name => $method_name) {
                $sorted_row[$attribute_name] = $this->$method_name($row);
            }

            $sorted_data[] = array_filter($sorted_row);
            $progress->advance();
        }

        $progress->clear();
        $this->clearLineUp();
        return $sorted_data;
    }

    /**
     * Fetch a company.
     *
     * @return Company
     */
    protected function fetchCompany()
    {
        return Company::query()->where(self::ATTRIBUTE_TYPE_NAME, CompanyTableSeeder::COMPANY_NAME)->first();
    }

    /**
     * Create parent products and their related attributes.
     *
     * @param  Mixed[] $data
     * @param  Company $company
     * @return array
     * @throws Exception
     */
    protected function createParentProducts(array $data, Company $company)
    {
        $row_by_parent_product_id = [];
        $progress = new ProgressBar($this->getOutput(), count($data));
        $progress->start();
        try {
            foreach ($data as $row) {
                $parent_product = ParentProduct::create([
                    'company_id' => $company->id,
                    'status'     => AttributableModelStatus::DRAFT_STATUS,
                ]);
                $this->createParentProductAttributes($parent_product, $row);

                $row_by_parent_product_id[$parent_product->id] = [
                    'parent_product' => $parent_product,
                    'row'            => $row,
                ];

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

        return $row_by_parent_product_id;
    }

    /**
     * This is the main function that create parent products.
     * While creating the main parent products, the function build an array (@see $this->attribute_link_values_mapping)
     * This array contain a list of all the names of imported parent products
     * The main reason while it do so is to reuse then while it will link the complementary product
     * (@see createComplementaryProducts)
     *
     * @param ParentProduct $parent_product
     * @param String[] $row_data
     * @throws Exception
     */
    protected function createParentProductAttributes(ParentProduct $parent_product, array $row_data)
    {
        foreach ($row_data as $attribute_name => $attribute_value) {
            if ($attribute_name != self::ATTRIBUTE_TYPE_COMPLEMENTARY_PRODUCT) {
                $attribute = Attribute::where(self::ATTRIBUTE_TYPE_NAME, '=', $attribute_name)->first();

                if (is_null($attribute)) {
                    throw new Exception("Attribute with name {$attribute_name} is undefined");
                } else {
                    $attribute_id = $attribute->id;
                }

                $link_attribute = $this->createParentProductLinkAttributes($parent_product, $attribute_id);

                if (isset($attribute_value['keys']) && empty($attribute_value['keys'])) {
                    continue;
                }

                /** @var LinkAttributeValue $value */
                $value = $link_attribute->values()->create([
                    'values' => $attribute_value,
                ]);

                if ($attribute_name == self::ATTRIBUTE_TYPE_NAME) {
                    $this->attribute_link_values_mapping[$value->id] = $attribute_value[self::FRENCH_CODE];
                }
            }
        }
    }

    /**
     * @param ParentProduct $parent_product
     * @param $attribute_id
     * @return LinkAttribute
     */
    private function createParentProductLinkAttributes(ParentProduct $parent_product, $attribute_id)
    {
        return $parent_product->linkAttributes()->create(['attribute_id' => $attribute_id]);
    }

    /**
     * @param array $parent_products_map
     * @throws Exception
     */
    protected function createComplementaryProducts(array $parent_products_map)
    {
        $attribute = Attribute::where(
            self::ATTRIBUTE_TYPE_NAME,
            '=',
            self::ATTRIBUTE_TYPE_COMPLEMENTARY_PRODUCT
        )->first();

        $progress = new ProgressBar($this->getOutput(), count($parent_products_map));
        $progress->start();

        foreach ($parent_products_map as $parent_product_data) {
            /** @var ParentProduct $parent_product */
            $parent_product = $parent_product_data['parent_product'];
            $csv_row = $parent_product_data['row'];

            if (isset($csv_row[self::ATTRIBUTE_TYPE_COMPLEMENTARY_PRODUCT])) {
                $parent_product_ids = $this->buildTextLinkMultipleAttributeValue(
                    $csv_row[self::ATTRIBUTE_TYPE_COMPLEMENTARY_PRODUCT]
                );

                if (!empty($parent_product_ids)) {
                    /** @var LinkAttribute $link_attribute */
                    $link_attribute = $this->createParentProductLinkAttributes($parent_product, $attribute->id);
                    $link_attribute->values()->create(['values' => $parent_product_ids]);

                    $progress->advance();
                } else {
                    throw new Exception('
                        Current CSV row contain one or many complementary product but one of them does not match any
                        Attribute value. Please check ' . $csv_row[self::FRENCH_PRODUCT_NAME_ROW] . ' row
                    ');
                }
            }
        }
        $progress->clear();
        $this->clearLineUp();
    }

    /**
     * @param string $attribute_name
     * @param array $choices
     * @throws Exception
     * @return Int[]
     */
    protected function buildChoiceImageNoDisplayLinkAttributeValues($attribute_name, array $choices)
    {
        $attribute_values = $this->getAttributeValuesByName($attribute_name);

        $keys = ['keys' => []];

        foreach ($choices as $choice) {
            $attribute_key = null;

            foreach ($attribute_values as $key => $value) {
                if ($choice == $value[self::ATTRIBUTE_TYPE_NAME]) {
                    $attribute_key = $key;
                    array_push($keys['keys'], $attribute_key);
                }
            }

            if (is_null($attribute_key)) {
                throw new Exception(
                    "buildChoiceImageNoDisplayLinkAttributeValues() error.
                     '{$choice}' have no match for any {$attribute_name}"
                );
            }
        }

        return $keys;
    }

    /**
     * Will return an array of all choices key if any
     * Handle "choice_multiple" and "choice" data type
     *
     * @param String $attribute_name
     * @param array $choices
     * @return array
     * @throws Exception
     */
    protected function buildChoiceLinkAttributeValues($attribute_name, array $choices)
    {
        if (empty($choices)) {
            return [];
        }

        $attribute_values = $this->getAttributeValuesByName($attribute_name);
        $keys = ['keys'=>[]];
        foreach ($choices as $choice) {
            $key = array_search($choice, $attribute_values);
            if ($key !== false) {
                array_push($keys['keys'], $key);
            } else {
                throw new Exception(
                    "buildChoiceLinkAttributeValues() error .'{$choice}' have no match for any {$attribute_name}"
                );
            }
        }

        return $keys;
    }

    /**
     * @param String $french_text
     * @param String $english_text
     * @return String[]
     */
    protected function buildTextLinkAttributeValues($french_text, $english_text)
    {
        $array = [];
        if ($french_text) {
            $array[self::FRENCH_CODE] = $french_text;
        }

        if ($english_text) {
            $array[self::ENGLISH_CODE] = $english_text;
        }
        return $array;
    }

    /**
     * @param String $attribute_name
     * @return String[]
     * @throws Exception
     */
    protected function getAttributeValuesByName($attribute_name)
    {
        $attribute = Attribute::where(self::ATTRIBUTE_TYPE_NAME, $attribute_name)->first();
        if (is_null($attribute)) {
            throw new Exception('No match for column name in Attribute table. => ' . $attribute_name);
        } else {
            $attribute_values = $attribute->value->values[self::FRENCH_CODE];
        }
        return $attribute_values;
    }

    /**
     * @param String $french_values
     * @param String $english_values
     * @return String[]
     */
    protected function buildMultipleTextAttributeValues($french_values, $english_values)
    {
        return [self::FRENCH_CODE => $french_values, self::ENGLISH_CODE => $english_values];
    }

    /**
     * @param String[] $complementary_products
     * @return Integer[]
     * @throws Exception
     */
    protected function buildTextLinkMultipleAttributeValue(array $complementary_products)
    {
        $related_parent_product_id = [];

        foreach ($complementary_products as $complementary_product_name) {
            $link_attribute_value_id = array_search(
                strtolower($complementary_product_name),
                array_map('strtolower', $this->attribute_link_values_mapping)
            );

            if (!$link_attribute_value_id) {
                throw new Exception("{$complementary_product_name} does not match any parent product ");
            }

            $parent_product_id = LinkAttributeValue::find($link_attribute_value_id)->linkAttribute->attributable->id;
            array_push($related_parent_product_id, $parent_product_id);
        }

        return implode(',', $related_parent_product_id);
    }

    /**
     * @param $row_data
     * @return \String[]
     */
    protected function buildNameLinkAttributeValues($row_data)
    {
        return $this->buildTextLinkAttributeValues(
            $row_data[self::FRENCH_PRODUCT_NAME_ROW],
            $row_data[self::ENGLISH_PROCUCT_NAME_ROW]
        );
    }

    /**
     * @param $row_data
     * @return array
     */
    protected function buildProductNatureLinkAttributeValues($row_data)
    {
        return $this->buildChoiceLinkAttributeValues(
            self::ATTRIBUTE_TYPE_PRODUCT_NATURE,
            array_filter([$row_data[self::PRODUCT_NATURE_ROW]])
        );
    }

    /**
     * @param $row_data
     * @return \Int[]
     */
    protected function buildProductFunctionLinkAttributeValues($row_data)
    {
        return $this->buildChoiceImageNoDisplayLinkAttributeValues(
            self::ATTRIBUTE_TYPE_PRODUCT_FUNCTION,
            array_filter([
                $row_data[self::PRODUCT_FUNCTION_1_ROW],
                $row_data[self::PRODUCT_FUNCTION_2_ROW],
                $row_data[self::PRODUCT_FUNCTION_3_ROW],
            ])
        );
    }

    /**
     * @param $row_data
     * @return \Int[]
     */
    protected function buildProductRoleLinkAttributeValues($row_data)
    {
        return $this->buildChoiceImageNoDisplayLinkAttributeValues(
            self::ATTRIBUTE_TYPE_PRODUCT_ROLE,
            array_filter([
                $row_data[self::PRODUCT_ROLE_1],
                $row_data[self::PRODUCT_ROLE_2],
                $row_data[self::PRODUCT_ROLE_3],
            ])
        );
    }

    /**
     * @param $row_data
     * @return array
     */
    protected function buildBuildingComponentLinkAttributeValues($row_data)
    {
        return $this->buildChoiceLinkAttributeValues(
            self::ATTRIBUTE_TYPE_BUILDING_COMPONENT,
            array_filter([
                $row_data[self::BUILDING_COMPONENT_1],
                $row_data[self::BUILDING_COMPONENT_2],
                $row_data[self::BUILDING_COMPONENT_3],
                $row_data[self::BUILDING_COMPONENT_4],
                $row_data[self::BUILDING_COMPONENT_5],
                $row_data[self::BUILDING_COMPONENT_6],
            ])
        );
    }

    /**
     * @param $row_data
     * @return \String[]
     */
    protected function buildProductDescriptionLinkAttributeValues($row_data)
    {
        return $this->buildTextLinkAttributeValues(
            $row_data[self::COMMERCIAL_DESCRIPTION_FR],
            $row_data[self::COMMERCIAL_DESCRIPTION_EN]
        );
    }

    /**
     * @param $row_data
     * @return array
     */
    protected function buildProductBenefitsLinkAttributeValues($row_data)
    {
        return $this->buildMultipleTextAttributeValues(
            array_filter([
                $row_data[self::BENEFIT_1_FR],
                $row_data[self::BENEFIT_2_FR],
                $row_data[self::BENEFIT_3_FR],
            ]),
            array_filter([
                $row_data[self::BENEFIT_1_EN],
                $row_data[self::BENEFIT_2_EN],
                $row_data[self::BENEFIT_3_EN],
            ])
        );
    }

    /**
     * @param $row_data
     * @return \Int[]
     */
    protected function buildProductTestNormApprobationsLinkAttributeValues($row_data)
    {
        return array_filter($this->buildChoiceImageNoDisplayLinkAttributeValues(
            self::ATTRIBUTE_TYPE_PRODUCT_TEST_NORM_APPROBATION,
            array_filter([
                $row_data[self::TEST_STANDARD_APPROVAL_1],
                $row_data[self::TEST_STANDARD_APPROVAL_2],
                $row_data[self::TEST_STANDARD_APPROVAL_3],
                $row_data[self::TEST_STANDARD_APPROVAL_4],
                $row_data[self::TEST_STANDARD_APPROVAL_5],
                $row_data[self::TEST_STANDARD_APPROVAL_6],
            ])
        ));
    }

    /**
     * @param String[] $row_data
     * @return String[]
     */
    protected function buildComplementaryProductLinkAttributeValues($row_data)
    {
        return array_filter([
            $row_data[self::COMPLEMENTARY_PRODUCT_1],
            $row_data[self::COMPLEMENTARY_PRODUCT_2],
            $row_data[self::COMPLEMENTARY_PRODUCT_3],
        ]);
    }

    /**
     * @param String[] $row_data
     * @return String[]
     */
    protected function buildProductSignatureLinkAttributeValues($row_data)
    {
        return $this->buildChoiceLinkAttributeValues(
            self::ATTRIBUTE_TYPE_PRODUCT_SIGNATURE,
            array_filter([
                $row_data[self::SIGNATURE_1],
                $row_data[self::SIGNATURE_2],
                $row_data[self::SIGNATURE_3],
            ])
        );
    }

    /**
     * @param String[] $row_data
     * @return String[]
     */
    protected function buildProductInstallationVideoLinkAttributeValues(array $row_data)
    {
        return $this->buildTextLinkAttributeValues(
            $row_data[self::INSTALLATION_VIDEO_FR],
            $row_data[self::INSTALLATION_VIDEO_EN]
        );
    }

    /**
     * @param String[] $row_data
     * @return String[]
     */
    protected function buildProductPromotionalVideoLinkAttributeValues(array $row_data)
    {
        return $this->buildTextLinkAttributeValues(
            $row_data[self::PROMOTIONAL_VIDEO_FR],
            $row_data[self::PROMOTIONAL_VIDEO_EN]
        );
    }

    /**
     * Map attribute name with a corresponding method.
     * @return String[]
     */
    protected function getAttributeMethodMapping()
    {
        return [
            self::ATTRIBUTE_TYPE_NAME                          => 'buildNameLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_NATURE                => 'buildProductNatureLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_FUNCTION              => 'buildProductFunctionLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_ROLE                  => 'buildProductRoleLinkAttributeValues',
            self::ATTRIBUTE_TYPE_BUILDING_COMPONENT            => 'buildBuildingComponentLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_DESCRIPTION           => 'buildProductDescriptionLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_BENEFITS              => 'buildProductBenefitsLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_TEST_NORM_APPROBATION => 'buildProductTestNormApprobationsLinkAttributeValues',
            self::ATTRIBUTE_TYPE_COMPLEMENTARY_PRODUCT         => 'buildComplementaryProductLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_SIGNATURE             => 'buildProductSignatureLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_INSTALLATION_VIDEO    => 'buildProductInstallationVideoLinkAttributeValues',
            self::ATTRIBUTE_TYPE_PRODUCT_PROMOTIONAL_VIDEO     => 'buildProductPromotionalVideoLinkAttributeValues',
        ];
    }
}

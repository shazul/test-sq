<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeType;
use Pimeo\Models\Company;
use Pimeo\Models\ParentProduct;
use Tests\Libs\CreatesParentProduct;
use Tests\TestCase;
use Tests\Libs\DatabaseSetup;

class SearchKeywordsTest extends TestCase
{
    use DatabaseSetup, CreatesParentProduct;

    /** @var CompanyIndexer */
    private $companyIndexer;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function testAttributeTypeExists()
    {
        $attributeType = AttributeType::whereCode('keywords')->first();

        $this->assertInstanceOf(AttributeType::class, $attributeType, 'Attribute type "keywords" not found.');
    }

    public function testAttributeExistsIfCompanyCreated()
    {
        $this->createACompany();
        $company   = Company::orderBy('id', 'DESC')->first();
        $attribute = Attribute::whereName('search_keywords')->whereCompanyId($company->id)->first();

        $this->assertInstanceOf(Attribute::class, $attribute);
    }

    public function testAttributeIndexed()
    {
        $parentProduct          = $this->createProduct();
        $searchKeywordAttribute = Attribute::whereName('search_keywords')
            ->whereCompanyId($parentProduct->company_id)
            ->first();

        $linkAttribute  = $parentProduct->linkAttributes()->whereAttributeId($searchKeywordAttribute->id)->first();
        $values         = $linkAttribute->values;
        $frenchKeywords = $values['keys']['fr'];

        $indexer = new ProductIndexer($parentProduct->company);
        $indexer->indexOne($parentProduct->id);
        sleep(1);

        $elasticSearchResult = $this->getResults($parentProduct);

        if (!empty($elasticSearchResult->hits)) {
            $document = $elasticSearchResult->hits[0]->_source;

            $this->assertTrue(property_exists($document, 'search_keywords'));
            $this->assertEquals($document->search_keywords, $frenchKeywords);
        } else {
            $this->fail('Parent product not found in ElasticSearch index.');
        }
    }

    private function createACompany()
    {
        $this->visit('/company/create')
            ->type('Soprema (Suisse)', 'name')
            ->select([ParentProduct::class], 'models[]')
            ->press('Save');
    }

    private function createProduct()
    {
        $parentProduct = $this->createValidParentProduct(true, true);

        $childProduct = $this->createValidChildProduct(true);
        $childProduct->update(['status' => AttributableModelStatus::PUBLISHED_STATUS]);

        $childProduct->parent_product_id = $parentProduct->id;
        $childProduct->save();

        return $parentProduct;
    }

    /**
     * @param $parentProduct
     * @param string $productName
     *
     * @return mixed
     */
    private function getResults($parentProduct, $productName = '')
    {
        $indexes   = $this->companyIndexer->getIndexesByCompanyId($parentProduct->company->id);
        $indexName = $indexes['fr'];

        $indexerHost = $this->companyIndexer->client->transport->getConnection()->getHost();

        $base_url = "http://$indexerHost/$indexName/product/_search?pretty";
        $query = empty($productName) ? '' : "&q=name:$productName";

        return json_decode(file_get_contents($base_url . urlencode($query)))->hits;
    }
}

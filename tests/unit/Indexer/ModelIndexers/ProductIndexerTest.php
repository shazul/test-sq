<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\ProductIndexer;
use Tests\Libs\DatabaseSetup;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\Traits\QueryableFields;
use Tests\TestCase;

class ProductIndexerTest extends TestCase
{
    use DatabaseSetup, QueryableFields;

    /** @var  ProductIndexer */
    protected $class;

    /** @var CompanyIndexer */
    private $companyIndexer;

    protected $productsQty = 3;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function test_indexOne()
    {
        $parentProduct = $this->createProduct();
        $productName = $this->getProductName($parentProduct);

        $indexer = new ProductIndexer($parentProduct->company);
        $indexer->indexOne($parentProduct->id);
        sleep(1);

        $results = $this->getResults($parentProduct, $productName);

        $this->assertTrue($results->total > 0);
    }

    public function test_deleteOne()
    {
        $parentProduct = $this->createProduct();
        $productName = $this->getProductName($parentProduct);

        $indexer = new ProductIndexer($parentProduct->company);
        $indexer->indexOne($parentProduct->id);
        sleep(1);
        $indexer->deleteOne($parentProduct->id);
        sleep(1);
        $results = $this->getResults($parentProduct, $productName);
        $this->assertEmpty($results->total, 0);
    }

    public function test_indexAll()
    {
        for ($i = 0; $i < $this->productsQty; $i++) {
            $parentProduct = $this->createProduct();
        }

        $indexer = new ProductIndexer($parentProduct->company);
        $indexer->indexAll();
        sleep(1);

        $results = $this->getResults($parentProduct);

        $this->assertEquals($this->productsQty, $results->total);
    }

    public function test_deleteAll()
    {
        for ($i = 0; $i < $this->productsQty; $i++) {
            $parentProduct = $this->createProduct();
        }

        $indexer = new ProductIndexer($parentProduct->company);
        $indexer->indexAll();
        sleep(1);

        $indexer->deleteAll();
        sleep(1);

        $results = $this->getResults($parentProduct);

        $this->assertEmpty($results->total);
    }

    /**
     * @param $parentProduct
     * @param $productName
     *
     * @return mixed
     */
    private function getResults($parentProduct, $productName = '')
    {
        $indexes = $this->companyIndexer->getIndexesByCompanyId($parentProduct->company->id);
        $indexName = $indexes['fr'];

        $indexerHost = $this->companyIndexer->client->transport->getConnection()->getHost();

        $base_url = "http://$indexerHost/$indexName/product/_search?pretty";
        $query = empty($productName) ? '' : "&q=name:$productName";

        return json_decode(file_get_contents($base_url . urlencode($query)))->hits;
    }

    /**
     * @return ParentProduct
     */
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
     *
     * @return mixed
     */
    private function getProductName($parentProduct)
    {
        $productValues = $this->getQueryForFields('parent_products', ParentProduct::class, ['name'])
            ->where('parent_products.id', $parentProduct->id)->first()->values;
        return json_decode($productValues)->fr;
    }
}

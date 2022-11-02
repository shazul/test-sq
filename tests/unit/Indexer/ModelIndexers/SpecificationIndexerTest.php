<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\SpecificationIndexer;
use Pimeo\Models\Specification;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class SpecificationIndexerTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyIndexer */
    private $companyIndexer;

    protected $specificationsQty = 3;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function test_index_one()
    {
        $specificationId = $this->createValidSpecification();
        $specification = Specification::find($specificationId);

        $indexer = new SpecificationIndexer($specification->company);
        $indexer->indexOne($specification->id);
        sleep(1);

        $results = $this->getResults($specification->company);

        $this->assertTrue($results->total > 0);
    }

    public function test_index_all()
    {
        for ($i = 0; $i < $this->specificationsQty; $i++) {
            $specificationId = $this->createValidSpecification();
        }
        $specification = Specification::find($specificationId);

        $indexer = new SpecificationIndexer($specification->company);
        $indexer->indexAll();
        sleep(1);

        $results = $this->getResults($specification->company);

        $this->assertEquals($results->total, $this->specificationsQty);
    }

    public function test_delete_one()
    {
        $specificationId = $this->createValidSpecification();
        $specification = Specification::find($specificationId);

        $indexer = new SpecificationIndexer($specification->company);
        $indexer->indexOne($specification->id);
        sleep(1);

        $indexer->deleteOne($specificationId);
        sleep(1);

        $results = $this->getResults($specification->company);

        $this->assertEquals($results->total, 0);
    }

    public function test_delete_all()
    {
        for ($i = 0; $i < $this->specificationsQty; $i++) {
            $specificationId = $this->createValidSpecification();
        }
        $specification = Specification::find($specificationId);

        $indexer = new SpecificationIndexer($specification->company);
        $indexer->indexAll();
        sleep(1);

        $indexer->deleteAll();
        sleep(1);

        $results = $this->getResults($specification->company);

        $this->assertEquals($results->total, 0);
    }

    /**
     * @param $company
     *
     * @return mixed
     */
    private function getResults($company)
    {
        $indexes = $this->companyIndexer->getIndexesByCompanyId($company->id);
        $indexName = $indexes['fr'];

        $indexerHost = $this->companyIndexer->client->transport->getConnection()->getHost();

        $base_url = "http://$indexerHost/$indexName/devis/_search?pretty";

        return json_decode(file_get_contents($base_url))->hits;
    }
}

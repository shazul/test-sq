<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\SystemIndexer;
use Pimeo\Models\System;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class SystemIndexerTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyIndexer */
    private $companyIndexer;

    protected $systemsQty = 3;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function test_index_one()
    {
        $systemId = $this->createValidSystem(true);
        $system = System::find($systemId);

        $indexer = new SystemIndexer($system->company);
        $indexer->indexOne($system->id);
        sleep(1);

        $results = $this->getResults($system->company);

        $this->assertTrue($results->total > 0);
    }

    public function test_index_all()
    {
        for ($i = 0; $i < $this->systemsQty; $i++) {
            $systemId = $this->createValidSystem();
        }
        $system = System::find($systemId);

        $indexer = new SystemIndexer($system->company);
        $indexer->indexAll();
        sleep(1);

        $results = $this->getResults($system->company);

        $this->assertEquals($results->total, $this->systemsQty);
    }

    public function test_delete_one()
    {
        $systemId = $this->createValidSystem();
        $system = System::find($systemId);

        $indexer = new SystemIndexer($system->company);
        $indexer->indexOne($system->id);
        sleep(1);

        $indexer->deleteOne($systemId);
        sleep(1);

        $results = $this->getResults($system->company);

        $this->assertEquals($results->total, 0);
    }

    public function test_delete_all()
    {
        for ($i = 0; $i < $this->systemsQty; $i++) {
            $systemId = $this->createValidSystem();
        }
        $system = System::find($systemId);

        $indexer = new SystemIndexer($system->company);
        $indexer->indexAll();
        sleep(1);

        $indexer->deleteAll();
        sleep(1);

        $results = $this->getResults($system->company);

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

        $base_url = "http://$indexerHost/$indexName/system/_search?pretty";

        return json_decode(file_get_contents($base_url))->hits;
    }
}

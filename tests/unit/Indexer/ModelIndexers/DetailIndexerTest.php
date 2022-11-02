<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\DetailIndexer;
use Pimeo\Models\Detail;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class DetailIndexerTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyIndexer */
    private $companyIndexer;

    protected $detailsQty = 4;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function test_index_one()
    {
        $detailId = $this->createValidDetail();
        $detail = Detail::find($detailId);

        $indexer = new DetailIndexer($detail->company);
        $indexer->indexOne($detail->id);
        sleep(1);

        $results = $this->getResults($detail->company);

        $this->assertTrue($results->total > 0);
    }

    public function test_index_all()
    {
        for ($i = 0; $i < $this->detailsQty; $i++) {
            $detailId = $this->createValidDetail();
        }
        $detail = Detail::find($detailId);

        $indexer = new DetailIndexer($detail->company);
        $indexer->indexAll();
        sleep(1);

        $results = $this->getResults($detail->company);

        $this->assertEquals($results->total, $this->detailsQty);
    }

    public function test_delete_one()
    {
        $detailId = $this->createValidDetail();
        $detail = Detail::find($detailId);

        $indexer = new DetailIndexer($detail->company);
        $indexer->indexOne($detail->id);
        sleep(1);

        $indexer->deleteOne($detailId);
        sleep(1);

        $results = $this->getResults($detail->company);

        $this->assertEquals($results->total, 0);
    }

    public function test_delete_all()
    {
        for ($i = 0; $i < $this->detailsQty; $i++) {
            $detailId = $this->createValidDetail();
        }
        $detail = Detail::find($detailId);

        $indexer = new DetailIndexer($detail->company);
        $indexer->indexAll();
        sleep(1);

        $indexer->deleteAll();
        sleep(1);

        $results = $this->getResults($detail->company);

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

        $base_url = "http://$indexerHost/$indexName/details/_search?pretty";

        return json_decode(file_get_contents($base_url))->hits;
    }
}

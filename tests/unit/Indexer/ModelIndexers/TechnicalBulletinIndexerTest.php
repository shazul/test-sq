<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\TechnicalBulletinIndexer;
use Pimeo\Models\TechnicalBulletin;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class TechnicalBulletinIndexerTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyIndexer */
    private $companyIndexer;

    protected $technicalBulletinsQty = 3;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function test_index_one()
    {
        $technicalBulletinId = $this->createValidTechnicalBulletin();
        $technicalBulletin = TechnicalBulletin::find($technicalBulletinId);

        $indexer = new TechnicalBulletinIndexer($technicalBulletin->company);
        $indexer->indexOne($technicalBulletin->id);
        sleep(1);

        $results = $this->getResults($technicalBulletin->company);

        $this->assertTrue($results->total > 0);
    }

    public function test_index_all()
    {
        for ($i = 0; $i < $this->technicalBulletinsQty; $i++) {
            $technicalBulletinId = $this->createValidTechnicalBulletin();
        }
        $technicalBulletin = TechnicalBulletin::find($technicalBulletinId);

        $indexer = new TechnicalBulletinIndexer($technicalBulletin->company);
        $indexer->indexAll();
        sleep(1);

        $results = $this->getResults($technicalBulletin->company);

        $this->assertEquals($results->total, $this->technicalBulletinsQty);
    }

    public function test_delete_one()
    {
        $technicalBulletinId = $this->createValidTechnicalBulletin();
        $technicalBulletin = TechnicalBulletin::find($technicalBulletinId);

        $indexer = new TechnicalBulletinIndexer($technicalBulletin->company);
        $indexer->indexOne($technicalBulletin->id);
        sleep(1);

        $indexer->deleteOne($technicalBulletinId);
        sleep(1);

        $results = $this->getResults($technicalBulletin->company);

        $this->assertEquals($results->total, 0);
    }

    public function test_delete_all()
    {
        for ($i = 0; $i < $this->technicalBulletinsQty; $i++) {
            $technicalBulletinId = $this->createValidTechnicalBulletin();
        }
        $technicalBulletin = TechnicalBulletin::find($technicalBulletinId);

        $indexer = new TechnicalBulletinIndexer($technicalBulletin->company);
        $indexer->indexAll();
        sleep(1);

        $indexer->deleteAll();
        sleep(1);

        $results = $this->getResults($technicalBulletin->company);

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

        $base_url = "http://$indexerHost/$indexName/technical_bulletins/_search?pretty";

        return json_decode(file_get_contents($base_url))->hits;
    }
}

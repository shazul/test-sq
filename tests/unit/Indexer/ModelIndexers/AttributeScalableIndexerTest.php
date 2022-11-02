<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\AttributeScalableIndexer;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class AttributeScalableIndexerTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyIndexer */
    private $companyIndexer;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer = app(CompanyIndexer::class);
    }

    public function test_index_one_choice_attribute()
    {
        $company = Company::first();
        $attribute = $this->getAttributes()->random();

        $indexer = new AttributeScalableIndexer($company);
        $indexer->indexOne($attribute->id);
        sleep(1);

        $results = $this->getResults($company);

        $this->assertTrue($results->total > 0);
    }

    public function test_do_not_index_one_no_choice_attribute()
    {
        $company = Company::first();
        $attribute = $this->getAttributes(false)->random();

        $indexer = new AttributeScalableIndexer($company);
        $indexer->indexOne($attribute->id);
        sleep(1);

        $results = $this->getResults($company);

        $this->assertEquals($results->total, 0);
    }

    public function test_index_all_that_should_be_indexed()
    {
        $company = Company::first();
        $this->makeChoiceAttributesIndexable();

        $attributesToIndex = $this->getAttributes();

        $indexer = new AttributeScalableIndexer($company);
        $indexer->indexAll();
        sleep(1);

        $results = $this->getResults($company);

        $this->assertEquals($results->total, count($attributesToIndex));
    }

    public function test_delete_one()
    {
        $company = Company::first();
        $attribute = $this->getAttributes()->random();

        $indexer = new AttributeScalableIndexer($company);
        $indexer->indexOne($attribute->id);
        sleep(1);

        $indexer->deleteOne($attribute->id);
        sleep(1);

        $results = $this->getResults($company);

        $this->assertEquals($results->total, 0);
    }

    public function test_delete_all()
    {
        $company = Company::first();
        $this->makeChoiceAttributesIndexable();

        $indexer = new AttributeScalableIndexer($company);
        $indexer->indexAll();
        sleep(1);

        $indexer->deleteAll();
        sleep(1);

        $results = $this->getResults($company);

        $this->assertEmpty($results->total);
    }

    /**
     * @param boolean $indexable
     * @return mixed
     */
    protected function getAttributes($indexable = true)
    {
        $shouldUseLike = $indexable ? '' : 'not ';

        return Attribute::with('value', 'label', 'type')
            ->where('has_value', $indexable)
            ->whereHas('type', function ($query) use ($shouldUseLike) {
                $query->where('code', $shouldUseLike . 'like', '%choice%');
            })
            ->get();
    }

    protected function makeChoiceAttributesIndexable()
    {
        $indexableAttributes = $this->getAttributes();

        foreach ($indexableAttributes as $attribute) {
            $attribute->should_index = 1;
            $attribute->save();
        }
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

        $base_url = "http://$indexerHost/$indexName/attribute/_search?pretty";

        return json_decode(file_get_contents($base_url))->hits;
    }
}

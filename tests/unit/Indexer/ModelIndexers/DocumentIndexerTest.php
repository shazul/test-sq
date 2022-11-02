<?php

namespace Tests\Unit;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Indexer\ModelIndexers\DocumentIndexer;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class DocumentIndexerTest extends TestCase
{
    use DatabaseSetup;

    /** @var CompanyIndexer */
    private $companyIndexer;

    /** @var DocumentIndexer */
    private $documentIndexer;

    /** @var array */
    private $documents = [];

    /** @var array */
    private $models = [];

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->companyIndexer  = app(CompanyIndexer::class);
        $this->documentIndexer = new DocumentIndexer(current_company());
    }

    public function testIndexOne()
    {
        $this->createAttributableModelsWithDocuments();

        if (count($this->documents) > 0) {
            $this->documentIndexer->indexOne($this->documents[0]->id);
            sleep(1);

            $results = $this->getResults(current_company());
            $this->assertEquals(1, $results->total);
        } else {
            self::fail('No documents have been generated or linked to a model.');
        }
    }

    public function testIndexAll()
    {
        $this->createAttributableModelsWithDocuments();

        if (count($this->documents) > 0) {
            $this->documentIndexer->indexAll();
            sleep(1);

            $results = $this->getResults(current_company());
            $this->assertEquals(count($this->documents), $results->total);
        } else {
            self::fail('No documents have been generated or linked to a model.');
        }
    }

    public function testDeleteOne()
    {
        $this->createAttributableModelsWithDocuments();

        if (count($this->documents) > 0) {
            $this->documentIndexer->indexOne($this->documents[0]->id);
            sleep(1);

            $this->documentIndexer->deleteOne($this->documents[0]->id);
            sleep(1);

            $results = $this->getResults(current_company());

            $this->assertEquals(0, $results->total);
        } else {
            self::fail('No documents have been generated or linked to a model.');
        }
    }

    public function testDeleteAll()
    {
        $this->createAttributableModelsWithDocuments();

        $this->documentIndexer->indexAll();
        sleep(1);

        $this->documentIndexer->deleteAll();
        sleep(1);

        $results = $this->getResults(current_company());
        $this->assertEquals(0, $results->total);
    }

    public function testDeleteAllFromAttributableModel()
    {
        foreach ($this->models as $model) {
            $this->documentIndexer->deleteAllFromAttributableModel($model->id, get_class($model));
        }

        $results = $this->getResults(current_company());
        $this->assertEquals(0, $results->total);
    }

    private function createAttributableModelsWithDocuments()
    {
        $technicalBulletinId = $this->createValidTechnicalBulletin();
        $technicalBulletin   = TechnicalBulletin::find($technicalBulletinId);

        $specificationId = $this->createValidSpecification();
        $specification   = Specification::find($specificationId);

        $detailId = $this->createValidDetail();
        $detail   = Detail::find($detailId);

        /** @var ChildProduct $childProduct */
        $childProduct = $this->createValidChildProduct();
        /** @var ParentProduct $parentProduct */
        $parentProduct = $this->createValidParentProduct();

        $systemId = $this->createValidSystem();
        $system   = System::find($systemId);

        $this->documents = array_merge(
            $this->documentIndexer->getAllDocumentFromAttributableModel($technicalBulletin)->all(),
            $this->documentIndexer->getAllDocumentFromAttributableModel($specification)->all(),
            $this->documentIndexer->getAllDocumentFromAttributableModel($detail)->all(),
            $this->documentIndexer->getAllDocumentFromAttributableModel($childProduct)->all(),
            $this->documentIndexer->getAllDocumentFromAttributableModel($parentProduct)->all(),
            $this->documentIndexer->getAllDocumentFromAttributableModel($system)->all()
        );

        foreach ($this->documents as $key => $document) {
            if (! $document->attributable->isIndexable()) {
                unset($this->documents[$key]);
            }
        }

        $this->models[get_class($technicalBulletin)] = $technicalBulletin;
        $this->models[get_class($specification)]     = $specification;
        $this->models[get_class($detail)]            = $detail;
        $this->models[get_class($childProduct)]      = $childProduct;
        $this->models[get_class($parentProduct)]     = $parentProduct;
        $this->models[get_class($system)]            = $system;
    }

    private function getResults($company)
    {
        $indexes   = $this->companyIndexer->getIndexesByCompanyId($company->id);
        $indexName = $indexes['fr'];

        $indexerHost = $this->companyIndexer->client->transport->getConnection()->getHost();

        $base_url = "http://$indexerHost/$indexName/_search?type=document";

        return json_decode(file_get_contents($base_url))->hits;
    }
}

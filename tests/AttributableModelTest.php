<?php

namespace Tests;

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Models\Attributable;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Tests\Libs\DatabaseSetup;

class AttributableModelTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
        app(CompanyIndexer::class)->indexCompanies();
    }

    public function testAttributableModelIsIndexable()
    {
        $technicalBulletinId = $this->createValidTechnicalBulletin();
        /** @var TechnicalBulletin $technicalBulletin */
        $technicalBulletin = TechnicalBulletin::find($technicalBulletinId);
        $this->assertTrue($technicalBulletin->isIndexable());

        $technicalBulletin->status = AttributableModelStatus::INCOMPLETE_STATUS;
        $this->assertFalse($technicalBulletin->isIndexable());

        $technicalBulletin->status = AttributableModelStatus::COMPLETE_STATUS;
        $this->assertTrue($technicalBulletin->isIndexable());

        while (! $technicalBulletin->mediaLinks->isEmpty()) {
            $technicalBulletin->mediaLinks->pop();
        }
        $this->assertFalse($technicalBulletin->isIndexable());
    }

    public function testProductIsIndexable()
    {
        /** @var ParentProduct $parentProduct */
        $childProduct  = $this->createValidChildProduct(true, true);
        $parentProduct = $this->createValidParentProduct(true, true);

        $childProduct->parent_product_id = $parentProduct->id;
        $childProduct->save();
        $parentProduct->childProducts->add($childProduct);

        $this->assertTrue($parentProduct->isIndexable());
    }

    public function testGetName()
    {
        $technicalBulletinId = $this->createValidTechnicalBulletin();
        /** @var TechnicalBulletin $technicalBulletin */
        $technicalBulletin = TechnicalBulletin::find($technicalBulletinId);
        $this->assertNotEmpty($technicalBulletin->getName(current_language_code()));

        $specificationId = $this->createValidSpecification();
        /** @var Specification $specification */
        $specification = Specification::find($specificationId);
        $this->assertNotEmpty($specification->getName(current_language_code()));

        $detailId = $this->createValidDetail();
        /** @var Detail $detail */
        $detail = Detail::find($detailId);
        $this->assertNotEmpty($detail->getName(current_language_code()));

        /** @var ChildProduct $childProduct */
        $childProduct = $this->createValidChildProduct();
        $this->assertNotEmpty($childProduct->getName(current_language_code()));

        /** @var ParentProduct $parentProduct */
        $parentProduct = $this->createValidParentProduct();
        $this->assertNotEmpty($parentProduct->getName(current_language_code()));

        $systemId = $this->createValidSystem();
        /** @var System $system */
        $system = System::find($systemId);
        $this->assertNotEmpty($system->getName(current_language_code()));
    }
}

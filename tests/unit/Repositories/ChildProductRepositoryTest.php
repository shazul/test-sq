<?php

namespace Tests\Unit;

use Pimeo\Repositories\ChildProductRepository;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class ChildProductRepositoryTest extends TestCase
{
    use DatabaseSetup;

    /** @var  ChildProductRepository */
    protected $class;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->class = app(ChildProductRepository::class);
    }

    public function test_natureID()
    {
        $child = $this->createValidChildProduct($this->editor->getCompany()->languages);
        $parent = $this->createValidParentProduct($this->editor->getCompany()->languages);
        $child->parent_product_id = $parent->id;
        $child->save();

        $parentNature = $parent->nature_id;
        $childNature = $this->class->natureID($child);

        $this->assertEquals($parentNature, $childNature);
    }
}

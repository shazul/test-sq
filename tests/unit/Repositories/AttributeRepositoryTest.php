<?php

namespace Tests\Unit;

use Pimeo\Models\Nature;
use Pimeo\Repositories\AttributeRepository;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class AttributeRepositoryTest extends TestCase
{
    use DatabaseSetup;

    /** @var  ChildProductRepository */
    protected $class;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
        $this->class = app(AttributeRepository::class);
    }

    public function test_allWhereNature()
    {
        $attributes = $this->class->allWhereNature(Nature::first()->id, 'child_product');

        $this->assertFalse($attributes->contains('model_type', 'parent_product'));
    }
}

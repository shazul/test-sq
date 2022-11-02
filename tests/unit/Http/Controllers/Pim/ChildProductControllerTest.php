<?php

use Pimeo\Events\Pim\ChildProductWasDeleted;
use Pimeo\Jobs\Pim\ChildProduct\DeleteChildProduct;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\LinkAttribute;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class ChildProductControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function test_list_products()
    {
        $this->get('/child-product/listing/')->seeStatusCode(200);
    }

    public function test_create_child_product()
    {
        $childProduct = $this->createValidChildProduct();

        $this->seeInDatabase(
            'child_products',
            ['id' => $childProduct->id, 'status' => AttributableModelStatus::DRAFT_STATUS]
        );
    }

    public function test_create_complete_child_product()
    {
        $childProduct = $this->createValidChildProduct(true);

        $this->seeInDatabase(
            'child_products',
            ['id' => $childProduct->id, 'status' => AttributableModelStatus::DRAFT_STATUS]
        );
    }

    public function test_edit_child_product()
    {
        $childProduct = $this->createValidChildProduct();

        $edited_product = $this->editChildProduct($childProduct);

        self::assertNotEquals($childProduct->updated_at->timestamp, $edited_product->updated_at->timestamp);
    }

    public function test_delete_childProduct()
    {
        $childProduct = $this->createValidChildProduct();
        $linked_attributes_id = $childProduct->linkAttributes()->pluck('id');

        $delete_request = new DeleteChildProduct($childProduct);
        // $this->expectsEvents(ChildProductWasDeleted::class);
        $delete_request->handle();

        $existing_linked_attribute = LinkAttribute::whereIn('id', $linked_attributes_id)->get()->toArray();
        $existing_product = ChildProduct::find($childProduct->id);
        self::assertTrue($existing_product === null && empty($existing_linked_attribute));
    }
}

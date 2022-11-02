<?php

use Pimeo\Events\Pim\SpecificationWasDeleted;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Jobs\Pim\Specification\DeleteSpecification;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Specification;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class SpecificationControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function test_an_editor_can_list_specifications()
    {
        $this->actingAs($this->editor);

        $this->get('/specification/listing')->seeStatusCode(200);
    }

    public function test_an_editor_can_create_a_specification()
    {
        $this->actingAs($this->editor);

        $last_specification_id = $this->createValidSpecification();

        $this->seeInDatabase(
            'specifications',
            ['id' => $last_specification_id, 'status' => AttributableModelStatus::COMPLETE_STATUS]
        );
    }

    public function test_create_specification_created_by()
    {
        $this->actingAs($this->editor);

        $last_specification_id = $this->createValidSpecification();

        $this->seeInDatabase('specifications', [
            'id'         => $last_specification_id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->editor->id,
        ]);
    }

    public function test_an_editor_can_edit_a_specification()
    {
        $this->actingAs($this->editor);

        $last_specification_id = $this->createValidSpecification();

        /** @var Specification $specification */
        $specification = Specification::find($last_specification_id)->first();

        $edited_specification = $this->editValidSpecification($specification);

        self::assertNotEquals($specification->updated_at->timestamp, $edited_specification->updated_at->timestamp);
    }

    public function test_edit_a_specification_updated_by()
    {
        $this->actingAs($this->editor);
        $last_specification_id = $this->createValidSpecification();
        /** @var Specification $specification */
        $specification = Specification::find($last_specification_id)->first();

        $this->actingAs($this->admin);
        $edited_specification = $this->editValidSpecification($specification);

        $this->seeInDatabase('specifications', [
            'id'         => $edited_specification->id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->admin->id,
        ]);
    }

    public function test_an_editor_can_delete_a_specification()
    {
        $this->actingAs($this->editor);
        $last_specification_id = $this->createValidSpecification();
        $specification = Specification::find($last_specification_id);
        $linked_attributes_id = array_pluck($specification->linkAttributes()->get()->toArray(), 'id');

        $delete_request = new DeleteSpecification($specification);
        $this->expectsEvents(SpecificationWasDeleted::class);
        $delete_request->handle();
        $existing_linked_attribute = LinkAttribute::whereIn('id', $linked_attributes_id)->get()->toArray();
        $existing_specification = Specification::find($last_specification_id);

        $specification_deleted = false;
        if ($existing_specification === null && empty($existing_linked_attribute)) {
            $specification_deleted = true;
        }
        self::assertTrue($specification_deleted);
    }
}

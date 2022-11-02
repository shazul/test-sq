<?php

use Pimeo\Events\Pim\DetailWasDeleted;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Jobs\Pim\Detail\DeleteDetail;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Detail;
use Pimeo\Models\LinkAttribute;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class DetailControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function test_an_editor_can_list_detail()
    {
        $this->actingAs($this->editor);

        $this->get('/detail/listing/')->seeStatusCode(200);
    }

    public function test_an_editor_can_create_a_detail()
    {
        $this->actingAs($this->editor);

        $last_detail_id = $this->createValidDetail();

        $this->seeInDatabase(
            'details',
            ['id' => $last_detail_id, 'status' => AttributableModelStatus::COMPLETE_STATUS]
        );
    }

    public function test_create_detail_created_by()
    {
        $this->actingAs($this->editor);

        $last_detail_id = $this->createValidDetail();

        $this->seeInDatabase('details', [
            'id'         => $last_detail_id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->editor->id,
        ]);
    }

    public function test_an_editor_can_edit_a_detail()
    {
        $this->actingAs($this->editor);

        $last_detail_id = $this->createValidDetail();

        /** @var Detail $detail */
        $detail = Detail::find($last_detail_id)->first();

        $edited_detail = $this->editValidDetail($detail);

        self::assertNotEquals($detail->updated_at->timestamp, $edited_detail->updated_at->timestamp);
    }

    public function test_edit_a_detail_updated_by()
    {
        $this->actingAs($this->editor);
        $last_detail_id = $this->createValidDetail();

        /** @var Detail $detail */
        $detail = Detail::find($last_detail_id)->first();

        $this->actingAs($this->admin);
        $edited_detail = $this->editValidDetail($detail);

        $this->seeInDatabase('details', [
            'id'         => $edited_detail->id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->admin->id,
        ]);
    }

    public function test_an_editor_can_delete_a_detail()
    {
        $this->actingAs($this->editor);
        $last_detail_id = $this->createValidDetail();
        $detail = Detail::find($last_detail_id);
        $linked_attributes_id = array_pluck($detail->linkAttributes()->get()->toArray(), 'id');

        $delete_request = new DeleteDetail($detail);
        $this->expectsEvents(DetailWasDeleted::class);
        $delete_request->handle();
        $existing_linked_attribute = LinkAttribute::whereIn('id', $linked_attributes_id)->get()->toArray();
        $existing_detail = Detail::find($last_detail_id);

        $detail_deleted = false;
        if ($existing_detail === null && empty($existing_linked_attribute)) {
            $detail_deleted = true;
        }
        self::assertTrue($detail_deleted);
    }
}

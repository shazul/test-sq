<?php

use Pimeo\Events\Pim\SystemWasDeleted;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Jobs\Pim\System\DeleteSystem;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\System;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class SystemControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();

        app(CompanyIndexer::class)->indexCompanies();
    }

    public function test_an_editor_can_list_systems()
    {
        $this->actingAs($this->editor);

        $this->get('/system/listing')->seeStatusCode(200);
    }

    public function test_an_editor_can_create_a_system()
    {
        $this->actingAs($this->editor);

        $last_system_id = $this->createValidSystem();

        $this->seeInDatabase(
            'systems',
            ['id' => $last_system_id, 'status' => AttributableModelStatus::INCOMPLETE_STATUS]
        );
    }

    public function test_create_system_created_by()
    {
        $this->actingAs($this->editor);

        $last_system_id = $this->createValidSystem();

        $this->seeInDatabase('systems', [
            'id'         => $last_system_id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->editor->id,
        ]);
    }

    public function test_an_editor_can_edit_a_system()
    {
        $this->actingAs($this->editor);

        $last_system_id = $this->createValidSystem();

        /** @var System $system */
        $system = System::find($last_system_id)->first();

        $edited_system = $this->editValidSystem($system);

        self::assertNotEquals($system->updated_at->timestamp, $edited_system->updated_at->timestamp);
    }

    public function test_edit_a_system_updated_by()
    {
        $this->actingAs($this->editor);
        $last_system_id = $this->createValidSystem();
        /** @var System $system */
        $system = System::find($last_system_id)->first();

        $this->actingAs($this->admin);
        $edited_system = $this->editValidSystem($system);

        $this->seeInDatabase('systems', [
            'id'         => $edited_system->id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->admin->id,
        ]);
    }

    public function test_an_editor_can_delete_a_system()
    {
        $this->actingAs($this->editor);
        $last_system_id = $this->createValidSystem();
        $system = System::find($last_system_id);
        $linked_attributes_id = array_pluck($system->linkAttributes()->get()->toArray(), 'id');

        $delete_request = new DeleteSystem($system);
        $this->expectsEvents(SystemWasDeleted::class);
        $delete_request->handle();
        $existing_linked_attribute = LinkAttribute::whereIn('id', $linked_attributes_id)->get()->toArray();
        $existing_system = System::find($last_system_id);

        $system_deleted = false;
        if ($existing_system === null && empty($existing_linked_attribute)) {
            $system_deleted = true;
        }
        self::assertTrue($system_deleted);
    }

    public function testBuildingComponentsAreShown()
    {
        $this->actingAs($this->editor);

        $systemId = $this->createValidSystem();
        $system   = System::find($systemId);

        $this->visit('/system/listing/incomplete')->seeStatusCode(200);

        $this->see($system->getName(current_language_code()));
        $this->see($system->getBuildingComponentsInlineTitles());
    }
}

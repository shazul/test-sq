<?php

use Pimeo\Events\Pim\TechnicalBulletinWasDeleted;
use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Jobs\Pim\TechnicalBulletin\DeleteTechnicalBulletin;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\TechnicalBulletin;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class TechnicalBulletinControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function test_an_editor_can_list_technical_bulletins()
    {
        $this->actingAs($this->editor);

        $this->get('/technical-bulletin/listing')->seeStatusCode(200);
    }

    public function test_an_editor_can_create_a_technical_bulletin()
    {
        $this->actingAs($this->editor);

        $last_technical_bulletin_id = $this->createValidTechnicalBulletin();

        $this->seeInDatabase(
            'technical_bulletins',
            ['id' => $last_technical_bulletin_id, 'status' => AttributableModelStatus::COMPLETE_STATUS]
        );
    }

    public function test_create_technical_bulletin_created_by()
    {
        $this->actingAs($this->editor);

        $last_technical_bulletin_id = $this->createValidTechnicalBulletin();

        $this->seeInDatabase('technical_bulletins', [
            'id'         => $last_technical_bulletin_id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->editor->id,
        ]);
    }

    public function test_an_editor_can_edit_a_technical_bulletin()
    {
        $this->actingAs($this->editor);

        $last_technical_bulletin_id = $this->createValidTechnicalBulletin();

        /** @var TechnicalBulletin $technical_bulletin */
        $technical_bulletin = TechnicalBulletin::find($last_technical_bulletin_id)->first();

        /** @var TechnicalBulletin $edited_technical_bulletin */
        $edited_technical_bulletin = $this->editValidTechnicalBulletin($technical_bulletin);

        self::assertNotEquals(
            $technical_bulletin->updated_at->timestamp,
            $edited_technical_bulletin->updated_at->timestamp
        );
    }

    public function test_edit_a_technical_bulletin_updated_by()
    {
        $this->actingAs($this->editor);
        $last_technical_bulletin_id = $this->createValidTechnicalBulletin();
        /** @var TechnicalBulletin $technical_bulletin */
        $technical_bulletin = TechnicalBulletin::find($last_technical_bulletin_id)->first();

        $this->actingAs($this->admin);
        $edited_technical_bulletin = $this->editValidTechnicalBulletin($technical_bulletin);

        $this->seeInDatabase('technical_bulletins', [
            'id'         => $edited_technical_bulletin->id,
            'created_by' => $this->editor->id,
            'updated_by' => $this->admin->id,
        ]);
    }

    public function test_an_editor_can_delete_a_technical_bulletin()
    {
        $this->actingAs($this->editor);
        $last_technical_bulletin_id = $this->createValidTechnicalBulletin();
        $technical_bulletin = TechnicalBulletin::find($last_technical_bulletin_id);
        $linked_attributes_id = array_pluck($technical_bulletin->linkAttributes()->get()->toArray(), 'id');

        $delete_request = new DeleteTechnicalBulletin($technical_bulletin);
        $this->expectsEvents(TechnicalBulletinWasDeleted::class);
        $delete_request->handle();
        $existing_linked_attribute = LinkAttribute::whereIn('id', $linked_attributes_id)->get()->toArray();
        $existing_technical_bulletin = TechnicalBulletin::find($last_technical_bulletin_id);

        $technical_bulletin_deleted = false;
        if ($existing_technical_bulletin === null && empty($existing_linked_attribute)) {
            $technical_bulletin_deleted = true;
        }
        self::assertTrue($technical_bulletin_deleted);
    }
}

<?php

use Tests\TestCase;
use Tests\Libs\DatabaseSetup;
use Pimeo\Models\BuildingComponent;

class AttributeCreateFormTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function testBuildingComponentFieldsExist()
    {
        $this->actingAs($this->editor);

        $this->visit('/attribute/create/system');

        foreach (BuildingComponent::forCurrentCompany()->get() as $component) {
            $this->seeElement('input', ['name' => 'buildingComponents[]', 'value' => $component->id]);
            $this->see(trans('building-component.component.' . $component->code));
        }
    }

    public function testBuildingComponentFieldIsRequired()
    {
        $this->actingAs($this->editor);
        $this->visit('/attribute/create/system')
             ->seeStatusCode(200)
             ->press('Create')
             ->seePageIs('/attribute/create/system')
             ->see('You must select at least one building component.');
    }
}

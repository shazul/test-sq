<?php

use Pimeo\Models\BuildingComponent;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class SystemCreateFormTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function testBuildingComponentFieldExists()
    {
        $this->actingAs($this->editor);
        $this->visit('/system/create')
             ->seeStatusCode(200)
             ->seeElement('select', ['id' => 'buildingComponents[]']);
    }

    public function testBuildingComponentFieldIsRequired()
    {
        $this->actingAs($this->editor);
        $this->visit('/system/create')
             ->seeStatusCode(200)
             ->press('Save')
             ->seePageIs('/system/create')
             ->see('You must select at least one building component.');
    }

    public function testBuildingComponentAreAllListed()
    {
        $buildingComponents = BuildingComponent::forCurrentCompany()->get();

        $this->actingAs($this->editor);
        $this->visit('/system/create')
             ->seeStatusCode(200);

        foreach ($buildingComponents as $component) {
            $this->see('<option value="' . $component->id . '">' . trans(
                'building-component.component.' . $component->code
            ) . '</option>');
        }
    }
}

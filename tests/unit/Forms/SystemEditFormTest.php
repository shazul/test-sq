<?php

use Tests\TestCase;
use Tests\Libs\DatabaseSetup;
use Pimeo\Models\System;
use Pimeo\Models\Attribute;

class SystemEditFormTest extends TestCase
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

        $systemId = $this->createValidSystem(true);

        $this->visit('system/' . $systemId . '/edit')
             ->seeStatusCode(200)
             ->seeElement('select', ['id' => 'buildingComponents[]']);
    }

    public function testBuildingComponentAreSelected()
    {
        $this->actingAs($this->editor);

        $systemId = $this->createValidSystem(true);

        $this->visit('system/' . $systemId . '/edit')
             ->seeStatusCode(200);

        $system = System::find($systemId);

        foreach($system->buildingComponents as $component) {
            $this->see('<option value="' . $component->id . '" selected>' . trans(
                    'building-component.component.' . $component->code
                ) . '</option>');
        }
    }
}

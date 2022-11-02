<?php

use Tests\TestCase;
use Tests\Libs\DatabaseSetup;

class AttributeControllerTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
    }

    public function testBuildingComponentsAttrAreShown()
    {
        $this->actingAs($this->editor);
        $attribute = \Pimeo\Models\Attribute::where('model_type', 'system')->first();
        $attribute->buildingComponents()->sync([1, 2, 3]);
        $attribute->save();

        if (! $attribute) {
            fail('Please create a system attribute.');
        }

        $this->visit('/attributes/system')->seeStatusCode(200);
        $this->see(trans('building-component.index.table.header') . '</th>');
        foreach (\Pimeo\Models\BuildingComponent::find([1, 2, 3]) as $component) {
            $this->see('label label-' . $component->code);
        }
    }
}

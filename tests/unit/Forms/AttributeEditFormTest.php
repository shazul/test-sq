<?php

use Tests\TestCase;
use Tests\Libs\DatabaseSetup;
use Pimeo\Models\BuildingComponent;

class AttributeEditFormTest extends TestCase
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

        $attribute = $this->getAttributeWithBuildingComponents();
        $this->visit('/attribute/' . $attribute->id . '/edit');

        foreach (BuildingComponent::forCurrentCompany()->get() as $component) {
            $this->seeElement('input', ['name' => 'buildingComponents[]', 'value' => $component->id]);
            $this->see(trans('building-component.component.' . $component->code));
        }
    }

    public function testBuildingComponentsAreSelect()
    {
        $this->actingAs($this->editor);

        $attribute = $this->getAttributeWithBuildingComponents();
        $this->visit('/attribute/' . $attribute->id . '/edit');

        foreach (BuildingComponent::find([1, 2, 3]) as $component) {
            $this->see('checked name="buildingComponents[]" type="checkbox" value="' . $component->id . '"');
        }
    }

    protected function getAttributeWithBuildingComponents()
    {
        $attribute = \Pimeo\Models\Attribute::where('model_type', 'system')->first();
        $attribute->buildingComponents()->sync([1, 2, 3]);

        return $attribute->fresh();
    }
}

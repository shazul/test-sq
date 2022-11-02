<?php

use Illuminate\Database\Migrations\Migration;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeValue;
use Pimeo\Models\System;
use Pimeo\Models\LinkAttribute;
use Illuminate\Support\Facades\DB;
use Pimeo\Models\BuildingComponent;

class AssignBuildingComponentsToSystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $buildingComponentAttribute = Attribute::where('name', 'building_component')
                                               ->where('model_type', 'system')
                                               ->get()
                                               ->first();

        if ($buildingComponentAttribute) {
            $attributeValues = AttributeValue::where('attribute_id', $buildingComponentAttribute->id)
                                             ->get()
                                             ->first();

            $systems = System::all();
            foreach ($systems as $system) {
                $selectedBuildingComponents = LinkAttribute::where('attributable_id', $system->id)
                                                           ->where('attributable_type', System::class)
                                                           ->where('attribute_id', $buildingComponentAttribute->id)
                                                           ->get()
                                                           ->first();

                if ($selectedBuildingComponents instanceof LinkAttribute) {
                    $buildingComponents = $selectedBuildingComponents->values;
                    if (isset($buildingComponents['keys']) && is_array($buildingComponents['keys'])) {
                        foreach ($buildingComponents['keys'] as $valueKey) {
                            $componentCode     = str_slug($attributeValues->values['en'][$valueKey], '_');
                            $buildingComponent = BuildingComponent::where('code', $componentCode)->get()->first();
                            DB::table('system_building_component')->insert([
                                'system_id'             => $system->id,
                                'building_component_id' => $buildingComponent->id
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

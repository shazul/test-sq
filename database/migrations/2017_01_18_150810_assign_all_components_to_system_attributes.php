<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AssignAllComponentsToSystemAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $systemsAttributes  = \Pimeo\Models\Attribute::where('model_type', 'system')->get();
        $buildingComponents = \Pimeo\Models\BuildingComponent::all();

        $componentsIds = [];
        foreach ($buildingComponents as $component) {
            $componentsIds[] = $component->id;
        }

        /** @var \Pimeo\Models\Attribute $attribute */
        foreach ($systemsAttributes as $attribute) {
            $attribute->buildingComponents()->sync($componentsIds);
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Pimeo\Models\BuildingComponent;

class AddValuesToBuildingComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('building_components')->insert([
            ['code' => BuildingComponent::ROOFS],
            ['code' => BuildingComponent::FOUNDATIONS],
            ['code' => BuildingComponent::WALLS],
            ['code' => BuildingComponent::BRIDGES],
            ['code' => BuildingComponent::PARKING_DECKS],
            ['code' => BuildingComponent::BALCONIES_PLAZA_DECKS],
            ['code' => BuildingComponent::FOUNTAINS_PONDS],
            ['code' => BuildingComponent::INDOOR_APPLICATIONS]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Pimeo\Models\BuildingComponent;

class CreateBuildingComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_components', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
        });

        /**/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('building_components');
    }
}

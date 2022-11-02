<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemBuildingComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_building_component', function (Blueprint $table) {
            $table->integer('system_id')->unsigned()->index();
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
            $table->integer('building_component_id')->unsigned()->index();
            $table->foreign('building_component_id')->references('id')->on('building_components')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('system_building_component');
    }
}

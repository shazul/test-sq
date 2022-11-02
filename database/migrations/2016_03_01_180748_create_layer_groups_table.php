<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layer_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('system_id')->unsigned()->index();
            $table->text('name');
            $table->integer('position')->unsigned();
            $table->timestamps();

            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('layer_groups');
    }
}

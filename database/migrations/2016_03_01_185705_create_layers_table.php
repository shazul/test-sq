<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('layer_group_id')->unsigned()->index();
            $table->integer('parent_product_id')->unsigned()->index()->nullable();
            $table->text('product_name');
            $table->text('product_function');
            $table->integer('position')->unsigned();
            $table->timestamps();

            $table->foreign('layer_group_id')->references('id')->on('layer_groups')->onDelete('cascade');
            $table->foreign('parent_product_id')->references('id')->on('parent_products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('layers');
    }
}

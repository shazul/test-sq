<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_attribute_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('link_attribute_id')->unsigned()->index();
            $table->integer('last_edited_by')->unsigned()->nullable()->index();
            $table->text('values');
            $table->timestamps();

            $table->foreign('link_attribute_id')->references('id')->on('link_attributes')->onDelete('cascade');
            $table->foreign('last_edited_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_attribute_values');
    }
}

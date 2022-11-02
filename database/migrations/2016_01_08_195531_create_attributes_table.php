<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->index();
            $table->integer('attribute_type_id')->unsigned()->index();
            $table->integer('attribute_label_id')->unsigned()->index();
            $table->integer('last_edited_by')->unsigned()->nullable()->index();
            $table->string('name');
            $table->string('model_type');
            $table->string('system_type')->nullable();
            $table->boolean('has_value')->default(0);
            $table->boolean('is_min_requirement')->default(0);
            $table->boolean('is_parent_attribute')->default(0);
            $table->text('options')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('attribute_type_id')->references('id')->on('attribute_types')->onDelete('cascade');
            $table->foreign('attribute_label_id')->references('id')->on('attribute_labels')->onDelete('cascade');
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
        Schema::drop('attributes');
    }
}

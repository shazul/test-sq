<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->index();
            $table->integer('parent_product_id')->unsigned()->index()->nullable();
            $table->integer('company_catalog_product_id')->unsigned()->index();
            $table->integer('last_edited_by')->unsigned()->nullable()->index();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('parent_product_id')->references('id')->on('parent_products')->onDelete('cascade');
            $table->foreign('company_catalog_product_id')->references('id')->on('company_catalog_products')->onDelete('cascade');
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
        Schema::drop('child_products');
    }
}

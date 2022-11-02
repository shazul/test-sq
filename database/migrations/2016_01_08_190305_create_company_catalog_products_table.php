<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCatalogProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('technical_product_id')->unsigned()->index();
            $table->integer('company_catalog_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('company_catalog_id')->references('id')->on('company_catalogs')->onDelete('cascade');
            $table->foreign('technical_product_id')->references('id')->on('technical_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_catalog_products');
    }
}

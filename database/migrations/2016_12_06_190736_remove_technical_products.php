<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTechnicalProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('child_products', function (Blueprint $table) {
            $table->dropForeign('child_products_company_catalog_product_id_foreign');
            $table->renameColumn('company_catalog_product_id', 'company_catalog_id');
        });

        // Move company_catalog_products.company_catalog_id to child_products.company_catalog_id
        $catalogs = DB::table('company_catalog_products')
            ->pluck('company_catalog_id', 'id');

        $childProducts = DB::table('child_products')->get();

        foreach($childProducts as $product) {
            DB::table('child_products')
                ->where('company_catalog_id', $product->company_catalog_id)
                ->update(['company_catalog_id' => $catalogs[$product->company_catalog_id]]);
        }

        Schema::table('child_products', function (Blueprint $table) {
            $table->foreign('company_catalog_id')->references('id')->on('company_catalogs');
        });

        Schema::drop('company_catalog_products');
        Schema::drop('technical_products');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('technical_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_fr');
            $table->string('name_en');
            $table->text('original_attributes');
            $table->timestamps();
        });

        Schema::create('company_catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('technical_product_id')->unsigned()->index();
            $table->integer('company_catalog_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('company_catalog_id')->references('id')->on('company_catalogs')->onDelete('cascade');
            $table->foreign('technical_product_id')->references('id')->on('technical_products')->onDelete('cascade');
        });

        Schema::table('child_products', function (Blueprint $table) {
            $table->renameColumn('company_catalog_id', 'company_catalog_product_id');
            $table->foreign('company_catalog_product_id')->references('id')->on('company_catalog_products')->onDelete('cascade');
        });
    }
}

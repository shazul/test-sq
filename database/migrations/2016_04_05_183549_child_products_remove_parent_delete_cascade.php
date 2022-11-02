<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChildProductsRemoveParentDeleteCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('child_products', function (Blueprint $table) {
            $table->dropForeign(['parent_product_id']);
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
        Schema::table('child_products', function (Blueprint $table) {
            $table->dropForeign(['parent_product_id']);
            $table->foreign('parent_product_id')->references('id')->on('parent_products')->onDelete('cascade');
        });
    }
}

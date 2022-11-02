<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTechnologyAttributeToElasticsearchIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('attributes')
            ->where('name', 'product_technology')
            ->update(['should_index' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('attributes')
            ->where('name', 'product_technology')
            ->update(['should_index' => false]);
    }
}

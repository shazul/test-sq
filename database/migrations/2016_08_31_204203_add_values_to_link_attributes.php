<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddValuesToLinkAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_attributes', function (Blueprint $table) {
            $table->text('values')->nullable();
        });

        // Move the values from the link_attribute_values table to the new field
        DB::statement('UPDATE link_attributes
        SET values = link_attribute_values.values
        FROM link_attribute_values
        WHERE link_attribute_values.link_attribute_id = link_attributes.id');

        Schema::drop('link_attribute_values'); // Bon débarras!
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_attributes', function (Blueprint $table) {
            $table->dropColumn('values');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;


/**
 * This migration was use to add the fountain system type to the system_type table.
 * Since this table does not exist anymore.. it's pointless to do anything about it.
 * To avoid making a migration that remove this particular migration from the migration table...
 * we decided to leave this migration empty.
 */
class AddSystemTypeFountains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}

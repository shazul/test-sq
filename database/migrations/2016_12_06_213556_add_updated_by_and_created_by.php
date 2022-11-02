<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedByAndCreatedBy extends Migration
{
    private $tables = [
        'attributes', 'child_products', 'companies', 'details', 'parent_products',
        'specifications', 'systems', 'technical_bulletins', 'users'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->integer('created_by')->unsigned()->nullable()->index();
                $table->integer('updated_by')->unsigned()->nullable()->index();
                if (Schema::hasColumn($tableName, 'last_edited_by')) {
                    $table->dropColumn('last_edited_by');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->integer('last_edited_by')->unsigned()->nullable()->index();
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSystemTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropForeign('systems_system_type_id_foreign');
            $table->dropIndex(['system_type_id']);
            $table->dropColumn('system_type_id');
        });

        Schema::drop('system_types');

        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn('system_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('system_types', function (Blueprint $table) {
            $table->increments('id');
            $table->text('code')->unique();
            $table->text('group_name');
            $table->timestamps();

            $table->integer('company_id')->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('systems', function (Blueprint $table) {
            $table->integer('system_type_id')->unsigned()->index()->nullable();
            $table->foreign('system_type_id')->references('id')->on('system_types')->onDelete('cascade');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->string('system_type')->nullable();
        });

    }
}

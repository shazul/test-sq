<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSystemTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropForeign('systems_system_type_id_foreign');
            $table->dropColumn('system_type_id');
        });

        Schema::drop('system_types');
    }
}

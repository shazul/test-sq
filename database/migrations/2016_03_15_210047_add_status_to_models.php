<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Pimeo\Models\AttributableModelStatus;

class AddStatusToModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $enum = [
            AttributableModelStatus::COMPLETE_STATUS,
            AttributableModelStatus::INCOMPLETE_STATUS,
            AttributableModelStatus::FRESH_STATUS,
        ];

        Schema::table('child_products', function (Blueprint $table) use ($enum) {
            $table->enum('status', $enum)->default(AttributableModelStatus::FRESH_STATUS);
        });

        Schema::table('parent_products', function (Blueprint $table) use ($enum) {
            $table->enum('status', $enum)->default(AttributableModelStatus::FRESH_STATUS);
        });

        Schema::table('details', function (Blueprint $table) use ($enum) {
            $table->enum('status', $enum)->default(AttributableModelStatus::FRESH_STATUS);
        });

        Schema::table('specifications', function (Blueprint $table) use ($enum) {
            $table->enum('status', $enum)->default(AttributableModelStatus::FRESH_STATUS);
        });

        Schema::table('systems', function (Blueprint $table) use ($enum) {
            $table->enum('status', $enum)->default(AttributableModelStatus::FRESH_STATUS);
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
            $table->dropColumn('status');
        });

        Schema::table('parent_products', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('details', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('specifications', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

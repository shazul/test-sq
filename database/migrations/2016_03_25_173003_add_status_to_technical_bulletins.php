<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Pimeo\Models\AttributableModelStatus;

class AddStatusToTechnicalBulletins extends Migration
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

        Schema::table('technical_bulletins', function (Blueprint $table) use ($enum) {
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
        Schema::table('technical_bulletins', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

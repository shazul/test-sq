<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\ParentProduct;

class ParentChildsMigrateWorkflowStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Créé champ new_status pcq on ne peut pas modifier le champ status pcq il est un 'enum'
        Schema::table('child_products', function (Blueprint $table) {
            $table->string('new_status', 20)->default(AttributableModelStatus::FRESH_STATUS);
        });

        Schema::table('parent_products', function (Blueprint $table) {
            $table->string('new_status', 20)->default(AttributableModelStatus::FRESH_STATUS);
        });

        // Update aux nouveaux status
        ChildProduct::whereStatus('complete')->update(['new_status' => AttributableModelStatus::PUBLISHED_STATUS]);

        ChildProduct::whereStatus('complete')->whereParentProductId(null)
            ->update(['new_status' => AttributableModelStatus::PARENTLESS_STATUS]);

        ChildProduct::whereStatus('incomplete')->update(['new_status' => AttributableModelStatus::PARENTLESS_STATUS]);

        ParentProduct::whereStatus('complete')->update(['new_status' => AttributableModelStatus::DRAFT_STATUS]);
        ParentProduct::whereStatus('incomplete')->update(['new_status' => AttributableModelStatus::DRAFT_STATUS]);

        // Remplace l'ancienne status par la nouvelle
        Schema::table('child_products', function (Blueprint $table) {
            $table->dropColumn(['status']);
            $table->renameColumn('new_status', 'status');
        });
        Schema::table('parent_products', function (Blueprint $table) {
            $table->dropColumn(['status']);
            $table->renameColumn('new_status', 'status');
        });
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

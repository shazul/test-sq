<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Attribute;
use Pimeo\Models\ParentProduct;

class AddNatureToParentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parent_products', function (Blueprint $table) {
            $table->smallInteger('nature_id')->default(1);
            $table->foreign('nature_id')->references('id')->on('natures');
        });

        $linkAttributes = LinkAttribute::where('attributable_type', 'Pimeo\Models\ParentProduct')
            ->where('attribute_id', 2)
            ->select(['values', 'attributable_id']);

        foreach ($linkAttributes->get() as $linkAttribute) {
            $nature_id = $linkAttribute->values['keys'][0];
            ParentProduct::where('id', $linkAttribute->attributable_id)->update(['nature_id' => $nature_id]);
        }

        $linkAttributes->delete();

        $attributeNature = Attribute::find(2);
        if ($attributeNature != null) {
            $attributeNature->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parent_products', function (Blueprint $table) {
            $table->dropColumn('nature_id');
        });
    }
}

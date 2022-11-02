<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class AddAttributeWebBrochure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attributeType = DB::table('attribute_types')->where('id', 11)->first();

        if($attributeType){
            $data = [
                'name' => 'web-brochure-0',
                'values' => '{"fr_CA":"Brochure Web","en_CA":"Web Brochure"}',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
            $id_attribute_labels = DB::table('attribute_labels')->insertGetId($data);

            $data = [
                'company_id' => 1,
                'attribute_type_id' => 11,
                'attribute_label_id' => $id_attribute_labels,
                'name' => 'web-brochure-0',
                'model_type' => 'parent_product',
                'has_value' => 0,
                'is_min_requirement' => 0,
                'is_parent_attribute' => 0,
                'options' => '{"special_index_key":"web_brochure"}',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'should_index' => 0,
            ];
            $id_attribute = DB::table('attributes')->insertGetId($data);

            DB::table('attribute_nature')->insert([
                ['attribute_id' => $id_attribute, 'nature_id' => 1],
                ['attribute_id' => $id_attribute, 'nature_id' => 2],
                ['attribute_id' => $id_attribute, 'nature_id' => 3],
                ['attribute_id' => $id_attribute, 'nature_id' => 4]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $attribute = DB::table('attributes')->select('id', 'attribute_label_id')->where('name', '=', 'web-brochure-0')->first();
        if (isset($attribute->id)) {
            DB::table('attribute_nature')->where('attribute_id', '=', $attribute->id)->delete();
            DB::table('attribute_labels')->where('id', '=', $attribute->attribute_label_id)->delete();
            DB::table('attributes')->where('id', '=', $attribute->id)->delete();
        }
    }
}

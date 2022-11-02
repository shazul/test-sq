<?php

use Illuminate\Database\Migrations\Migration;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeType;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\User;

class CreateKeywordsAttributeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attributeTypesCount = AttributeType::count();

        // Prevent duplicate entry when running tests.
        if ($attributeTypesCount > 0) {
            $attributeType = AttributeType::whereCode('keywords')->first();
            $companies = Company::all();
            $attributeName = 'search_keywords';
            $user = User::find(1);
            $counter = 1;
            foreach ($companies as $company) {
                $labels = [];
                /** @var Language[] $languages */
                $languages = $company->languages;
                foreach ($languages as $language) {
                    $labels[$language->code] = 'Search Keywords';
                }

                $attributeLabel = AttributeLabel::create([
                    'name'   => $attributeName . '-detail-' . $counter,
                    'values' => $labels,
                ]);


                $attribute = Attribute::create([
                    'company_id'          => $company->id,
                    'attribute_type_id'   => $attributeType->id,
                    'attribute_label_id'  => $attributeLabel->id,
                    'name'                => $attributeName,
                    'model_type'          => 'detail',
                    'has_value'           => false,
                    'is_min_requirement'  => false,
                    'is_parent_attribute' => false,
                    'should_index'        => false,
                    'options'             => [
                        'special_index_key' => 'search_keywords',
                    ],
                ]);

                $counter++;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AttributeType::where('code', 'keywords')->delete();
    }
}

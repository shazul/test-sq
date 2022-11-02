<?php

use Illuminate\Database\Migrations\Migration;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\AttributeType;
use Pimeo\Models\Company;
use Pimeo\Models\Nature;
use Pimeo\Models\User;

class CreateKeywordsAttribute extends Migration
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

            if (!$attributeType) {
                $attributeType = AttributeType::create([
                    'name'   => 'Mots clés',
                    'public' => false,
                    'code'   => 'keywords',
                    'specs'  => [
                        'type'     => 'keywords',
                        'multiple' => 0,
                        'access'   => 'private',
                    ],
                ]);
            }

            $companies = Company::all();
            $natures   = Nature::all();

            $labels = [
                'fr' => 'Mots clés de recherche',
                'en' => 'Search keywords',
            ];

            $attributeName = 'search_keywords';

            $attributeLabel = AttributeLabel::create([
                'name'   => $attributeName,
                'values' => $labels,
            ]);

            $user = User::find(1);

            foreach ($companies as $company) {
                $attribute = Attribute::whereName($attributeName)->whereCompanyId($company->id)->first();

                if (!$attribute) {
                    $attribute = Attribute::create([
                        'company_id'          => $company->id,
                        'attribute_type_id'   => $attributeType->id,
                        'attribute_label_id'  => $attributeLabel->id,
                        'name'                => $attributeName,
                        'model_type'          => 'parent_product',
                        'has_value'           => false,
                        'is_min_requirement'  => false,
                        'is_parent_attribute' => true,
                        'should_index'        => false,
                        'options'             => [
                            'special_index_key' => 'search_keywords',
                        ],
                    ]);

                    foreach ($natures as $nature) {
                        $nature->attributes()->attach($attribute);
                    }
                }
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

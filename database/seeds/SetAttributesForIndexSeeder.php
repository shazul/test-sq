<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;

class SetAttributesForIndexSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $field_name = [
            'detail_function',
            'building_component',
            'product_function',
            'product_role',
            'system_bridging',
            'system_installation_method',
            'system_guaranty',
            'system_test_norms_approbations',
            'system_function',
        ];
        $this->addIndexationFlagForAttributes($field_name);
    }

    /**
     * @param array $attributes
     */
    private function addIndexationFlagForAttributes(array $attributes)
    {
        $attributes = Attribute::whereIn('name', $attributes)->get();

        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            $attribute->should_index = 1;
            $attribute->save();
        }
    }
}

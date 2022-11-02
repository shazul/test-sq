<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Detail;

class DetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = [];

        $company_id = $this->fetchCompany()->id;
        $attributes['name'] = Attribute::where('name', 'detail_name')->first()->id;
        $attributes['sheet'] = Attribute::where('name', 'detail_sheet')->first()->id;
        $attributes['building_component'] = Attribute::where('name', 'detail_building_component')->first()->id;
        $faker = Faker\Factory::create();

        factory(Detail::class, 50)->create(['company_id' => $company_id])->each(function (
            Detail $detail
        ) use ( $attributes, $faker)
        {
            // name
            $link_attribute = $detail->linkAttributes()->create(['attribute_id' => $attributes['name']]);

            $link_attribute->values()->create([
                'values' => [
                    'fr' => "Detail {$faker->name} (fr)",
                    'en' => "Detail {$faker->name} (en)",
                ],
            ]);

            // sheet
            $link_attribute = $detail->linkAttributes()->create(['attribute_id' => $attributes['sheet']]);

            $link_attribute->values()->create([
                'values' => [
                    'fr' => [
                        [
                            'name'      => $faker->word,
                            'extension' => 'pdf',
                            'file_path' => date('Y-m-d'),
                            'file_size' => rand(100, 2000)
                        ]
                    ],
                    'en' => [
                        [
                            'name'      => $faker->word,
                            'extension' => 'pdf',
                            'file_path' => date('Y-m-d'),
                            'file_size' => rand(100, 2000)
                        ]
                    ]
                ]
            ]);

            // building component
            $link_attribute = $detail->linkAttributes()->create(['attribute_id' => $attributes['building_component']]);

            $randomQty = rand(1, 8);

            $link_attribute->values()->create([
                'values' => ['keys' => array_rand(range(0, 7), $randomQty)]
            ]);
        });
    }

    /**
     * Fetch a company.
     *
     * @return Company
     */
    protected function fetchCompany()
    {
        return Company::where('name', CompanyTableSeeder::COMPANY_NAME)->first();
    }
}

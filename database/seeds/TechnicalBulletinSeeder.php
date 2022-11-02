<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\TechnicalBulletin;

class TechnicalBulletinSeeder extends Seeder
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
        $attributes['name'] = Attribute::where('name', 'technical_bulletin_name')->first()->id;
        $attributes['file'] = Attribute::where('name', 'technical_bulletin_file')->first()->id;
        $attributes['building_component'] = Attribute::where('name', 'technical_bulletin_building_component')->first()->id;
        $faker = Faker\Factory::create();

        factory(TechnicalBulletin::class, 50)->create(['company_id' => $company_id])->each(function (
            TechnicalBulletin $technical_bulletin
        ) use ($attributes, $faker)
        {
            // name
            $link_attribute = $technical_bulletin->linkAttributes()->create(['attribute_id' => $attributes['name']]);

            $link_attribute->values()->create([
                'values' => [
                    'fr' => "Tech Bulletin {$faker->name} (fr)",
                    'en' => "Tech Bulletin {$faker->name} (en)",
                ],
            ]);

            // file
            $link_attribute = $technical_bulletin->linkAttributes()->create(['attribute_id' => $attributes['file']]);

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
            $link_attribute = $technical_bulletin->linkAttributes()->create(['attribute_id' => $attributes['building_component']]);

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

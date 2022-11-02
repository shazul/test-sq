<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Pimeo\Models\Company;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\LinkAttributeValue;
use Pimeo\Models\ParentProduct;

class ParentProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $company_id = $this->fetchCompany()->id;

        factory(ParentProduct::class, 50)->create(['company_id' => $company_id])->each(function ($p) use ($faker) {
            // name
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 1,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => [
                    'fr' => $faker->name,
                    'en' => $faker->name,
                ],
            ]);
            $p->linkAttributes()->save($linkAttribute);

            // product_function
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 3,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => ['keys' => rand(0, 4)],
            ]);
            $p->linkAttributes()->save($linkAttribute);

            // building_component
            $randomQty = rand(1, 8);
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 4,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => ['keys' => array_rand(range(0, 7), $randomQty)],
            ]);
            $p->linkAttributes()->save($linkAttribute);

            // product_role
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 5,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => ['keys' => rand(0, 20)],
            ]);
            $p->linkAttributes()->save($linkAttribute);

            // product_description
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 6,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => [
                    'fr' => $faker->sentence,
                    'en' => $faker->sentence,
                ],
            ]);
            $p->linkAttributes()->save($linkAttribute);

            // product_image
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 8,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => [
                    'fr' => [
                        [
                            'name'      => $faker->word,
                            'extension' => 'jpg',
                            'file_path' => date('Y-m-d'),
                            'file_size' => rand(100, 2000),
                        ],
                    ],
                    'en' => [
                        [
                            'name'      => $faker->word,
                            'extension' => 'jpg',
                            'file_path' => date('Y-m-d'),
                            'file_size' => rand(100, 2000),
                        ],
                    ],
                ],
            ]);
            $p->linkAttributes()->save($linkAttribute);

            $parent_products = ParentProduct::whereCompanyId($this->fetchCompany()->id)
                ->with(['linkAttributes' => function ($query) {
                    $query->whereHas('attribute', function ($query) {
                        $query->where('name', 'name');
                    });
                }])
                ->get();

            // complementary_products
            $products = $parent_products->random(3);
            $array_ids = [];
            foreach ($products as $product) {
                $array_ids[] = $product->id;
            }
            $linkAttribute = factory(LinkAttribute::class)->make([
                'attribute_id'      => 11,
                'attributable_type' => 'Pimeo\Models\ParentProduct',
                'values' => implode(',', $array_ids),
            ]);
            $p->linkAttributes()->save($linkAttribute);

        });
    }

    /**
     * Fetch a company.
     *
     * @return Company
     */
    protected function fetchCompany()
    {
        return Company::query()->where('name', CompanyTableSeeder::COMPANY_NAME)->first();
    }
}

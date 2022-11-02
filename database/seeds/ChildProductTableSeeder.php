<?php

use Illuminate\Database\Seeder;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\ParentProduct;

class ChildProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $company = Company::first();
        $catalogs = $company->companyCatalogs;

        /** @var ParentProduct $parent */
        $parent = ParentProduct::first();

        /** @var ChildProduct $product */
        $product = factory(ChildProduct::class)->create([
            'company_id' => $company->id,
            'company_catalog_id' => $catalogs->first()->id,
            'parent_product_id' => $parent->id,
        ]);

        /** @var Attribute $attribute */
        $attribute = Attribute::first();

        $link = new LinkAttribute;
        $link->attribute_id = $attribute->id;

        $product->linkAttributes()->save($link);
    }
}

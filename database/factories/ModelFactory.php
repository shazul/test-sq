<?php

use Pimeo\Models;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Models\AttributeLabel::class, function () {
    return [
        'name'   => null,
        'values' => [],
    ];
});

$factory->define(Models\AttributeType::class, function () {
    return [
        'name'  => null,
        'code'  => null,
        'specs' => [],
    ];
});

$factory->define(Models\AttributeValue::class, function () {
    return [
        'attribute_id' => null,
        'values'       => [],
    ];
});

$factory->define(Models\Attribute::class, function () {
    return [
        'company_id'          => null,
        'attribute_type_id'   => null,
        'attribute_label_id'  => null,
        'created_by'          => null,
        'updated_by'           => null,
        'name'                => null,
        'model_type'          => null,
        'has_value'           => false,
        'is_parent_attribute' => false,
    ];
});

$factory->define(Models\CompanyCatalog::class, function () {
    return [
        'company_id' => null,
    ];
});

$factory->define(Models\ChildProduct::class, function () {
    return [
        'company_id'         => null,
        'parent_product_id'  => null,
        'company_catalog_id' => null,
        'created_by'         => null,
        'updated_by'         => null,
    ];
});

$factory->define(Models\Company::class, function () {
    return [
        'default_language_id' => Models\Language::all()->random()->id,
        'name'                => null,
    ];
});

$factory->define(Models\Detail::class, function () {
    return [
        'company_id'     => null,
        'created_by'     => null,
        'updated_by'     => null,
    ];
});

$factory->define(Models\Specification::class, function () {
    return [
        'company_id'     => null,
        'created_by'     => null,
        'updated_by'     => null,
    ];
});

$factory->define(Models\Group::class, function () {
    return [
        'name' => null,
        'code' => null,
    ];
});

$factory->define(Models\Language::class, function () {
    return [
        'name' => null,
        'code' => null,
    ];
});

$factory->define(Models\LinkAttribute::class, function () {
    return [
        'attribute_id'      => null,
        'attributable_id'   => null,
        'attributable_type' => null,
    ];
});

$factory->define(Models\LinkMedia::class, function () {
    return [
        'media_id'      => null,
        'linkable_id'   => null,
        'linkable_type' => null,
    ];
});

$factory->define(Models\Media::class, function () {
    return [
        'code' => null,
        'name' => null,
    ];
});

$factory->define(Models\ParentProduct::class, function () {
    return [
        'company_id'     => null,
        'created_by'     => null,
        'updated_by'     => null,
        'status'         => collect(['draft', 'published'])->random(),
        'nature_id'      => rand(1, 4),
    ];
});

$factory->define(Models\System::class, function () {
    return [
        'company_id'     => null,
        'created_by'     => null,
        'updated_by'     => null,
    ];
});

$factory->define(Models\User::class, function (Faker\Generator $faker) {
    return [
        'first_name'     => $faker->firstName,
        'last_name'      => $faker->lastName,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'active'         => true,
    ];
});

$factory->define(Models\Nature::class, function () {
    return [
        'code' => null,
        'name' => null,
    ];
});

$factory->define(Models\LayerGroup::class, function (Faker\Generator $faker) {
    $name = [];
    foreach (Models\Language::all() as $language) {
        $name[$language->code] = 'Layers group' . $faker->name;
    }
    return [
        'system_id' => null,
        'name'      => $name,
        'position'  => null,
    ];
});

$factory->define(Models\Layer::class, function (Faker\Generator $faker) {
    $product_name = [];
    $product_function = [];
    foreach (Models\Language::all() as $language) {
        $product_name[$language->code] = 'Layer ' . $faker->name;
        $product_function[$language->code] = 'Function ' . $faker->name;
    }

    return [
        'layer_group_id'    => null,
        'parent_product_id' => null,
        'product_name'      => $product_name,
        'product_function'  => $product_function,
        'position'          => null,
    ];
});

$factory->define(Models\TechnicalBulletin::class, function () {
    return [
        'company_id'     => null,
        'created_by'     => null,
        'updated_by'     => null,
    ];
});

$factory->define(Models\CompanyBlog::class, function () {
    return [
        'blog_id'     => null,
        'language_id' => null,
        'company_id'  => null,
    ];
});

<?php

/** @var Illuminate\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application API Routes
|--------------------------------------------------------------------------
*/

$router->get('/', function () {
    return view('blueprint.api');
})->name('api.blueprint');

$router->post('auth', 'ApiAuth@authenticate')->name('api.auth');

$router->group(['middleware' => 'api'], function () use ($router) {
    $router->get('companies', 'CompaniesController@index')->name('api.companies');
    $router->get('companies/{company}', 'CompaniesController@show')->name('api.company');

    $router->group(['prefix' => 'companies/{company}'], function () use ($router) {
        $router->get('products', 'CompaniesController@products')->name('api.company.products');
        $router->get('systems', 'CompaniesController@systems')->name('api.company.systems');
        $router->get('details', 'CompaniesController@details')->name('api.company.details');
        $router->get('specifications', 'CompaniesController@specifications')->name('api.company.specifications');
        $router->get('technical-bulletins', 'CompaniesController@technicalBulletins')
               ->name('api.company.technical-bulletins');
        $router->get('languages', 'CompaniesController@languages')->name('api.company.languages');
        $router->get('medias', 'CompaniesController@medias')->name('api.company.medias');
        $router->get('medias/{media}/products', 'CompaniesController@mediaProducts')
               ->name('api.company.media.products');
        $router->get('medias/{media}/systems', 'CompaniesController@mediaSystems')
            ->name('api.company.media.systems');
    });

    $router->get('products', 'ProductsController@index')->name('api.products');
    $router->get('products/{product}', 'ProductsController@show')->name('api.product');

    $router->get('systems', 'SystemsController@index')->name('api.systems');
    $router->get('systems/{system}', 'SystemsController@show')->name('api.system');

    $router->get('details', 'DetailsController@index')->name('api.details');
    $router->get('details/{detail}', 'DetailsController@show')->name('api.detail');

    $router->get('specifications', 'SpecificationsController@index')->name('api.specifications');
    $router->get('specifications/{specification}', 'SpecificationsController@show')->name('api.specification');

    $router->get('technical-bulletins', 'TechnicalBulletinController@index')->name('api.technical-bulletins');
    $router->get('technical-bulletins/{technicalBulletin}', 'TechnicalBulletinController@show')
           ->name('api.technical-bulletins.show');

    $router->get('languages', 'LanguagesController@index')->name('api.languages');
});

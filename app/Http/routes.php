<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'Auth\AuthController@showLoginForm'); // Should we have a landing page instead?
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
});

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'Pim'], function () {
    Route::get('/user/change/language/{language}', 'UserController@changeLanguage')->name('user.change.language');
    Route::get('/user/change/company/{company}', 'UserController@changeCompany')->name('user.change.company');

    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('users/my-profile', 'UserController@editMyself')->name('user.edit-my-profile');
    Route::put('user/my-profile/{user}', 'UserController@updateProfile')->name('user.update-my-profile');

    Route::resource('company', 'CompanyController', ['except' => ['show']]);

    Route::resource('parent-product', 'ParentProductController', ['except' => ['show', 'index']]);
    Route::group(['prefix' => 'parent-product', 'middleware' => 'model:' . ParentProduct::class], function () {
        Route::get('listing/{status?}', 'ParentProductController@index')->name('parent-product.index');
        Route::get('{parent_product}/edit-attribute', 'ParentProductController@editAttribute')
            ->name('parent-product.edit-attribute');
        Route::get('{parent_product}/edit-nature', 'ParentProductController@editNature')
            ->name('parent-product.edit-nature');
        Route::post('upload-image', 'ParentProductController@uploadImage')
            ->name('parent-product.update.image');
        Route::put('{parent_product}/update-attribute', 'ParentProductController@updateAttribute')
            ->name('parent-product.update-attribute');
        Route::post('{parent_product}/add-inline-attribute', 'ParentProductController@parentAddInlineAttribute')
            ->name('parent-product.add-inline-attribute');
        Route::post('{parent_product}/delete-inline-attribute', 'ParentProductController@parentDeleteInlineAttribute')
            ->name('parent-product.delete-inline-attribute');
        Route::put('{parent_product}/update-nature', 'ParentProductController@updateNature')
            ->name('parent-product.update-nature');
        Route::get('{parent_product}', 'ParentProductController@show')->name('parent-product.show');
        Route::post('validate-required-attributes', 'ParentProductController@validateRequiredAttributes')
            ->name('parent-product.validate-required-attributes');
    });

    Route::resource('child-product', 'ChildProductController', ['except' => ['show', 'index']]);
    Route::group(['prefix' => 'child-product', 'middleware' => 'model:' . ChildProduct::class], function () {
        Route::get('{child_product}/edit-attribute', 'ChildProductController@editAttribute')
            ->name('child-product.edit-attribute');
        Route::get('{child_product}/approve', 'ChildProductController@approve')
            ->name('child-product.approve');
        Route::post('{child_product}/approve', 'ChildProductController@approveEdit')
            ->name('child-product.approve-edit');
        Route::get('listing/{status?}', 'ChildProductController@index')->name('child-product.index');
        Route::put('{child_product}/update-attribute', 'ChildProductController@updateAttribute')
            ->name('child-product.update-attribute');
        Route::post('{child_product}/add-inline-attribute', 'ChildProductController@childAddInlineAttribute')
            ->name('child-product.add-inline-attribute');
        Route::post('{child_product}/delete-inline-attribute', 'ChildProductController@childDeleteInlineAttribute')
            ->name('child-product.delete-inline-attribute');
        Route::get('{child_product}', 'ChildProductController@show')->name('child-product.show');
        Route::get('{child_product}/edit-parentless', 'ChildProductController@editParentless')
            ->name('child-product.edit-parentless');
        Route::post('{child_product}/update-parentless', 'ChildProductController@updateParentless')
            ->name('child-product.update-parentless');
        Route::get('{child_product}/edit-parent', 'ChildProductController@editParent')
            ->name('child-product.edit-parent');
        Route::post('{child_product}/update-parent', 'ChildProductController@updateParent')
            ->name('child-product.update-parent');
        Route::post('{child_product}/copy', 'ChildProductController@copyAttributes')
            ->name('child-product.copy-attribute');
        Route::post('validate-required-attributes', 'ChildProductController@validateRequiredAttributes')
            ->name('child-product.validate-required-attributes');
    });

    Route::resource('system', 'SystemController', ['except' => ['index', 'show', 'create', 'edit']]);
    Route::group(['prefix' => 'system', 'middleware' => 'model:' . System::class], function () {
        Route::get('add-layer-groups/{system}', 'SystemLayerGroupController@create')
             ->name('system.add-layer-groups');
        Route::get('layer-group/{layer_group}', 'SystemLayerGroupController@edit')
             ->name('system.edit-layer-group');
        Route::put('layer-group/{layer_group}', 'SystemLayerGroupController@update')
             ->name('system.update-layer-group');
        Route::post('add-layer-groups/{system}', 'SystemLayerGroupController@store')
             ->name('system.store-layer-groups');
        Route::delete('layer-group/{layer_group}', 'SystemLayerGroupController@destroy')
             ->name('system.delete-layer-group');

        Route::get('edit-layer/{layer}', 'SystemLayerController@edit')
             ->name('system.edit-layer');
        Route::delete('layer/{layer}', 'SystemLayerController@destroy')
             ->name('system.delete-layer');
        Route::put('layer/{layer}', 'SystemLayerController@update')
             ->name('system.update-layer');
        Route::get('add-layer/{layer_group}', 'SystemLayerController@create')
             ->name('system.add-layer');
        Route::post('add-layer/{layer_group}', 'SystemLayerController@store')
             ->name('system.store-layer');

        Route::get('create', 'SystemController@create')->name('system.create');
        Route::get('listing/{status?}', 'SystemController@index')->name('system.index');
        Route::get('{system}/edit', 'SystemController@edit')->name('system.edit');

        Route::post('{system}/add-inline-attribute', 'SystemController@systemAddInlineAttribute')
            ->name('system.add-inline-attribute');
        Route::post('{system}/delete-inline-attribute', 'SystemController@systemDeleteInlineAttribute')
            ->name('system.delete-inline-attribute');
        Route::put('{system}/update-attribute', 'SystemController@updateAttribute')
            ->name('system.update-attribute');
        Route::get('{system}', 'SystemController@show')->name('system.show');
    });

    Route::resource('specification', 'SpecificationController', ['except' => ['index', 'show']]);
    Route::group(['prefix' => 'specification', 'middleware' => 'model:' . Specification::class], function () {
        Route::get('listing/{status?}', 'SpecificationController@index')->name('specification.index');
        Route::get('{specification}/edit-attribute', 'SpecificationController@editAttribute')
            ->name('specification.edit-attribute');
        Route::put('{specification}/update-attribute', 'SpecificationController@updateAttribute')
            ->name('specification.update-attribute');
        Route::post('{specification}/add-inline-attribute', 'SpecificationController@specAddInlineAttribute')
            ->name('specification.add-inline-attribute');
        Route::post('{specification}/delete-inline-attribute', 'SpecificationController@specDeleteInlineAttribute')
            ->name('specification.delete-inline-attribute');
        Route::get('{specification}', 'SpecificationController@show')->name('specification.show');
    });

    Route::resource('detail', 'DetailController', ['except' => ['index', 'show']]);
    Route::group(['prefix' => 'detail', 'middleware' => 'model:' . Detail::class], function () {
        Route::get('listing/{status?}', 'DetailController@index')->name('detail.index');
        Route::get('{detail}/edit-attribute', 'DetailController@editAttribute')->name('detail.edit-attribute');
        Route::put('{detail}/update-attribute', 'DetailController@updateAttribute')
            ->name('detail.update-attribute');
        Route::post('{detail}/add-inline-attribute', 'DetailController@specAddInlineAttribute')
            ->name('detail.add-inline-attribute');
        Route::post('{detail}/delete-inline-attribute', 'DetailController@specDeleteInlineAttribute')
            ->name('detail.delete-inline-attribute');
        Route::get('{detail}', 'DetailController@show')->name('detail.show');
    });

    Route::resource('technical-bulletin', 'TechnicalBulletinController', ['except' => ['index', 'show']]);
    Route::group(['prefix' => 'technical-bulletin', 'middleware' => 'model:' . TechnicalBulletin::class], function () {
        Route::get('listing/{status?}', 'TechnicalBulletinController@index')->name('technical-bulletin.index');
        Route::get('{technical_bulletin}/edit-attribute', 'TechnicalBulletinController@editAttribute')
            ->name('technical-bulletin.edit-attribute');
        Route::put('{technical_bulletin}/update-attribute', 'TechnicalBulletinController@updateAttribute')
            ->name('technical-bulletin.update-attribute');
        Route::post(
            '{technical_bulletin}/add-inline-attribute',
            'TechnicalBulletinController@bulletinAddInlineAttribute'
        )->name('technical-bulletin.add-inline-attribute');
        Route::post(
            '{technical_bulletin}/delete-inline-attribute',
            'TechnicalBulletinController@bulletinDeleteInlineAttribute'
        )->name('technical-bulletin.delete-inline-attribute');
        Route::get('{technical_bulletin}', 'TechnicalBulletinController@show')->name('technical-bulletin.show');
    });
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::resource('attribute', 'Pim\AttributeController', ['except' => ['index', 'create']]);

    Route::get('attributes/{model}', 'Pim\AttributeController@index')
        ->name('attribute.index');

    Route::get('attribute/create/{model}/{system?}', 'Pim\AttributeController@create')
        ->name('attribute.create');

    Route::get('attribute/group/create/{model}/{system?}', 'Pim\AttributeGroupController@create')
        ->name('attribute.group.create');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('export', 'Pim\ExportationController@index')->name('exportation.index');
    Route::get('export/parent_products', 'Pim\ExportationController@exportParent')->name('exportation.exportParent');
    Route::get('export/all_products', 'Pim\ExportationController@exportAll')->name('exportation.exportAll');
});

Route::get('/blueprint', function () {
    return view('blueprint.api');
});

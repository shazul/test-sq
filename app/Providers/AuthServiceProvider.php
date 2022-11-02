<?php

namespace Pimeo\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\Detail;
use Pimeo\Models\Layer;
use Pimeo\Models\LayerGroup;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Models\User;
use Pimeo\Policies\AttributePolicy;
use Pimeo\Policies\ChildProductPolicy;
use Pimeo\Policies\CompanyPolicy;
use Pimeo\Policies\DetailPolicy;
use Pimeo\Policies\ParentProductPolicy;
use Pimeo\Policies\SpecificationPolicy;
use Pimeo\Policies\SystemLayerGroupPolicy;
use Pimeo\Policies\SystemLayerPolicy;
use Pimeo\Policies\SystemPolicy;
use Pimeo\Policies\TechnicalBulletinPolicy;
use Pimeo\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Attribute::class         => AttributePolicy::class,
        ChildProduct::class      => ChildProductPolicy::class,
        Company::class           => CompanyPolicy::class,
        Detail::class            => DetailPolicy::class,
        Layer::class             => SystemLayerPolicy::class,
        LayerGroup::class        => SystemLayerGroupPolicy::class,
        ParentProduct::class     => ParentProductPolicy::class,
        Specification::class     => SpecificationPolicy::class,
        System::class            => SystemPolicy::class,
        TechnicalBulletin::class => TechnicalBulletinPolicy::class,
        User::class              => UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('manage-users', 'Pimeo\Policies\UserPolicy@manage');

        $gate->define('manage-companies', 'Pimeo\Policies\CompanyPolicy@manage');

        $gate->define('create-attribute', 'Pimeo\Policies\AttributePolicy@create');
        $gate->define('manage-attribute', 'Pimeo\Policies\AttributePolicy@manage');

        $gate->define('create-technical-bulletin', 'Pimeo\Policies\TechnicalBulletinPolicy@create');
        $gate->define('edit-technical-bulletin', 'Pimeo\Policies\TechnicalBulletinPolicy@edit');
        $gate->define('delete-technical-bulletin', 'Pimeo\Policies\TechnicalBulletinPolicy@delete');

        $gate->define('create-detail', 'Pimeo\Policies\DetailPolicy@create');
        $gate->define('edit-detail', 'Pimeo\Policies\DetailPolicy@edit');
        $gate->define('delete-detail', 'Pimeo\Policies\DetailPolicy@delete');

        $gate->define('create-specification', 'Pimeo\Policies\SpecificationPolicy@create');
        $gate->define('edit-specification', 'Pimeo\Policies\SpecificationPolicy@edit');
        $gate->define('delete-specification', 'Pimeo\Policies\SpecificationPolicy@delete');

        $gate->define('create-system', 'Pimeo\Policies\SystemPolicy@create');
        $gate->define('edit-system', 'Pimeo\Policies\SystemPolicy@edit');
        $gate->define('delete-system', 'Pimeo\Policies\SystemPolicy@delete');

        $gate->define('create-parent-product', 'Pimeo\Policies\ParentProductPolicy@create');
        $gate->define('delete-parent-product', 'Pimeo\Policies\ParentProductPolicy@delete');
        $gate->define('edit-parent-product', 'Pimeo\Policies\ParentProductPolicy@edit');

        $gate->define('approve-child-product', 'Pimeo\Policies\ChildProductPolicy@approve');
        $gate->define('create-child-product', 'Pimeo\Policies\ChildProductPolicy@create');
        $gate->define('delete-child-product', 'Pimeo\Policies\ChildProductPolicy@delete');
        $gate->define('edit-child-product', 'Pimeo\Policies\ChildProductPolicy@edit');

        $gate->define('import-child-product', 'Pimeo\Policies\ChildProductPolicy@import');
        $gate->define('unorphan-child-product', 'Pimeo\Policies\ChildProductPolicy@unorphan');
    }
}

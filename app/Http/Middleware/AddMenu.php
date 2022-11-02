<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Pimeo\Models\Attribute;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Services\Navigator;

class AddMenu
{
    public $blacklist;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()) {
            return $next($request);
        }

        $this->blacklist = $this->generateBlacklistedAccessArray();

        nav()
        ->add('home', 'menu.home', 'home')
        ->header('menu.content')
        ->setBlacklistedAccess($this->blacklist);

        nav()
            ->add('parent-product.index', 'menu.parent_products', 'file-o')
                ->includes([
                    'parent-product.create',
                    'parent-product.edit',
                    'parent-product.edit-attribute',
                    'parent-product.show',
                ])
            ->add('child-product.index', 'menu.child_products', 'files-o')
                ->includes([
                    'child-product.create',
                    'child-product.edit',
                    'child-product.edit-attribute',
                    'child-product.show',
                    'child-product.approve',
                ])
            ->add('system.index', 'menu.systems', 'building-o')
            ->add('specification.index', 'menu.specifications', 'pencil-square-o')
                ->includes([
                    'specifications.create',
                    'specifications.edit',
                    'specifications.edit-attribute',
                    'specifications.show',
                ])
            ->add('detail.index', 'menu.details', 'object-group')
                ->includes([
                    'detail.create',
                    'detail.edit',
                    'detail.edit-attribute',
                    'detail.show',
                ])
            ->add('technical-bulletin.index', 'menu.technical_bulletins', 'newspaper-o')
                ->includes([
                    'technical-bulletin.create',
                    'technical-bulletin.edit',
                    'technical-bulletin.edit-attribute',
                    'technical-bulletin.show',
                ])
            ->header('menu.manage')
            ->add('user.edit-my-profile', 'menu.my_profile', 'user');

        if (auth()->user() && auth()->user()->can('manage-users')) {
            nav()->add('user.index', 'menu.users', 'user-plus')
                ->includes(['user.create', 'user.edit']);
        }

        if (auth()->user() && auth()->user()->can('manage-companies')) {
            nav()->add('company.index', 'menu.companies', 'building')
                ->includes(['company.create', 'company.edit']);
        }

        if (auth()->user() && current_company()->id == 1) {
            nav()->add(['exportation.index'], 'menu.exportation', 'file-excel-o');
        }

        nav()
            ->add('attribute.index', 'menu.attributes.parent', 'cog', function (Navigator $nav) {
                $nav->setBlacklistedAccess($this->blacklist)
                    ->add(['attribute.index', 'child_product'], 'menu.attributes.child_products')
                    ->add(['attribute.index', 'detail'], 'menu.attributes.details')
                    ->add(['attribute.index', 'parent_product'], 'menu.attributes.parent_products')
                    ->add(['attribute.index', 'specification'], 'menu.attributes.specifications')
                    ->add(['attribute.index', 'system'], 'menu.attributes.systems')
                    ->add(['attribute.index', 'technical_bulletin'], 'menu.attributes.technical_bulletins')
                        ->allIncludes([
                            'attribute.create',
                            'attribute.edit',
                        ])
                        ->allWithParameters(['attribute' => function (Attribute $attribute, $parameters) {
                            return $attribute->model_type == $parameters[0];
                        }]);
            });

        return $next($request);
    }

    private function generateBlacklistedAccessArray()
    {
        $companyModels = CompanyModel::whereCompanyId(current_company()->id)->get()->pluck('model')->toArray();
        $modelsMapping = [
            'parent_products' => ParentProduct::class,
            'child_products' => ChildProduct::class,
            'systems' => System::class,
            'specifications' => Specification::class,
            'details' => Detail::class,
            'technical_bulletins' => TechnicalBulletin::class,
        ];

        $access = array_keys(array_diff($modelsMapping, $companyModels));

        return $access;
    }
}

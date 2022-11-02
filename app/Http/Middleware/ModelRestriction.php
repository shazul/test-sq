<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Pimeo\Models\ChildProduct;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;

/**
 * Class ModelRestriction
 * @package Pimeo\Http\Middleware
 *
 * Check if a company is allowed to access some models. For instance it can check if Soprema Canada has access to Detail
 */
class ModelRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null $model
     * @return mixed
     */
    public function handle($request, Closure $next, $model = null)
    {
        if ($model === null) {
            $modelKey = ($request->route('model')) ? $request->route('model') : $request->get('model_type');
            $model = $this->modelMapping($modelKey);
        }

        $allowed = CompanyModel::where(['company_id' => current_company()->id, 'model' => $model])->first();

        if (!$allowed) {
            return redirect()->route('home');
        }

        return $next($request);
    }

    private function modelMapping($key)
    {
        $model = [
            'parent_product' => ParentProduct::class,
            'child_product' => ChildProduct::class,
            'detail' => Detail::class,
            'specification' => Specification::class,
            'system' => System::class,
            'technical_bulletin' => TechnicalBulletin::class,
        ];

        return $model[$key];
    }
}

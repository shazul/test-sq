<?php

namespace Pimeo\Repositories;

use Pimeo\Models\ChildProduct;
use Pimeo\Models\Company;
use Pimeo\Models\CompanyModel;
use Pimeo\Models\Detail;
use Pimeo\Models\ParentProduct;
use Pimeo\Models\Specification;
use Pimeo\Models\System;
use Pimeo\Models\TechnicalBulletin;

class CompanyModelRepository
{
    /**
     * @param Company $company
     * @return string[];
     */
    public static function getModelsForCompany(Company $company)
    {
        $companyModels = CompanyModel::whereCompanyId($company->id)->get();
        $models = [];
        $allModels = self::getAllAvailableModelForDisplay();

        foreach ($companyModels as $companyModel) {
            $className = get_class(new $companyModel->model());
            $models[$className] = $allModels[$className];
        }

        return $models;
    }

    public static function createAllForExistingCompanies()
    {
        $companies = Company::all();
        $models = array_keys(self::getAllAvailableModelForDisplay());
        foreach ($companies as $company) {
            foreach ($models as $model) {
                CompanyModel::create(['company_id' => $company->id, 'model' => $model]);
            }
        }
    }

    /**
     * @param Company $company
     * @return Company[]|null
     */
    public function allForCompany(Company $company)
    {
        return CompanyModel::whereCompanyId($company->id)->all();
    }

    /**
     * @return string[]
     */
    public static function getAllAvailableModelForDisplay()
    {
        return [
            ParentProduct::class => trans('company.model.parent_products'),
            ChildProduct::class => trans('company.model.child_products'),
            Specification::class => trans('company.model.specifications'),
            Detail::class => trans('company.model.details'),
            System::class => trans('company.model.systems'),
            TechnicalBulletin::class => trans('company.model.technical_bulletins'),
        ];
    }
}

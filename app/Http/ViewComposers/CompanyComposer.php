<?php

namespace Pimeo\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Pimeo\Models\User;

class CompanyComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $companies = $this->getAvailableAlternativeCompanies();

        $view->with('view_companies', $companies);
    }

    /**
     * @return static
     */
    private function getAvailableAlternativeCompanies()
    {
        static $available_companies = null;

        if ($available_companies === null) {
            $companies = auth()->user()->companies()->get();

            $current_company_id = current_company()->id;

            $available_companies = $companies->reject(function ($company) use ($current_company_id) {
                return $company->id == $current_company_id;
            });
        }

        return $available_companies;
    }
}

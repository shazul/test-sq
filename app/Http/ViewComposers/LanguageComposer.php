<?php

namespace Pimeo\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Models\User;

class LanguageComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $languages = $this->getAvailableAlternativeLanguage();

        $view->with('view_languages', $languages);
        $view->with('current_language', $this->getCurrentLanguage());
    }

    /**
     * @return Language Current user language
     */
    private function getCurrentLanguage()
    {
        static $currentLanguage = null;

        if ($currentLanguage === null) {
            $current_language_id = Auth::user()->getLanguage()->id;
            $currentLanguage = Language::find($current_language_id);
        }

        return $currentLanguage;
    }

    /**
     * @return static
     */
    private function getAvailableAlternativeLanguage()
    {
        static $available_languages = null;

        if ($available_languages === null) {
            $active_language_id = Auth::user()->getLanguage()->id;
            $company = $this->getCurrentUserCompany();
            $available_languages = $company->languages()->where('language_id', '!=', $active_language_id)->get();
        }

        return $available_languages;
    }

    /**
     * @return Company
     */
    protected function getCurrentUserCompany()
    {
        return current_company();
    }
}

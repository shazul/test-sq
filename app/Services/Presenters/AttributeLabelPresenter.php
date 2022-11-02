<?php

namespace Pimeo\Services\Presenters;

use Auth;
use Laracasts\Presenter\Presenter;

class AttributeLabelPresenter extends Presenter
{
    public function value()
    {
        $language = app()->getLocale();
        $userLanguageCode = Auth::user()->getLanguageCode();

        return array_get($this->values, $language, array_get($this->values, $userLanguageCode));
    }
}

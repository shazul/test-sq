<?php

namespace Pimeo\Http\Controllers\Api;

use Pimeo\Models\Language;

class LanguagesController extends ApiController
{
    /**
     * Show all languages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $languages = Language::all();

        return $this->response($languages);
    }
}

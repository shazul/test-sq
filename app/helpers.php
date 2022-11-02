<?php
use Illuminate\Database\Eloquent\Collection;
use Pimeo\Models\Company;
use Pimeo\Models\Language;
use Pimeo\Services\Flash;

/**
 * @param  string $message
 * @param  bool   $dismiss
 * @return \Pimeo\Services\Flash
 */
function flash($message = null, $dismiss = null)
{
    /** @var Flash $flash */
    $flash = app('Pimeo\Services\Flash');

    if (func_num_args() == 0) {
        return $flash;
    }

    return $flash->info($message, $dismiss);
}

if (!function_exists('breadcrumb')) {
    /**
     * Get the breadcrumb instance.
     *
     * @return Pimeo\Services\Breadcrumb\Breadcrumb
     */
    function breadcrumb()
    {
        return app(Pimeo\Services\Breadcrumb\Breadcrumb::class);
    }
}

function old_value($key, $default = null)
{
    $key = str_replace(['[', ']'], ['.', ''], $key);

    return old($key, $default);
}

function language_code_trans($code)
{
    $trans_key = 'views.language';
    $trans_key .= '.' . Language::whereCode($code)->first()->name;

    return trans($trans_key);
}

function current_language_code()
{
    if (!auth()->user()) {
        return Language::whereCode(get_default_language_code())->first()->code;
    }

    return auth()->user()->getLanguageCode();
}

function current_language_iso_code()
{
    if (!auth()->user()) {
        return Language::whereCode(get_default_language_code())->first()->iso_code;
    }

    return auth()->user()->getLanguageIsoCode();
}

function current_company()
{
    return auth()->user()->getCompany();
}

function nav()
{
    return app('navigator');
}

function get_default_language_code()
{
    return current_company()->defaultLanguage->code;
}

/**
 * @return Collection
 */
function get_current_company_languages()
{
    static $languages = null;

    if ($languages == null) {
        $languages = current_company()->languages()->get();
    }

    return $languages;
}

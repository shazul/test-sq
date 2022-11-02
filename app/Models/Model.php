<?php

namespace Pimeo\Models;

use Illuminate\Database\Eloquent\Model as ModelAbstract;

abstract class Model extends ModelAbstract
{
    /**
     * Override default items per page.
     *
     * @var int
     */
    public static $overridePerPage = 0;

    /**
     * Languages to output.
     *
     * @var array|null
     */
    public static $languages = null;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        if (self::$overridePerPage) {
            return self::$overridePerPage;
        }

        return parent::getPerPage();
    }

    /**
     * Verify the presence of the language to output.
     *
     * @param  string $language
     * @return bool
     */
    protected function hasLanguage($language)
    {
        return in_array($language, self::$languages);
    }
}

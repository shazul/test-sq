<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pimeo\Transformers\Transformer;

class DefineLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('lang')) {
            Transformer::setLanguages($this->clean(explode(',', $request->get('lang'))));
        }

        return $next($request);
    }

    /**
     * Clean languages parameter.
     *
     * @param  string $languages
     * @return array
     */
    protected function clean($languages)
    {
        array_walk($languages, function ($language) {
            return trim($language);
        });

        return $languages;
    }
}

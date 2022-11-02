<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Pimeo\Models\Model;

class DefinePerPageQuery
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
        if ($request->has('per_page')) {
            Model::$overridePerPage = (int)$request->get('per_page');
        }

        return $next($request);
    }
}

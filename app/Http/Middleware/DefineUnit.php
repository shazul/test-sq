<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Pimeo\Transformers\Transformer;

class DefineUnit
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
        if ($request->has('units')) {
            Transformer::setUnits($this->clean(explode(',', $request->get('units'))));
        }

        return $next($request);
    }

    /**
     * Clean units parameter.
     *
     * @param  string $units
     * @return array
     */
    protected function clean($units)
    {
        array_walk($units, function ($unit) {
            return trim($unit);
        });

        return $units;
    }
}

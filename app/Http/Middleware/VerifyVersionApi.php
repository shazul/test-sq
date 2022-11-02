<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class VerifyVersionApi extends BaseMiddleware
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
        if (!$this->supportsVersion($request->header('Accept'))) {
            return $this->respond('soprema.api.bad-version', 'version_not_supported', 400, true);
        }

        return $next($request);
    }

    /**
     * Verify if the accept header include a supported API version.
     *
     * @param  string $acceptHeader
     * @return bool
     */
    protected function supportsVersion($acceptHeader)
    {
        preg_match('/application\/vnd\.soprema\.(?P<version>v[0-9]+)\+json/', $acceptHeader, $matches);

        return isset($matches['version']) && in_array($matches['version'], config('api.supported'));
    }
}

<?php

namespace Pimeo\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Pimeo\Models\User;

class SetLanguage
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = $this->getUser()) {
            $code = $user->getLanguageIsoCode();
            app()->setLocale($code);
        }

        return $next($request);
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->auth->user();
    }
}

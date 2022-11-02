<?php

namespace Pimeo\Http;

use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Pimeo\Http\Middleware\ModelRestriction;
use Pimeo\Http\Middleware\RememberListingArguments;
use Pimeo\Http\Middleware\SetLanguage;
use Tymon\JWTAuth\Middleware\GetUserFromToken;
use Tymon\JWTAuth\Middleware\RefreshToken;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            Middleware\EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            SetLanguage::class,
            ShareErrorsFromSession::class,
            Middleware\VerifyCsrfToken::class,
            Middleware\AddMenu::class,
            RememberListingArguments::class,
        ],
        'api' => [
            'throttle:60,1',
            Middleware\VerifyVersionApi::class,
            'jwt.auth',
            Middleware\DefinePerPageQuery::class,
            Middleware\DefineLanguage::class,
            Middleware\DefineUnit::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'        => Middleware\Authenticate::class,
        'auth.basic'  => AuthenticateWithBasicAuth::class,
        'guest'       => Middleware\RedirectIfAuthenticated::class,
        'throttle'    => ThrottleRequests::class,
        'jwt.auth'    => GetUserFromToken::class,
        'jwt.refresh' => RefreshToken::class,
        'model'       => ModelRestriction::class,
    ];
}

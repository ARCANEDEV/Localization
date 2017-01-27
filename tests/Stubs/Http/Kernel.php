<?php namespace Arcanedev\Localization\Tests\Stubs\Http;

use Arcanedev\Localization\Traits\LocalizationKernelTrait;
use Orchestra\Testbench\Http\Kernel as HttpKernel;

/**
 * Class     Kernel
 *
 * @package  Arcanedev\Localization\Tests\Stubs\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Kernel extends HttpKernel
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use LocalizationKernelTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Orchestra\Testbench\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    ];
}

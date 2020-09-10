<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Stubs\Http;

use Orchestra\Testbench\Http\Kernel as HttpKernel;

/**
 * Class     Kernel
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Kernel extends HttpKernel
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            Middleware\EncryptCookies::class, // Custom

            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Orchestra\Testbench\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
}

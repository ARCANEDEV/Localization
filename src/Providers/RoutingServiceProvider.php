<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Middleware\{
    LocaleCookieRedirect, LocaleSessionRedirect, LocalizationRedirect, LocalizationRoutes, TranslationRedirect
};
use Arcanedev\Localization\Routing\Router;
use Arcanedev\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class     RoutingServiceProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RoutingServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'localization-session-redirect' => LocaleSessionRedirect::class,
        'localization-cookie-redirect'  => LocaleCookieRedirect::class,
        'localization-redirect'         => LocalizationRedirect::class,
        'localized-routes'              => LocalizationRoutes::class,
        'translation-redirect'          => TranslationRedirect::class,
    ];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        /** @var  \Illuminate\Routing\Router  $router */
        $router = $this->app['router'];

        $router->mixin(new Router);

        foreach ($this->routeMiddleware as $name => $class) {
            $router->aliasMiddleware($name, $class);
        }
    }
}

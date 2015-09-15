<?php namespace Arcanedev\Localization\Routing;

use Arcanedev\Localization\Middleware\LocaleSessionRedirect;
use Arcanedev\Localization\Middleware\LocalizationRedirectFilter;
use Arcanedev\Localization\Middleware\LocalizationRoutes;
use Closure;
use Illuminate\Routing\Router;

/**
 * Class     TransRouter
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransRouter
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct(Router $router)
    {
        $this->registerMiddleware($router);
        $this->registerLocalizedGroup($router);
    }

    public static function translate(Router $router)
    {
        return new self($router);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function registerMiddleware(Router $router)
    {
        $router->middleware('localize',              LocalizationRoutes::class);
        $router->middleware('localizationRedirect',  LocalizationRedirectFilter::class);
        $router->middleware('localeSessionRedirect', LocaleSessionRedirect::class);
    }

    private function registerLocalizedGroup(Router $router)
    {
        $router->macro('localizedGroup', function (Closure $callback) use ($router) {
            $attributes = [
                'prefix'     => localization()->setLocale(),
                'middleware' => [
                    'localeSessionRedirect',
                    'localizationRedirect'
                ],
            ];

            $router->group($attributes, $callback);
        });
    }
}

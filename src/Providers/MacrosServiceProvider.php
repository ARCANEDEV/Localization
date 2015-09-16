<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Middleware\LocaleSessionRedirect;
use Arcanedev\Localization\Middleware\LocalizationRedirectFilter;
use Arcanedev\Localization\Middleware\LocalizationRoutes;
use Arcanedev\Support\ServiceProvider;
use Closure;
use Illuminate\Routing\Router;

/**
 * Class     MacrosServiceProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MacrosServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $router = $this->app['router'];

        $this->registerMiddlewares($router);
        $this->registerMacros($router);
    }

    private function registerMiddlewares(Router $router)
    {
        $router->middleware('localize',              LocalizationRoutes::class);
        $router->middleware('localizationRedirect',  LocalizationRedirectFilter::class);
        $router->middleware('localeSessionRedirect', LocaleSessionRedirect::class);
    }

    private function registerMacros($router)
    {
        $this->registerLocalizedGroupMacro($router);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Route Macros
     | ------------------------------------------------------------------------------------------------
     */
    private function registerLocalizedGroupMacro(Router $router)
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

<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Middleware\LocaleCookieRedirect;
use Arcanedev\Localization\Middleware\LocaleSessionRedirect;
use Arcanedev\Localization\Middleware\LocalizationRedirect;
use Arcanedev\Localization\Middleware\LocalizationRoutes;
use Arcanedev\Support\ServiceProvider;
use Closure;
use Illuminate\Routing\Router;

/**
 * Class     RouterServiceProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouterServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $middleware = [];

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

    /**
     * Register the middlewares.
     *
     * @param  Router  $router
     */
    private function registerMiddlewares(Router $router)
    {
        $this->registerMiddleware($router, 'localized-routes',              LocalizationRoutes::class);
        $this->registerMiddleware($router, 'localization-session-redirect', LocaleSessionRedirect::class);
        $this->registerMiddleware($router, 'localization-cookie-redirect',  LocaleCookieRedirect::class);
        $this->registerMiddleware($router, 'localization-redirect',         LocalizationRedirect::class);
    }

    /**
     * Register a middleware.
     *
     * @param  Router  $router
     * @param  string  $name
     * @param  string  $class
     */
    private function registerMiddleware(Router $router, $name, $class)
    {
        $router->middleware($name,  $class);

        if ($this->app['config']->get('localization.route.middleware.' . $name, false)) {
            $this->middleware[] = $name;
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Route Macros
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the router macros.
     *
     * @param  Router  $router
     */
    private function registerMacros(Router $router)
    {
        $this->registerLocalizedGroupMacro($router);
    }

    /**
     * Register the 'localizedGroup' macro.
     *
     * @param  Router  $router
     */
    private function registerLocalizedGroupMacro(Router $router)
    {
        $middleware = $this->middleware;

        $router->macro('localizedGroup', function (Closure $callback) use ($router, $middleware) {
            $attributes = [
                'prefix'     => localization()->setLocale(),
                'middleware' => $middleware,
            ];

            $router->group($attributes, $callback);
        });
    }
}

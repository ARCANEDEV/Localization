<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Middleware\LocaleCookieRedirect;
use Arcanedev\Localization\Middleware\LocaleSessionRedirect;
use Arcanedev\Localization\Middleware\LocalizationRedirect;
use Arcanedev\Localization\Middleware\LocalizationRoutes;
use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;
use Arcanedev\Localization\Routing\Router;

/**
 * Class     RoutingServiceProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RoutingServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The application's route middleware.
     *
     * @var array
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
        $this->registerRouter();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Router Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the router instance.
     */
    protected function registerRouter()
    {
        $this->app['router'] = $this->app->share(function ($app) {
            return new Router($app['events'], $app);
        });

        $this->registerMiddlewares($this->app['router']);
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

        if ($this->getMiddleware($name)) {
            $this->middleware[] = $name;
        }
    }

    /**
     * Get the middleware status.
     *
     * @param  string  $name
     *
     * @return bool
     */
    private function getMiddleware($name)
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        return (bool) $config->get('localization.route.middleware.' . $name, false);
    }
}

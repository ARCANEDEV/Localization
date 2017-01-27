<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Middleware;
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
        $this->app->singleton('router', function ($app) {
            return new Router($app['events'], $app);
        });

        $this->registerMiddlewares();
    }

    /**
     * Register the middlewares.
     */
    private function registerMiddlewares()
    {
        /** @var Router $router */
        $router      = $this->app['router'];
        $middlewares = [
            'localization-session-redirect' => Middleware\LocaleSessionRedirect::class,
            'localization-cookie-redirect'  => Middleware\LocaleCookieRedirect::class,
            'localization-redirect'         => Middleware\LocalizationRedirect::class,
            'localized-routes'              => Middleware\LocalizationRoutes::class,
            'translation-redirect'          => Middleware\TranslationRedirect::class,
        ];

        foreach ($middlewares as $name => $class) {
            $this->registerMiddleware($router, $name, $class);
        }
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
        $router->aliasMiddleware($name, $class);

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
        /** @var  \Illuminate\Contracts\Config\Repository  $config */
        $config = $this->app['config'];

        return (bool) $config->get("localization.route.middleware.{$name}", false);
    }
}

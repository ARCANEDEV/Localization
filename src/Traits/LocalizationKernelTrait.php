<?php namespace Arcanedev\Localization\Traits;

/**
 * Class     LocalizationKernelTrait
 *
 * @package  Arcanedev\Localization\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Foundation\Application      app
 * @property  \Arcanedev\Localization\Routing\Router  router
 * @property  array                                   middlewareGroups
 * @property  array                                   routeMiddleware
 */
trait LocalizationKernelTrait
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the route dispatcher callback.
     *
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        $this->replaceRouter();

        return parent::dispatchToRouter();
    }

    /**
     * Replace the illuminate router with the localization router.
     */
    protected function replaceRouter()
    {
        $this->router = $this->app->make('router');

        foreach ($this->middlewareGroups as $key => $middleware) {
            $this->router->middlewareGroup($key, $middleware);
        }

        foreach ($this->routeMiddleware as $key => $middleware) {
            $this->router->aliasMiddleware($key, $middleware);
        }
    }
}

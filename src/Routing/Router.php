<?php namespace Arcanedev\Localization\Routing;

use Closure;
use Illuminate\Routing\Router as IlluminateRouter;

/**
 * Class     Router
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Router extends IlluminateRouter
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get active middlewares.
     *
     * @return array
     */
    protected function getActiveMiddlewares()
    {
        $middleware = config('localization.route.middleware', []);

        return array_keys(array_filter($middleware));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Route Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a route group with shared attributes.
     *
     * @param  array     $attributes
     * @param  \Closure  $callback
     */
    public function localizedGroup(Closure $callback, $attributes = [])
    {
        $prefix     = localization()->setLocale();
        $middleware = $this->getActiveMiddlewares();
        $attributes = array_merge($attributes, compact('prefix', 'middleware'));

        $this->group(array_filter($attributes), $callback);
    }

    /**
     * Register a new translated GET route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transGet($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->get($uri, $action);
    }

    /**
     * Register a new translated POST route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPost($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->post($uri, $action);
    }

    /**
     * Register a new translated PUT route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPut($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->put($uri, $action);
    }

    /**
     * Register a new translated PATCH route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPatch($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->patch($uri, $action);
    }

    /**
     * Register a new translated DELETE route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transDelete($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->delete($uri, $action);
    }

    /**
     * Register a new translated OPTIONS route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transOptions($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->options($uri, $action);
    }

    /**
     * Register a new translated any route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transAny($trans, $action)
    {
        $uri = localization()->transRoute($trans);

        return $this->any($uri, $action);
    }
}

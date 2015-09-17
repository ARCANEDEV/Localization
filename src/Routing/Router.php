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
        $attributes = array_merge($attributes, [
            'prefix'     => localization()->setLocale(),
            'middleware' => $this->getMiddleware(),
        ]);

        $this->group($attributes, $callback);
    }

    /**
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transGet($trans, $action)
    {
        $uri = $trans; // TODO: Add translation

        return $this->get($uri, $action);
    }

    /**
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPost($trans, $action)
    {
        $uri = $trans; // TODO: Add translation

        return $this->post($uri, $action);
    }

    /**
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPut($trans, $action)
    {
        $uri = $trans; // TODO: Add translation

        return $this->put($uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPatch($trans, $action)
    {
        $uri = $trans; // TODO: Add translation

        return $this->patch($uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transDelete($trans, $action)
    {
        $uri = $trans; // TODO: Add translation

        return $this->delete($uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transOptions($trans, $action)
    {
        $uri = $trans; // TODO: Add translation

        return $this->options($uri, $action);
    }
}

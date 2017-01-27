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
        return array_keys(array_filter(
            config('localization.route.middleware', [])
        ));
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
        $attributes = array_merge($attributes, [
            'prefix'     => localization()->setLocale(),
            'middleware' => $this->getActiveMiddlewares(),
        ]);

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
        return $this->get(
            localization()->transRoute($trans), $action
        );
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
        return $this->post(
            localization()->transRoute($trans), $action
        );
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
        return $this->put(
            localization()->transRoute($trans), $action
        );
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
        return $this->patch(
            localization()->transRoute($trans), $action
        );
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
        return $this->delete(
            localization()->transRoute($trans), $action
        );
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
        return $this->options(
            localization()->transRoute($trans), $action
        );
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
        return $this->any(
            localization()->transRoute($trans), $action
        );
    }
}

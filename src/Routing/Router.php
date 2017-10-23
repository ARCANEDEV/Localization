<?php namespace Arcanedev\Localization\Routing;

use Closure;

/**
 * Class     Router
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Router
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get active middlewares.
     *
     * @return Closure|array
     */
    protected function getActiveMiddlewares()
    {
        return function() {
            return array_keys(array_filter(
                config('localization.route.middleware', [])
            ));
        };
    }

    /* -----------------------------------------------------------------
     |  Route Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a route group with shared attributes.
     *
     * @return Closure
     * @internal param array $attributes
     * @internal param Closure $callback
     */
    public function localizedGroup()
    {
        return function(Closure $callback, $attributes = []) {
            $attributes = array_merge($attributes, [
                'prefix' => localization()->setLocale(),
                'middleware' => $this->getActiveMiddlewares(),
            ]);

            $this->group(array_filter($attributes), $callback);
        };
    }

    /**
     * Register a new translated GET route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transGet()
    {
        return function($trans, $action) {
            return $this->get(
                $this->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated POST route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transPost()
    {
        return function($trans, $action) {
            return $this->post(
                $this->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated PUT route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transPut()
    {
        return function($trans, $action) {
            return $this->put(
                $this->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated PATCH route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transPatch()
    {
        return function($trans, $action) {
            return $this->patch(
                $this->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated DELETE route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transDelete()
    {
        return function($trans, $action) {
            return $this->delete(
                $this->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated OPTIONS route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transOptions()
    {
        return function($trans, $action) {
            return $this->options(
                $this->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated any route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     * @internal param string $trans
     * @internal param array|Closure|string $action
     *
     */
    public function transAny()
    {
        return function($trans, $action) {
            return $this->any(
                $this->transRoute($trans), $action
            );
        };
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate the route.
     *
     * @return Closure|string
     * @internal param string $key
     *
     */
    protected function transRoute()
    {
        return function($key) {
            return localization()->transRoute($key);
        };
    }
}
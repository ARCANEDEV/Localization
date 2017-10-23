<?php namespace Arcanedev\Localization\Routing;

use Closure;

/**
 * Class     Router
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Illuminate\Routing\Router
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
     */
    public function localizedGroup()
    {
        return function(Closure $callback, array $attributes = []) {
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
     */
    public function transGet()
    {
        return function($trans, $action) {
            return $this->get(
                localization()->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated POST route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     */
    public function transPost()
    {
        return function($trans, $action) {
            return $this->post(
                localization()->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated PUT route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     */
    public function transPut()
    {
        return function($trans, $action) {
            return $this->put(
                localization()->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated PATCH route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     */
    public function transPatch()
    {
        return function($trans, $action) {
            return $this->patch(
                localization()->transRoute($trans), $action
            );
        };
    }

    /**
     * Register a new translated DELETE route with the router.
     *
     * @return Closure|\Illuminate\Routing\Route
     */
    public function transDelete()
    {
        return function($trans, $action) {
            return $this->delete(
                localization()->transRoute($trans), $action
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
                localization()->transRoute($trans), $action
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
                localization()->transRoute($trans), $action
            );
        };
    }
}

<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Routing;

use Closure;

/**
 * Class     Router
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Illuminate\Routing\Router
 */
class Router
{
    /* -----------------------------------------------------------------
     |  Route Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a route group with shared attributes.
     *
     * @return \Closure
     */
    public function localizedGroup(): Closure
    {
        return function(Closure $callback, array $attributes = []) {
            $activeMiddleware = array_keys(array_filter(
                config('localization.route.middleware', [])
            ));

            $attributes = array_merge($attributes, [
                'prefix'     => localization()->setLocale(),
                'middleware' => $activeMiddleware,
            ]);

            $this->group(array_filter($attributes), $callback);
        };
    }

    /**
     * Register a new translated GET route with the router.
     *
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transGet(): Closure
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
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transPost(): Closure
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
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transPut(): Closure
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
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transPatch(): Closure
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
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transDelete(): Closure
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
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transOptions(): Closure
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
     * @return \Closure|\Illuminate\Routing\Route
     */
    public function transAny(): Closure
    {
        return function($trans, $action) {
            return $this->any(
                localization()->transRoute($trans), $action
            );
        };
    }
}

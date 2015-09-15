<?php namespace Arcanedev\Localization\Routing;

use Illuminate\Routing\Router;

/**
 * Class     TransRouter
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransRouter extends Router
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a localized route group with shared attributes.
     *
     * @param  \Closure  $callback
     */
    public function trans(\Closure $callback)
    {
        $attributes = [
            'prefix'     => localization()->setLocale(),
            'middleware' => [
                'localeSessionRedirect',
                'localizationRedirect'
            ],
        ];

        $this->group($attributes, $callback);
    }

    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function get($uri, $action);

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function post($uri, $action);

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function put($uri, $action);

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function patch($uri, $action);

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function delete($uri, $action);

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function options($uri, $action);

    /**
     * Register a new route responding to all verbs.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function any($uri, $action);

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
//    public function match($methods, $uri, $action);
}

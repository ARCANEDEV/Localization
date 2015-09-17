<?php namespace Arcanedev\Localization\Routing;

use Closure;

/**
 * Class     Router
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Router extends \Illuminate\Routing\Router
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
}

<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\RouteBindable;
use Arcanedev\Localization\Contracts\Url as UrlContract;
use Illuminate\Http\Request;

/**
 * Class     Url
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo:    Refactoring
 */
class Url implements UrlContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Extract attributes for current url.
     *
     * @param  bool|false|string  $url
     *
     * @return array
     */
    public static function extractAttributes($url = false)
    {
        $parse  = parse_url($url);
        $path   = isset($parse['path']) ? explode('/', $parse['path']) : [];
        $url    = [];

        foreach ($path as $segment) {
            if ( ! empty($segment)) $url[] = $segment;
        }

        /** @var \Illuminate\Routing\Router $router */
        $router = app('router');

        return self::extractAttributesFromRoutes($url, $router->getRoutes());
    }

    /**
     * Change uri attributes (wildcards) for the ones in the $attributes array.
     *
     * @param  array   $attributes
     * @param  string  $uri
     *
     * @return string
     */
    public static function substituteAttributes(array $attributes, $uri)
    {
        foreach ($attributes as $key => $value) {
            if ($value instanceof RouteBindable) {
                $value = $value->getWildcardValue();
            }

            $uri = str_replace(['{' . $key . '?}', '{' . $key . '}'], $value, $uri);
        }

        // delete empty optional arguments that are not in the $attributes array
        return preg_replace('/\/{[^)]+\?}/', '', $uri);
    }

    /**
     * Build URL using array data from parse_url.
     *
     * @param  array|false  $parsed
     *
     * @return string
     */
    public static function unparse($parsed)
    {
        if (empty($parsed)) return '';

        self::checkParsedUrl($parsed);

        $url  = self::getUrl($parsed);
        $url .= self::getQuery($parsed);
        $url .= self::getFragment($parsed);

        return $url;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Extract Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Extract attributes from routes.
     *
     * @param  array                                $url
     * @param  \Illuminate\Routing\RouteCollection  $routes
     *
     * @return array
     */
    private static function extractAttributesFromRoutes($url, $routes)
    {
        $attributes = [];

        foreach ($routes as $route) {
            /**
             * @var  \Illuminate\Routing\Route  $route
             * @var  \Illuminate\Http\Request   $request
             */
            $request = Request::create(implode('/', $url));

            if ( ! $route->matches($request)) {
                continue;
            }

            $match = self::hasAttributesFromUriPath($url, $route->uri(), $attributes);

            if ($match)
                break;
        }

        return $attributes;
    }

    /**
     * Check if has attributes from a route.
     *
     * @param  array   $url
     * @param  string  $path
     * @param  array   $attributes
     *
     * @return bool
     */
    private static function hasAttributesFromUriPath($url, $path, &$attributes)
    {
        $i     = 0;
        $match = true;
        $path  = explode('/', $path);

        foreach ($path as $j => $segment) {
            if (isset($url[$i])) {
                if ($segment !== $url[$i]) {
                    self::extractAttributesFromSegment($url, $path, $i, $j, $segment, $attributes);
                }

                $i++;
                continue;
            }
            elseif ( ! preg_match('/{[\w]+\?}/', $segment)) {
                // No optional parameters but no more $url given this route does not match the url
                $match = false;
                break;
            }
        }

        if (isset($url[$i + 1]))
            $match = false;

        return $match;
    }

    /**
     * Extract attribute from a segment.
     *
     * @param  array   $url
     * @param  array   $path
     * @param  int     $i
     * @param  int     $j
     * @param  string  $segment
     * @param  array   $attributes
     */
    private static function extractAttributesFromSegment($url, $path, $i, $j, $segment, &$attributes)
    {
        // Required parameters
        if (preg_match('/{[\w]+}/', $segment)) {
            $attributeName              = preg_replace(['/{/', '/\?/', '/}/'], '', $segment);
            $attributes[$attributeName] = $url[$i];
        }

        // Optional parameter
        if (
            preg_match('/{[\w]+\?}/', $segment) &&
            ( ! isset($path[$j + 1]) || $path[$j + 1] !== $url[$i])
        ) {
            $attributeName              = preg_replace(['/{/', '/\?/', '/}/'], '', $segment);
            $attributes[$attributeName] = $url[$i];
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Unparse Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check parsed URL.
     *
     * @param  array  $parsed
     */
    private static function checkParsedUrl(array &$parsed)
    {
        $scheme    =& $parsed['scheme'];
        $user      =& $parsed['user'];
        $pass      =& $parsed['pass'];
        $host      =& $parsed['host'];
        $port      =& $parsed['port'];
        $path      =& $parsed['path'];
        $path      = '/' . ltrim($path, '/'); // If / is missing for path.
        $query     =& $parsed['query'];
        $fragment  =& $parsed['fragment'];

        $parsed    = compact(
            'scheme', 'user', 'pass', 'host', 'port', 'path', 'query', 'fragment'
        );
    }

    /**
     * Get URL.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getUrl(array $parsed)
    {
        $url       = '';

        if (strlen($parsed['scheme'])) {
            $url = $parsed['scheme'] . ':' . self::getHierPart($parsed);
        }

        return $url;
    }

    /**
     * Get hier part.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getHierPart(array $parsed)
    {
        $path      = $parsed['path'];
        $authority = self::getAuthority($parsed);

        if (strlen($authority)) {
            $path = '//' . $authority . $path;
        }

        return $path;
    }

    /**
     * Get authority.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getAuthority(array $parsed)
    {
        $userInfo  = self::getUserInfo($parsed);
        $host      = self::getHost($parsed);

        if (strlen($userInfo)) {
            return $userInfo . '@' . $host;
        }

        return $host;
    }

    /**
     * Get user info.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getUserInfo(array $parsed)
    {
        $userInfo = '';

        if (strlen($parsed['pass'])) {
            $userInfo = $parsed['user'] . ':' . $parsed['pass'];
        }

        return $userInfo;
    }

    /**
     * Get host.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getHost(array $parsed)
    {
        $host = $parsed['host'];

        if ( ! empty((string) $parsed['port'])) {
            $host = $host . ':' . $parsed['port'];
        }

        return $host;
    }

    /**
     * Get Query.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getQuery(array $parsed)
    {
        $query = '';

        if (strlen($parsed['query'])) {
            $query = '?' . $parsed['query'];
        }

        return $query;
    }

    /**
     * Get fragment.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getFragment(array $parsed)
    {
        $fragment = '';

        if (strlen($parsed['fragment'])) {
            $fragment = '#' . $parsed['fragment'];
        }

        return $fragment;
    }
}

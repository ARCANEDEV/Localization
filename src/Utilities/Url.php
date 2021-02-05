<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\RouteBindable;
use Arcanedev\Localization\Contracts\Url as UrlContract;
use Illuminate\Http\Request;

/**
 * Class     Url
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Url implements UrlContract
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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
        $parse = parse_url((string) $url);
        $path  = isset($parse['path']) ? explode('/', $parse['path']) : [];
        $url   = [];

        foreach ($path as $segment) {
            if ( ! empty($segment))
                $url[] = $segment;
        }

        return self::extractAttributesFromRoutes($url, app('router')->getRoutes());
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
            if ($value instanceof RouteBindable)
                $value = $value->getWildcardValue();

            $uri = str_replace(['{'.$key.'?}', '{'.$key.'}'], (string) $value, $uri);
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

        $parsed = self::checkParsedUrl($parsed);

        $url  = self::getUrl($parsed);
        $url .= self::getQuery($parsed);
        $url .= self::getFragment($parsed);

        return $url;
    }

    /* -----------------------------------------------------------------
     |  Extract Methods
     | -----------------------------------------------------------------
     */

    /**
     * Extract attributes from routes.
     *
     * @param  array                                $url
     * @param  \Illuminate\Routing\RouteCollection  $routes
     *
     * @return array
     */
    private static function extractAttributesFromRoutes(array $url, $routes): array
    {
        $attributes = [];

        foreach ($routes as $route) {
            /**
             * @var  \Illuminate\Routing\Route  $route
             * @var  \Illuminate\Http\Request   $request
             */
            $request = Request::create(implode('/', $url));

            if ( ! $route->matches($request))
                continue;

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
    private static function hasAttributesFromUriPath($url, $path, &$attributes): bool
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
    private static function extractAttributesFromSegment($url, $path, $i, $j, $segment, &$attributes): void
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

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check parsed URL.
     *
     * @param  array  $parsed
     *
     * @return array
     */
    private static function checkParsedUrl(array $parsed): array
    {
        $scheme   =& $parsed['scheme'];
        $user     =& $parsed['user'];
        $pass     =& $parsed['pass'];
        $host     =& $parsed['host'];
        $port     =& $parsed['port'];
        $path     =& $parsed['path'];
        $path     = '/'.ltrim($path, '/'); // If / is missing for path.
        $query    =& $parsed['query'];
        $fragment =& $parsed['fragment'];

        return compact(
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
    private static function getUrl(array $parsed): string
    {
        return strlen((string) $parsed['scheme'])
            ? $parsed['scheme'].':'.self::getHierPart($parsed)
            : '';
    }

    /**
     * Get hier part.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getHierPart(array $parsed): string
    {
        return strlen($authority = self::getAuthority($parsed))
            ? '//'.$authority.$parsed['path']
            : $parsed['path'];
    }

    /**
     * Get authority.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getAuthority(array $parsed): string
    {
        $host = self::getHost($parsed);

        return strlen($userInfo = self::getUserInfo($parsed)) ? $userInfo.'@'.$host : $host;
    }

    /**
     * Get user info.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getUserInfo(array $parsed): string
    {
        return strlen((string) $parsed['pass'])
            ? $parsed['user'].':'.$parsed['pass']
            : '';
    }

    /**
     * Get host.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getHost(array $parsed): string
    {
        return empty((string) $parsed['port'])
            ? $parsed['host']
            : $parsed['host'].':'.$parsed['port'];
    }

    /**
     * Get Query.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getQuery(array $parsed): string
    {
        return strlen((string) $parsed['query'])
            ? '?'.$parsed['query']
            : '';
    }

    /**
     * Get fragment.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getFragment(array $parsed): string
    {
        return strlen((string) $parsed['fragment'])
            ? '#'.$parsed['fragment']
            : '';
    }
}

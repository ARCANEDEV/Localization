<?php namespace Arcanedev\Localization\Utilities;

/**
 * Class     Url
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo:    Refactoring
 */
class Url
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
        $attributes = [];

        if (empty($url)) {
            if ( ! app('router')->current()) {
                return [];
            }

            $attributes = app('router')->current()->parameters();
            $response   = event('routes.translation', [$attributes]);

            if ( ! empty($response)) {
                $response = array_shift($response);
            }

            if (is_array($response)) {
                $attributes = array_merge($attributes, $response);
            }

            return $attributes;
        }

        $parse = parse_url($url);
        $parse = isset($parse['path']) ? explode('/', $parse['path']) : [];
        $url   = [];

        foreach ($parse as $segment) {
            if ( ! empty($segment)) $url[] = $segment;
        }

        foreach (app('router')->getRoutes() as $route) {
            /** @var \Illuminate\Routing\Route $route */
            $path = $route->getUri();

            if ( ! preg_match('/{[\w]+}/', $path)) {
                continue;
            }

            $path  = explode('/', $path);
            $i     = 0;
            $match = true;

            foreach ($path as $j => $segment) {
                if (isset($url[$i])) {
                    if ($segment === $url[$i]) {
                        $i++;
                        continue;
                    }

                    if (preg_match('/{[\w]+}/', $segment)) {
                        // must-have parameters
                        $attributeName = preg_replace([ "/}/", "/{/", "/\?/" ], "", $segment);
                        $attributes[$attributeName] = $url[ $i ];
                        $i++;
                        continue;
                    }

                    if (preg_match('/{[\w]+\?}/', $segment)) {
                        // optional parameters
                        if ( ! isset($path[$j + 1]) || $path[$j + 1] !== $url[$i]) {
                            // optional parameter taken
                            $attributeName = preg_replace(['/}/', '/{/', '/\?/'], '', $segment);
                            $attributes[$attributeName] = $url[$i];
                            $i++;
                            continue;
                        }
                    }
                }
                elseif ( ! preg_match('/{[\w]+\?}/', $segment)) {
                    // no optional parameters but no more $url given
                    // this route does not match the url
                    $match = false;
                    break;
                }
            }

            if (isset($url[$i + 1])) {
                $match = false;
            }

            if ($match) {
                return $attributes;
            }
        }

        return $attributes;
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
            $uri = str_replace('{' . $key . '}',  $value, $uri);
            $uri = str_replace('{' . $key . '?}', $value, $uri);
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
        $url = '';

        if (empty($parsed)) {
            return $url;
        }

        $parsed         = self::checkParsedUrl($parsed);

        $userInfo  = ! strlen($parsed['pass'])     ? $parsed['pass'] : $parsed['user'] . ':' . $parsed['pass'];
        $host      = ! (string) $parsed['port']    ? $parsed['host'] : $parsed['host'] . ':' . $parsed['port'];
        $authority = ! strlen($userInfo)           ? $host           : $userInfo . '@' . $host;
        $hierPart  = ! strlen($authority)          ? $parsed['path'] : '//' . $authority . $parsed['path'];
        $url       = ! strlen($parsed['scheme'])   ? $hierPart       : $parsed['scheme'] . ':' . $hierPart;
        $url       = ! strlen($parsed['query'])    ? $url            : $url    . '?' . $parsed['query'];
        $url       = ! strlen($parsed['fragment']) ? $url            : $url    . '#' . $parsed['fragment'];

        return $url;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     *
     * @param  array  $parsed
     *
     * @return array
     */
    private static function checkParsedUrl($parsed)
    {
        $scheme    =& $parsed['scheme'];
        $host      =& $parsed['host'];
        $port      =& $parsed['port'];
        $user      =& $parsed['user'];
        $pass      =& $parsed['pass'];
        $path      =& $parsed['path'];
        $path      = '/' . ltrim($path, '/');
        $query     =& $parsed['query'];
        $fragment  =& $parsed['fragment'];

        return compact('scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment');
    }
}

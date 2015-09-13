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

        self::checkParsedUrl($parsed);

        $url  = self::getUrl($parsed);
        $url .= self::getQuery($parsed);
        $url .= self::getFragment($parsed);

        return $url;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param  array  $parsed
     *
     * @return array
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
     * Get Url.
     *
     * @param  array  $parsed
     *
     * @return string
     */
    private static function getUrl(array $parsed)
    {
        $hierPart  = self::getHierPart($parsed);

        if (strlen($parsed['scheme'])) {
            return $parsed['scheme'] . ':' . $hierPart;
        }

        return $hierPart;
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
        $authority = self::getAuthority($parsed);

        if (strlen($authority)) {
            return '//' . $authority . $parsed['path'];
        }

        return $parsed['path'];
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
        if (strlen($parsed['pass'])) {
            return $parsed['user'] . ':' . $parsed['pass'];
        }

        return '';
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
        if ( ! empty((string) $parsed['port'])) {
            return $parsed['host'] . ':' . $parsed['port'];
        }

        return $parsed['host'];
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
        if (strlen($parsed['fragment'])) {
            return '#' . $parsed['fragment'];
        }

        return '';
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
        if (strlen($parsed['query'])) {
            return '?' . $parsed['query'];
        }

        return '';
    }
}

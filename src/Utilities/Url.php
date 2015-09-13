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
     * Build URL using array data from parse_url.
     *
     * @param  array|false  $parsedUrl  -  Array of data from parse_url function
     *
     * @return string
     */
    public static function unparse($parsedUrl)
    {
        if (empty($parsedUrl)) {
            return '';
        }

        $url  = '';
        $url .= isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $url .= isset($parsedUrl['host'])   ? $parsedUrl['host']           : '';
        $url .= isset($parsedUrl['port'])   ? ':' . $parsedUrl['port']     : '';
        $user = isset($parsedUrl['user'])   ? $parsedUrl['user']           : '';
        $pass = isset($parsedUrl['pass'])   ? ':' . $parsedUrl['pass']     : '';

        $url .= $user . (($user || $pass) ? "$pass@" : '');

        $url = ! empty($url)
            ? $url . (isset($parsedUrl['path']) ? '/' . ltrim($parsedUrl['path'], '/') : '')
            : $url . (isset($parsedUrl['path']) ? $parsedUrl['path']                   : '');

        $url .= isset($parsedUrl['query'])    ? '?' . $parsedUrl['query']    : '';
        $url .= isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return $url;
    }
}

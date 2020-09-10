<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Contracts;

/**
 * Interface  Url
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Url
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
    public static function extractAttributes($url = false);

    /**
     * Change uri attributes (wildcards) for the ones in the $attributes array.
     *
     * @param  array   $attributes
     * @param  string  $uri
     *
     * @return string
     */
    public static function substituteAttributes(array $attributes, $uri);

    /**
     * Build URL using array data from parse_url.
     *
     * @param  array|false  $parsed
     *
     * @return string
     */
    public static function unparse($parsed);
}

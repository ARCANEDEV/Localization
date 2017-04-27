<?php namespace Arcanedev\Localization\Contracts;

/**
 * Interface  RouteBindable
 *
 * @package   Arcanedev\Localization\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RouteBindable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the wildcard value from the class.
     *
     * @return int|string
     */
    public function getWildcardValue();
}

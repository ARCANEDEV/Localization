<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Contracts;

/**
 * Interface  RouteBindable
 *
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

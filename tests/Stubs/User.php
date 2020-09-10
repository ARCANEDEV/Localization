<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Stubs;

use Arcanedev\Localization\Contracts\RouteBindable;

/**
 * Class     User
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class User implements RouteBindable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * User's username.
     *
     * @var string
     */
    public $username;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * User constructor.
     *
     * @param  string  $username
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the wildcard value from the class.
     *
     * @return int|string
     */
    public function getWildcardValue()
    {
        return $this->username;
    }
}

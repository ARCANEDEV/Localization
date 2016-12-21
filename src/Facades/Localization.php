<?php namespace Arcanedev\Localization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     Localization
 *
 * @package  Arcanedev\Localization\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Localization extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Arcanedev\Localization\Contracts\Localization::class;
    }
}

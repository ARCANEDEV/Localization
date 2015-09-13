<?php namespace Arcanedev\Localization\Bases;

use Arcanedev\Localization\Localization;
use Arcanedev\Support\Bases\Middleware as BaseMiddleware;

/**
 * Class     Middleware
 *
 * @package  Arcanedev\Localization\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Middleware extends BaseMiddleware
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Localization */
    protected $localization;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->localization = app('arcanedev.localization');
    }
}

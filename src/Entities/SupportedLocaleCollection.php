<?php namespace Arcanedev\Localization\Entities;

use Illuminate\Support\Collection;

/**
 * Class     SupportedLocaleCollection
 *
 * @package  Arcanedev\Localization\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SupportedLocaleCollection extends Collection
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Transform the collection with only locale's native name.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toNative()
    {
        return $this->map(function (Locale $locale) {
            return $locale->native();
        })->toBase();
    }
}

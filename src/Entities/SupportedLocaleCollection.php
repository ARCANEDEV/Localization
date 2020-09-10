<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Entities;

use Illuminate\Support\Collection;

/**
 * Class     SupportedLocaleCollection
 *
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

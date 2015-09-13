<?php namespace Arcanedev\Localization\Entities;

use Arcanedev\Support\Collection;

/**
 * Class     LocaleCollection
 *
 * @package  Arcanedev\Localization\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollection extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Locale collection.
     *
     * @var array
     */
    protected $items     = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Function
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load all locales.
     *
     * @return self
     */
    public function loadAll()
    {
        foreach (config('localization.locales', []) as $key => $localeData) {
            $this->put($key, new Locale($key, $localeData));
        }

        return $this;
    }

    /**
     * Get all supported locales.
     *
     * @return self
     */
    public function loadSupported()
    {
        $this->items = [];
        $supported   = config('localization.supported-locales', []);

        $this->items = $this->loadAll()->filter(function(Locale $locale) use ($supported) {
            return in_array($locale->key(), $supported);
        })->toArray();

        return $this;
    }
}

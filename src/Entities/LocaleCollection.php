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
     * Get the first locale from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed          $default
     *
     * @return Locale|mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return parent::first($callback, $default);
    }

    /**
     * Load all locales.
     *
     * @todo:   Clean this method
     *
     * @return self
     */
    public function loadAll()
    {
        $this->loadFromArray(config('localization.locales', []));

        return $this;
    }

    /**
     * Get all supported locales.
     *
     * @todo:   Clean this method
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

    /**
     * Load locales from array.
     *
     * @param  array  $locales
     *
     * @return self
     */
    public function loadFromArray(array $locales)
    {
        foreach ($locales as $key => $locale) {
            $this->put($key, new Locale($key, $locale));
        }

        return $this;
    }
}

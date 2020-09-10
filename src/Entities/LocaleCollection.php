<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Entities;

use Illuminate\Support\Collection;

/**
 * Class     LocaleCollection
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollection extends Collection
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Supported locales.
     *
     * @var array
     */
    protected $supported = [];

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set supported locales keys.
     *
     * @param  array  $supported
     *
     * @return $this
     */
    public function setSupportedKeys(array $supported)
    {
        $this->supported = $supported;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the first locale from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed          $default
     *
     * @return \Arcanedev\Localization\Entities\Locale|mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return parent::first($callback, $default);
    }

    /**
     * Get supported locales collection.
     *
     * @return \Arcanedev\Localization\Entities\SupportedLocaleCollection
     */
    public function getSupported()
    {
        return new SupportedLocaleCollection(
            $this->filter(function(Locale $locale) {
                return in_array($locale->key(), $this->supported);
            })
        );
    }

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

    /**
     * Load from config.
     *
     * @return $this
     */
    public function loadFromConfig()
    {
        return $this->loadFromArray(config('localization.locales', []))
                    ->setSupportedKeys(config('localization.supported-locales', []));
    }

    /**
     * Load locales from array.
     *
     * @param  array  $locales
     *
     * @return $this
     */
    public function loadFromArray(array $locales)
    {
        $this->items = [];

        foreach ($locales as $key => $locale) {
            $this->put($key, Locale::make($key, $locale));
        }

        return $this;
    }
}

<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Illuminate\Config\Repository as Config;

/**
 * Class     LocalesManager
 *
 * @package  Arcanedev\Localization
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalesManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Current locale.
     *
     * @var string
     */
    protected $currentLocale;

    /**
     * @var \Arcanedev\Localization\Entities\LocaleCollection
     */
    protected $locales;

    /**
     * @var \Arcanedev\Localization\Entities\LocaleCollection
     */
    protected $supportedLocales;

    /**
     * The config repository.
     *
     * @var Config
     */
    private $config;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create LocaleManager instance.
     *
     * @param  Config  $config
     */
    public function __construct(Config $config)
    {
        $this->config           = $config;
        $this->locales          = new LocaleCollection;
        $this->supportedLocales = new LocaleCollection;

        $this->load();
    }

    /**
     * Load all locales data.
     *
     * @throws UndefinedSupportedLocalesException
     */
    private function load()
    {
        $this->setDefaultLocale($this->config->get('app.locale'));
        $this->locales->loadFromArray($this->getConfig('locales'));
        $this->setSupportedLocales($this->getConfig('supported-locales'));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the default locale.
     *
     * @param  string  $defaultLocale
     *
     * @return self
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;

        return $this;
    }

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Set the current locale.
     *
     * @param  string  $currentLocale
     *
     * @return self
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;

        return $this;
    }

    /**
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales()
    {
        return $this->locales;
    }

    /**
     * Get supported locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupportedLocales()
    {
        return $this->supportedLocales;
    }

    /**
     * Get supported locales keys.
     *
     * @return array
     */
    public function getSupportedLocalesKeys()
    {
        $supportedLocales = $this->getSupportedLocales();

        return $supportedLocales->keys()->toArray();
    }

    /**
     * Set supported locales.
     *
     * @param  array  $supportedLocales
     *
     * @return self
     *
     * @throws UndefinedSupportedLocalesException
     */
    public function setSupportedLocales(array $supportedLocales)
    {
        if ( ! is_array($supportedLocales) || empty($supportedLocales)) {
            throw new UndefinedSupportedLocalesException;
        }

        $this->supportedLocales = $this->filterLocales($supportedLocales);

        return $this;
    }

    /**
     * Get localization config.
     *
     * @param  string  $name
     * @param  mixed   $default
     *
     * @return mixed
     */
    private function getConfig($name, $default = null)
    {
        return $this->config->get("localization.$name", $default);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Filter locale collection.
     *
     * @param  array  $supportedLocales
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    private function filterLocales(array $supportedLocales)
    {
        return $this->locales->filter(function(Locale $locale) use ($supportedLocales) {
            return in_array($locale->key(), $supportedLocales);
        });
    }
}

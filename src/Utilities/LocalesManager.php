<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\LocalesManagerInterface;
use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;

/**
 * Class     LocalesManager
 *
 * @package  Arcanedev\Localization
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalesManager implements LocalesManagerInterface
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
     * The application instance.
     *
     * @var Application
     */
    private $app;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create LocaleManager instance.
     *
     * @param  Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app              = $app;
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
        $this->locales->loadFromArray($this->getConfig('locales'));
        $this->setSupportedLocales($this->getConfig('supported-locales'));
        $this->setDefaultLocale();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Set the default locale.
     *
     * @param  string  $defaultLocale
     *
     * @return self
     */
    public function setDefaultLocale($defaultLocale = null)
    {
        if (is_null($defaultLocale)) {
            $defaultLocale = $this->config()->get('app.locale');
        }

        $this->isDefaultLocaleSupported($defaultLocale);
        $this->defaultLocale = $defaultLocale;

        return $this;
    }

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        if ( ! is_null($this->currentLocale)) {
            return $this->currentLocale;
        }

        if ($this->useAcceptLanguageHeader()) {
            return $this->negotiateLocale();
        }

        // Get application default language
        return $this->getDefaultLocale();
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
     * Get the current locale entity.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity()
    {
        return $this->getSupportedLocales()->get($this->getCurrentLocale());
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
     * Get config repository.
     *
     * @return Config
     */
    private function config()
    {
        return $this->app['config'];
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
        return $this->config()->get("localization.$name", $default);
    }

    /**
     * Get negotiated locale.
     *
     * @return string
     */
    private function negotiateLocale()
    {
        $negotiator = new Negotiator($this->getDefaultLocale(), $this->getSupportedLocales());

        return $negotiator->negotiate($this->app['request']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if default is supported.
     *
     * @throws UnsupportedLocaleException
     */
    public function isDefaultLocaleSupported($defaultLocale)
    {
        if ( ! $this->isSupportedLocale($defaultLocale)) {
            throw new UnsupportedLocaleException(
                "Laravel default locale [{$defaultLocale}] is not in the `supported-locales` array."
            );
        }
    }

    /**
     * Check if locale is supported.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    public function isSupportedLocale($locale)
    {
        return $this->getSupportedLocales()->has($locale);
    }

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInURL()
    {
        return (bool) $this->getConfig('hide-default-in-url', false);
    }

    /**
     * Returns the translation key for a given path.
     *
     * @return bool
     */
    private function useAcceptLanguageHeader()
    {
        return (bool) $this->getConfig('accept-language-header', true);
    }

    /**
     * Get current or default locale.
     *
     * @return string
     */
    public function getCurrentOrDefaultLocale()
    {
        // If we reached this point and isDefaultLocaleHiddenInURL is true we have to assume we are routing
        // to a default locale route.
        if ($this->isDefaultLocaleHiddenInURL()) {
            $this->setCurrentLocale($this->getDefaultLocale());
        }

        // But if isDefaultLocaleHiddenInURL is false, we have to retrieve it from the browser...
        return $this->getCurrentLocale();
    }

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

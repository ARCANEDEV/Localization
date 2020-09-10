<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\LocalesManager as LocalesManagerContract;
use Arcanedev\Localization\Entities\{Locale, LocaleCollection};
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * Class     LocalesManager
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalesManager implements LocalesManagerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
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
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * LocalesManager constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app              = $app;
        $this->locales          = new LocaleCollection;
        $this->supportedLocales = new LocaleCollection;

        $this->load();
    }

    /**
     * Load all locales data.
     */
    private function load(): void
    {
        $this->locales->loadFromArray($this->getConfig('locales'));
        $this->setSupportedLocales($this->getConfig('supported-locales'));
        $this->setDefaultLocale();
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set and return current locale.
     *
     * @param  string|null  $locale
     *
     * @return string
     */
    public function setLocale($locale = null)
    {
        if (empty($locale) || ! is_string($locale)) {
            // If the locale has not been passed through the function
            // it tries to get it from the first segment of the url
            $locale = $this->request()->segment(1);
        }

        if ($this->isSupportedLocale($locale)) {
            $this->setCurrentLocale($locale);
        }
        else {
            // if the first segment/locale passed is not valid the system would ask which locale have to take
            // it could be taken by the browser depending on your configuration
            $locale = null;

            $this->getCurrentOrDefaultLocale();
        }

        $this->app->setLocale($this->getCurrentLocale());
        $this->updateRegional();

        return $locale;
    }

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
     * @return $this
     */
    public function setDefaultLocale($defaultLocale = null)
    {
        if (is_null($defaultLocale))
            $defaultLocale = $this->config()->get('app.locale');

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
        if ( ! is_null($this->currentLocale))
            return $this->currentLocale;

        if ($this->useAcceptLanguageHeader())
            return $this->negotiateLocale();

        // Get application default language
        return $this->getDefaultLocale();
    }

    /**
     * Set the current locale.
     *
     * @param  string  $currentLocale
     *
     * @return $this
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
        return $this->getSupportedLocales()->keys()->toArray();
    }

    /**
     * Set supported locales.
     *
     * @param  array  $supportedLocales
     *
     * @return $this
     *
     * @throws \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     */
    public function setSupportedLocales(array $supportedLocales)
    {
        if ( ! is_array($supportedLocales) || empty($supportedLocales))
            throw new UndefinedSupportedLocalesException;

        $this->supportedLocales = $this->filterLocales($supportedLocales);

        return $this;
    }

    /**
     * Get config repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    private function config()
    {
        return $this->app['config'];
    }

    /**
     * Get config repository.
     *
     * @return \Illuminate\Http\Request
     */
    private function request()
    {
        return $this->app['request'];
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
        return Negotiator::make($this->getDefaultLocale(), $this->getSupportedLocales())
            ->negotiate($this->request());
    }

    /* -----------------------------------------------------------------
     |  Methods Functions
     | -----------------------------------------------------------------
     */

    /**
     * Check if default is supported.
     *
     * @param  string  $defaultLocale
     *
     * @throws \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     */
    public function isDefaultLocaleSupported($defaultLocale)
    {
        if ( ! $this->isSupportedLocale($defaultLocale))
            throw new UnsupportedLocaleException(
                "Laravel default locale [{$defaultLocale}] is not in the `supported-locales` array."
            );
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
    public function isDefaultLocaleHiddenInUrl()
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
        // If we reached this point and isDefaultLocaleHiddenInUrl is true we have to assume we are routing
        // to a default locale route.
        if ($this->isDefaultLocaleHiddenInUrl())
            $this->setCurrentLocale($this->getDefaultLocale());

        // But if isDefaultLocaleHiddenInUrl is false, we have to retrieve it from the browser...
        return $this->getCurrentLocale();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Filter locale collection.
     *
     * @param  array  $supportedLocales
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    private function filterLocales(array $supportedLocales): LocaleCollection
    {
        return $this->locales->filter(function(Locale $locale) use ($supportedLocales) {
            return in_array($locale->key(), $supportedLocales);
        });
    }

    /**
     * Update locale regional.
     */
    private function updateRegional(): void
    {
        $currentLocale = $this->getCurrentLocaleEntity();

        if ( ! empty($regional = $currentLocale->regional())) {
            $suffix = $this->getConfig('utf-8-suffix', '.UTF-8');

            setlocale(LC_TIME, $regional.$suffix);
            setlocale(LC_MONETARY, $regional.$suffix);
        }
    }
}

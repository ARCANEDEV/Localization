<?php namespace Arcanedev\Localization;

use Arcanedev\Localization\Contracts\LocalizationInterface;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class     Localization
 *
 * @package  Arcanedev\Localization
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Localization implements LocalizationInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Laravel application instance.
     *
     * @var Application
     */
    private $app;

    /**
     * Default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Supported Locales.
     *
     * @var Entities\LocaleCollection
     */
    protected $supportedLocales;

    /**
     * Supported Locales.
     *
     * @var Entities\LocaleCollection
     */
    protected $locales;

    /**
     * Current locale.
     *
     * @var string|false
     */
    protected $currentLocale = false;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Creates new instance.
     *
     * @param  Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->supportedLocales = new LocaleCollection;
        $this->locales          = new LocaleCollection;

        $this->defaultLocale    = $this->config()->get('app.locale');

        $this->getSupportedLocales();
        $this->isDefaultLocaleSupported();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Config instance.
     *
     * @return \Illuminate\Config\Repository
     */
    private function config()
    {
        return $this->app['config'];
    }

    /**
     * Return an array of all supported Locales.
     *
     * @throws UndefinedSupportedLocalesException
     *
     * @return LocaleCollection
     */
    public function getSupportedLocales()
    {
        if ( ! $this->supportedLocales->isEmpty()) {
            return $this->supportedLocales;
        }

        $locales = $this->config()->get('localization.supported-locales');

        if ( ! is_array($locales) || empty($locales)) {
            throw new UndefinedSupportedLocalesException;
        }

        return $this->supportedLocales->loadSupported();
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
    private function isDefaultLocaleSupported()
    {
        if ($this->supportedLocales->has($this->defaultLocale)) {
            return;
        }

        throw new UnsupportedLocaleException(
            'Laravel default locale [' . $this->defaultLocale . '] is not in the `supported-locales` array.'
        );
    }
}

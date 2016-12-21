<?php namespace Arcanedev\Localization\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\Localization\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string  */
    protected $defaultLocale    = 'en';

    /** @var array */
    protected $supportedLocales = ['en', 'es', 'fr'];

    /** @var string  */
    protected $testUrlOne       = 'http://localhost/';

    /** @var string  */
    protected $testUrlTwo       = 'http://localhost';

    /** @var Stubs\Http\RouteRegistrar */
    protected $routeRegistrar;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\Localization\LocalizationServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Localization' => \Arcanedev\Localization\Facades\Localization::class,
        ];
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(\Illuminate\Contracts\Http\Kernel::class, Stubs\Http\Kernel::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /**
         * @var  \Illuminate\Contracts\Config\Repository         $config
         * @var  \Illuminate\Translation\Translator              $translator
         * @var  \Arcanedev\Localization\Contracts\Localization  $localization
         */
        $config       = $app['config'];
        $translator   = $app['translator'];
        $localization = $app['arcanedev.localization'];

        $config->set('app.url',    $this->testUrlOne);
        $config->set('app.locale', $this->defaultLocale);
        $config->set('localization.route.middleware', [
            'localization-session-redirect' => false,
            'localization-cookie-redirect'  => false,
            'localization-redirect'         => true,
            'localized-routes'              => true,
            'translation-redirect'          => true,
        ]);

        $translator->getLoader()->addNamespace(
            'localization',
            realpath(__DIR__) . DS . 'fixtures'. DS .'lang'
        );

        $translator->load('localization', 'routes', 'en');
        $translator->load('localization', 'routes', 'es');
        $translator->load('localization', 'routes', 'fr');

        $localization->setBaseUrl($this->testUrlOne);

        $this->setRoutes();
    }

    /**
     * Refresh routes and refresh application
     *
     * @param  bool|string  $locale
     * @param  bool         $session
     * @param  bool         $cookie
     */
    protected function refreshApplication($locale = false, $session = false, $cookie = false)
    {
        parent::refreshApplication();

        app('config')->set('localization.route.middleware', [
            'localization-session-redirect' => $session,
            'localization-cookie-redirect'  => $cookie,
            'localization-redirect'         => true,
            'localized-routes'              => true,
            'translation-redirect'          => true,
        ]);

        $this->setRoutes($locale);
    }

    /**
     * Set routes for testing
     *
     * @param  string|bool  $locale
     */
    protected function setRoutes($locale = false)
    {
        $this->routeRegistrar = new Stubs\Http\RouteRegistrar;

        if ($locale) {
            localization()->setLocale($locale);
        }

        $this->routeRegistrar->map(app('router'));
    }
}

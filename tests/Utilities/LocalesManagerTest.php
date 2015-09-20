<?php namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\LocalesManager;

/**
 * Class     LocalesManagerTest
 *
 * @package  Arcanedev\Localization\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalesManagerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var LocalesManager */
    private $localesManager;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->localesManager = app('arcanedev.localization.locales-manager');

        $this->localesManager->setCurrentLocale('en');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->localesManager);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(LocalesManager::class, $this->localesManager);
    }

    /** @test */
    public function it_can_set_and_get_current_locale()
    {
        foreach ($this->supportedLocales as $locale) {
            $this->localesManager->setCurrentLocale($locale);

            $this->assertEquals($locale, $this->localesManager->getCurrentLocale());
        }
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $locales = $this->localesManager->getAllLocales();

        $this->assertInstanceOf(
            \Arcanedev\Localization\Entities\LocaleCollection::class, $locales
        );
        $this->assertFalse($locales->isEmpty());
        $this->assertCount(289, $locales);
        $this->assertEquals(289, $locales->count());
    }

    /** @test */
    public function it_can_get_supported_locales()
    {
        $supportedLocales = $this->localesManager->getSupportedLocales();

        $this->assertInstanceOf(
            \Arcanedev\Localization\Entities\LocaleCollection::class, $supportedLocales
        );
        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(count($this->supportedLocales), $supportedLocales);
        $this->assertEquals(count($this->supportedLocales), $supportedLocales->count());
    }

    /** @test */
    public function it_can_set_and_get_supported_locales()
    {
        $supported = ['en', 'fr'];

        $this->localesManager->setSupportedLocales($supported);

        $supportedLocales = $this->localesManager->getSupportedLocales();

        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(2, $supportedLocales);
        $this->assertEquals(2, $supportedLocales->count());

        foreach ($supported as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }
    }

    /** @test */
    public function it_can_get_supported_locales_keys()
    {
        $supportedKeys = $this->localesManager->getSupportedLocalesKeys();

        $this->assertCount(count($this->supportedLocales), $supportedKeys);
        $this->assertEquals($this->supportedLocales, $supportedKeys);
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     */
    public function it_must_throw_undefined_supported_locales_exception_on_set_with_empty_array()
    {
        $this->localesManager->setSupportedLocales([]);
    }

    /** @test */
    public function it_can_get_current_locale_without_negotiator()
    {
        $this->app['config']->set('localization.accept-language-header', false);

        foreach ($this->supportedLocales as $locale) {
            $this->app['config']->set('app.locale', $locale);

            $this->localesManager = new LocalesManager($this->app);

            $this->assertEquals($locale, $this->localesManager->getCurrentLocale());
        }
    }

    /** @test */
    public function it_can_get_default_or_current_locale()
    {
        $this->app['config']->set('localization.hide-default-in-url', false);

        $this->localesManager = new LocalesManager($this->app);
        $this->localesManager->setCurrentLocale('fr');

        $this->assertEquals('en', $this->localesManager->getDefaultLocale());
        $this->assertEquals('fr', $this->localesManager->getCurrentLocale());
        $this->assertEquals('fr', $this->localesManager->getCurrentOrDefaultLocale());

        $this->app['config']->set('localization.hide-default-in-url', true);

        $this->localesManager = new LocalesManager($this->app);
        $this->localesManager->setCurrentLocale('fr');

        $this->assertEquals('en', $this->localesManager->getDefaultLocale());
        $this->assertEquals('fr', $this->localesManager->getCurrentLocale());
        $this->assertEquals('en', $this->localesManager->getCurrentOrDefaultLocale());
    }

    /** @test */
    public function it_can_set_and_get_default_locale()
    {
        foreach ($this->supportedLocales as $locale) {
            $this->localesManager->setDefaultLocale($locale);

            $this->assertEquals($locale, $this->localesManager->getDefaultLocale());
        }
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     * @expectedExceptionMessage Laravel default locale [jp] is not in the `supported-locales` array.
     */
    public function it_must_throw_unsupported_locale_exception_on_set_default_locale()
    {
        $this->localesManager->setDefaultLocale('jp');
    }
}

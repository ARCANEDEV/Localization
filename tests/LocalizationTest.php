<?php namespace Arcanedev\Localization\Tests;

use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Localization;

/**
 * Class     LocalizationTest
 *
 * @package  Arcanedev\Localization\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Localization */
    private $localization;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->localization = $this->app['arcanedev.localization'];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->localization);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Localization::class, $this->localization);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     * @expectedExceptionMessage  Laravel default locale [jp] is not in the `supported-locales` array.
     */
    public function it_must_throw_unsupported_locale_exception_on_default_locale()
    {
        $this->app['config']->set('app.locale', 'jp');
        $this->app['arcanedev.localization'];
    }

    /**
     * @test
     * @expectedException         \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     * @expectedExceptionMessage  Supported locales must be defined.
     */
    public function it_must_throw_undefined_supported_locales_exception()
    {
        $this->app['config']->set('localization.supported-locales', []);
        $this->app['arcanedev.localization'];
    }

    /** @test */
    public function it_can_get_supported_locales()
    {
        $supportedLocales = $this->localization->getSupportedLocales();

        $this->assertInstanceOf(LocaleCollection::class, $supportedLocales);
        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(count(self::$supportedLocales), $supportedLocales);

        foreach(self::$supportedLocales as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }
    }
}

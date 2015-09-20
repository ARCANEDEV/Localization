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
        $this->assertEquals('en', $this->localesManager->getCurrentLocale());

        $this->localesManager->setCurrentLocale('fr');

        $this->assertEquals('fr', $this->localesManager->getCurrentLocale());
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
        $this->assertCount(3, $supportedLocales);
        $this->assertEquals(3, $supportedLocales->count());
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

        $this->assertCount(3, $supportedKeys);
        $this->assertEquals(['en', 'es', 'fr'], $supportedKeys);
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
}

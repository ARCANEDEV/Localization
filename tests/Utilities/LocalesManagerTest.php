<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Entities\Locale;
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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Contracts\LocalesManager */
    private $localesManager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->localesManager = app(\Arcanedev\Localization\Contracts\LocalesManager::class);

        $this->localesManager->setCurrentLocale('en');
    }

    public function tearDown(): void
    {
        unset($this->localesManager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        static::assertInstanceOf(LocalesManager::class, $this->localesManager);
    }

    /** @test */
    public function it_can_set_and_get_current_locale(): void
    {
        foreach ($this->supportedLocales as $locale) {
            $this->localesManager->setCurrentLocale($locale);

            static::assertSame($locale, $this->localesManager->getCurrentLocale());
        }
    }

    /** @test */
    public function it_can_get_current_locale_entity(): void
    {
        foreach ($this->supportedLocales as $locale) {
            $this->localesManager->setCurrentLocale($locale);

            $localeEntity = $this->localesManager->getCurrentLocaleEntity();

            static::assertInstanceOf(Locale::class, $localeEntity);
            static::assertSame($locale, $localeEntity->key());
        }
    }

    /** @test */
    public function it_can_get_all_locales(): void
    {
        $locales = $this->localesManager->getAllLocales();

        static::assertInstanceOf(
            \Arcanedev\Localization\Entities\LocaleCollection::class, $locales
        );
        static::assertFalse($locales->isEmpty());
        static::assertCount(289, $locales);
        static::assertSame(289, $locales->count());
    }

    /** @test */
    public function it_can_get_supported_locales(): void
    {
        $supportedLocales = $this->localesManager->getSupportedLocales();

        static::assertInstanceOf(
            \Arcanedev\Localization\Entities\LocaleCollection::class, $supportedLocales
        );
        static::assertFalse($supportedLocales->isEmpty());
        static::assertCount(count($this->supportedLocales), $supportedLocales);
        static::assertSame(count($this->supportedLocales), $supportedLocales->count());
    }

    /** @test */
    public function it_can_set_and_get_supported_locales(): void
    {
        $supported = ['en', 'fr'];

        $this->localesManager->setSupportedLocales($supported);

        $supportedLocales = $this->localesManager->getSupportedLocales();

        static::assertFalse($supportedLocales->isEmpty());
        static::assertCount(2, $supportedLocales);
        static::assertSame(2, $supportedLocales->count());

        foreach ($supported as $locale) {
            static::assertTrue($supportedLocales->has($locale));
        }
    }

    /** @test */
    public function it_can_get_supported_locales_keys(): void
    {
        $supportedKeys = $this->localesManager->getSupportedLocalesKeys();

        static::assertCount(count($this->supportedLocales), $supportedKeys);
        static::assertSame($this->supportedLocales, $supportedKeys);
    }

    /** @test */
    public function it_can_get_current_locale_without_negotiator(): void
    {
        $this->app['config']->set('localization.accept-language-header', false);

        foreach ($this->supportedLocales as $locale) {
            $this->app['config']->set('app.locale', $locale);

            $this->localesManager = new LocalesManager($this->app);

            static::assertSame($locale, $this->localesManager->getCurrentLocale());
        }
    }

    /** @test */
    public function it_can_get_default_or_current_locale(): void
    {
        $this->app['config']->set('localization.hide-default-in-url', false);

        $this->localesManager = new LocalesManager($this->app);
        $this->localesManager->setCurrentLocale('fr');

        static::assertSame('en', $this->localesManager->getDefaultLocale());
        static::assertSame('fr', $this->localesManager->getCurrentLocale());
        static::assertSame('fr', $this->localesManager->getCurrentOrDefaultLocale());

        $this->app['config']->set('localization.hide-default-in-url', true);

        $this->localesManager = new LocalesManager($this->app);
        $this->localesManager->setCurrentLocale('fr');

        static::assertSame('en', $this->localesManager->getDefaultLocale());
        static::assertSame('fr', $this->localesManager->getCurrentLocale());
        static::assertSame('en', $this->localesManager->getCurrentOrDefaultLocale());
    }

    /** @test */
    public function it_can_set_and_get_default_locale(): void
    {
        foreach ($this->supportedLocales as $locale) {
            $this->localesManager->setDefaultLocale($locale);

            static::assertSame($locale, $this->localesManager->getDefaultLocale());
        }
    }

    /** @test */
    public function it_must_throw_unsupported_locale_exception_on_set_default_locale(): void
    {
        $this->expectException(\Arcanedev\Localization\Exceptions\UnsupportedLocaleException::class);
        $this->expectExceptionMessage("Laravel default locale [jp] is not in the `supported-locales` array.");

        $this->localesManager->setDefaultLocale('jp');
    }

    /** @test */
    public function it_must_throw_undefined_supported_locales_exception_on_set_with_empty_array(): void
    {
        $this->expectException(\Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException::class);

        $this->localesManager->setSupportedLocales([]);
    }
}

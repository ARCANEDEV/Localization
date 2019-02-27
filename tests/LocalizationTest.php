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
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        static::assertInstanceOf(Localization::class, localization());
    }

    /** @test */
    public function it_must_throw_unsupported_locale_exception_on_default_locale()
    {
        $this->expectException(\Arcanedev\Localization\Exceptions\UnsupportedLocaleException::class);
        $this->expectExceptionMessage('Laravel default locale [jp] is not in the `supported-locales` array.');

        app('config')->set('app.locale', 'jp');

        new Localization(
            $this->app,
            $this->app[\Arcanedev\Localization\Contracts\RouteTranslator::class],
            $this->app[\Arcanedev\Localization\Contracts\LocalesManager::class]
        );
    }

    /** @test */
    public function it_can_set_and_get_supported_locales()
    {
        $supportedLocales = localization()->getSupportedLocales();

        static::assertInstanceOf(LocaleCollection::class, $supportedLocales);
        static::assertFalse($supportedLocales->isEmpty());
        static::assertCount(count($this->supportedLocales), $supportedLocales);

        foreach($this->supportedLocales as $locale) {
            static::assertTrue($supportedLocales->has($locale));
        }

        $locales = ['en', 'fr'];

        localization()->setSupportedLocales($locales);
        $supportedLocales = localization()->getSupportedLocales();

        static::assertInstanceOf(LocaleCollection::class, $supportedLocales);
        static::assertFalse($supportedLocales->isEmpty());
        static::assertCount(count($locales), $supportedLocales);

        foreach($locales as $locale) {
            static::assertTrue($supportedLocales->has($locale));
        }
    }

    /** @test */
    public function it_must_throw_undefined_supported_locales_exception_on_set_supported_locales_with_empty_array()
    {
        $this->expectException(\Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException::class);

        localization()->setSupportedLocales([]);
    }

    /** @test */
    public function it_can_get_supported_locales_keys()
    {
        static::assertSame(
            $this->supportedLocales,
            localization()->getSupportedLocalesKeys()
        );
    }

    /** @test */
    public function it_can_set_locale()
    {
        static::assertSame(route('about'), 'http://localhost/about');

        $this->refreshApplication('es');

        static::assertSame('es', localization()->setLocale('es'));
        static::assertSame('es', localization()->getCurrentLocale());
        static::assertSame(route('about'), 'http://localhost/acerca');

        $this->refreshApplication();

        static::assertSame('en', localization()->setLocale('en'));
        static::assertSame(route('about'), 'http://localhost/about');

        static::assertNull(localization()->setLocale('de'));
        static::assertSame('en', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_get_default_locale()
    {
        static::assertSame('en', localization()->getDefaultLocale());

        localization()->setLocale('es');
        $this->refreshApplication('es');

        static::assertSame('en', localization()->getDefaultLocale());
    }

    /** @test */
    public function it_can_get_current_locale()
    {
        static::assertSame('en', localization()->getCurrentLocale());
        static::assertNotEquals('es', localization()->getCurrentLocale());
        static::assertNotEquals('fr', localization()->getCurrentLocale());

        localization()->setLocale('es');
        $this->refreshApplication('es');

        static::assertNotEquals('en', localization()->getCurrentLocale());
        static::assertSame('es', localization()->getCurrentLocale());
        static::assertNotEquals('fr', localization()->getCurrentLocale());

        localization()->setLocale('fr');
        $this->refreshApplication('fr');

        static::assertNotEquals('en', localization()->getCurrentLocale());
        static::assertNotEquals('es', localization()->getCurrentLocale());
        static::assertSame('fr', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_localize_url()
    {
        static::assertSame(
            $this->testUrlOne.localization()->getCurrentLocale(),
            localization()->localizeURL()
        );

        // Missing trailing slash in a URL
        static::assertSame(
            $this->testUrlTwo.'/'.localization()->getCurrentLocale(),
            localization()->localizeURL()
        );

        app('config')->set('localization.hide-default-in-url', true);

        // testing hide default locale option
        static::assertNotEquals(
            $this->testUrlOne.localization()->getDefaultLocale(),
            localization()->localizeURL()
        );
        static::assertSame(
            $this->testUrlOne,
            localization()->localizeURL()
        );

        localization()->setLocale('es');

        static::assertSame(
            $this->testUrlOne.'es',
            localization()->localizeURL()
        );
        static::assertSame(
            $this->testUrlOne.'about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );
        static::assertNotEquals(
            $this->testUrlOne.'en/about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );

        app('config')->set('localization.hide-default-in-url', false);

        static::assertSame(
            $this->testUrlOne.'en/about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );

        static::assertNotEquals(
            $this->testUrlOne.'about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );
    }

    /** @test */
    public function it_can_get_localized_url()
    {
        static::assertSame(
            $this->testUrlOne.localization()->getCurrentLocale(),
            localization()->getLocalizedURL()
        );

        app('config')->set('localization.hide-default-in-url', true);

        // testing default language hidden

        static::assertNotEquals(
            $this->testUrlOne.localization()->getDefaultLocale(),
            localization()->getLocalizedURL()
        );

        localization()->setLocale('es');

        static::assertNotEquals(
            $this->testUrlOne,
            localization()->getLocalizedURL()
        );
        static::assertNotEquals(
            $this->testUrlOne.localization()->getDefaultLocale(),
            localization()->getLocalizedURL()
        );
        static::assertSame(
            $this->testUrlOne.localization()->getCurrentLocale(),
            localization()->getLocalizedURL()
        );
        static::assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getLocalizedURL('es', $this->testUrlOne.'about')
        );

        localization()->setLocale('en');
        $response = $this->makeCall(
            $this->testUrlOne.'about',
            ['HTTP_ACCEPT_LANGUAGE' => 'en,es']
        );

        $response->isOk();
        static::assertSame($this->testUrlOne.'es/acerca', $response->getContent());

        $this->refreshApplication();
        app('config')->set('localization.hide-default-in-url', true);

        static::assertSame(
            $this->testUrlOne.'test',
            localization()->getLocalizedURL('en', $this->testUrlOne.'test')
        );

        $response = $this->makeCall(
            localization()->getLocalizedURL('en', $this->testUrlOne.'test'),
            ['HTTP_ACCEPT_LANGUAGE' => 'en,es']
        );

        $response->isOk();
        static::assertSame('Test text', $response->getContent());

        $this->refreshApplication('es');

        static::assertSame(
            $this->testUrlOne.'es/test',
            localization()->getLocalizedURL('es', $this->testUrlOne.'test')
        );
    }

    /**
     * @test
     *
     * @param  bool    $hideDefault
     * @param  bool    $forceDefault
     * @param  string  $locale
     * @param  string  $url
     * @param  string  $expected
     *
     * @dataProvider getLocalizedURLDataProvider
     */
    public function it_can_get_localized_url_with_specific_format($hideDefault, $forceDefault, $locale, $url, $expected)
    {
        $this->app['config']->set('localization.hide-default-in-url', $hideDefault);

        static::assertEquals(
            $expected,
            \localization()->getLocalizedURL($locale, $url, [], $forceDefault)
        );
    }

    /** @test */
    public function it_can_get_url_from_route_name_translated()
    {
        static::assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getUrlFromRouteName('es', 'localization::routes.about')
        );
        static::assertSame(
            $this->testUrlOne.'en/about',
            localization()->getUrlFromRouteName('en', 'localization::routes.about')
        );
        static::assertSame(
            $this->testUrlOne.'en/view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', [ 'id' => 1 ])
        );

        app('config')->set('localization.hide-default-in-url', true);

        static::assertSame(
            $this->testUrlOne.'about',
            localization()->getUrlFromRouteName('en', 'localization::routes.about')
        );
        static::assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getUrlFromRouteName('es', 'localization::routes.about')
        );
        static::assertSame(
            $this->testUrlOne.'es/ver/1',
            localization()->getUrlFromRouteName('es', 'localization::routes.view', ['id' => 1])
        );
        static::assertSame(
            $this->testUrlOne.'view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );
        static::assertNotEquals(
            $this->testUrlOne.'en/view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );

        app('config')->set('localization.hide-default-in-url', false);

        static::assertNotEquals(
            $this->testUrlOne.'view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );
        static::assertSame(
            $this->testUrlOne.'en/view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );
    }

    /** @test */
    public function it_must_throw_an_exception_on_unsupported_locale()
    {
        $this->expectException(\Arcanedev\Localization\Exceptions\UnsupportedLocaleException::class);
        $this->expectExceptionMessage("Locale 'jp' is not in the list of supported locales.");

        localization()->getUrlFromRouteName('jp', 'localization::routes.about');
    }

    /** @test */
    public function it_can_get_non_localized_url()
    {
        static::assertSame(
            $this->testUrlOne,
            localization()->getNonLocalizedURL($this->testUrlOne.'en')
        );
        static::assertSame(
            $this->testUrlOne,
            localization()->getNonLocalizedURL($this->testUrlOne.'es')
        );
        static::assertSame(
            $this->testUrlOne.'view/1',
            localization()->getNonLocalizedURL($this->testUrlOne.'en/view/1')
        );
        static::assertSame(
            $this->testUrlOne.'ver/1',
            localization()->getNonLocalizedURL($this->testUrlOne.'es/ver/1')
        );
    }

    /** @test */
    public function it_can_get_current_locale_name()
    {
        $locales = [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
        ];

        foreach ($locales as $locale => $name) {
            $this->refreshApplication($locale);

            static::assertSame($name, localization()->getCurrentLocaleName());
        }
    }

    /** @test */
    public function it_can_get_current_locale_script()
    {
        foreach ($this->supportedLocales as $locale) {
            localization()->setLocale($locale);
            $this->refreshApplication($locale);

            static::assertSame('Latn', localization()->getCurrentLocaleScript());
        }
    }

    /** @test */
    public function it_can_get_current_locale_direction()
    {
        foreach ($this->supportedLocales as $locale) {
            $this->refreshApplication($locale);

            static::assertSame('ltr', localization()->getCurrentLocaleDirection());
        }
    }

    /** @test */
    public function it_can_get_current_locale_native()
    {
        $locales = [
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
        ];

        foreach ($locales as $locale => $name) {
            $this->refreshApplication($locale);

            static::assertSame($name, localization()->getCurrentLocaleNative());
        }
    }

    /** @test */
    public function testGetCurrentLocaleRegional()
    {
        $locales = [
            'en' => 'en_GB',
            'es' => 'es_ES',
            'fr' => 'fr_FR',
        ];

        foreach ($locales as $locale => $regional) {
            $this->refreshApplication($locale);

            static::assertSame($regional, localization()->getCurrentLocaleRegional());
        }
    }

    /** @test */
    public function it_can_create_url_from_uri()
    {
        static::assertSame(
            'http://localhost/view/1',
            localization()->createUrlFromUri('/view/1')
        );

        localization()->setLocale('es');
        $this->refreshApplication('es');

        static::assertSame(
            'http://localhost/ver/1',
            localization()->createUrlFromUri('/ver/1')
        );
    }

    /** @test */
    public function it_can_render_locales_navigation_bar()
    {
        $navbar = localization()->localesNavbar();

        static::assertStringContainsString('<ul class="navbar-locales">', $navbar);
        static::assertStringContainsString('<li class="active">', $navbar);
        static::assertStringContainsString(e('English'),  $navbar);
        static::assertStringContainsString(e('Español'),  $navbar);
        static::assertStringContainsString(e('Français'), $navbar);
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $locales = localization()->getAllLocales();

        static::assertInstanceOf(LocaleCollection::class, $locales);
        static::assertFalse($locales->isEmpty());
        static::assertCount(289, $locales);
        static::assertSame(289, $locales->count());
    }

    /** @test */
    public function it_can_get_localized_url_with_relative_urls()
    {
        static::assertSame($this->testUrlOne.'en', localization()->LocalizeURL('/'));

        $urls  = [
            '/contact',
            '/contact/',
            $this->testUrlOne.'/contact',
            $this->testUrlOne.'/contact/'
        ];

        foreach ($urls as $url) {
            static::assertSame(
                $this->testUrlOne.'en/contact',
                localization()->LocalizeURL($url)
            );
        }
    }

    /** @test */
    public function it_can_get_localized_url_with_route_name_from_lang()
    {
        static::assertSame(
            'http://localhost/en/view/en-view-slug',
            localized_route('localization::routes.view', ['id' => 'en-view-slug'])
        );

        static::assertSame(
            'http://localhost/en/view/en-view-slug',
            localized_route('localization::routes.view', ['id' => 'en-view-slug'], 'en')
        );

        static::assertSame(
            'http://localhost/fr/voir/fr-view-slug',
            localized_route('localization::routes.view', ['id' => 'fr-view-slug'], 'fr')
        );

        static::assertSame(
            'http://localhost/es/ver/es-view-slug',
            localized_route('localization::routes.view', ['id' => 'es-view-slug'], 'es')
        );
    }

    /** @test */
    public function it_can_use_facade()
    {
        static::assertSame(
            $this->app->getLocale(),
            \Arcanedev\Localization\Facades\Localization::getDefaultLocale()
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a call.
     *
     * @param  string  $uri
     * @param  array   $server
     *
     * @return \Illuminate\Http\Response
     */
    public function makeCall($uri, array $server = [])
    {
        return $this->call('GET', $uri, [], [], [], $server);
    }

    /* -----------------------------------------------------------------
     |  Providers
     | -----------------------------------------------------------------
     */
    /**
     * Provide data for `it_can_get_localized_url_with_specific_format`.
     *
     * @return array
     */
    public function getLocalizedURLDataProvider()
    {
        $url = 'http://localhost/';

        // [$hideDefault, $forceDefault, $locale, $url, $expected]

        return [
            // Do not hide default with [es] locale
            [false, false, 'es', $url,                       $url.'es'],
            [false, false, 'es', $url.'es',                  $url.'es'],
            [false, false, 'es', $url.'en/about',            $url.'es/acerca'],
            [false, false, 'es', $url.'ver/1',               $url.'es/ver/1'],
            [false, false, 'es', $url.'view/1/project',      $url.'es/ver/1/proyecto'],
            [false, false, 'es', $url.'view/1/project/1',    $url.'es/ver/1/proyecto/1'],

            // Do not hide default with [en] locale
            [false, false, 'en', $url.'en',                  $url.'en'],
            [false, false, 'en', $url.'about',               $url.'en/about'],
            [false, false, 'en', $url.'ver/1',               $url.'en/ver/1'],
            [false, false, 'en', $url.'view/1/project',      $url.'en/view/1/project'],
            [false, false, 'en', $url.'view/1/project/1',    $url.'en/view/1/project/1'],

            // Hide default with [es] locale
            [true,  false, 'es', $url,                       $url.'es'],
            [true,  false, 'es', $url.'es',                  $url.'es'],
            [true,  false, 'es', $url.'en/about',            $url.'es/acerca'],
            [true,  false, 'es', $url.'ver/1',               $url.'es/ver/1'],
            [true,  false, 'es', $url.'view/1/project',      $url.'es/ver/1/proyecto'],
            [true,  false, 'es', $url.'view/1/project/1',    $url.'es/ver/1/proyecto/1'],

            // Hide default with [en] locale
            [true,  false, 'en', $url.'en',                  $url.''],
            [true,  false, 'en', $url.'about',               $url.'about'],
            [true,  false, 'en', $url.'ver/1',               $url.'ver/1'],
            [true,  false, 'en', $url.'view/1/project',      $url.'view/1/project'],
            [true,  false, 'en', $url.'view/1/project/1',    $url.'view/1/project/1'],

            // Do not hide default + forcing the show with [es] locale
            [false, true,  'es', $url,                       $url.'es'],
            [false, true,  'es', $url.'es',                  $url.'es'],
            [false, true,  'es', $url.'en/about',            $url.'es/acerca'],
            [false, true,  'es', $url.'ver/1',               $url.'es/ver/1'],
            [false, true,  'es', $url.'view/1/project',      $url.'es/ver/1/proyecto'],
            [false, true,  'es', $url.'view/1/project/1',    $url.'es/ver/1/proyecto/1'],

            // Do not hide default + forcing the show with [en] locale
            [false, true,  'en', $url.'en',                  $url.'en'],
            [false, true,  'en', $url.'about',               $url.'en/about'],
            [false, true,  'en', $url.'ver/1',               $url.'en/ver/1'],
            [false, true,  'en', $url.'view/1/project',      $url.'en/view/1/project'],
            [false, true,  'en', $url.'view/1/project/1',    $url.'en/view/1/project/1'],

            // Do not hide default + forcing the show with [es] locale
            [true,  true,  'es', $url,                       $url.'es'],
            [true,  true,  'es', $url.'es',                  $url.'es'],
            [true,  true,  'es', $url.'en/about',            $url.'es/acerca'],
            [true,  true,  'es', $url.'ver/1',               $url.'es/ver/1'],
            [true,  true,  'es', $url.'view/1/project',      $url.'es/ver/1/proyecto'],
            [true,  true,  'es', $url.'view/1/project/1',    $url.'es/ver/1/proyecto/1'],

            // Do not hide default + forcing the show with [en] locale
            [true,  true,  'en', $url.'en',                  $url.'en'],
            [true,  true,  'en', $url.'about',               $url.'en/about'],
            [true,  true,  'en', $url.'ver/1',               $url.'en/ver/1'],
            [true,  true,  'en', $url.'view/1/project',      $url.'en/view/1/project'],
            [true,  true,  'en', $url.'view/1/project/1',    $url.'en/view/1/project/1'],
        ];
    }
}

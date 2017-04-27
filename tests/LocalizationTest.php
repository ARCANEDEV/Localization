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
        $this->assertInstanceOf(Localization::class, localization());
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     * @expectedExceptionMessage  Laravel default locale [jp] is not in the `supported-locales` array.
     */
    public function it_must_throw_unsupported_locale_exception_on_default_locale()
    {
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

        $this->assertInstanceOf(LocaleCollection::class, $supportedLocales);
        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(count($this->supportedLocales), $supportedLocales);

        foreach($this->supportedLocales as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }

        $locales = ['en', 'fr'];

        localization()->setSupportedLocales($locales);
        $supportedLocales = localization()->getSupportedLocales();

        $this->assertInstanceOf(LocaleCollection::class, $supportedLocales);
        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(count($locales), $supportedLocales);

        foreach($locales as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     */
    public function it_must_throw_undefined_supported_locales_exception_on_set_supported_locales_with_empty_array()
    {
        localization()->setSupportedLocales([]);
    }

    /** @test */
    public function it_can_get_supported_locales_keys()
    {
        $this->assertSame(
            $this->supportedLocales,
            localization()->getSupportedLocalesKeys()
        );
    }

    /** @test */
    public function it_can_set_locale()
    {
        $this->assertSame(route('about'), 'http://localhost/about');

        $this->refreshApplication('es');

        $this->assertSame('es', localization()->setLocale('es'));
        $this->assertSame('es', localization()->getCurrentLocale());
        $this->assertSame(route('about'), 'http://localhost/acerca');

        $this->refreshApplication();

        $this->assertSame('en', localization()->setLocale('en'));
        $this->assertSame(route('about'), 'http://localhost/about');

        $this->assertNull(localization()->setLocale('de'));
        $this->assertSame('en', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_get_default_locale()
    {
        $this->assertSame('en', localization()->getDefaultLocale());

        localization()->setLocale('es');
        $this->refreshApplication('es');

        $this->assertSame('en', localization()->getDefaultLocale());
    }

    /** @test */
    public function it_can_get_current_locale()
    {
        $this->assertSame('en', localization()->getCurrentLocale());
        $this->assertNotEquals('es', localization()->getCurrentLocale());
        $this->assertNotEquals('fr', localization()->getCurrentLocale());

        localization()->setLocale('es');
        $this->refreshApplication('es');

        $this->assertNotEquals('en', localization()->getCurrentLocale());
        $this->assertSame('es', localization()->getCurrentLocale());
        $this->assertNotEquals('fr', localization()->getCurrentLocale());

        localization()->setLocale('fr');
        $this->refreshApplication('fr');

        $this->assertNotEquals('en', localization()->getCurrentLocale());
        $this->assertNotEquals('es', localization()->getCurrentLocale());
        $this->assertSame('fr', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_localize_url()
    {
        $this->assertSame(
            $this->testUrlOne.localization()->getCurrentLocale(),
            localization()->localizeURL()
        );

        // Missing trailing slash in a URL
        $this->assertSame(
            $this->testUrlTwo.'/'.localization()->getCurrentLocale(),
            localization()->localizeURL()
        );

        app('config')->set('localization.hide-default-in-url', true);

        // testing hide default locale option
        $this->assertNotEquals(
            $this->testUrlOne.localization()->getDefaultLocale(),
            localization()->localizeURL()
        );
        $this->assertSame(
            $this->testUrlOne,
            localization()->localizeURL()
        );

        localization()->setLocale('es');

        $this->assertSame(
            $this->testUrlOne.'es',
            localization()->localizeURL()
        );
        $this->assertSame(
            $this->testUrlOne.'about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );
        $this->assertNotEquals(
            $this->testUrlOne.'en/about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );

        app('config')->set('localization.hide-default-in-url', false);

        $this->assertSame(
            $this->testUrlOne.'en/about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );

        $this->assertNotEquals(
            $this->testUrlOne.'about',
            localization()->localizeURL($this->testUrlOne.'about', 'en')
        );
    }

    /** @test */
    public function it_can_get_localized_url()
    {
        $this->assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getLocalizedURL('es', $this->testUrlOne.'en/about')
        );
        $this->assertSame(
            $this->testUrlOne.'es/ver/1',
            localization()->getLocalizedURL('es', $this->testUrlOne.'view/1')
        );
        $this->assertSame(
            $this->testUrlOne.'es/ver/1/proyecto',
            localization()->getLocalizedURL('es', $this->testUrlOne.'view/1/project')
        );
        $this->assertSame(
            $this->testUrlOne.'es/ver/1/proyecto/1',
            localization()->getLocalizedURL('es', $this->testUrlOne.'view/1/project/1')
        );
        $this->assertSame(
            $this->testUrlOne.'en/about',
            localization()->getLocalizedURL('en', $this->testUrlOne.'about')
        );
        $this->assertSame(
            $this->testUrlOne.localization()->getCurrentLocale(),
            localization()->getLocalizedURL()
        );

        app('config')->set('localization.hide-default-in-url', true);

        // testing default language hidden
        $this->assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getLocalizedURL('es', $this->testUrlOne.'about')
        );
        $this->assertSame(
            $this->testUrlOne.'about',
            localization()->getLocalizedURL('en', $this->testUrlOne.'about')
        );
        $this->assertSame(
            $this->testUrlOne,
            localization()->getLocalizedURL()
        );
        $this->assertNotEquals(
            $this->testUrlOne.localization()->getDefaultLocale(),
            localization()->getLocalizedURL()
        );

        localization()->setLocale('es');

        $this->assertNotEquals(
            $this->testUrlOne,
            localization()->getLocalizedURL()
        );
        $this->assertNotEquals(
            $this->testUrlOne.localization()->getDefaultLocale(),
            localization()->getLocalizedURL()
        );
        $this->assertSame(
            $this->testUrlOne.localization()->getCurrentLocale(),
            localization()->getLocalizedURL()
        );
        $this->assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getLocalizedURL('es', $this->testUrlOne.'about')
        );

        localization()->setLocale('en');
        $response = $this->makeCall(
            $this->testUrlOne.'about',
            ['HTTP_ACCEPT_LANGUAGE' => 'en,es']
        );

        $this->assertResponseOk();
        $this->assertSame($this->testUrlOne.'es/acerca', $response->getContent());

        $this->refreshApplication();
        app('config')->set('localization.hide-default-in-url', true);

        $this->assertSame(
            $this->testUrlOne.'test',
            localization()->getLocalizedURL('en', $this->testUrlOne.'test')
        );

        $response = $this->makeCall(
            localization()->getLocalizedURL('en', $this->testUrlOne.'test'),
            ['HTTP_ACCEPT_LANGUAGE' => 'en,es']
        );

        $this->assertResponseOk();
        $this->assertSame('Test text', $response->getContent());

        $this->refreshApplication('es');

        $this->assertSame(
            $this->testUrlOne.'es/test',
            localization()->getLocalizedURL('es', $this->testUrlOne.'test')
        );
    }

    /** @test */
    public function it_can_get_url_from_route_name_translated()
    {
        $this->assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getUrlFromRouteName('es', 'localization::routes.about')
        );
        $this->assertSame(
            $this->testUrlOne.'en/about',
            localization()->getUrlFromRouteName('en', 'localization::routes.about')
        );
        $this->assertSame(
            $this->testUrlOne.'en/view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', [ 'id' => 1 ])
        );

        app('config')->set('localization.hide-default-in-url', true);

        $this->assertSame(
            $this->testUrlOne.'about',
            localization()->getUrlFromRouteName('en', 'localization::routes.about')
        );
        $this->assertSame(
            $this->testUrlOne.'es/acerca',
            localization()->getUrlFromRouteName('es', 'localization::routes.about')
        );
        $this->assertSame(
            $this->testUrlOne.'es/ver/1',
            localization()->getUrlFromRouteName('es', 'localization::routes.view', ['id' => 1])
        );
        $this->assertSame(
            $this->testUrlOne.'view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );
        $this->assertNotEquals(
            $this->testUrlOne.'en/view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );

        app('config')->set('localization.hide-default-in-url', false);

        $this->assertNotEquals(
            $this->testUrlOne.'view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );
        $this->assertSame(
            $this->testUrlOne.'en/view/1',
            localization()->getUrlFromRouteName('en', 'localization::routes.view', ['id' => 1])
        );
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     * @expectedExceptionMessage  Locale 'jp' is not in the list of supported locales.
     */
    public function it_must_throw_an_exception_on_unsupported_locale()
    {
        localization()->getUrlFromRouteName('jp', 'localization::routes.about');
    }

    /** @test */
    public function it_can_get_non_localized_url()
    {
        $this->assertSame(
            $this->testUrlOne,
            localization()->getNonLocalizedURL($this->testUrlOne.'en')
        );
        $this->assertSame(
            $this->testUrlOne,
            localization()->getNonLocalizedURL($this->testUrlOne.'es')
        );
        $this->assertSame(
            $this->testUrlOne.'view/1',
            localization()->getNonLocalizedURL($this->testUrlOne.'en/view/1')
        );
        $this->assertSame(
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

            $this->assertSame($name, localization()->getCurrentLocaleName());
        }
    }

    /** @test */
    public function it_can_get_current_locale_script()
    {
        foreach ($this->supportedLocales as $locale) {
            localization()->setLocale($locale);
            $this->refreshApplication($locale);

            $this->assertSame('Latn', localization()->getCurrentLocaleScript());
        }
    }

    /** @test */
    public function it_can_get_current_locale_direction()
    {
        foreach ($this->supportedLocales as $locale) {
            $this->refreshApplication($locale);

            $this->assertSame('ltr', localization()->getCurrentLocaleDirection());
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

            $this->assertSame($name, localization()->getCurrentLocaleNative());
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

            $this->assertSame($regional, localization()->getCurrentLocaleRegional());
        }
    }

    /** @test */
    public function it_can_create_url_from_uri()
    {
        $this->assertSame(
            'http://localhost/view/1',
            localization()->createUrlFromUri('/view/1')
        );

        localization()->setLocale('es');
        $this->refreshApplication('es');

        $this->assertSame(
            'http://localhost/ver/1',
            localization()->createUrlFromUri('/ver/1')
        );
    }

    /** @test */
    public function it_can_render_locales_navigation_bar()
    {
        $navbar = localization()->localesNavbar();

        $this->assertContains('<ul class="navbar-locales">', $navbar);
        $this->assertContains('<li class="active">', $navbar);
        $this->assertContains(e('English'),  $navbar);
        $this->assertContains(e('Español'),  $navbar);
        $this->assertContains(e('Français'), $navbar);
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $locales = localization()->getAllLocales();

        $this->assertInstanceOf(LocaleCollection::class, $locales);
        $this->assertFalse($locales->isEmpty());
        $this->assertCount(289, $locales);
        $this->assertSame(289, $locales->count());
    }

    /** @test */
    public function it_can_get_localized_url_with_relative_urls()
    {
        $this->assertSame($this->testUrlOne.'en', localization()->LocalizeURL('/'));

        $urls  = [
            '/contact',
            '/contact/',
            $this->testUrlOne.'/contact',
            $this->testUrlOne.'/contact/'
        ];

        foreach ($urls as $url) {
            $this->assertSame(
                $this->testUrlOne.'en/contact',
                localization()->LocalizeURL($url)
            );
        }
    }

    /** @test */
    public function it_can_get_localized_url_with_route_name_from_lang()
    {
        $this->assertSame(
            'http://localhost/en/view/en-view-slug',
            localized_route('localization::routes.view', ['id' => 'en-view-slug'])
        );

        $this->assertSame(
            'http://localhost/en/view/en-view-slug',
            localized_route('localization::routes.view', ['id' => 'en-view-slug'], 'en')
        );

        $this->assertSame(
            'http://localhost/fr/voir/fr-view-slug',
            localized_route('localization::routes.view', ['id' => 'fr-view-slug'], 'fr')
        );

        $this->assertSame(
            'http://localhost/es/ver/es-view-slug',
            localized_route('localization::routes.view', ['id' => 'es-view-slug'], 'es')
        );
    }

    /** @test */
    public function it_can_use_facade()
    {
        $this->assertSame(
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
}

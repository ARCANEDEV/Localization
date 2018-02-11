<?php namespace Arcanedev\Localization\Tests\Entities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Tests\TestCase;

/**
 * Class     LocaleCollectionTest
 *
 * @package  Arcanedev\Localization\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollectionTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Entities\LocaleCollection */
    private $locales;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->locales = new LocaleCollection;
    }

    public function tearDown()
    {
        unset($this->locales);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\Collection::class,
            \Arcanedev\Localization\Entities\LocaleCollection::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->locales);
        }

        static::assertTrue($this->locales->isEmpty());
        static::assertCount(0, $this->locales);
        static::assertSame(0, $this->locales->count());
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $data = config('localization.locales', []);
        $this->locales->loadFromArray($data);

        static::assertFalse($this->locales->isEmpty());
        static::assertCount(289, $this->locales);
        static::assertSame(289, $this->locales->count());
    }

    /** @test */
    public function it_can_get_supported_locales()
    {
        $this->locales
            ->loadFromArray(config('localization.locales', []))
            ->setSupportedKeys(config('localization.supported-locales', []));

        $supported = $this->locales->getSupported();

        $expectations = [
            \Illuminate\Support\Collection::class,
            \Arcanedev\Localization\Entities\SupportedLocaleCollection::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $supported);
        }

        $count = count($this->supportedLocales);
        static::assertFalse($supported->isEmpty());
        static::assertCount($count, $supported);
        static::assertSame($count, $supported->count());
    }

    /** @test */
    public function it_can_transform_locales_to_native_names()
    {
        $this->locales
            ->loadFromArray(config('localization.locales', []))
            ->setSupportedKeys(config('localization.supported-locales', []));

        foreach ($this->locales->toNative() as $key => $native) {
            static::assertTrue($this->locales->has($key), "Locale [$key] not found");
            static::assertSame($this->locales->get($key)->native(), $native);
        }

        $expected = [
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
        ];

        static::assertEquals($expected, $this->locales->getSupported()->toNative()->toArray());
    }

    /** @test */
    public function it_can_load_locales_from_config()
    {
        $this->locales->loadFromConfig();

        $supported = $this->locales->getSupported();

        // Assert all locales
        static::assertFalse($this->locales->isEmpty());
        static::assertCount(289,  $this->locales);
        static::assertSame(289, $this->locales->count());

        // Assert supported locales
        static::assertFalse($supported->isEmpty());
        $count = count($this->supportedLocales);
        static::assertCount($count,  $supported);
        static::assertSame($count, $supported->count());
    }

    /** @test */
    public function it_can_get_first_locale()
    {
        $this->locales->loadFromConfig();

        $locale = $this->locales->first();

        static::assertInstanceOf(Locale::class, $locale);
        static::assertSame('aa', $locale->key());
    }
}

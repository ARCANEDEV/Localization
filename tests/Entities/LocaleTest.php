<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests\Entities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Tests\TestCase;
use Illuminate\Support\Arr;

/**
 * Class     LocaleTest
 *
 * @package  Arcanedev\Localization\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Entities\Locale */
    private $locale;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function tearDown(): void
    {
        unset($this->locale);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Test Methods
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $this->locale = $this->makeLocale('en');

        static::assertInstanceOf(Locale::class, $this->locale);

        static::assertSame('en',      $this->locale->key());
        static::assertSame('English', $this->locale->name());
        static::assertSame('Latin',   $this->locale->script());
        static::assertSame('ltr',     $this->locale->direction());
        static::assertSame('English', $this->locale->native());
        static::assertSame('en_GB',   $this->locale->regional());

        static::assertTrue($this->locale->isDefault());
    }

    /** @test */
    public function it_must_lower_direction_case(): void
    {
        $key          = 'en';
        $data         = $this->getLocale($key);
        $data['dir']  = 'LTR';
        $this->locale = new Locale($key, $data);

        static::assertSame(strtolower($data['dir']), $this->locale->direction());
    }

    /** @test */
    public function it_can_get_direction_if_empty(): void
    {
        $key          = 'en';
        $data         = $this->getLocale($key);
        $data['dir']  = '';
        $this->locale = new Locale($key, $data);

        static::assertSame('ltr', $this->locale->direction());
    }

    /** @test */
    public function it_can_convert_entity_to_array(): void
    {
        $this->locale = $this->makeLocale('en');

        static::assertIsArray($this->locale->toArray());
    }

    /** @test */
    public function it_can_convert_entity_to_json(): void
    {
        $this->locale = $this->makeLocale('en');

        static::assertJson($this->locale->toJson());
        static::assertJson(json_encode($this->locale, JSON_PRETTY_PRINT));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a locale.
     *
     * @param  string  $key
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    private function makeLocale($key): Locale
    {
        return Locale::make($key, $this->getLocale($key));
    }

    /**
     * Get locale data.
     *
     * @param  string  $key
     *
     * @return array
     */
    private function getLocale($key): array
    {
        return Arr::get([
            'ar' => [
                'name'     => 'Arabic',
                'script'   => 'Arab',
                'dir'      => 'rtl',
                'native'   => 'العربية',
                'regional' => 'ar_AE',
            ],
            'en' => [
                'name'     => 'English',
                'script'   => 'Latin',
                'dir'      => 'ltr',
                'native'   => 'English',
                'regional' => 'en_GB',
            ],
            'fr' => [
                'name'     => 'French',
                'script'   => 'Latin',
                'dir'      => 'ltr',
                'native'   => 'Français',
                'regional' => 'fr_FR',
            ],
        ], $key);
    }
}

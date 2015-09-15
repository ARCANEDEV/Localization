<?php namespace Arcanedev\Localization\Tests\Entities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Tests\TestCase;

/**
 * Class     LocaleTest
 *
 * @package  Arcanedev\Localization\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Locale */
    private $locale;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();


    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->locale);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->locale = $this->makeLocale('en');

        $this->assertInstanceOf(Locale::class, $this->locale);

        $this->assertEquals('en',      $this->locale->key());
        $this->assertEquals('English', $this->locale->name());
        $this->assertEquals('Latin',   $this->locale->script());
        $this->assertEquals('ltr',     $this->locale->direction());
        $this->assertEquals('English', $this->locale->native());
        $this->assertTrue($this->locale->isDefault());
    }

    /** @test */
    public function it_must_lower_direction_case()
    {
        $key          = 'en';
        $data         = $this->getLocale($key);
        $data['dir']  = 'LTR';
        $this->locale = new Locale($key, $data);

        $this->assertEquals(strtolower($data['dir']), $this->locale->direction());
    }

    /** @test */
    public function it_can_get_direction_if_empty()
    {
        $key          = 'en';
        $data         = $this->getLocale($key);
        $data['dir']  = '';
        $this->locale = new Locale($key, $data);

        $this->assertEquals('ltr', $this->locale->direction());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function makeLocale($key)
    {
        $data = $this->getLocale($key);

        return new Locale($key, $data);
    }

    private function getLocale($key)
    {
        return array_get([
            'ar'         => [
                'name'   => 'Arabic',
                'script' => 'Arab',
                'dir'    => 'rtl',
                'native' => 'العربية',
            ],
            'en'         => [
                'name'   => 'English',
                'script' => 'Latin',
                'dir'    => 'ltr',
                'native' => 'English',
            ],
            'fr'         => [
                'name'   => 'French',
                'script' => 'Latin',
                'dir'    => 'ltr',
                'native' => 'Français',
            ],
        ], $key);
    }
}

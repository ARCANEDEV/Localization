<?php namespace Arcanedev\Localization\Tests\Entities;

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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var LocaleCollection */
    private $locales;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->locales = new LocaleCollection;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($locales);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(LocaleCollection::class, $this->locales);
        $this->assertTrue($this->locales->isEmpty());
        $this->assertCount(0, $this->locales);
        $this->assertEquals(0, $this->locales->count());
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $this->locales->loadAll();

        $this->assertFalse($this->locales->isEmpty());
        $this->assertCount(289, $this->locales);
        $this->assertEquals(289, $this->locales->count());
    }

    /** @test */
    public function it_can_get_supported_locales()
    {
        $this->locales->loadSupported();

        $count = count($this->supportedLocales);
        $this->assertFalse($this->locales->isEmpty());
        $this->assertCount($count, $this->locales);
        $this->assertEquals($count, $this->locales->count());
    }
}

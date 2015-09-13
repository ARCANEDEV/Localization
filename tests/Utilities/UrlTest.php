<?php namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\Url;

/**
 * Class     UrlTest
 *
 * @package  Arcanedev\Localization\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UrlTest extends TestCase
{
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
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_unparse_url()
    {
        $urls = [
            '',
            'http://www.example.com/',
            'http://username:password@example.com/'
        ];

        foreach ($urls as $url) {
            $parsed = ! empty($url) ? parse_url($url) : [];

            $this->assertEquals($url, Url::unparse($parsed));
        }
    }
}

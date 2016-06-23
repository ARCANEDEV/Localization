<?php namespace Arcanedev\Localization\Tests\Middleware;

use Arcanedev\Localization\Tests\TestCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Class     LocaleSessionRedirectTest
 *
 * @package  Arcanedev\Localization\Tests\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleSessionRedirectTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_redirect_with_locale_session()
    {
        $this->refreshApplication(false, true);
        session()->put('locale', 'fr');

        /** @var Response|RedirectResponse $response */
        $response = $this->call('GET', $this->testUrlOne);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($this->testUrlOne . 'fr', $response->getTargetUrl());

        session()->put('locale', 'es');

        $response = $this->call('GET', $this->testUrlOne);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($this->testUrlOne . 'es', $response->getTargetUrl());
    }

    /** @test */
    public function it_can_pass_redirect_without_session()
    {
        $this->refreshApplication(false, true);
        session()->put('locale', null);

        /** @var RedirectResponse $response */
        $response = $this->call('GET', $this->testUrlOne);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($this->testUrlOne . 'en', $response->getTargetUrl());
    }
}

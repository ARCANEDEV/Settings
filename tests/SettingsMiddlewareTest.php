<?php namespace Arcanedev\Settings\Tests;

/**
 * Class     SettingsMiddlewareTest
 *
 * @package  Arcanedev\Settings\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsMiddlewareTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_save_settings_via_middleware()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals('Dummy Home page', $response->content());

        // The Settings are saved by terminating the request.
    }
}

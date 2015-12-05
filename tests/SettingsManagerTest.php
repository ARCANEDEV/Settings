<?php namespace Arcanedev\Settings\Tests;

use Arcanedev\Settings\SettingsManager;

/**
 * Class     SettingsManagerTest
 *
 * @package  Arcanedev\Settings\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsManagerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var SettingsManager */
    private $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->app['arcanedev.settings.manager'];
    }

    public function tearDown()
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(SettingsManager::class, $this->manager);
    }

    /** @test */
    public function it_can_get_default_driver()
    {
        $this->assertEquals('json', $this->manager->getDefaultDriver());
    }

    /** @test */
    public function it_can_switch_driver()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config       = $this->app['config'];
        $expectations = [
            'json'     => \Arcanedev\Settings\Stores\JsonStore::class,
            'database' => \Arcanedev\Settings\Stores\DatabaseStore::class,
            'memory'   => \Arcanedev\Settings\Stores\MemoryStore::class,
            'array'    => \Arcanedev\Settings\Stores\MemoryStore::class,
        ];

        foreach ($expectations as $driver => $expected) {
            $this->assertInstanceOf($expected, $this->manager->driver($driver));

            $config->set('settings.default', $driver);

            $this->assertEquals($driver, $this->manager->getDefaultDriver());
            $this->assertInstanceOf($expected, $this->manager->driver());
        }
    }
}

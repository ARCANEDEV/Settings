<?php namespace Arcanedev\Settings\Tests\Stores;

use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Stores\DatabaseStore;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     DatabaseStoreTest
 *
 * @package  Arcanedev\Settings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DatabaseStoreTest extends AbstractStoreTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    protected $capsule;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish', [
            '--provider' => \Arcanedev\Settings\SettingsServiceProvider::class,
            '--tag'      => ['migrations'],
        ]);

        $this->artisan('migrate');
    }

    public function tearDown()
    {
        $this->artisan('migrate:reset');

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create store instance.
     *
     * @param  array  $data
     *
     * @return StoreContract
     */
    protected function createStore(array $data = [])
    {
        if ( ! empty($data)) {
            $store = $this->createStore();
            $store->set($data);
            $store->save();

            unset($store);
        }

        return new DatabaseStore('testing');
    }
}

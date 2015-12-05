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

        $this->container = new \Illuminate\Container\Container;
        $this->capsule   = new \Illuminate\Database\Capsule\Manager($this->container);
        $this->capsule->setAsGlobal();
        $this->container['db'] = $this->capsule;
        $this->capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $this->capsule->schema()->create('settings', function(Blueprint $table) {
            $table->string('key', 64)->unique();
            $table->string('value', 4096);
        });
    }

    public function tearDown()
    {
        $this->capsule->schema()->drop('settings');
        unset($this->capsule);
        unset($this->container);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
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

        return new DatabaseStore($this->capsule->getConnection());
    }
}

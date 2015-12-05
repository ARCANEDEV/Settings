<?php namespace Arcanedev\Settings\Tests\Stores;

use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Stores\MemoryStore;

/**
 * Class     MemoryStoreTest
 *
 * @package  Arcanedev\Settings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MemoryStoreTest extends AbstractStoreTest
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
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create store instance.
     *
     * @param  array $data
     *
     * @return StoreContract
     */
    protected function createStore(array $data = [])
    {
        return new MemoryStore($data);
    }

    protected function assertStoreEquals(StoreContract $store, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->all(), $message);
    }

    protected function assertStoreKeyEquals(StoreContract $store, $key, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->get($key), $message);
    }
}

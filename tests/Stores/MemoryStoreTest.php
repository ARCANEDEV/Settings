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

    protected function assertStoreEquals($expected, $message = null)
    {
        $this->assertEquals($expected, $this->store->all(), $message);
    }

    protected function assertStoreKeyEquals($key, $expected, $message = null)
    {
        $this->assertEquals($expected, $this->store->get($key), $message);
    }
}

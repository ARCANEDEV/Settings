<?php namespace Arcanedev\Settings\Tests\Stores;

use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Stores\ArrayStore;

/**
 * Class     ArrayStoreTest
 *
 * @package  Arcanedev\Settings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ArrayStoreTest extends MemoryStoreTest
{
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
        return new ArrayStore($data);
    }
}

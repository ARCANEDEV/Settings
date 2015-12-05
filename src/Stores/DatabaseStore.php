<?php namespace Arcanedev\Settings\Stores;

use Arcanedev\Settings\Bases\Store;
use Arcanedev\Settings\Contracts\Store as StoreContract;

/**
 * Class     DatabaseStore
 *
 * @package  Arcanedev\Settings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DatabaseStore extends Store implements StoreContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * {@inheritdoc}
     */
    protected function read()
    {
        // TODO: Implement read() method.
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        // TODO: Implement write() method.
    }
}

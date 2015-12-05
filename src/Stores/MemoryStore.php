<?php namespace Arcanedev\Settings\Stores;

use Arcanedev\Settings\Bases\Store;
use Arcanedev\Settings\Contracts\Store as StoreContract;

/**
 * Class     MemoryStore
 *
 * @package  Arcanedev\Settings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MemoryStore extends Store implements StoreContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the memory store instance.
     *
     * @param  array  $data
     */
    public function __construct(array $data = null)
    {
        if ($data) {
            $this->data = $data;
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * {@inheritdoc}
     */
    protected function read()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function write(array $data)
    {
        // Do nothing, John SNOW.
    }
}

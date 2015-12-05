<?php namespace Arcanedev\Settings\Tests\Stores;

use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Tests\TestCase;

/**
 * Class     AbstractStoreTest
 *
 * @package  Arcanedev\Settings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractStoreTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_must_get_empty_array_in_init()
    {
        $store = $this->createStore();

        $this->assertEquals([], $store->all());
    }

    /** @test */
    public function it_can_set()
    {
        $store = $this->createStore();
        $store->set('foo', 'bar');

        $this->assertStoreKeyEquals($store, 'foo', 'bar');
    }

    /** @test */
    public function it_can_set_nested_keys_value()
    {
        $store = $this->createStore();
        $store->set('foo.bar', 'baz');

        $this->assertStoreEquals($store, ['foo' => ['bar' => 'baz']]);
    }

    /**
     * @test
     *
     * @expectedException         \UnexpectedValueException
     * @expectedExceptionMessage  Non-array segment encountered
     */
    public function it_cannot_set_nested_key_on_non_array_member()
    {
        $store = $this->createStore();
        $store->set('foo', 'bar');
        $store->set('foo.bar', 'baz');
    }

    /** @test */
    public function it_can_forget_key()
    {
        $store = $this->createStore();
        $store->set('foo', 'bar');
        $store->set('bar', 'baz');

        $this->assertStoreEquals($store, ['foo' => 'bar', 'bar' => 'baz']);

        $store->forget('foo');

        $this->assertStoreEquals($store, ['bar' => 'baz']);
    }

    /** @test */
    public function it_can_forget_nested_key()
    {
        $store = $this->createStore();
        $store->set('foo.bar', 'baz');
        $store->set('foo.baz', 'bar');
        $store->set('bar.foo', 'baz');

        $this->assertStoreEquals($store, [
            'foo' => [
                'bar' => 'baz',
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('foo.bar');

        $this->assertStoreEquals($store, [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('bar.foo');
        $expected = [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [],
        ];

        if ($store instanceof \Arcanedev\Settings\Stores\DatabaseStore) {
            unset($expected['bar']);
        }

        $this->assertStoreEquals($store, $expected);
    }

    /** @test */
    public function it_can_reset()
    {
        $store = $this->createStore(['foo' => 'bar']);

        $this->assertStoreEquals($store, ['foo' => 'bar']);

        $store->reset();

        $this->assertStoreEquals($store, []);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Common Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create store instance.
     *
     * @param  array  $data
     *
     * @return StoreContract
     */
    abstract protected function createStore(array $data = []);

    /* ------------------------------------------------------------------------------------------------
     |  Assert Functions
     | ------------------------------------------------------------------------------------------------
     */
    protected function assertStoreEquals(StoreContract $store, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->all(), $message);

        $store->save();
        $store = $this->createStore();

        $this->assertEquals($expected, $store->all(), $message);
    }

    protected function assertStoreKeyEquals(StoreContract $store, $key, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->get($key), $message);

        $store->save();
        $store = $this->createStore();

        $this->assertEquals($expected, $store->get($key), $message);
    }
}

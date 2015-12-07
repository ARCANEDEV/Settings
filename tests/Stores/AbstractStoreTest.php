<?php namespace Arcanedev\Settings\Tests\Stores;

use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Stores\DatabaseStore;
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
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var StoreContract */
    protected $store;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->store = $this->createStore();
    }

    public function tearDown()
    {
        unset($this->store);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_must_get_empty_array_in_init()
    {
        $this->assertEquals([], $this->store->all());
    }

    /** @test */
    public function it_can_set()
    {
        $this->store->set('foo', 'bar');

        $this->assertStoreKeyEquals('foo', 'bar');
    }

    /** @test */
    public function it_can_set_nested_keys_value()
    {
        $this->store->set('foo.bar', 'baz');

        $this->assertStoreEquals([
            'foo' => [
                'bar' => 'baz',
            ],
        ]);
    }

    /**
     * @test
     *
     * @expectedException         \UnexpectedValueException
     * @expectedExceptionMessage  Non-array segment encountered
     */
    public function it_cannot_set_nested_key_on_non_array_member()
    {
        $this->store->set('foo', 'bar');
        $this->store->set('foo.bar', 'baz');
    }

    /** @test */
    public function it_can_forget_key()
    {
        $this->store->set('foo', 'bar');
        $this->store->set('bar', 'baz');

        $this->assertStoreEquals([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        $this->store->forget('foo');

        $this->assertStoreEquals([
            'bar' => 'baz',
        ]);
    }

    /** @test */
    public function it_can_forget_nested_key()
    {
        $this->store->set('foo.bar', 'baz');
        $this->store->set('foo.baz', 'bar');
        $this->store->set('bar.foo', 'baz');

        $this->assertStoreEquals([
            'foo' => [
                'bar' => 'baz',
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $this->store->forget('foo.bar');

        $this->assertStoreEquals([
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $this->store->forget('bar.foo');
        $expected = [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [],
        ];

        if ($this->store instanceof DatabaseStore) {
            unset($expected['bar']);
        }

        $this->assertStoreEquals($expected);
    }

    /** @test */
    public function it_can_reset()
    {
        $this->store = $this->createStore([
            'foo' => 'bar',
        ]);

        $this->assertStoreEquals([
            'foo' => 'bar',
        ]);

        $this->store->reset();

        $this->assertStoreEquals([]);
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
    protected function assertStoreEquals($expected, $message = null)
    {
        $this->assertEquals($expected, $this->store->all(), $message);

        $this->store->save();
        $store = $this->createStore();

        $this->assertEquals($expected, $store->all(), $message);
    }

    protected function assertStoreKeyEquals($key, $expected, $message = null)
    {
        $this->assertEquals($expected, $this->store->get($key), $message);

        $this->store->save();
        $this->store = $this->createStore();

        $this->assertEquals($expected, $this->store->get($key), $message);
    }
}

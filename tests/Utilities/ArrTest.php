<?php namespace Arcanedev\Settings\Tests\Utilities;

use Arcanedev\Settings\Tests\TestCase;
use Arcanedev\Settings\Utilities\Arr;

/**
 * Class     ArrTest
 *
 * @package  Arcanedev\Settings\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ArrTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     * @dataProvider  provideGetData
     *
     * @param  array         $data
     * @param  array|string  $key
     * @param  mixed         $expected
     */
    public function it_can_get(array $data, $key, $expected)
    {
        $this->assertEquals($expected, Arr::get($data, $key));
    }

    /**
     * @test
     * @dataProvider provideSetData
     *
     * @param  array   $data
     * @param  string  $key
     * @param  mixed   $value
     * @param  array   $expected
     */
    public function it_can_set(array $data, $key, $value, array $expected)
    {
        Arr::set($data, $key, $value);

        $this->assertEquals($expected, $data);
    }

    /**
     * @test
     *
     * @expectedException         \UnexpectedValueException
     * @expectedExceptionMessage  Non-array segment encountered
     */
    public function it_must_throws_exception_on_set_non_array_segment_value()
    {
        $data = ['foo' => 'bar'];

        Arr::set($data, 'foo.bar', 'baz');
    }

    /**
     * @test
     * @dataProvider  provideHasData
     *
     * @param  array   $data
     * @param  string  $key
     * @param  bool    $expected
     */
    public function it_can_check_if_has(array $data, $key, $expected)
    {
        $this->assertEquals($expected, Arr::has($data, $key));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Data Providers
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Provide data for get method.
     *
     * @return array
     */
    public function provideGetData()
    {
        return [
            [
                [],
                'foo',
                null,
            ],[
                ['foo' => 'bar'],
                'foo',
                'bar',
            ],[
                ['foo' => 'bar'],
                'bar',
                null,
            ],[
                ['foo' => 'bar'],
                'foo.bar',
                null,
            ],[
                ['foo' => ['bar' => 'baz']],
                'foo.bar',
                'baz',
            ],[
                ['foo' => ['bar' => 'baz']],
                'foo.baz',
                null,
            ],[
                ['foo' => ['bar' => 'baz']],
                'foo',
                ['bar' => 'baz'],
            ],[
                ['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'],
                ['foo', 'bar'],
                ['foo' => 'foo', 'bar' => 'bar'],
            ],[
                ['foo' => ['bar' => 'bar', 'baz' => 'baz'], 'qux' => 'qux'],
                ['foo.bar', 'qux'],
                ['foo' => ['bar' => 'bar'], 'qux' => 'qux'],
            ],[
                ['foo' => ['bar' => 'bar'], 'baz' => 'baz'],
                ['foo.bar'],
                ['foo' => ['bar' => 'bar']],
            ],[
                ['foo' => ['bar' => 'bar'], 'qux' => 'qux'],
                ['foo.bar', 'baz'],
                ['foo' => ['bar' => 'bar'], 'baz' => null],
            ],
        ];
    }

    /**
     * Provide data for set method.
     *
     * @return array
     */
    public function provideSetData()
    {
        return [
            [
                [],
                'foo',
                'bar',
                ['foo' => 'bar'],
            ],[
                [],
                'foo.bar',
                'baz',
                ['foo' => ['bar' => 'baz']],
            ],[
                [],
                'foo.bar.baz',
                'foo',
                ['foo' => ['bar' => ['baz' => 'foo']]],
            ],[
                ['foo' => 'bar'],
                'foo',
                'baz',
                ['foo' => 'baz'],
            ],[
                ['foo' => ['bar' => 'baz']],
                'foo.baz',
                'foo',
                ['foo' => ['bar' => 'baz', 'baz' => 'foo']],
            ],[
                ['foo' => ['bar' => 'baz']],
                'foo.baz.bar',
                'baz',
                ['foo' => ['bar' => 'baz', 'baz' => ['bar' => 'baz']]],
            ],
        ];
    }

    /**
     * Provide data for has method.
     *
     * @return array
     */
    public function provideHasData()
    {
        return [
            [
                [], 'foo', false,
            ],[
                ['foo' => 'bar'], 'foo', true,
            ],[
                ['foo' => 'bar'], 'bar', false,
            ],[
                ['foo' => 'bar'], 'foo.bar', false,
            ],[
                ['foo' => ['bar' => 'baz']], 'foo.bar', true,
            ],[
                ['foo' => ['bar' => 'baz']], 'foo.baz', false,
            ],[
                ['foo' => ['bar' => 'baz']], 'foo', true,
            ],[
                ['foo' => null], 'foo', true,
            ],[
                ['foo' => ['bar' => null]], 'foo.bar', true,
            ],
        ];
    }
}

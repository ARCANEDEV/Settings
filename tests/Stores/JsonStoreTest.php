<?php namespace Arcanedev\Settings\Tests\Stores;

use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Stores\JsonStore;
use Prophecy\Argument;

/**
 * Class     JsonStoreTest
 *
 * @package  Arcanedev\Settings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonStoreTest extends AbstractStoreTest
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

        $this->cleanFixtures();
    }

    /**
     * Create store instance.
     *
     * @param  array  $data
     *
     * @return StoreContract
     */
    protected function createStore(array $data = [])
    {
        $path = $this->getFixturesPath() . '/settings.json';

        if ( ! empty($data)) {
            file_put_contents($path, json_encode($data));
        }

        return $this->makeStore($this->app['files'], $path);
    }

    protected function makeStore($files, $path = 'fake-path/settings.json')
    {
        return new JsonStore($files, $path);
    }

    protected function cleanFixtures()
    {
        $path = $this->getFixturesPath() . '/settings.json';

        if (file_exists($path)) {
            unlink($path);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException         \InvalidArgumentException
     * @expectedExceptionMessage  fake-path/settings.json is not writable.
     */
    public function it_must_throws_exception_when_file_not_writeable()
    {
        $files = $this->prophesize(\Illuminate\Filesystem\Filesystem::class);

        $files->exists(Argument::type('string'))->shouldBeCalled()->willReturn(true);
        $files->isWritable(Argument::type('string'))->shouldBeCalled()->willReturn(false);

        $this->makeStore($files->reveal());
    }

    /**
     * @test
     *
     * @expectedException         \InvalidArgumentException
     * @expectedExceptionMessage  Could not write to fake-path/settings.json.
     */
    public function it_must_throws_exception_when_files_put_fails()
    {
        $files = $this->prophesize(\Illuminate\Filesystem\Filesystem::class);

        $files->exists(Argument::type('string'))->shouldBeCalled()->willReturn(false);
        $files->put(Argument::type('string'), '{}')->shouldBeCalled()->willReturn(false);

        $this->makeStore($files->reveal());
    }

    /**
     * @test
     *
     * @expectedException         \RuntimeException
     * @expectedExceptionMessage  Invalid JSON in fake-path/settings.json
     */
    public function it_must_throws_exception_when_file_contains_invalid_json()
    {
        $files = $this->prophesize(\Illuminate\Filesystem\Filesystem::class);

        $files->exists(Argument::type('string'))->shouldBeCalled()->willReturn(true);
        $files->isWritable(Argument::type('string'))->shouldBeCalled()->willReturn(true);
        $files->get(Argument::type('string'))->shouldBeCalled()->willReturn('[[!1!11]');

        $store = $this->makeStore($files->reveal());
        $store->get('foo');
    }
}

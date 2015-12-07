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
    /** @var DatabaseStore */
    protected $store;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish', [
            '--provider' => \Arcanedev\Settings\SettingsServiceProvider::class,
            '--tag'      => ['migrations'],
        ]);

        $this->artisan('migrate');
    }

    public function tearDown()
    {
        $this->artisan('migrate:reset');

        parent::tearDown();
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
        if ( ! empty($data)) {
            $store = $this->createStore();
            $store->set($data);
            $store->save();

            unset($store);
        }

        return new DatabaseStore('testing');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_set_extra_columns()
    {
        $this->addingExtraColumn();
        $this->store->setExtraColumns([
            'user_id' => 1,
        ]);
        $this->store->set('foo', 'bar');

        $this->store->save();

        $this->seeInDatabase('settings', [
            'user_id' => 1,
            'key'     => 'foo',
            'value'   => 'bar',
        ]);
    }

    /** @test */
    public function it_can_set_constraint()
    {
        $this->addingExtraColumn();

        $this->store->setExtraColumns([
            'user_id' => 1,
        ]);
        $this->store->set('foo', 'bar');
        $this->store->save();

        $this->store->setExtraColumns([
            'user_id' => 2,
        ]);
        $this->store->set('baz', 'qux');
        $this->store->save();

        $this->seeInDatabase('settings', [
            'user_id' => 1,
            'key'     => 'foo',
            'value'   => 'bar',
        ]);

        $this->seeInDatabase('settings', [
            'user_id' => 2,
            'key'     => 'baz',
            'value'   => 'qux',
        ]);

        $expected = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $this->assertEquals($expected, $this->store->all());

        $this->store->setConstraint(function (\Arcanedev\Settings\Models\Setting $model, $insert) {
            return $model->where('user_id', 1);
        });

        $this->assertTrue($this->store->hasConstraint());

        $this->assertEquals(['foo' => 'bar'], $this->store->all());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function addingExtraColumn()
    {
        /**
         * @var \Illuminate\Database\Schema\Builder   $schema
         */
        $schema = $this->app['db']->connection()->getSchemaBuilder();

        $schema->table('settings', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->default(0);
        });
    }
}

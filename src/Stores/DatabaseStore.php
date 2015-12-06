<?php namespace Arcanedev\Settings\Stores;

use Arcanedev\Settings\Bases\Store;
use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Models\Setting;
use Arcanedev\Settings\Utilities\Arr;
use Closure;

/**
 * Class     DatabaseStore
 *
 * @package  Arcanedev\Settings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DatabaseStore extends Store implements StoreContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Settings\Models\Setting */
    protected $model;

    /**
     * Any query constraints that should be applied.
     *
     * @var Closure|null
     */
    protected $queryConstraint;

    /**
     * Any extra columns that should be added to the rows.
     *
     * @var array
     */
    protected $extraColumns = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the Database store instance.
     *
     * @param  string  $connection
     * @param  string  $table
     */
    public function __construct($connection, $table = null)
    {
        $this->model = new Setting;
        $this->setConnection($connection);
        $this->setTable($table ?: 'settings');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the database connection.
     *
     * @param  string  $connection
     *
     * @return self
     */
    public function setConnection($connection)
    {
        $this->model->setConnection($connection);

        return $this;
    }

    /**
     * Set the table to query from.
     *
     * @param  string  $table
     *
     * @return self
     */
    public function setTable($table)
    {
        $this->model->setTable($table);

        return $this;
    }

    /**
     * Set the query constraint.
     *
     * @param  \Closure  $callback
     *
     * @return self
     */
    public function setConstraint(Closure $callback)
    {
        $this->data            = [];
        $this->loaded          = false;
        $this->queryConstraint = $callback;

        return $this;
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @param  array  $columns
     *
     * @return self
     */
    public function setExtraColumns(array $columns)
    {
        $this->extraColumns = $columns;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * {@inheritdoc}
     */
    public function forget($key)
    {
        parent::forget($key);

        // because the database store cannot store empty arrays, remove empty
        // arrays to keep data consistent before and after saving
        $segments = explode('.', $key);
        array_pop($segments);

        while ( ! empty($segments)) {
            $segment = implode('.', $segments);

            // non-empty array - exit out of the loop
            if ($this->get($segment)) {
                break;
            }

            // remove the empty array and move on to the next segment
            $this->forget($segment);
            array_pop($segments);
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
        $results  = [];

        foreach ($this->model->all() as $setting) {
            /** @var Setting $setting */
            Arr::set($results, $setting->key, $setting->value);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        list($inserts, $updates, $deletes) = $this->prepareData($data);

        $this->updateSettings($updates);
        $this->insertSettings($inserts);
        $this->deleteSettings($deletes);
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare settings data.
     *
     * @param  array  $data
     *
     * @return array
     */
    private function prepareData(array $data)
    {
        $inserts = array_dot($data);
        $updates = [];
        $deletes = [];

        foreach ($this->model()->lists('key') as $key) {
            if (isset($inserts[$key]))
                $updates[$key] = $inserts[$key];
            else
                $deletes[]     = $key;

            unset($inserts[$key]);
        }

        return [$inserts, $updates, $deletes];
    }

    /**
     * Update settings data.
     *
     * @param  array $updates
     */
    private function updateSettings($updates)
    {
        foreach ($updates as $key => $value) {
            $this->model()->where('key', $key)->update(compact('value'));
        }
    }

    /**
     * Insert settings data.
     *
     * @param  array $inserts
     */
    private function insertSettings(array $inserts)
    {
        if (empty($inserts)) {
            return;
        }

        $dbData = [];

        foreach ($inserts as $key => $value) {
            $data     = compact('key', 'value');
            $dbData[] = empty($this->extraColumns) ? $data : array_merge($this->extraColumns, $data);
        }

        $this->model(true)->insert($dbData);
    }

    /**
     * Delete settings data.
     *
     * @param  array  $deletes
     */
    private function deleteSettings(array $deletes)
    {
        if (empty($deletes)) {
            return;
        }

        $this->model()->whereIn('key', $deletes)->delete();
    }

    /**
     * Create a new query builder instance.
     *
     * @param  $insert  bool  Whether the query is an insert or not.
     *
     * @return \Arcanedev\Settings\Models\Setting
     */
    private function model($insert = false)
    {
        $model = $this->model;

        if ($insert === false) {
            foreach ($this->extraColumns as $key => $value) {
                $model->where($key, $value);
            }
        }

        if ( ! is_null($this->queryConstraint)) {
            /** @var  Closure  $callback */
            $callback = $this->queryConstraint;
            $callback($model, $insert);
        }

        return $model;
    }
}

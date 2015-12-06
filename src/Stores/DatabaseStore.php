<?php namespace Arcanedev\Settings\Stores;

use Arcanedev\Settings\Bases\Store;
use Arcanedev\Settings\Contracts\Store as StoreContract;
use Arcanedev\Settings\Utilities\Arr;
use Closure;
use Illuminate\Database\Connection;

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
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The table to query from.
     *
     * @var string
     */
    protected $table;

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
     * @param  \Illuminate\Database\Connection  $connection
     * @param  string                           $table
     */
    public function __construct(Connection $connection, $table = null)
    {
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
     * @param  \Illuminate\Database\Connection  $connection
     *
     * @return self
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;

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
        $this->table = $table;

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
        return $this->parseReadData($this->newQuery()->get());
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        $keys       = $this->newQuery()->lists('key');
        $insertData = array_dot($data);
        $updateData = [];
        $deleteKeys = [];

        foreach ($keys as $key) {
            if (isset($insertData[$key]))
                $updateData[$key] = $insertData[$key];
            else
                $deleteKeys[] = $key;

            unset($insertData[$key]);
        }

        foreach ($updateData as $key => $value) {
            $this->newQuery()
                ->where('key', '=', $key)
                ->update(array('value' => $value));
        }

        if ( ! empty($insertData)) {
            $this->newQuery(true)
                ->insert($this->prepareInsertData($insertData));
        }

        if ( ! empty($deleteKeys)) {
            $this->newQuery()
                ->whereIn('key', $deleteKeys)
                ->delete();
        }
    }

    /**
     * Parse data coming from the database.
     *
     * @param  array $data
     *
     * @return array
     */
    protected function parseReadData($data)
    {
        $results = [];

        foreach ($data as $row) {
            if (is_array($row)) {
                $key   = $row['key'];
                $value = $row['value'];
            }
            elseif (is_object($row)) {
                $key   = $row->key;
                $value = $row->value;
            }
            else {
                throw new \UnexpectedValueException(
                    'Expected array or object, got ' . gettype($row)
                );
            }

            Arr::set($results, $key, $value);
        }

        return $results;
    }

    /**
     * Transforms settings data into an array ready to be insterted into the
     * database. Call array_dot on a multidimensional array before passing it
     * into this method!
     *
     * @param  array $data Call array_dot on a multidimensional array before passing it into this method!
     *
     * @return array
     */
    protected function prepareInsertData(array $data)
    {
        $dbData = [];

        foreach ($data as $key => $value) {
            $dbData[] = empty($this->extraColumns)
                ? compact('key', 'value')
                : array_merge($this->extraColumns, compact('key', 'value'));
        }

        return $dbData;
    }

    /**
     * Create a new query builder instance.
     *
     * @param  $insert  boolean  Whether the query is an insert or not.
     *
     * @return \Arcanedev\Settings\Models\Setting
     */
    protected function newQuery($insert = false)
    {
        $query = $this->connection->table($this->table);

        if ( ! $insert) {
            foreach ($this->extraColumns as $key => $value) {
                $query->where($key, '=', $value);
            }
        }

        if ($this->queryConstraint !== null) {
            /** @var  Closure  $callback */
            $callback = $this->queryConstraint;
            $callback($query, $insert);
        }

        return $query;
    }
}

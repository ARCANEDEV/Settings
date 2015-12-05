<?php namespace Arcanedev\Settings\Stores;

use Arcanedev\Settings\Bases\Store;
use Arcanedev\Settings\Contracts\Store as StoreContract;
use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class     JsonStore
 *
 * @package  Arcanedev\Settings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonStore extends Store implements StoreContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The json file path.
     *
     * @var string
     */
    protected $path;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the Json store instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string                             $path
     */
    public function __construct(Filesystem $files, $path = null)
    {
        $this->files = $files;
        $this->setPath($path ?: storage_path('app/settings.json'));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the path for the JSON file.
     *
     * @param  string  $path
     *
     * @return self
     */
    public function setPath($path)
    {
        if (
            ! $this->files->exists($path) &&
            $this->files->put($path, '{}') === false
        ) {
            throw new InvalidArgumentException("Could not write to $path.");
        }

        if ( ! $this->files->isWritable($path)) {
            throw new InvalidArgumentException("$path is not writable.");
        }

        $this->path = $path;

        return $this;
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
        $contents = $this->files->get($this->path);

        $data = json_decode($contents, true);

        if ($data === null) {
            throw new RuntimeException("Invalid JSON in {$this->path}");
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        $contents = ! empty($data) ? json_encode($data) : '{}';

        $this->files->put($this->path, $contents);
    }
}

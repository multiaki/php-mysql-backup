<?php namespace Dimsav\Backup\Storage;

use Illuminate\Container\Container;

class StorageFactory
{

    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function make(array $config, $name)
    {
        $config = $this->parseConfig($config, $name);

        return $this->createStorage($config['driver']);
    }

    /**
     * Parse and prepare the storage configuration
     *
     * @param  array   $config
     * @param  string  $name
     * @return array
     */
    protected function parseConfig(array $config, $name)
    {
        return array_add(array_add($config, 'prefix', ''), 'name', $name);
    }

    protected function createStorage($driver, $config)
    {
        switch ($driver)
        {
            case 'dropbox':
                return new DropboxStorage($config);
        }

        throw new \InvalidArgumentException("Unsupported driver [$driver]");
    }
}

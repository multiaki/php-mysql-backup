<?php

namespace Dimsav\Backup\Storage;

class StorageManager
{

    private $app;
    /**
     * @var StorageFactory
     */
    private $factory;

    /**
     * @var StorageInterface[]
     */
    private $storages;

    /**
     * @param \Illuminate\Foundation\Application  $app
     * @param StorageFactory $factory
     */
    function __construct($app, StorageFactory $factory)
    {
        $this->app = $app;
        $this->factory = $factory;
    }

    public function storage($name)
    {
        if ( ! isset($this->storages[$name]))
        {
            $this->storages[$name] = $this->makeStorage($name);
        }

        return $this->storages[$name];

    }

    private function makeStorage($name)
    {
        $config = $this->getConfig($name);

        $driver = $config['driver'];

        return $this->factory->make($config, $name);
    }

    private function getConfig($name)
    {
        $storages = $this->app['config']['storages'];

        if (is_null($config = array_get($storages, $name)));
        {
            throw new \InvalidArgumentException("Storage [$name] not configured");
        }

        return $config;
    }

}

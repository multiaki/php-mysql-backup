<?php namespace Dimsav\Backup\Element;

use Dimsav\Backup\Element\Drivers\Directory;
use Dimsav\Backup\Element\Drivers\Mysql;
use Dimsav\Backup\Shell;
use Dimsav\UnixZipper;

class ElementFactory
{
    private $config;
    private $supportedDrivers = array('mysql', 'directories');

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function make($projectName, $driver, $elementName)
    {
        $this->validate($projectName, $driver, $elementName);
        $config = $this->config[$projectName][$driver][$elementName];
        if ($driver == 'mysql')
        {
            if ( ! isset($config['database']))
            {
                $config['database'] = $elementName;
            }
            return new Mysql($config, new Shell());
        }
        elseif ($driver == 'directories')
        {
            $root = $this->config[$projectName]['root_dir'];

            if ( is_array($config) && ! isset($config['directory']))
            {
                $config['directory'] = $elementName;
            }
            return new Directory($root, $config, new UnixZipper);
        }
    }

    private function validate($projectName, $driver, $elementName)
    {

        if ( ! isset($this->config[$projectName]))
        {
            throw new \InvalidArgumentException("The project '$projectName' was not found.");
        }

        if ( ! in_array($driver, $this->supportedDrivers))
        {
            throw new \InvalidArgumentException("The driver '$driver' is not supported. Check your settings in project '$projectName'.");
        }

        if ( ! isset($this->config[$projectName][$driver]))
        {
            throw new \InvalidArgumentException("The project '$projectName' has no driver '$driver' set.");
        }

        if ( ! isset($this->config[$projectName][$driver][$elementName]))
        {
            throw new \InvalidArgumentException("The project '$projectName' has no driver '$driver' named '$elementName'.");
        }

        if ($driver == 'directories')
        {
            $this->validateDirectories($projectName);
        }

    }

    private function validateDirectories($projectName)
    {
        if ( ! isset($this->config[$projectName]['root_dir']))
        {
            throw new \InvalidArgumentException(
                "The project '$projectName' has no root_dir set. Please set it to backup project files");
        }
    }
}

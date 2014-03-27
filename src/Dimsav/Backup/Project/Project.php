<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Storage\Storage;

class Project {

    /**
     * @var string
     */
    private $name;

    private $storageNames = array();

    /**
     * Associative array containing the storages used for this project.
     * The keys represent the storage aliases.
     *
     * @var Storage[]
     */
    private $storages = array();
    private $password;

    /**
     * @var Element[]
     */
    private $elements = array();

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function addStorage(Storage $storage)
    {
        $this->storages[$storage->getAlias()] = $storage;
    }

    public function getStorages()
    {
        return $this->storages;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $storageNames
     */
    public function setStorageNames($storageNames)
    {
        $this->storageNames = $storageNames;
    }

    /**
     * @return array
     */
    public function getStorageNames()
    {
        return $this->storageNames;
    }

    public function addElement(Element $element)
    {
        $this->elements[] = $element;
    }

    public function getElements()
    {
        return $this->elements;
    }
}
